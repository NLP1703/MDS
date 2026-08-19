<?php
/**
 * Connexion à la base du site vitrine.
 *
 * Volontairement séparée de `database.php` : le site et l'application de
 * collecte terrain sont deux métiers distincts, sur deux bases distinctes. En
 * production, ce compte n'a besoin que de SELECT sur `realisations` et
 * `categories_realisation` — le site publie, il ne modifie rien.
 *
 * L'hôte et les identifiants retombent sur ceux de `database.php` : sur une
 * installation XAMPP les deux bases vivent dans le même serveur MySQL, et
 * dupliquer la valeur inviterait à n'en changer qu'une le jour du déménagement.
 */

return [
    'host'    => getenv('MDS_SITE_DB_HOST') ?: (getenv('MDS_DB_HOST') ?: '127.0.0.1'),
    'port'    => (int) (getenv('MDS_SITE_DB_PORT') ?: (getenv('MDS_DB_PORT') ?: 3306)),
    'name'    => getenv('MDS_SITE_DB_NAME') ?: 'mds_site',
    'user'    => getenv('MDS_SITE_DB_USER') ?: (getenv('MDS_DB_USER') ?: 'root'),
    'pass'    => getenv('MDS_SITE_DB_PASS') ?: (getenv('MDS_DB_PASS') ?: ''),
    'charset' => 'utf8mb4',
];
