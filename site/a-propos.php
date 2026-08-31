<?php
declare(strict_types=1);

$page        = 'a-propos';
$titre       = 'À Propos — MDS Market Research';
$description = "Fondée à Douala, MDS Market Research allie rigueur méthodologique, "
    . "innovation analytique et ancrage local. Certifié ISO 20252.";
$image_og    = 'assets/images/simplice-ngampou.png';

require __DIR__ . '/partials/config.php';

/**
 * L'équipe MDS, dans l'ordre fourni par la direction.
 *
 * Une entrée par personne :
 *   'nom'        — nom complet
 *   'role'       — fonction
 *   'photo'      — nom de fichier dans assets/images/, ou null pour une
 *                  pastille aux initiales
 *   'experience' — ancienneté, ou null
 *   'langues'    — langues de travail, ou null
 *   'mission'    — rôle dans les processus internes (R1, R2, R3, S1), ou null
 *
 * Ces informations sont des champs et non un paragraphe : elles se répètent
 * d'une fiche à l'autre et se comparent d'un coup d'œil, ce qu'un texte suivi
 * ne permettrait pas.
 *
 * Le dirigeant garde sa section propre au-dessus : c'est lui que l'on vient
 * chercher en premier. Tant que ce tableau est vide, la rubrique ne s'affiche
 * pas du tout.
 */
$equipe = [
    [
        'nom'        => 'Franck Wanda',
        'role'       => 'Research Executive',
        'photo'      => 'equipe-franck-wanda.jpg',
        'experience' => '10 ans +',
        'langues'    => 'Français et Anglais',
        'mission'    => 'Pilote de R1 : Prospecter, Traiter les Appels d\'offre et conclure les contrats',
    ],
    [
        'nom'        => 'Felix TACHI',
        'role'       => 'Research Executive',
        'photo'      => 'equipe-felix-tachi.jpg',
        'experience' => '4 ans et plus',
        'langues'    => 'Français',
        'mission'    => 'Acteur de R1',
    ],
    [
        'nom'        => 'Gwendoline SIMO',
        'role'       => 'Responsable administration & finances',
        'photo'      => 'equipe-gwendoline-simo.jpg',
        'experience' => '8 ans et plus',
        'langues'    => 'Français',
        'mission'    => 'Pilote de R3 — facturer, encaisser et recouvrer',
    ],
    [
        'nom'        => 'Cyrille Stephane Assoukom',
        'role'       => 'Project Manager',
        'photo'      => 'equipe-cyrille-assoukom.jpg',
        'experience' => '15 ans +',
        'langues'    => 'Français et Anglais',
        'mission'    => 'Animatrice R1: Prospecter, Traiter les Appels d\'offres et conclure les contrats',
    ],
    [
        'nom'        => 'Pacôme EMMTEU',
        'role'       => 'Concepteur de scripts & analyste de données',
        'photo'      => 'equipe-pacome-emmteu.jpg',
        'experience' => '4 ans et plus',
        'langues'    => 'Français et anglais',
        'mission'    => 'Pilote de S1 — gérer les ressources',
    ],
    [
        'nom'        => 'Marie Josephine Ngo Ninde',
        'role'       => 'Responsable de l\'intégrité du Système de Management Qualité',
        'photo'      => 'equipe-jmarie-ngo-ninde.jpg',
        'experience' => '3 ans +',
        'langues'    => 'Français',
        'mission'    => 'Animatrice M1: Elaborer la Stratégie et Piloter L\'excellence Opérationnelle',
    ],
    [
        'nom'        => 'Marcel Vernier Tchio Kembou',
        'role'       => 'Responsable du Contrôle Qualité - Cartographe',
        'photo'      => 'equipe-marcel-tchio.jpg',
        'experience' => '3 ans +',
        'langues'    => 'Français',
        'mission'    => 'Pilote R2: Executer les contrats',
    ],
    [
        'nom'        => 'Merline KEMCHE',
        'role'       => 'Analyste',
        'photo'      => 'equipe-merline-kemche.jpg',
        'experience' => '2 ans et plus',
        'langues'    => 'Français',
        'mission'    => 'Actrice de R2',
    ],
    [
        'nom'        => 'MALLON NGAYA Martin',
        'role'       => 'Consultant Fied Team',
        'photo'      => 'equipe-martin-ngaya.jpg',
        'experience' => '2ans +',
        'langues'    => 'Français et Anglais',
        'mission'    => 'Animateur de R2: Executer les contrats',
    ],
];
?>
<!DOCTYPE html>
<html lang="<?= e(mds_langue()) ?>">
<head>
<?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body class="bg-surface font-body-md text-on-surface antialiased min-h-screen flex flex-col overflow-x-hidden">

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="flex-grow" id="contenu">

