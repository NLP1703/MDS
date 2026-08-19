<?php
declare(strict_types=1);

$page        = 'expertise';
$titre       = 'Notre Expertise — MDS Market Research';
$description = "Les 5 pôles d'excellence de MDS : marketing & branding, innovation, "
    . "trade & distribution, études sociales et médias, data analytics.";

require __DIR__ . '/partials/config.php';

/**
 * Le déroulé d'une étude.
 *
 * Une vraie séquence — chaque étape conditionne la suivante — ce qui justifie
 * la numérotation et la frise. Un ordre décoratif posé sur des éléments non
 * ordonnés tromperait le lecteur.
 */
$etapes = [
    ['Cadrage du besoin',        "Nous partons de la décision à prendre, pas du questionnaire. Objectifs, hypothèses et critères de réussite sont arrêtés avec vous."],
    ['Conception méthodologique', "Plan d'échantillonnage, questionnaire, mode de collecte et taille d'échantillon calibrés sur la précision attendue."],
    ['Collecte terrain',          "Nos enquêteurs formés déploient la collecte sur tablette, avec horodatage et géolocalisation de chaque interview."],
    ['Contrôle qualité',          "Ré-interviews de contrôle, détection des incohérences et validation avant tout traitement. C'est ici que se joue la fiabilité."],
    ['Analyse',                   'Redressement, traitements statistiques et modélisation, jusqu\'aux croisements qui répondent à la question de départ.'],
    ['Restitution',               'Rapport, tableaux de bord et recommandations actionnables, présentés à vos équipes.'],
];
?>
<!DOCTYPE html>
<html lang="<?= e(mds_langue()) ?>">
<head>
<?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body class="bg-background text-on-background font-body-md antialiased min-h-screen flex flex-col overflow-x-hidden">

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="flex-grow" id="contenu">

<!-- Hero -->
<section class="py-xxl px-gutter max-w-container-max mx-auto reveal">
<div class="text-center max-w-3xl mx-auto space-y-lg">
<h1 class="font-display-lg text-headline-lg-mobile md:text-display-lg text-primary"><?= te('Notre Expertise') ?></h1>
<p class="font-body-lg text-body-lg text-on-surface-variant"><?= te("MDS Market Research déploie 5 pôles d'excellence pour transformer vos données en stratégies actionnables. Une approche institutionnelle alliant rigueur analytique et innovation.") ?></p>
</div>
</section>

<!-- Grille des 5 pôles -->
<section class="pb-xxl px-gutter max-w-container-max mx-auto">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">

<!-- 1. Marketing Strategy & Branding -->
<div class="bg-surface-container-lowest rounded-xl p-lg card-elevation-1 bento-card md:col-span-2 lg:col-span-2 reveal relative overflow-hidden">
<div class="absolute top-0 right-0 w-32 h-32 bg-secondary opacity-5 rounded-bl-full"></div>
<div class="flex items-start gap-md">
<div class="p-sm bg-primary-fixed rounded-lg text-primary shrink-0">
<span class="material-symbols-outlined text-3xl" aria-hidden="true">bar_chart</span>
</div>
<div class="space-y-sm">
<h2 class="font-headline-md text-headline-md text-on-surface"><?= te('Marketing Strategy & Branding') ?></h2>
<p class="font-body-md text-on-surface-variant"><?= te("Positionnement de marque, études de perception et élaboration de stratégies marketing robustes pour asseoir votre leadership sur le marché. Nous analysons l'ADN de votre marque pour optimiser son impact.") ?></p>
<ul class="list-disc list-inside text-on-surface-variant font-body-md mt-sm">
<li><?= te('Brand Equity & Tracking') ?></li>
<li><?= te('Études de positionnement') ?></li>
<li><?= te('Stratégies Go-to-Market') ?></li>
</ul>
</div>
</div>
</div>

<!-- 2. Innovation & Créativité -->
<div class="bg-surface-container-lowest rounded-xl p-lg card-elevation-1 bento-card reveal">
<div class="flex flex-col items-start gap-md h-full">
<div class="p-sm bg-tertiary-fixed rounded-lg text-tertiary-container">
<span class="material-symbols-outlined icon-fill text-3xl" aria-hidden="true">lightbulb</span>
</div>
<div class="space-y-sm flex-grow">
<h2 class="font-headline-md text-headline-md text-on-surface"><?= te('Innovation & Créativité') ?></h2>
<p class="font-body-md text-on-surface-variant"><?= te("Accélérez votre développement produit via des phases d'idéation structurées et des tests de concept rigoureux.") ?></p>
</div>
<span class="inline-block px-sm py-xs bg-tertiary-fixed-dim bg-opacity-20 text-tertiary font-label-sm rounded-md"><?= te('Idéation & Tests') ?></span>
</div>
</div>

<!-- 3. Trade & Distribution Audit -->
<div class="bg-surface-container-lowest rounded-xl p-lg card-elevation-1 bento-card reveal">
<div class="flex flex-col items-start gap-md h-full">
<div class="p-sm bg-secondary-fixed rounded-lg text-secondary">
<span class="material-symbols-outlined text-3xl" aria-hidden="true">shopping_cart</span>
</div>
<div class="space-y-sm flex-grow">
<h2 class="font-headline-md text-headline-md text-on-surface"><?= te('Trade & Distribution Audit') ?></h2>
<p class="font-body-md text-on-surface-variant"><?= te("Contrôle qualité de l'exécution en point de vente. Retail Audit, relevés de prix et Mystery Shopping pour garantir l'excellence opérationnelle.") ?></p>
</div>
<span class="inline-block px-sm py-xs bg-secondary-fixed bg-opacity-20 text-secondary font-label-sm rounded-md"><?= te('Retail & Mystery') ?></span>
</div>
</div>

