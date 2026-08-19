<?php
declare(strict_types=1);

namespace Mds\Core;

use Mds\Middleware\Middleware;

/**
 * Chaîne de middlewares.
 *
 * Chaque middleware reçoit la requête et la main sur la suite : il peut la
 * modifier, court-circuiter (préflight CORS), ou envelopper l'appel (capture
 * des erreurs). L'ordre est celui de la déclaration, et il compte — la capture
 * d'erreurs doit être la plus extérieure pour attraper aussi les fautes des
 * autres middlewares.
 */
final class Pipeline
{
    /** @param list<Middleware> $middlewares */
    public function __construct(private array $middlewares)
    {
    }

    public function traiter(Request $requete, callable $noyau): Response
    {
        $suivant = $noyau;

        foreach (array_reverse($this->middlewares) as $middleware) {
            $courant = $suivant;
            $suivant = static fn(Request $r): Response => $middleware->traiter($r, $courant);
        }

        return $suivant($requete);
    }
}
