<?php
declare(strict_types=1);

namespace Mds\Middleware;

use Mds\Core\Request;
use Mds\Core\Response;

/**
 * Contrat commun. `$suivant` rend la main à la suite de la chaîne ; ne pas
 * l'appeler court-circuite le traitement, ce qui est parfois exactement le but
 * (préflight CORS).
 */
interface Middleware
{
    public function traiter(Request $requete, callable $suivant): Response;
}
