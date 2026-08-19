<?php
declare(strict_types=1);

/**
 * ═══════════════════════════════════════════════════════════════════════════
 *  API MDS — Retail Audit Tracker · point d'entrée unique
 * ═══════════════════════════════════════════════════════════════════════════
 *
 *  Seul ce dossier `public/` est exposé par Apache : la configuration, les
 *  contrôleurs et les modèles vivent un niveau au-dessus, hors de portée
 *  d'une requête HTTP, même si PHP venait à ne plus interpréter les fichiers.
 *
 *  Ce fichier ne contient aucune logique métier. Il assemble :
 *
 *      requête → [erreurs] → [CORS] → [corps JSON] → routeur → contrôleur
 *                                                                   ↓
 *                                                              service → modèle → MySQL
 *
 *  L'ordre des middlewares compte. La capture d'erreurs est la plus
 *  extérieure : elle doit attraper aussi les fautes des autres middlewares,
 *  sinon une erreur dans le décodage JSON sortirait en page HTML d'Apache,
 *  que le frontend ne saurait pas distinguer d'une panne réseau.
 */

use Mds\Core\Config;
use Mds\Core\Container;
use Mds\Core\Pipeline;
use Mds\Core\Request;
use Mds\Core\Response;
use Mds\Core\Router;
use Mds\Middleware\CorsMiddleware;
use Mds\Middleware\ErreurMiddleware;
use Mds\Middleware\JsonBodyMiddleware;

/** @var Container $container */
$container = require __DIR__ . '/../bootstrap.php';

$config = $container->get(Config::class);
date_default_timezone_set((string) $config->get('app.fuseau', 'UTC'));

// Une notice PHP au milieu d'une réponse JSON la rend illisible : on la
// transforme en exception, que le middleware d'erreurs saura présenter.
set_error_handler(static function (int $niveau, string $message, string $fichier, int $ligne): bool {
    throw new ErrorException($message, 0, $niveau, $fichier, $ligne);
});

$router = new Router();
(require __DIR__ . '/../routes/api.php')($router);

$pipeline = new Pipeline([
    new ErreurMiddleware($config),
    new CorsMiddleware($config),
    new JsonBodyMiddleware(),
]);

$reponse = $pipeline->traiter(
    Request::depuisGlobales(),
    static fn(Request $requete): Response => $router->dispatcher($requete, $container)
);

$reponse->envoyer();
