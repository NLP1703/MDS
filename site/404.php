<?php
declare(strict_types=1);

/**
 * Page d'erreur 404.
 *
 * Déclarée dans `.htaccess`. Sans elle, une URL erronée tombait sur la page
 * brute d'Apache — hors charte, en anglais, et sans aucun moyen de rebondir.
 *
 * Le code HTTP est réémis explicitement : appelée via `ErrorDocument`, cette
 * page hérite du bon statut, mais ouverte directement elle répondrait 200 —
 * et un moteur d'indexation la prendrait pour une page valide.
 */

http_response_code(404);

$page        = '';
$titre       = 'Page introuvable — MDS Market Research';
$description = "La page demandée n'existe pas ou a été déplacée.";

require __DIR__ . '/partials/config.php';
?>
<!DOCTYPE html>
<html lang="<?= e(mds_langue()) ?>">
<head>
<?php require __DIR__ . '/partials/head.php'; ?>
<meta content="noindex" name="robots"/>
</head>
<body class="bg-background text-on-background font-body-md antialiased min-h-screen flex flex-col overflow-x-hidden">

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="flex-grow flex items-center justify-center px-gutter py-xxl" id="contenu">
<div class="text-center max-w-2xl mx-auto space-y-lg">

<span class="material-symbols-outlined text-[64px] text-outline" aria-hidden="true">travel_explore</span>

<p class="font-display-lg text-display-lg text-primary">404</p>

<h1 class="font-headline-lg text-headline-lg text-on-surface"><?= te('Cette page est introuvable') ?></h1>

<p class="font-body-lg text-body-lg text-on-surface-variant">
    <?= te("L'adresse demandée n'existe pas, ou la page a été déplacée. Voici par où reprendre.") ?>
</p>

<nav aria-label="<?= te('Pages du site') ?>" class="grid grid-cols-1 sm:grid-cols-2 gap-md pt-md">
<?php foreach (MDS_MENU as $item): ?>
<a class="btn-contour px-lg py-md font-label-md text-label-md text-center" href="<?= e($item['url']) ?>"><?= te($item['libelle']) ?></a>
<?php endforeach; ?>
</nav>

<p class="font-body-md text-body-md text-on-surface-variant pt-md">
    <?= te("Besoin d'aide ?") ?>
    <a class="text-secondary hover:underline" href="mailto:<?= e(MDS['email']) ?>"><?= e(MDS['email']) ?></a>
    ·
    <a class="text-secondary hover:underline" href="<?= e(mds_tel(MDS['tel_fixe'])) ?>"><?= e(MDS['tel_fixe']) ?></a>
</p>

</div>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
