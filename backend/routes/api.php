<?php
declare(strict_types=1);

/**
 * Table des routes.
 *
 * Un seul fichier pour toute la surface de l'API : ce qui n'y figure pas
 * n'existe pas. C'est la première chose à lire pour savoir ce que le serveur
 * sait faire, et le seul endroit à modifier pour l'étendre.
 *
 * L'API sert le site vitrine de `site/` et lui seul. Elle a d'abord servi une
 * application de collecte terrain, retirée du projet : si vous cherchez
 * `/session` ou `/visites`, elles n'existent plus.
 *
 * Base d'appel : http://localhost/mds-api
 */

use Mds\Controllers\ActualiteController;
use Mds\Controllers\ContactController;
use Mds\Controllers\RealisationController;
use Mds\Controllers\SanteController;
use Mds\Core\Router;

return static function (Router $router): void {
    // Le service répond-il, et la base est-elle vraiment là ?
    $router->get('/sante', [SanteController::class, 'etat']);

    // Portfolio de la page Réalisations. Filtre optionnel : ?categorie=retail
    $router->get('/realisations', [RealisationController::class, 'lister']);

    // Actualités : liste filtrable, et détail d'une annonce.
    $router->get('/actualites', [ActualiteController::class, 'lister']);
    $router->get('/actualite', [ActualiteController::class, 'detail']);

    // Formulaire de la page Contact. Seule route écrite depuis l'internet
    // public : validation, pot de miel et limiteur de débit sont dans
    // ContactService.
    $router->post('/contact', [ContactController::class, 'recevoir']);
};
