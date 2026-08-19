<?php
declare(strict_types=1);

/**
 * Détail d'une actualité.
 *
 * Rendue **côté serveur**, contrairement à la liste : le titre et le texte
 * doivent figurer dans le HTML livré, faute de quoi ni les moteurs de
 * recherche ni les aperçus de lien WhatsApp ne verraient l'annonce — ce qui
 * viderait de son sens le fait de la publier.
 *
 * Le contenu est donc récupéré ici, par un appel à l'API depuis le serveur.
 */

$page = 'actualites';

require __DIR__ . '/partials/config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

$actualite = null;
$panne     = false;

if ($id !== false && $id !== null) {
    /* Appel serveur à serveur, borné dans le temps : une API lente ne doit pas
       laisser le visiteur devant une page blanche. En cas d'échec, la page
       s'affiche quand même et le dit. */
    $contexte = stream_context_create([
        'http' => ['timeout' => 4, 'ignore_errors' => true, 'header' => "Accept: application/json\r\n"],
    ]);

    $brut = @file_get_contents(MDS['api'] . '/actualite?id=' . $id, false, $contexte);

    if ($brut === false) {
        $panne = true;
    } else {
        $donnees = json_decode($brut, true);
        if (is_array($donnees) && isset($donnees['id'])) {
            $actualite = $donnees;
        }
    }
}

if ($actualite !== null) {
    $titre       = $actualite['titre'] . ' — MDS Market Research';
    $description = $actualite['resume'] ?? ('Publié le ' . $actualite['dateLibelle'] . '.');
    // Adresse propre à cette annonce, pour que chaque partage pointe sur elle
    // et non sur la liste.
    $url_page    = 'actualite.php?id=' . $actualite['id'];
    if (!empty($actualite['image'])) {
        $image_og = $actualite['image'];
    }
} else {
    http_response_code($panne ? 503 : 404);
    $titre       = 'Actualité introuvable — MDS Market Research';
    $description = "Cette annonce n'existe pas ou n'est plus publiée.";
}

/** Objet du courriel de réponse, pré-rempli avec l'intitulé de l'annonce. */
$sujet_reponse = $actualite !== null
    ? rawurlencode($actualite['titre'])
    : '';
?>
<!DOCTYPE html>
<html lang="<?= e(mds_langue()) ?>">
<head>
<?php require __DIR__ . '/partials/head.php'; ?>
<?php if ($actualite === null): ?>
<meta content="noindex" name="robots"/>
<?php endif; ?>
</head>
<body class="bg-background text-on-background font-body-md antialiased min-h-screen flex flex-col overflow-x-hidden">

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="flex-grow px-gutter py-xxl w-full max-w-container-max mx-auto" id="contenu">

<?php if ($actualite === null): ?>

<div class="max-w-2xl mx-auto text-center space-y-lg py-xxl">
<span class="material-symbols-outlined text-[56px] text-outline" aria-hidden="true">
    <?= $panne ? 'cloud_off' : 'search_off' ?>
</span>
<h1 class="font-headline-lg text-headline-lg text-primary">
    <?= $panne ? te('Actualités momentanément indisponibles') : te('Cette annonce est introuvable') ?>
</h1>
<p class="font-body-lg text-body-lg text-on-surface-variant">
    <?= $panne
        ? te("Le serveur n'a pas répondu. Réessayez dans un instant.")
        : te("Elle n'existe pas, ou elle n'est plus publiée.") ?>
</p>
<a class="btn-accent inline-flex items-center gap-sm px-lg py-md font-label-md text-label-md" href="actualites.php">
<span class="material-symbols-outlined text-[18px]" aria-hidden="true">arrow_back</span>
    <?= te('Toutes les actualités') ?>
</a>
</div>

<?php else: ?>

<article class="max-w-3xl mx-auto">

<nav aria-label="<?= te("Fil d'Ariane") ?>" class="mb-lg">
<a class="inline-flex items-center gap-xs text-secondary font-label-md text-label-md hover:gap-sm transition-all" href="actualites.php">
<span class="material-symbols-outlined text-[18px]" aria-hidden="true">arrow_back</span>
    <?= te('Toutes les actualités') ?>
</a>
</nav>

