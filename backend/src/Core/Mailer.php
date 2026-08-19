<?php
declare(strict_types=1);

namespace Mds\Core;

use RuntimeException;

/**
 * Envoi d'un courriel par SMTP.
 *
 * Écrit à la main, comme l'autochargement et le routeur : le projet se pose
 * sur un XAMPP sans Composer, et faire dépendre l'envoi d'un `composer install`
 * ajouterait une étape à expliquer pour deux cents lignes de dialogue.
 *
 * Volontairement limité à ce dont le formulaire a besoin — un message texte,
 * un destinataire, une adresse de réponse. Pas de pièce jointe, pas de HTML,
 * pas d'envoi groupé. Le jour où il en faut, PHPMailer fait cela mieux.
 *
 * ─── Ce qui est délicat dans SMTP ───
 *
 * Trois pièges, tous traités plus bas :
 *
 *   · les réponses multilignes — `250-TAILLE` puis `250 OK` : il faut lire
 *     jusqu'à la ligne dont le quatrième caractère est une espace ;
 *   · une ligne du corps réduite à un point termine la transmission ; on la
 *     double donc (« dot-stuffing ») ;
 *   · un retour à la ligne glissé dans un en-tête permet d'en injecter
 *     d'autres — un `Cc` par exemple. Toute valeur d'en-tête est nettoyée.
 */
final class Mailer
{
    /** @param array<string,mixed> $config Section `mail` de la configuration. */
    public function __construct(private array $config)
    {
    }

    public function actif(): bool
    {
        return (bool) ($this->config['actif'] ?? false);
    }

    /**
     * Envoie un message texte.
     *
     * @throws RuntimeException si le serveur refuse à n'importe quelle étape.
     */
    public function envoyer(string $destinataire, string $sujet, string $corps, ?string $repondreA = null): void
    {
        if (!$this->actif()) {
            throw new RuntimeException("Aucun serveur SMTP n'est configuré.");
        }

        $securite = (string) ($this->config['securite'] ?? 'tls');
        $hote     = (string) $this->config['hote'];
        $port     = (int) $this->config['port'];
        $delai    = (int) ($this->config['delai'] ?? 10);

        // `ssl` chiffre dès la connexion ; `tls` négocie ensuite par STARTTLS.
        $adresse = ($securite === 'ssl' ? 'ssl://' : 'tcp://') . $hote . ':' . $port;

        $flux = @stream_socket_client($adresse, $code, $erreur, $delai);

        if ($flux === false) {
            throw new RuntimeException("Connexion à $hote:$port impossible : $erreur");
        }

        stream_set_timeout($flux, $delai);

        try {
            $this->lire($flux, 220);

            $nomLocal = $this->nomLocal();
            $this->dialoguer($flux, "EHLO $nomLocal", 250);

            if ($securite === 'tls') {
                $this->dialoguer($flux, 'STARTTLS', 220);

                $chiffre = @stream_socket_enable_crypto(
                    $flux,
                    true,
                    STREAM_CRYPTO_METHOD_TLS_CLIENT
                    | STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT
                    | STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT
                );

                if ($chiffre !== true) {
                    throw new RuntimeException('La négociation TLS a échoué.');
                }

                // Le serveur oublie tout ce qui précède STARTTLS : on se
                // represente, faute de quoi AUTH sera refusé.
                $this->dialoguer($flux, "EHLO $nomLocal", 250);
            }

            $utilisateur = (string) ($this->config['utilisateur'] ?? '');
            if ($utilisateur !== '') {
                $this->dialoguer($flux, 'AUTH LOGIN', 334);
                $this->dialoguer($flux, base64_encode($utilisateur), 334);
                $this->dialoguer($flux, base64_encode((string) $this->config['motdepasse']), 235);
            }

            $expediteur = (string) $this->config['expediteur'];

            $this->dialoguer($flux, 'MAIL FROM:<' . $expediteur . '>', 250);
            $this->dialoguer($flux, 'RCPT TO:<' . $destinataire . '>', 250);
            $this->dialoguer($flux, 'DATA', 354);

            $this->ecrire($flux, $this->composer($destinataire, $sujet, $corps, $repondreA));
            $this->dialoguer($flux, '.', 250);

            // L'échec d'un QUIT n'a aucune importance : le serveur a déjà
            // accepté le message au point précédent.
            @$this->ecrire($flux, "QUIT\r\n");
        } finally {
            @fclose($flux);
        }
    }