<!-- Hero -->
<section class="relative w-full min-h-[420px] flex items-center bg-surface-container-highest">
<div class="absolute inset-0 z-0">
<div class="bg-cover bg-center w-full h-full opacity-40" style="background-image: url('assets/images/equipe-mds.png')"></div>
<div class="absolute inset-0 bg-gradient-to-r from-surface via-surface/80 to-transparent"></div>
</div>
<div class="relative z-10 w-full max-w-container-max mx-auto px-gutter py-xxl">
<div class="max-w-2xl space-y-lg">
<span class="inline-block bg-primary-container/10 text-primary-container px-sm py-xs rounded-[6px] font-label-sm text-label-sm"><?= te('À PROPOS DE NOUS') ?></span>
<h1 class="font-display-lg text-headline-lg-mobile md:text-display-lg text-on-surface"><?= te("Notre engagement : l'excellence au cœur de l'Afrique") ?></h1>
<p class="font-body-lg text-body-lg text-on-surface-variant"><?= te("Nous sommes MDS Market Research. Plus qu'un cabinet d'études, nous sommes votre partenaire stratégique pour naviguer la complexité des marchés africains avec précision et fiabilité.") ?></p>
</div>
</div>
</section>

<!-- Histoire & mission -->
<section class="py-xxl w-full max-w-container-max mx-auto px-gutter">
<div class="grid grid-cols-1 md:grid-cols-2 gap-xl items-center">
<div class="space-y-lg reveal">
<h2 class="font-headline-lg text-headline-lg text-primary"><?= te('Transformer la donnée en décision stratégique.') ?></h2>
<p class="font-body-md text-body-md text-on-surface-variant"><?= te("Fondée à Douala, Cameroun, MDS Market Research s'est construite sur une conviction forte : l'intuition ne suffit plus dans un paysage économique en mutation rapide. Les décideurs ont besoin de certitudes, de données fiables et d'analyses pointues.") ?></p>
<p class="font-body-md text-body-md text-on-surface-variant"><?= te("Notre mission est de fournir cette certitude. En alliant méthodologies rigoureuses, technologies modernes de collecte et connaissance intime des contextes locaux, nous éclairons la voie pour les entreprises cherchant à croître et à consolider leur présence en Afrique centrale et de l'Ouest.") ?></p>
<div class="flex flex-col sm:flex-row gap-md pt-md">
<div class="flex-1 bg-surface-container-low p-md rounded-lg border-l-4 border-secondary-container">
<span class="block font-headline-md text-headline-md text-primary mb-xs">15+</span>
<span class="font-label-md text-label-md text-on-surface-variant"><?= te("Années d'expertise") ?></span>
</div>
<div class="flex-1 bg-surface-container-low p-md rounded-lg border-l-4 border-secondary-container">
<span class="block font-headline-md text-headline-md text-primary mb-xs">500+</span>
<span class="font-label-md text-label-md text-on-surface-variant"><?= te('Projets réalisés') ?></span>
</div>
</div>
</div>
<div class="rounded-xl overflow-hidden card-elevation-1 reveal delay-200">
<img alt="<?= te("L'équipe MDS Market Research lors d'une cérémonie de remise de certificats") ?>" class="w-full h-full object-cover" height="605" loading="lazy" src="assets/images/equipe-mds.png" width="897"/>
</div>
</div>
</section>