<header class="space-y-md pb-lg mb-lg border-b border-outline-variant reveal">
<div class="flex flex-wrap items-center gap-md">
<span class="inline-block bg-accent/15 text-accent-dark border border-accent/30 px-sm py-xs rounded-[6px] font-label-sm text-label-sm">
    <?= e($actualite['categorieLibelle']) ?>
</span>
<time class="font-label-md text-label-md text-on-surface-variant flex items-center gap-xs" datetime="<?= e($actualite['date']) ?>">
<span class="material-symbols-outlined text-[16px]" aria-hidden="true">calendar_month</span>
    <?= te('Publié le') ?> <?= e($actualite['dateLibelle']) ?>
</time>
</div>
<h1 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary"><?= e($actualite['titre']) ?></h1>
<?php if (!empty($actualite['resume'])): ?>
<p class="font-body-lg text-body-lg text-on-surface-variant"><?= e($actualite['resume']) ?></p>
<?php endif; ?>
</header>

<?php if (!empty($actualite['image'])): ?>
<div class="rounded-xl overflow-hidden card-elevation-1 mb-lg reveal">
<img alt="<?= e($actualite['imageAlt'] ?? '') ?>" class="w-full h-auto object-cover" loading="lazy" src="<?= e($actualite['image']) ?>"/>
</div>
<?php endif; ?>

<?php if (!empty($actualite['contenu'])): ?>
<div class="font-body-md text-body-md text-on-surface-variant space-y-md reveal">
<?= mds_rendre_contenu((string) $actualite['contenu']) ?>
</div>
<?php else: ?>
<?php /* Ni texte complet ni lien externe. Plutôt qu'une page creuse, on donne
         l'action attendue : pour un recrutement ou un appel d'offres, c'est
         répondre. */ ?>
<div class="bg-surface-container-low rounded-xl border border-outline-variant p-xl reveal">
<h2 class="font-headline-md text-headline-md text-primary mb-sm"><?= te('Comment répondre') ?></h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-lg">
    <?= te('Le dossier complet de cette annonce est communiqué sur demande. Écrivez-nous en rappelant son intitulé, ou appelez-nous') ?> <?= te(MDS['horaires']) ?>.
</p>
<div class="flex flex-col sm:flex-row gap-md">
<a class="btn-accent inline-flex items-center justify-center gap-sm px-lg py-md font-label-md text-label-md"
   href="mailto:<?= e(MDS['email']) ?>?subject=<?= $sujet_reponse ?>">
<span class="material-symbols-outlined text-[18px]" aria-hidden="true">mail</span>
    <?= te('Répondre par courriel') ?>
</a>
<a class="btn-contour inline-flex items-center justify-center gap-sm px-lg py-md font-label-md text-label-md"
   href="<?= e(mds_tel(MDS['tel_fixe'])) ?>">
<span class="material-symbols-outlined text-[18px]" aria-hidden="true">call</span>
    <?= e(MDS['tel_fixe']) ?>
</a>
</div>
</div>
<?php endif; ?>

<?php if (!empty($actualite['lienExterne'])): ?>
<?php /* La source d'origine, présentée comme telle et non comme l'action
         principale : le texte est déjà sur cette page. */ ?>
<p class="mt-xl pt-lg border-t border-outline-variant font-label-md text-label-md text-on-surface-variant">
    <?= te("Annonce publiée à l'origine sur") ?>
    <a class="text-secondary hover:underline inline-flex items-center gap-xs" href="<?= e($actualite['lienExterne']) ?>" rel="noopener" target="_blank">mds-cmr.com<span class="material-symbols-outlined text-[16px]" aria-hidden="true">open_in_new</span></a>
</p>
<?php endif; ?>

<footer class="mt-xxl pt-lg border-t border-outline-variant flex flex-wrap gap-md justify-between items-center">
<a class="inline-flex items-center gap-xs text-secondary font-label-md text-label-md hover:gap-sm transition-all" href="actualites.php">
<span class="material-symbols-outlined text-[18px]" aria-hidden="true">arrow_back</span>
    <?= te('Toutes les actualités') ?>
</a>
<a class="inline-flex items-center gap-xs text-secondary font-label-md text-label-md hover:gap-sm transition-all" href="contact.php">
    <?= te('Nous contacter') ?> <span class="material-symbols-outlined text-[18px]" aria-hidden="true">arrow_forward</span>
</a>
</footer>

</article>

<?php endif; ?>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
