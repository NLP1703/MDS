<?php
declare(strict_types=1);

namespace Mds\Controllers;

use Mds\Core\Config;
use Mds\Core\Request;
use Mds\Core\Response;
use PDO;

/**
 * Sonde de santé.
 *
 * Elle interroge réellement la base : un service qui répond « ok » sans avoir
 * touché MySQL ne prouve rien, et c'est précisément l'erreur qu'on cherche à
 * exclure quand on appelle cette route — Apache tourne, mais MySQL est-il
 * démarré et la base importée ?
 */
final class SanteController
{
    public function __construct(private PDO $pdo, private Config $config)
    {
    }

    public function etat(Request $requete): Response
    {
        $tables = (int) $this->pdo
            ->query('SELECT COUNT(*) FROM information_schema.tables
                      WHERE table_schema = DATABASE()')
            ->fetchColumn();

        return Response::json([
            'service' => 'MDS Market Research — API du site',
            'etat'    => $tables > 0 ? 'ok' : 'base vide',
            'base'    => $this->config->get('database_site.name'),
            'tables'  => $tables,
            'heure'   => date('c'),
        ]);
    }
}
