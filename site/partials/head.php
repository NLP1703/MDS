<?php
declare(strict_types=1);

/**
 * En-tête du document : métadonnées, Open Graph, styles, Tailwind.
 *
 * Variables attendues de la page appelante :
 *   $titre       — titre de l'onglet et des aperçus de lien
 *   $description — une phrase, reprise par les moteurs et les aperçus
 *   $page        — clé du menu (voir MDS_MENU), pour marquer l'entrée active
 *   $image_og    — optionnel : image d'aperçu, relative au site
 *   $url_page    — optionnel : adresse à déclarer, relative au site
 *
 * `$url_page` sert aux pages qui n'ont pas d'entrée de menu propre — le détail
 * d'une actualité, par exemple, qui marque « Actualités » comme active mais ne
 * doit pas s'annoncer sous l'adresse de la liste : les aperçus de lien
 * renverraient tous au même endroit.
 */

require_once __DIR__ . '/config.php';

$titre       = $titre       ?? MDS['nom'];
$description = $description ?? "Cabinet d'études de marché, sociales et d'opinion à Douala. Certifié ISO 20252.";
$page        = $page        ?? '';
$image_og    = $image_og    ?? 'assets/images/logo-mds.png';
$url_page    = $url_page    ?? (MDS_MENU[$page]['url'] ?? '');

/* Titre et description passent par le dictionnaire comme le reste : ce sont
   eux qui s'affichent dans l'onglet, dans les résultats de recherche et dans
   les aperçus de lien — les laisser en français viderait la traduction de son
   intérêt là où elle se voit le plus. */
$titre       = t($titre);
$description = t($description);

$langue        = mds_langue();
$url_absolue   = rtrim(MDS['origine'], '/') . '/' . ltrim($url_page, '/');
$image_absolue = rtrim(MDS['origine'], '/') . '/' . ltrim($image_og, '/');
?>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title><?= e($titre) ?></title>
<meta content="<?= e($description) ?>" name="description"/>

<?php /* Aperçus de lien. Sans ces balises, un lien partagé sur WhatsApp ou
         LinkedIn n'affiche qu'une URL nue — sans titre, sans image. */ ?>
<meta content="website" property="og:type"/>
<meta content="<?= e($titre) ?>" property="og:title"/>
<meta content="<?= e($description) ?>" property="og:description"/>
<meta content="<?= e($url_absolue) ?>" property="og:url"/>
<meta content="<?= e($image_absolue) ?>" property="og:image"/>
<meta content="<?= e(MDS['nom']) ?>" property="og:site_name"/>
<meta content="<?= e(MDS_LANGUES[$langue]['locale']) ?>" property="og:locale"/>
<?php foreach (MDS_LANGUES as $code => $infos): ?>
<?php if ($code !== $langue): ?>
<meta content="<?= e($infos['locale']) ?>" property="og:locale:alternate"/>
<?php endif; ?>
<?php endforeach; ?>
<meta content="summary_large_image" name="twitter:card"/>
<meta content="<?= e($titre) ?>" name="twitter:title"/>
<meta content="<?= e($description) ?>" name="twitter:description"/>
<meta content="<?= e($image_absolue) ?>" name="twitter:image"/>

<?php /* `hreflang` dit aux moteurs que ces deux adresses sont la même page en
         deux langues, et non du contenu dupliqué. `x-default` désigne la
         version servie à qui n'a exprimé aucune préférence. */ ?>
<?php foreach (mds_alternatives() as $code => $adresse): ?>
<link href="<?= e($adresse) ?>" hreflang="<?= e($code) ?>" rel="alternate"/>
<?php endforeach; ?>
<link href="<?= e(rtrim(MDS['origine'], '/') . '/' . ltrim($url_page, '/')) ?>" hreflang="x-default" rel="alternate"/>

<link href="assets/images/favicon-32.png" rel="icon" sizes="32x32" type="image/png"/>
<link href="assets/images/favicon-180.png" rel="apple-touch-icon" sizes="180x180"/>
<meta content="#002a75" name="theme-color"/>

<?php /* Tailwind est servi en local : le site s'affiche sans accès réseau.
         La configuration doit suivre immédiatement — Tailwind la lit au
         démarrage, avant de générer les classes du document. */ ?>
<script src="assets/vendor/tailwind.js"></script>
<script src="assets/mds-tailwind.js"></script>
<link href="<?= e(mds_ressource('assets/mds.css')) ?>" rel="stylesheet"/>
