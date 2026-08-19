<?php
declare(strict_types=1);

namespace Mds\Core;

/**
 * La requête HTTP, figée en objet.
 *
 * Le chemin est débarrassé du préfixe d'installation : l'API fonctionne sous
 * n'importe quel alias Apache — `/mds-api`, `/api`, ou la racine — sans qu'une
 * seule route soit à réécrire.
 */
final class Request
{
    private function __construct(
        public readonly string $methode,
        public readonly string $chemin,
        /** @var array<string,string> */
        public readonly array $query,
        /** @var array<string,mixed> */
        public readonly array $corps,
        public readonly string $corpsBrut,
        /** @var array<string,string> */
        public readonly array $entetes,
    ) {
    }

    public static function depuisGlobales(): self
    {
        $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
        $chemin = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
        if ($base !== '' && str_starts_with($chemin, $base)) {
            $chemin = substr($chemin, strlen($base));
        }

        $brut = (string) file_get_contents('php://input');

        return new self(
            strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET')),
            '/' . trim($chemin, '/'),
            array_map('strval', $_GET),
            [],
            $brut,
            self::entetes(),
        );
    }

    /** Copie de la requête avec un corps décodé — posé par le middleware JSON. */
    public function avecCorps(array $corps): self
    {
        return new self(
            $this->methode,
            $this->chemin,
            $this->query,
            $corps,
            $this->corpsBrut,
            $this->entetes,
        );
    }

    public function query(string $cle, ?string $defaut = null): ?string
    {
        $v = $this->query[$cle] ?? null;
        return $v === null || $v === '' ? $defaut : $v;
    }

    public function entete(string $nom): ?string
    {
        return $this->entetes[strtolower($nom)] ?? null;
    }

    /**
     * L'adresse du client, pour le limiteur de débit du formulaire de contact.
     *
     * `REMOTE_ADDR` par défaut : `X-Forwarded-For` est un en-tête que le client
     * écrit lui-même, et le croire donnerait à un robot le moyen de changer
     * d'identité à chaque envoi — c'est-à-dire de désactiver le limiteur.
     *
     * Derrière un reverse proxy, `REMOTE_ADDR` devient l'adresse du proxy : la
     * même pour tous les visiteurs. Le limiteur compterait alors leurs envois
     * ensemble et fermerait le formulaire à tout le monde au cinquième message.
     *
     * D'où `MDS_ENTETE_IP` : il nomme le seul en-tête auquel se fier, celui que
     * l'hébergeur écrit lui-même et qu'un client ne peut pas usurper — par
     * exemple `x-vercel-forwarded-for`. Non renseigné, rien ne change. Y mettre
     * `x-forwarded-for` sans proxy devant reviendrait à laisser le visiteur
     * choisir son identité, et donc à désactiver le limiteur.
     */
    public function ip(): ?string
    {
        $entete = getenv('MDS_ENTETE_IP') ?: null;

        if (is_string($entete) && ($valeur = $this->entete($entete)) !== null) {
            // Ces en-têtes portent parfois une chaîne « client, proxy1, proxy2 » :
            // le client est en tête, le reste a été ajouté en chemin.
            $candidat = trim(explode(',', $valeur)[0]);

            if (filter_var($candidat, FILTER_VALIDATE_IP)) {
                return $candidat;
            }
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? null;

        return is_string($ip) && filter_var($ip, FILTER_VALIDATE_IP) ? $ip : null;
    }

    /** @return array<string,string> */
    private static function entetes(): array
    {
        $out = [];
        foreach ($_SERVER as $cle => $valeur) {
            if (str_starts_with($cle, 'HTTP_')) {
                $nom = strtolower(str_replace('_', '-', substr($cle, 5)));
                $out[$nom] = (string) $valeur;
            }
        }
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $out['content-type'] = (string) $_SERVER['CONTENT_TYPE'];
        }
        return $out;
    }
}