<!-- Valeurs -->
<section class="py-xxl bg-surface-container-low">
<div class="w-full max-w-container-max mx-auto px-gutter">
<div class="text-center max-w-3xl mx-auto mb-xl reveal">
<h2 class="font-headline-lg text-headline-lg text-primary mb-md"><?= te('Nos Valeurs Fondamentales') ?></h2>
<p class="font-body-md text-body-md text-on-surface-variant"><?= te('Les piliers qui soutiennent chaque étude, chaque analyse et chaque recommandation que nous livrons.') ?></p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
<div class="bg-surface p-lg rounded-xl card-elevation-1 bento-card flex flex-col items-start reveal">
<div class="w-12 h-12 rounded-full bg-primary-container/10 flex items-center justify-center mb-md text-primary-container">
<span class="material-symbols-outlined icon-fill" aria-hidden="true">rule</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-sm"><?= te('Rigueur Méthodologique') ?></h3>
<p class="font-body-md text-body-md text-on-surface-variant"><?= te("L'exactitude n'est pas une option. Nous appliquons des standards stricts à chaque étape, du design de l'échantillon à l'analyse statistique finale, garantissant des données incontestables.") ?></p>
</div>
<div class="bg-surface p-lg rounded-xl card-elevation-1 bento-card flex flex-col items-start reveal delay-100">
<div class="w-12 h-12 rounded-full bg-secondary-container/20 flex items-center justify-center mb-md text-secondary">
<span class="material-symbols-outlined icon-fill" aria-hidden="true">lightbulb</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-sm"><?= te('Innovation Analytique') ?></h3>
<p class="font-body-md text-body-md text-on-surface-variant"><?= te("Nous intégrons les dernières technologies d'étude de marché, des enquêtes mobiles géolocalisées à l'analyse sémantique avancée, pour capturer des insights plus profonds.") ?></p>
</div>
<div class="bg-surface p-lg rounded-xl card-elevation-1 bento-card flex flex-col items-start reveal delay-200">
<div class="w-12 h-12 rounded-full bg-tertiary-container/10 flex items-center justify-center mb-md text-tertiary-container">
<span class="material-symbols-outlined icon-fill" aria-hidden="true">public</span>
</div>
<h3 class="font-headline-md text-headline-md text-on-surface mb-sm"><?= te('Proximité Locale') ?></h3>
<p class="font-body-md text-body-md text-on-surface-variant"><?= te('Notre ancrage au Cameroun nous confère une compréhension intime des nuances culturelles, économiques et sociales indispensables pour interpréter correctement les comportements.') ?></p>
</div>
</div>
</div>
</section>

<!-- Certification ISO -->
<section class="py-xxl w-full max-w-container-max mx-auto px-gutter">
<div class="bg-primary rounded-xl overflow-hidden card-elevation-2 relative reveal">
<div class="relative z-10 p-xl flex flex-col md:flex-row items-center gap-xl">
<?php /* Les deux marques partagent la même largeur et le même cartouche blanc :
         sur un fond bleu nuit, deux fonds clairs de tailles différentes se
         liraient comme un décalage plutôt que comme une paire. Côte à côte sur
         mobile, l'une sous l'autre dès que le texte passe à droite.

         La largeur plafonne à 128 px. À 160 px, les deux cartouches et leur
         écart dépassaient la largeur utile de la carte sur un téléphone de
         360 px ; et ces marques appuient le propos, elles ne le remplacent
         pas — c'est le texte qui doit tenir la carte. */ ?>
<div class="flex flex-row md:flex-col items-center gap-md shrink-0">
<img alt="<?= te('Certification ISO 20252 délivrée à MDS par Cognicert') ?>" class="w-28 sm:w-32 h-auto object-contain bg-white rounded-lg p-sm" height="605" loading="lazy" src="assets/images/certification-iso-20252.png" width="857"/>
<img alt="<?= te('MDS est membre de la MSPA, Mystery Shopping Professionals Association') ?>" class="w-28 sm:w-32 h-auto object-contain bg-white rounded-lg p-sm" height="179" loading="lazy" src="assets/images/membre-mspa.png" width="303"/>
</div>
<?php /* Deux paragraphes plutôt qu'un : la norme et l'association ne
         couvrent pas le même terrain, et le second logo restait jusqu'ici
         sans un mot pour l'expliquer — sa seule légende était son texte de
         remplacement, que personne ne lit. */ ?>
