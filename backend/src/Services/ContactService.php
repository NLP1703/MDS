<?php
declare(strict_types=1);

namespace Mds\Services;

use Mds\Models\MessageContactModel;

/**
 * Réception des messages du formulaire de contact.
 *
 * Le point de doctrine : **la validation du navigateur ne compte pas**. Les
 * attributs `required` et `type="email"` de la page rendent le formulaire
 * agréable, ils ne protègent rien — un envoi direct par curl les ignore. Tout
 * est donc revérifié ici, et c'est cette vérification-là qui fait foi.
 *
 * Trois barrages, du moins coûteux au plus coûteux :
 *   1. le pot de miel — un champ caché que seuls les robots remplissent ;
 *   2. la validation de forme — champs requis, longueurs, adresse plausible ;
 *   3. le limiteur de débit — au-delà de N messages par IP et par heure, refus.
 */
final class ContactService
{
    /** Au-delà, ce n'est plus une demande de contact. */
    private const LONGUEURS = [
        'prenom'     => 80,
        'nom'        => 80,
        'entreprise' => 160,
        'email'      => 254,
        'message'    => 5000,
    ];

    private const MESSAGE_MINIMUM = 10;

    /** Fenêtre et plafond du limiteur de débit. */
    private const DEBIT_FENETRE_MINUTES = 60;
    private const DEBIT_MAXIMUM = 5;

    private const CONFIRMATION = 'Message envoyé avec succès. Nous vous contacterons bientôt.';

    public function __construct(
        private MessageContactModel $messages,
        private NotificationContact $notification,
    ) {
    }

    /**
     * @param array<string,mixed> $corps Corps JSON de la requête, tel quel.
     * @return array{statut:int, donnees:array<string,mixed>}
     */
    public function recevoir(array $corps, ?string $ip, ?string $userAgent): array
    {
        /*
         * Pot de miel. Le robot qui l'a rempli reçoit un succès : lui répondre
         * « rejeté » lui apprendrait quel champ éviter au prochain passage.
         * Rien n'est écrit en base.
         */
        if (trim((string) ($corps['bot_field'] ?? '')) !== '') {
            return ['statut' => 201, 'donnees' => ['message' => self::CONFIRMATION]];
        }

        $champs = $this->valider($corps);

        if ($champs !== []) {
            return ['statut' => 422, 'donnees' => [
                'erreur' => 'Certains champs sont invalides.',
                'champs' => $champs,
            ]];
        }

        // Le limiteur passe après la validation : inutile de compter les
        // messages d'une IP qui, de toute façon, n'a rien envoyé de valide.
        if ($ip !== null && $this->messages->comptesRecents($ip, self::DEBIT_FENETRE_MINUTES) >= self::DEBIT_MAXIMUM) {
            return ['statut' => 429, 'donnees' => [
                'erreur' => 'Trop de messages envoyés depuis cette connexion. '
                    . 'Réessayez dans une heure, ou écrivez directement à contact@mds-cmr.com.',
            ]];
        }

        $donnees = [
            'prenom'     => $this->texte($corps['firstName'] ?? ''),
            'nom'        => $this->texte($corps['lastName'] ?? ''),
            'entreprise' => $this->texteOuNull($corps['company'] ?? null),
            'email'      => $this->texte($corps['email'] ?? ''),
            'objet'      => $this->texte($corps['subject'] ?? ''),
            'message'    => $this->texte($corps['message'] ?? ''),
            'ip'         => $ip,
            'userAgent'  => $userAgent === null ? null : mb_substr($userAgent, 0, 255),
        ];

        // La base d'abord : elle fait foi. Le courriel n'est qu'une alerte, et
        // `prevenir()` ne remonte jamais d'erreur — une panne du serveur de
        // courrier ne doit pas faire croire au visiteur que sa demande est
        // perdue alors qu'elle est enregistrée.
        $id = $this->messages->enregistrer($donnees);

        $this->notification->prevenir($donnees, $id);

        return ['statut' => 201, 'donnees' => [
            'id'      => $id,
            'message' => self::CONFIRMATION,
        ]];
    }

    /**
     * @param array<string,mixed> $corps
     * @return array<string,string> Champ HTML fautif → message, vide si tout va bien.
     */
    private function valider(array $corps): array
    {
        $erreurs = [];

        // Les clés sont celles des attributs `name` du formulaire : la page
        // peut ainsi surligner directement le champ concerné.
        foreach (['firstName' => 'prenom', 'lastName' => 'nom', 'message' => 'message'] as $champ => $colonne) {
            $valeur = $this->texte($corps[$champ] ?? '');

            if ($valeur === '') {
                $erreurs[$champ] = 'Ce champ est obligatoire.';
                continue;
            }
            if (mb_strlen($valeur) > self::LONGUEURS[$colonne]) {
                $erreurs[$champ] = 'Ce champ dépasse ' . self::LONGUEURS[$colonne] . ' caractères.';
            }
        }

        // Un message de trois lettres n'est pas une demande — c'est un test de
        // robot, ou une saisie accidentelle.
        if (!isset($erreurs['message'])) {
            $message = $this->texte($corps['message'] ?? '');
            if ($message !== '' && mb_strlen($message) < self::MESSAGE_MINIMUM) {
                $erreurs['message'] = 'Merci de détailler votre demande (' . self::MESSAGE_MINIMUM . ' caractères minimum).';
            }
        }

        $email = $this->texte($corps['email'] ?? '');
        if ($email === '') {
            $erreurs['email'] = 'Ce champ est obligatoire.';
        } elseif (mb_strlen($email) > self::LONGUEURS['email'] || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreurs['email'] = 'Cette adresse email ne semble pas valide.';
        }

        $entreprise = $this->texte($corps['company'] ?? '');
        if (mb_strlen($entreprise) > self::LONGUEURS['entreprise']) {
            $erreurs['company'] = 'Ce champ dépasse ' . self::LONGUEURS['entreprise'] . ' caractères.';
        }

        /*
         * L'objet est confronté à la table plutôt qu'à une liste écrite ici :
         * ajouter une entrée au <select> de la page ne doit pas obliger à
         * repasser dans ce fichier.
         */
        $objet = $this->texte($corps['subject'] ?? '');
        if ($objet === '') {
            $erreurs['subject'] = 'Merci de sélectionner un objet.';
        } elseif (!in_array($objet, $this->messages->objetsValides(), true)) {
            $erreurs['subject'] = 'Cet objet n\'est pas reconnu.';
        }

        return $erreurs;
    }

    /** Normalise une valeur reçue en texte utilisable. */
    private function texte(mixed $valeur): string
    {
        if (!is_scalar($valeur)) {
            return '';
        }

        // Les caractères de contrôle n'ont rien à faire dans un formulaire ; on
        // les retire avant de mesurer la longueur, sinon un envoi de 5000
        // caractères nuls passerait pour un message.
        $texte = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', (string) $valeur) ?? '';

        return trim($texte);
    }

    private function texteOuNull(mixed $valeur): ?string
    {
        $texte = $this->texte($valeur);

        return $texte === '' ? null : $texte;
    }
}
