<?php
declare(strict_types=1);

/**
 * Amorçage de l'application.
 *
 * Chargé par `public/index.php` et par les scripts de `scripts/`. Il fait trois
 * choses et rien d'autre : enregistrer l'autochargement, lire la configuration,
 * câbler le conteneur.
 *
 * Pas de Composer : le projet vise une installation XAMPP posée telle quelle,
 * où `composer install` est une étape de plus à expliquer. L'autochargement
 * PSR-4 tient en dix lignes, autant les écrire.
 */

use Mds\Core\Config;
use Mds\Core\Container;
use Mds\Core\Database;
use Mds\Core\Mailer;

spl_autoload_register(static function (string $classe): void {
    $prefixe = 'Mds\\';
    if (!str_starts_with($classe, $prefixe)) {
        return;
    }
    $relatif = substr($classe, strlen($prefixe));
    $fichier = __DIR__ . '/src/' . str_replace('\\', '/', $relatif) . '.php';
    if (is_file($fichier)) {
        require $fichier;
    }
});

$config = Config::depuisDossier(__DIR__ . '/config');

$container = new Container();

$container->partage(Config::class, static fn() => $config);

/*
 * Connexion à la base du site.
 *
 * Elle est enregistrée sous une clé nommée et non sous `PDO::class` : le projet
 * a porté deux bases, et le typage seul ne distingue pas deux connexions. La
 * clé explicite rend impossible d'injecter la mauvaise, et laisse la place si
 * une seconde base revient un jour.
 *
 * Paresseuse : les scripts qui ne touchent pas la base n'ont pas à réveiller
 * MySQL.
 */
$container->partage(
    'pdo.site',
    static fn(Container $c) => Database::connexion($c->get(Config::class), 'database_site')
);

foreach ([
    Mds\Models\RealisationModel::class,
    Mds\Models\ActualiteModel::class,
    Mds\Models\MessageContactModel::class,
] as $modele) {
    $container->partage($modele, static fn(Container $c) => new $modele($c->get('pdo.site')));
}

$container->partage(
    Mds\Services\RealisationService::class,
    static fn(Container $c) => new Mds\Services\RealisationService(
        $c->get(Mds\Models\RealisationModel::class)
    )
);

$container->partage(
    Mds\Services\ActualiteService::class,
    static fn(Container $c) => new Mds\Services\ActualiteService(
        $c->get(Mds\Models\ActualiteModel::class)
    )
);

/* Le courrier sortant. Rien n'est ouvert ici : la connexion au serveur SMTP
   n'a lieu qu'à l'envoi, et seul le formulaire de contact en déclenche un. */
$container->partage(
    Mailer::class,
    static fn(Container $c) => new Mailer($c->get(Config::class)->section('mail'))
);

$container->partage(
    Mds\Services\NotificationContact::class,
    static fn(Container $c) => new Mds\Services\NotificationContact(
        $c->get(Mailer::class),
        $c->get(Config::class),
    )
);

$container->partage(
    Mds\Services\ContactService::class,
    static fn(Container $c) => new Mds\Services\ContactService(
        $c->get(Mds\Models\MessageContactModel::class),
        $c->get(Mds\Services\NotificationContact::class),
    )
);

$container->partage(
    Mds\Controllers\SanteController::class,
    static fn(Container $c) => new Mds\Controllers\SanteController(
        $c->get('pdo.site'),
        $c->get(Config::class),
    )
);

$container->partage(
    Mds\Controllers\RealisationController::class,
    static fn(Container $c) => new Mds\Controllers\RealisationController(
        $c->get(Mds\Services\RealisationService::class)
    )
);

$container->partage(
    Mds\Controllers\ActualiteController::class,
    static fn(Container $c) => new Mds\Controllers\ActualiteController(
        $c->get(Mds\Services\ActualiteService::class)
    )
);

$container->partage(
    Mds\Controllers\ContactController::class,
    static fn(Container $c) => new Mds\Controllers\ContactController(
        $c->get(Mds\Services\ContactService::class)
    )
);

return $container;