    /**
     * Assemble les en-têtes et le corps.
     *
     * Le sujet et les noms passent par un encodage MIME : un « Réf. » ou une
     * cédille sortiraient en caractères illisibles dans la liste des messages,
     * les en-têtes n'admettant que de l'ASCII.
     *
     * Le corps est encodé en base64 plutôt qu'envoyé tel quel : cela règle
     * d'un coup la longueur maximale des lignes et le point isolé en début de
     * ligne, sans avoir à découper le texte du visiteur.
     */
    private function composer(string $destinataire, string $sujet, string $corps, ?string $repondreA): string
    {
        $expediteur = (string) $this->config['expediteur'];
        $nom        = (string) ($this->config['expediteur_nom'] ?? '');

        $entetes = [
            'Date'         => date(DATE_RFC2822),
            'From'         => $nom === ''
                ? $expediteur
                : $this->encoder($nom) . ' <' . $expediteur . '>',
            'To'           => $destinataire,
            'Subject'      => $this->encoder($sujet),
            'MIME-Version' => '1.0',
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Content-Transfer-Encoding' => 'base64',
        ];

        if ($repondreA !== null && $repondreA !== '') {
            $entetes['Reply-To'] = $repondreA;
        }

        $sortie = '';
        foreach ($entetes as $cle => $valeur) {
            $sortie .= $cle . ': ' . $this->nettoyer((string) $valeur) . "\r\n";
        }

        return $sortie . "\r\n" . chunk_split(base64_encode($corps), 76, "\r\n");
    }

    /**
     * Retire ce qui permettrait d'injecter un en-tête.
     *
     * Le nom et l'adresse du visiteur finissent dans `Reply-To` et dans le
     * sujet. Un retour à la ligne y ouvrirait la porte à un `Bcc:` vers
     * n'importe qui — le formulaire deviendrait un relais à courrier
     * indésirable, au nom de MDS.
     */
    private function nettoyer(string $valeur): string
    {
        return trim(str_replace(["\r", "\n", "\0"], '', $valeur));
    }

    /** Encodage MIME d'un en-tête, seulement s'il sort de l'ASCII. */
    private function encoder(string $texte): string
    {
        $texte = $this->nettoyer($texte);

        if (preg_match('/^[\x20-\x7E]*$/', $texte) === 1) {
            return $texte;
        }

        return '=?UTF-8?B?' . base64_encode($texte) . '?=';
    }

    /**
     * Le nom annoncé dans EHLO.
     *
     * Certains serveurs refusent une adresse IP nue ou un nom vide. `localhost`
     * n'est pas flatteur mais il est accepté partout, et il ne sert qu'à la
     * présentation : c'est l'authentification qui décide de la suite.
     */
    private function nomLocal(): string
    {
        $nom = gethostname();

        return is_string($nom) && $nom !== '' ? $nom : 'localhost';
    }

    /** Envoie une commande et vérifie le code de retour. */
    private function dialoguer($flux, string $commande, int $attendu): string
    {
        $this->ecrire($flux, $commande . "\r\n");

        return $this->lire($flux, $attendu, $commande);
    }

    private function ecrire($flux, string $donnees): void
    {
        if (@fwrite($flux, $donnees) === false) {
            throw new RuntimeException('Écriture impossible vers le serveur SMTP.');
        }
    }

    /**
     * Lit une réponse, éventuellement multiligne, et contrôle son code.
     *
     * Le mot de passe n'apparaît jamais dans l'exception : il transite en
     * base64 dans la commande, et un message d'erreur finit souvent dans un
     * journal ou dans une réponse HTTP.
     */
    private function lire($flux, int $attendu, string $commande = 'connexion'): string
    {
        $reponse = '';

        while (($ligne = fgets($flux, 615)) !== false) {
            $reponse .= $ligne;

            // Dernière ligne : « 250 OK », par opposition à « 250-TAILLE ».
            if (strlen($ligne) < 4 || $ligne[3] !== '-') {
                break;
            }
        }

        $meta = stream_get_meta_data($flux);
        if (!empty($meta['timed_out'])) {
            throw new RuntimeException('Le serveur SMTP ne répond plus.');
        }

        $code = (int) substr($reponse, 0, 3);

        if ($code !== $attendu) {
            $etape = str_starts_with($commande, 'AUTH') || preg_match('/^[A-Za-z0-9+\/=]{8,}$/', $commande) === 1
                ? 'authentification'
                : $commande;

            throw new RuntimeException(
                "SMTP a refusé l'étape « $etape » : " . trim($reponse) . " (code $code, attendu $attendu)"
            );
        }

        return $reponse;
    }
}