<div class="text-on-primary space-y-md">
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg"><?= te("L'Exigence Certifiée : Norme ISO 20252") ?></h2>
<p class="font-body-md text-body-md opacity-90 max-w-3xl"><?= te("La qualité de nos processus est reconnue internationalement. La certification ISO 20252, spécifique aux études de marché, sociales et d'opinion, atteste de la rigueur de nos méthodologies de collecte, de la sécurité de nos données et de l'intégrité de nos analyses. C'est votre garantie d'une fiabilité sans compromis.") ?></p>
<p class="font-body-md text-body-md opacity-90 max-w-3xl"><?= te("MDS est également membre de la MSPA, la Mystery Shopping Professionals Association, l'association internationale du client mystère. Cette adhésion engage nos visites mystère sur son code de déontologie et sur les pratiques reconnues de la profession. Les deux références se complètent : la norme encadre nos études, l'association notre terrain mystère.") ?></p>
</div>
</div>
</div>
</section>

<!-- Le dirigeant -->
<section class="py-xxl w-full max-w-container-max mx-auto px-gutter">
<div class="grid grid-cols-1 md:grid-cols-[280px_1fr] gap-xl items-start">
<?php /* Portrait cadré en 4/5 et plafonné à 280 px : la photo pleine hauteur
         écrasait le texte, et la même proportion servira aux membres de
         l'équipe pour que la page reste régulière. */ ?>
<div class="rounded-xl overflow-hidden card-elevation-2 reveal aspect-[4/5] max-w-[280px] w-full">
<img alt="<?= te('Simplice NGAMPOU, CEO et fondateur de MDS Market Research') ?>" class="w-full h-full object-cover object-top" height="741" loading="lazy" src="assets/images/simplice-ngampou.png" width="858"/>
</div>
<div class="space-y-md reveal delay-100">
<div class="space-y-xs">
<span class="text-secondary font-label-sm text-label-sm uppercase tracking-wider"><?= te('CEO / Dirigeant') ?></span>
<h2 class="font-headline-lg text-headline-lg text-primary">Simplice NGAMPOU</h2>
</div>
<div class="space-y-md text-on-surface-variant font-body-md text-body-md">
<?php /* Les fragments sont traduits séparément parce que la mise en valeur
         (`font-semibold`, `italic`) coupe la phrase : le dictionnaire ne
         contient que du texte, jamais de balisage. Voir `i18n.php`. */ ?>
<p><?= te('MDS est dirigé par') ?> <span class="font-semibold text-on-surface">Simplice NGAMPOU</span>, <?= te('un professionnel chevronné avec plus de') ?> <span class="font-semibold text-on-surface"><?= te("22 ans d'expérience") ?></span> <?= te('en étude de marché et conseil marketing.') ?></p>
<p><?= te("Titulaire d'un Certificat d'Aptitude en Analyse Quantitative du") ?> <span class="italic">Burke Institute (Cincinnati, USA)</span> <?= te("et d'un Certificat d'Aptitude en Étude de Marché de l'Université de Géorgie (USA), Simplice possède les outils pour apporter des solutions à vos problématiques marketing et stratégiques.") ?></p>
<p><?= te('En tant que pilote du programme M1,') ?> <span class="italic"><?= te('« Developing Strategy and Driving Operational Excellence »') ?></span>, <?= te("il s'engage à combiner stratégie de haut niveau et exécution opérationnelle de qualité.") ?></p>
</div>
</div>
</div>
</section>

<?php if ($equipe !== []): ?>
<!-- L'équipe -->
<section class="pb-xxl w-full max-w-container-max mx-auto px-gutter">
<div class="max-w-3xl mb-xl reveal">
<span class="inline-block text-secondary font-label-sm text-label-sm uppercase tracking-wider mb-sm"><?= te("L'équipe") ?></span>
<h2 class="font-headline-lg text-headline-lg text-primary mb-md"><?= te('Celles et ceux qui mènent les études') ?></h2>
<p class="font-body-md text-body-md text-on-surface-variant"><?= te("Derrière chaque rapport, une équipe de terrain et d'analyse. Voici qui la compose.") ?></p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-lg">
<?php foreach ($equipe as $membre): ?>
<article class="bg-surface-container-lowest rounded-xl overflow-hidden card-elevation-1 bento-card border border-outline-variant flex flex-col reveal">
<?php /* Cadrage carré : les portraits fournis le sont, un 4/5 les rognerait
         au menton. */ ?>
