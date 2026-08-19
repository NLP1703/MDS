<?php
declare(strict_types=1);

namespace Mds\Core;

use PDO;
use PDOException;
use RuntimeException;

/**
 * Fabrique de connexion PDO.
 *
 * Deux réglages ne sont pas cosmétiques :
 *   ERRMODE_EXCEPTION — une erreur SQL doit interrompre la transaction, pas se
 *   glisser dans un `if` oublié ;
 *   EMULATE_PREPARES à false — les requêtes préparées le sont réellement côté
 *   serveur, ce qui ferme la porte à l'injection SQL au lieu de la refermer à
 *   moitié côté client.
 */
final class Database
{
    /**
     * Ouvre une connexion à partir d'une section de configuration.
     *
     * Le paramètre `$section` a été introduit quand le projet servait deux
     * bases. Il n'en reste qu'une, mais le paramètre demeure : il coûte une
     * ligne, et il évite d'avoir à rouvrir ce fichier le jour où une seconde
     * base réapparaît.
     */
    public static function connexion(Config $config, string $section = 'database_site'): PDO
    {
        $c = $config->section($section);

        if ($c === []) {
            throw new RuntimeException(
                "Section de configuration « $section » absente ou vide : "
                . "attendu backend/config/$section.php."
            );
        }

        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $c['host'] ?? '127.0.0.1',
            (int) ($c['port'] ?? 3306),
            $c['name'] ?? 'mds_site',
            $c['charset'] ?? 'utf8mb4'
        );

        try {
            return new PDO($dsn, $c['user'] ?? 'root', $c['pass'] ?? '', [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::ATTR_STRINGIFY_FETCHES  => false,
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException(
                'Connexion à MySQL impossible. Vérifiez que MySQL est démarré dans le panneau '
                . 'XAMPP et que la base « ' . ($c['name'] ?? '?') . ' » a été importée '
                . '(php backend/scripts/migrate-site.php). Détail : ' . $e->getMessage(),
                0,
                $e
            );
        }
    }
}