<!-- 4. Études Sociales & Médias -->
<div class="bg-surface-container-lowest rounded-xl p-lg card-elevation-1 bento-card md:col-span-2 lg:col-span-2 reveal relative overflow-hidden">
<div class="absolute bottom-0 right-0 w-48 h-48 bg-primary-container opacity-5 rounded-tl-full"></div>
<div class="flex items-start gap-md">
<div class="p-sm bg-error-container rounded-lg text-on-error-container shrink-0">
<span class="material-symbols-outlined text-3xl" aria-hidden="true">groups</span>
</div>
<div class="space-y-sm">
<h2 class="font-headline-md text-headline-md text-on-surface"><?= te('Études Sociales & Médias') ?></h2>
<p class="font-body-md text-on-surface-variant"><?= te("Décryptage de l'opinion publique, analyse des comportements sociétaux et mesure d'audience. Comprenez les dynamiques sociales qui influencent votre écosystème.") ?></p>
<ul class="list-disc list-inside text-on-surface-variant font-body-md mt-sm">
<li><?= te("Sondages d'opinion publique") ?></li>
<li><?= te("Études d'audience média") ?></li>
<li><?= te('Analyses socio-culturelles') ?></li>
</ul>
</div>
</div>
</div>

<!-- 5. Data Analytics & Business Intelligence -->
<div class="bg-surface-container-lowest rounded-xl p-lg card-elevation-1 bento-card lg:col-span-3 reveal relative overflow-hidden border border-outline-variant border-opacity-30">
<div class="absolute inset-0 bg-gradient-to-r from-surface to-surface-container-low opacity-50 z-0"></div>
<div class="relative z-10 flex flex-col md:flex-row items-center md:items-start gap-lg md:gap-xl">
<div class="p-lg bg-primary rounded-xl text-on-primary shrink-0">
<span class="material-symbols-outlined text-5xl" aria-hidden="true">database</span>
</div>
<div class="space-y-md">
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg text-primary"><?= te('Data Analytics & Business Intelligence') ?></h2>
<p class="font-body-lg text-on-surface-variant max-w-3xl"><?= te('Transformation des données brutes en intelligence économique. Nos outils de BI et nos modèles prédictifs vous offrent une vision claire pour des prises de décision stratégiques éclairées et sécurisées.') ?></p>
<div class="flex flex-wrap gap-sm mt-md">
<span class="px-md py-sm bg-surface rounded-full text-primary font-label-sm border border-outline-variant border-opacity-50"><?= te('Modélisation Prédictive') ?></span>
<span class="px-md py-sm bg-surface rounded-full text-primary font-label-sm border border-outline-variant border-opacity-50"><?= te('Dashboards Interactifs') ?></span>
<span class="px-md py-sm bg-surface rounded-full text-primary font-label-sm border border-outline-variant border-opacity-50"><?= te('Data Mining') ?></span>
</div>
</div>
</div>
</div>

</div>
</section>

<!-- Le déroulé d'une étude -->
<section class="py-xxl bg-surface-container-low">
<div class="px-gutter max-w-container-max mx-auto">
<div class="text-center max-w-3xl mx-auto mb-xl reveal">
<span class="inline-block text-secondary font-label-sm text-label-sm uppercase tracking-wider mb-sm"><?= te('Méthodologie') ?></span>
<h2 class="font-headline-lg text-headline-lg text-primary mb-md"><?= te("Le déroulé d'une étude") ?></h2>
<p class="font-body-md text-body-md text-on-surface-variant"><?= te("Six étapes, dans cet ordre. Chacune conditionne la suivante — c'est ce qui rend une donnée opposable.") ?></p>
</div>

<ol class="frise flex flex-col gap-xl max-w-4xl mx-auto list-none p-0">
<?php foreach ($etapes as $i => [$nom, $texte]): ?>
<li class="etape <?= $i % 2 ? 'reveal-droite' : 'reveal-gauche' ?>">
<span class="etape-pastille" aria-hidden="true"><?= $i + 1 ?></span>
<h3 class="font-headline-md text-headline-md text-on-surface mb-sm"><?= te($nom) ?></h3>
<p class="font-body-md text-body-md text-on-surface-variant"><?= te($texte) ?></p>
</li>
<?php endforeach; ?>
</ol>
</div>
</section>

<!-- Notre équipe -->
<section class="py-xxl px-gutter max-w-container-max mx-auto reveal">
<div class="text-center max-w-3xl mx-auto space-y-lg mb-xl">
<h2 class="font-display-lg text-headline-lg-mobile md:text-display-lg text-primary"><?= te('Notre Équipe') ?></h2>
<p class="font-body-lg text-on-surface-variant"><?= te("Une équipe d'experts passionnés, dédiée à transformer vos données en leviers de croissance stratégique.") ?></p>
</div>
<div class="relative rounded-xl overflow-hidden card-elevation-1">
<img alt="<?= te("L'équipe MDS Market Research lors d'une cérémonie de remise de certificats") ?>" class="w-full h-auto object-cover" height="605" loading="lazy" src="assets/images/equipe-mds.png" width="897"/>
<div class="absolute inset-0 bg-gradient-to-t from-primary/20 to-transparent pointer-events-none"></div>
</div>
</section>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