<?php if (!empty($membre['photo'])): ?>
<div class="zoom-doux aspect-square bg-surface-container">
<img alt="<?= te('Portrait de') ?> <?= e($membre['nom']) ?>" class="w-full h-full object-cover" height="520" loading="lazy" src="assets/images/<?= e($membre['photo']) ?>" width="520"/>
</div>
<?php else: ?>
<?php /* Sans photo, une pastille aux initiales plutôt qu'un cadre vide :
         la grille garde son alignement. */ ?>
<div class="aspect-square bg-primary flex items-center justify-center">
<span class="font-display-lg text-display-lg text-accent" aria-hidden="true"><?= e(mds_initiales($membre['nom'])) ?></span>
</div>
<?php endif; ?>

<div class="p-lg flex flex-col gap-xs flex-grow">
<span class="font-label-sm text-label-sm text-secondary uppercase tracking-wider"><?= te($membre['role'] ?? '') ?></span>
<h3 class="font-headline-md text-headline-md text-on-surface mb-sm"><?= e($membre['nom']) ?></h3>

<dl class="font-body-md text-body-md text-on-surface-variant flex flex-col gap-sm mt-auto pt-sm border-t border-outline-variant">
<?php if (!empty($membre['experience'])): ?>
<div class="flex items-start gap-sm">
<span class="material-symbols-outlined text-[18px] text-outline mt-[2px] shrink-0" aria-hidden="true">work_history</span>
<div>
<dt class="sr-only"><?= te('Expérience') ?></dt>
<dd><?= te($membre['experience']) ?></dd>
</div>
</div>
<?php endif; ?>
<?php if (!empty($membre['langues'])): ?>
<div class="flex items-start gap-sm">
<span class="material-symbols-outlined text-[18px] text-outline mt-[2px] shrink-0" aria-hidden="true">translate</span>
<div>
<dt class="sr-only"><?= te('Langues de travail') ?></dt>
<dd><?= te($membre['langues']) ?></dd>
</div>
</div>
<?php endif; ?>
<?php if (!empty($membre['mission'])): ?>
<div class="flex items-start gap-sm">
<span class="material-symbols-outlined text-[18px] text-accent-dark mt-[2px] shrink-0" aria-hidden="true">flag</span>
<div>
<dt class="sr-only"><?= te('Rôle dans les processus') ?></dt>
<dd class="font-label-md text-label-md text-on-surface"><?= te($membre['mission']) ?></dd>
</div>
</div>
<?php endif; ?>
</dl>
</div>
</article>
<?php endforeach; ?>
</div>
</section>
<?php endif; ?>

<!-- Appel à l'action -->
<section class="py-xxl bg-surface-container-low border-t border-surface-variant">
<div class="w-full max-w-container-max mx-auto px-gutter text-center reveal">
<h2 class="font-headline-lg text-headline-lg text-primary mb-md"><?= te('Prêt à fiabiliser vos décisions ?') ?></h2>
<p class="font-body-md text-body-md text-on-surface-variant max-w-2xl mx-auto mb-lg"><?= te('Contacter notre équipe pour échanger sur vos enjeux spécifiques et découvrir comment nos méthodologies peuvent enrichir et orienter votre stratégie de développement.') ?></p>
<div class="flex flex-col sm:flex-row gap-md justify-center items-center">
<a class="btn-accent px-lg py-md font-label-md text-label-md card-elevation-1" href="contact.php"><?= te('Contactez-nous') ?></a>
<a class="btn-contour px-lg py-md font-label-md text-label-md" href="realisations.php"><?= te('Voir nos réalisations') ?></a>
</div>
</div>
</section>

<?php /* Les marques clientes ne figurent que sur cette page : elles étayent le
         propos « qui sommes-nous », et n'ont pas leur place sous un
         formulaire de contact ou une liste d'annonces. */ ?>
<?php require __DIR__ . '/partials/bandeau-partenaires.php'; ?>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>
</body>
</html>
