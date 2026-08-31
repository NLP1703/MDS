<?php
declare(strict_types=1);

$page        = 'accueil';
$titre       = 'MDS Market Research — Marketing & Distribution Services';
$description = "Cabinet d'études de marché à Douala : marketing, innovation, trade & "
    . "distribution, études sociales et médias, data analytics. 20 ans d'expertise, certifié ISO 20252.";
$image_og    = 'assets/images/equipe-mds.png';

require __DIR__ . '/partials/config.php';

/** Les 4 arguments de la page d'accueil. */
$arguments = [
    ['tune',      'primary-container', 'bg-primary-container/10',  'Stratégies personnalisées', 'Des stratégies sur-mesure et efficaces'],
    ['verified',  'secondary',         'bg-secondary-container/20', 'Expertise approuvée',      'Une expertise de plus de 20 ans dans les études de marché'],
    ['database',  'tertiary-container', 'bg-tertiary-container/10', 'Données fiables',          'Des insights basés sur des données fiables et actualisées'],
    ['handshake', 'primary-container', 'bg-primary-container/10',  'Accompagnement complet',   'Un accompagnement de bout en bout pour booster votre croissance'],
];

/**
 * Les 5 activités MDS, chacune avec sa marque dédiée.
 *
 * Chacune porte désormais une photographie plutôt qu'un pictogramme : cinq
 * icônes de la même famille se ressemblent trop pour distinguer les pôles au
 * premier coup d'œil, là où une boutique et un tableau de post-its se lisent
 * instantanément.
 */
$activites = [
    ['photo-marche.jpg',     'Étal de fruits sur un marché africain, vendeurs au travail',           'Marketing',            'MDS Marketing',                   'Plans marketing : enquêtes, estimation, positionnement, segmentation, mesure client.', 'Données – Stratégie – Croissance'],
    ['photo-innovation.jpg', 'Notes adhésives couvertes d\'idées sur une table de travail',          'Innovation',           'MDS Innovation & Créativité',     'Tests de produits, évaluation publicitaire et développement de nouveaux produits.',    'Idées – Concepts – Produits'],
    ['photo-boutique.jpg',   'Commerçant dans sa boutique de quartier, rayonnages garnis',           'Trade & Distribution', 'MDS Distribution & Trade',        'Recensement, études, audits et soutien en points de vente.',                            'Insights – Distribution – Optimisation'],
    ['photo-social.jpg',     'Groupe de femmes en discussion autour d\'un ordinateur portable',      'Études sociales',      'MDS Études Sociales & Médias',    "Études média, comportement, sociales et sondages d'opinion.",                           'Comportements – Stratégies – Médias'],
    ['photo-analyse.jpg',    'Trois professionnelles en réunion de travail avec ordinateurs',        'Analytics',            'MDS Analytics & Data Management', 'Échantillonnage, numérisation, pondération et analyse.',                                'Données – Analyses – Décisions'],
];

/** Les chiffres clés — la valeur finale est animée depuis 0 côté navigateur. */
$chiffres = [
    ['25000', '+', 'Enquêtes par an'],
    ['30',    '',  'Projets par an'],
    ['640',   '',  'Agents de collecte formés'],
    ['100',   '+', 'Appareils connectés'],
];

/** Les actualités. Aucune page d'article n'existe : pas de lien « Lire plus ». */
$actualites = [
    ['Carrière',       'secondary',         '2026-07-23', '23 juillet 2026', 'Recrutement : Community Manager (H/F)'],
    ['Carrière',       'secondary',         '2026-06-12', '12 juin 2026',    'Recrutement : Community Manager (H/F)'],
    ["Appel d'offres", 'tertiary-container', '2026-04-08', '8 avril 2026',    "Appel d'offres : Sauvegarde, protection des données & contrôle d'accès réseau"],
    ["Appel d'offres", 'tertiary-container', '2026-04-08', '8 avril 2026',    "Appel d'offres : Formation, gestion et mise à jour de site internet"],
    ["Appel d'offres", 'tertiary-container', '2026-04-03', '3 avril 2026',    "Appel d'offres : Maintenance énergie solaire"],
];
?>
<!DOCTYPE html>
<html lang="<?= e(mds_langue()) ?>">
<head>
<?php require __DIR__ . '/partials/head.php'; ?>
<style>
    /* Motif du bandeau, en SVG inline pour ne dépendre d'aucun fichier. */
    .motif-grille {
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='40' height='40'><path d='M0 40L40 0H0z' fill='white' fill-opacity='0.05'/></svg>");
    }
</style>
</head>
<body class="bg-background text-on-background font-body-md antialiased min-h-screen flex flex-col overflow-x-hidden">

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="flex-grow" id="contenu">

<!-- ═══ Hero ═══ -->
<?php /* Photographie pleine largeur sous voile encre. Le dégradé part opaque à
         gauche pour garantir le contraste du texte, et s'ouvre à droite pour
         laisser voir la scène. */ ?>
<section class="relative min-h-[520px] flex items-center overflow-hidden">
<img alt="Réunion de travail autour d'une grande table, ordinateurs portables ouverts" class="parallaxe absolute inset-0 w-full h-full object-cover" height="935" src="assets/images/photo-reunion.jpg" width="1400"/>
<div class="absolute inset-0 bg-gradient-to-r from-primary via-primary/90 to-primary/30"></div>
<div class="relative z-10 px-gutter py-xxl w-full max-w-container-max mx-auto">
<div class="max-w-2xl space-y-lg">
<span class="inline-block bg-accent/15 text-accent px-md py-xs rounded-full font-label-sm text-label-sm tracking-wider"><?= e(strtoupper(MDS['nom_long'])) ?></span>
<h1 class="mots-anime font-display-lg text-headline-lg-mobile md:text-display-lg text-on-primary"><?= te('Bienvenue chez') ?> <?= e(MDS['nom_long']) ?></h1>
<p class="font-body-lg text-body-lg text-primary-fixed-dim"><?= te('Nous vous accompagnons à développer votre entreprise…') ?></p>
<div class="flex flex-col sm:flex-row gap-md pt-sm">
<a class="btn-accent px-lg py-md font-label-md text-label-md card-elevation-1 text-center" href="expertise.php"><?= te("Que faisons-nous\u{A0}?") ?></a>
<a class="px-lg py-md font-label-md text-label-md text-center rounded-lg border-[1.5px] border-on-primary/40 text-on-primary hover:border-secondary-container transition-colors pressable" href="realisations.php"><?= te('Nos réalisations') ?></a>
</div>
<div class="flex flex-wrap gap-xl pt-lg mt-lg border-t border-on-primary/20">
<div><span class="block font-headline-md text-headline-md text-accent">25 000+</span><span class="font-label-sm text-label-sm text-primary-fixed-dim"><?= te('enquêtes par an') ?></span></div>
<div><span class="block font-headline-md text-headline-md text-accent">640</span><span class="font-label-sm text-label-sm text-primary-fixed-dim"><?= te('agents formés') ?></span></div>
<div><span class="block font-headline-md text-headline-md text-accent">ISO 20252</span><span class="font-label-sm text-label-sm text-primary-fixed-dim"><?= te('certification') ?></span></div>
<?php /* « membre » et non « certification » : le macaron MSPA atteste d'une
         adhésion à l'association, pas d'un audit de conformité. Les deux
         tuiles se ressemblent, ce qu'elles annoncent ne se vaut pas. */ ?>
<div><span class="block font-headline-md text-headline-md text-accent">MSPA</span><span class="font-label-sm text-label-sm text-primary-fixed-dim"><?= te('membre') ?></span></div>
</div>
</div>
</div>
</section>

<!-- ═══ Les 4 arguments ═══ -->
<section class="px-gutter py-xxl w-full max-w-container-max mx-auto">
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-lg">
<?php foreach ($arguments as $i => [$icone, $couleur, $fond, $titre_arg, $texte]): ?>
<div class="bg-surface-container-lowest p-lg rounded-xl card-elevation-1 bento-card border border-surface-variant reveal<?= $i ? ' delay-' . ($i * 100) : '' ?>">
<div class="w-12 h-12 rounded-full <?= $fond ?> flex items-center justify-center mb-md text-<?= $couleur ?>">
<span class="material-symbols-outlined icon-fill" aria-hidden="true"><?= $icone ?></span>
</div>
<h2 class="font-headline-md text-headline-md text-on-surface mb-sm"><?= te($titre_arg) ?></h2>
<p class="font-body-md text-body-md text-on-surface-variant"><?= te($texte) ?></p>
</div>
<?php endforeach; ?>
</div>
</section>

<!-- ═══ Nous proposons ═══ -->
<section class="py-xxl bg-surface-container-low">
<div class="px-gutter w-full max-w-container-max mx-auto">
<div class="text-center max-w-3xl mx-auto mb-xl reveal">
<span class="inline-block text-secondary font-label-sm text-label-sm uppercase tracking-wider mb-sm"><?= te('Nous proposons') ?></span>
<h2 class="font-headline-lg text-headline-lg text-primary mb-md"><?= te('Une gamme étendue de solutions pour répondre à tous vos besoins') ?></h2>
<p class="font-body-md text-body-md text-on-surface-variant"><?= te('MDS est spécialisé dans') ?> <strong class="text-on-surface"><?= te('5 activités') ?></strong> <?= te("de recherche, avec une marque dédiée à chacune d'elles.") ?></p>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
<?php foreach ($activites as [$photo, $alt, $etiquette, $nom, $texte, $tagline]): ?>
<article class="bg-surface rounded-xl overflow-hidden card-elevation-1 bento-card border border-outline-variant flex flex-col reveal">
<div class="zoom-doux relative aspect-[4/3]">
<img alt="<?= e($alt) ?>" class="w-full h-full object-cover" height="667" loading="lazy" src="assets/images/<?= e($photo) ?>" width="1000"/>
<div class="absolute inset-0 bg-gradient-to-t from-primary/75 via-primary/10 to-transparent"></div>
<span class="absolute left-md bottom-md bg-accent text-on-accent font-label-sm text-label-sm px-sm py-xs rounded-[6px] tracking-wide"><?= te($etiquette) ?></span>
</div>
<div class="p-lg flex flex-col flex-grow">
<h3 class="font-headline-md text-headline-md text-on-surface mb-sm"><?= te($nom) ?></h3>
<p class="font-body-md text-body-md text-on-surface-variant mb-lg flex-grow"><?= te($texte) ?></p>
<a class="inline-flex items-center gap-xs text-secondary font-label-md text-label-md hover:gap-sm transition-all mb-md" href="expertise.php">
    <?= te('Plus') ?> <span class="material-symbols-outlined text-[18px]" aria-hidden="true">arrow_forward</span>
</a>
<span class="font-label-sm text-label-sm text-secondary border-t border-outline-variant pt-md"><?= te($tagline) ?></span>
</div>
</article>
<?php endforeach; ?>

<article class="bg-primary rounded-xl card-elevation-2 relative overflow-hidden flex flex-col justify-center p-lg reveal">
<div class="absolute inset-0 motif-grille bg-repeat opacity-60"></div>
<div class="relative z-10 text-on-primary space-y-md">
<h3 class="font-headline-md text-headline-md"><?= te('Un besoin précis ?') ?></h3>
<p class="font-body-md text-body-md opacity-90"><?= te("Découvrez le détail de nos cinq pôles d'excellence et la méthodologie qui les sous-tend.") ?></p>
<a class="btn-accent inline-flex items-center gap-sm px-lg py-sm font-label-md text-label-md" href="expertise.php">
    <?= te('Notre expertise') ?> <span class="material-symbols-outlined text-[18px]" aria-hidden="true">arrow_forward</span>
</a>
</div>
</article>
</div>
</div>
</section>

<!-- ═══ Valeurs ═══ -->
<section class="px-gutter py-xxl w-full max-w-container-max mx-auto">
<div class="bg-primary rounded-xl card-elevation-2 relative overflow-hidden reveal">
<div class="absolute inset-0 motif-grille bg-repeat opacity-60"></div>
<div class="relative z-10 text-center text-on-primary px-lg py-xxl space-y-md">
<p class="font-headline-lg text-headline-lg-mobile md:text-headline-lg"><?= te("Intégrité – Confidentialité – Apprenance – Esprit d'Équipe") ?></p>
<p class="font-body-lg text-body-lg opacity-90"><?= te("Vous méritez l'excellence") ?></p>
</div>
</div>
</section>

<!-- ═══ Mission / Vision / Stratégie ═══ -->
<section class="px-gutter py-xxl w-full max-w-container-max mx-auto">
<div class="max-w-4xl mx-auto reveal">
<div class="flex flex-wrap justify-center gap-sm mb-xl" id="ongletsMvs" role="tablist">
<button aria-controls="panneau-mission" aria-selected="true" class="onglet actif px-lg py-sm rounded-full font-label-md text-label-md cursor-pointer transition-colors" data-panneau="mission" id="onglet-mission" role="tab" type="button"><?= te('Notre Mission') ?></button>
<button aria-controls="panneau-vision" aria-selected="false" class="onglet px-lg py-sm rounded-full font-label-md text-label-md cursor-pointer transition-colors" data-panneau="vision" id="onglet-vision" role="tab" type="button"><?= te('Notre Vision') ?></button>
<button aria-controls="panneau-strategie" aria-selected="false" class="onglet px-lg py-sm rounded-full font-label-md text-label-md cursor-pointer transition-colors" data-panneau="strategie" id="onglet-strategie" role="tab" type="button"><?= te('Notre Stratégie') ?></button>
</div>
<div class="bg-surface-container-lowest rounded-xl card-elevation-1 border border-surface-variant p-xl min-h-[180px] flex items-center">
<div aria-labelledby="onglet-mission" class="panneau-mvs w-full text-center space-y-md" data-panneau="mission" id="panneau-mission" role="tabpanel">
<span class="material-symbols-outlined icon-fill text-[40px] text-primary-container" aria-hidden="true">flag</span>
<h2 class="font-headline-md text-headline-md text-primary"><?= te('Notre Mission') ?></h2>
<p class="font-body-lg text-body-lg text-on-surface-variant"><?= te('Mettre à la disposition des décideurs des informations de qualité — fiable, récente, exhaustive, disponible — au juste prix.') ?></p>
</div>
<div aria-labelledby="onglet-vision" class="panneau-mvs hidden w-full text-center space-y-md" data-panneau="vision" id="panneau-vision" role="tabpanel">
<span class="material-symbols-outlined icon-fill text-[40px] text-secondary" aria-hidden="true">visibility</span>
<h2 class="font-headline-md text-headline-md text-primary"><?= te('Notre Vision') ?></h2>
<p class="font-body-lg text-body-lg text-on-surface-variant"><?= te("Être le partenaire de choix dans la prise de décision des entreprises présentes en Afrique centrale et de l'Ouest.") ?></p>
</div>
<div aria-labelledby="onglet-strategie" class="panneau-mvs hidden w-full text-center space-y-md" data-panneau="strategie" id="panneau-strategie" role="tabpanel">
<span class="material-symbols-outlined icon-fill text-[40px] text-tertiary-container" aria-hidden="true">route</span>
<h2 class="font-headline-md text-headline-md text-primary"><?= te('Notre Stratégie') ?></h2>
<p class="font-body-lg text-body-lg text-on-surface-variant"><?= te("Produire une information fiable et personnalisée, en nous appuyant sur les technologies de collecte et d'analyse les plus récentes.") ?></p>
</div>
</div>
</div>
</section>

<!-- ═══ Bandeau d'appel ═══ -->
<section class="px-gutter pb-xxl w-full max-w-container-max mx-auto">
<div class="bg-surface-container-low rounded-xl border border-outline-variant p-xl flex flex-col md:flex-row items-center justify-between gap-lg reveal">
<div class="flex items-center gap-md text-center md:text-left">
<span class="material-symbols-outlined icon-fill text-[40px] text-secondary shrink-0" aria-hidden="true">support_agent</span>
<p class="font-body-lg text-body-lg text-on-surface-variant"><?= te('Appelez pour toute demande :') ?> <?= te(MDS['horaires']) ?>.</p>
</div>
<a class="font-headline-md text-headline-md text-primary hover:text-secondary transition-colors whitespace-nowrap" href="<?= e(mds_tel(MDS['tel_mobile'])) ?>"><?= e(MDS['tel_mobile']) ?></a>
</div>
</section>

<!-- ═══ Chiffres clés ═══ -->
<section class="py-xxl bg-primary relative overflow-hidden">
<div class="absolute inset-0 motif-grille bg-repeat opacity-60"></div>
<div class="relative z-10 px-gutter w-full max-w-container-max mx-auto">
<div class="text-center max-w-3xl mx-auto mb-xl text-on-primary reveal">
<h2 class="font-headline-lg text-headline-lg-mobile md:text-headline-lg mb-md"><?= te("Connectez-vous à l'excellence des données") ?></h2>
<p class="font-body-md text-body-md opacity-90"><?= te('Des chiffres qui témoignent de notre impact et de notre engagement. Découvrez comment MDS propulse la croissance des entreprises grâce à des solutions marketing éprouvées.') ?></p>
</div>
<div class="grid grid-cols-2 lg:grid-cols-4 gap-lg text-center text-on-primary" id="chiffres">
<?php foreach ($chiffres as $i => [$valeur, $suffixe, $libelle]): ?>
<div class="reveal<?= $i ? ' delay-' . ($i * 100) : '' ?>">
<?php /* La valeur finale est écrite dans le HTML : sans JavaScript, le
         chiffre s'affiche quand même — le site d'origine montrait 0. */ ?>
<div class="font-display-lg text-headline-lg-mobile md:text-display-lg" data-suffixe="<?= e($suffixe) ?>" data-valeur="<?= e($valeur) ?>"><?= number_format((int) $valeur, 0, ',', ' ') . $suffixe ?></div>
<p class="font-label-md text-label-md opacity-80 mt-xs"><?= te($libelle) ?></p>
</div>
<?php endforeach; ?>
</div>
</div>
</section>

<!-- ═══ Actualités ═══ -->
<section class="px-gutter py-xxl w-full max-w-container-max mx-auto">
<div class="text-center max-w-3xl mx-auto mb-xl reveal">
<span class="inline-block text-secondary font-label-sm text-label-sm uppercase tracking-wider mb-sm"><?= te('Dernières nouvelles & insights') ?></span>
<h2 class="font-headline-lg text-headline-lg text-primary"><?= te('MDS : alliez actualité et recherches approfondies pour anticiper les changements du marché') ?></h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg">
<?php foreach ($actualites as $i => [$cat, $couleur, $iso, $date, $titre_actu]): ?>
<article class="bg-surface-container-lowest rounded-xl card-elevation-1 bento-card border border-surface-variant p-lg flex flex-col reveal<?= $i % 3 ? ' delay-' . (($i % 3) * 100) : '' ?>">
<span class="inline-block self-start bg-<?= $couleur ?>/10 text-<?= $couleur ?> border border-<?= $couleur ?>/20 px-sm py-xs rounded-[6px] font-label-sm text-label-sm mb-md"><?= te($cat) ?></span>
<time class="font-label-sm text-label-sm text-on-surface-variant mb-xs flex items-center gap-xs" datetime="<?= e($iso) ?>">
<span class="material-symbols-outlined text-[16px]" aria-hidden="true">calendar_month</span><?= e($date) ?>
</time>
<h3 class="font-headline-md text-headline-md text-on-surface flex-grow"><?= e($titre_actu) ?></h3>
</article>
<?php endforeach; ?>

<article class="bg-primary rounded-xl card-elevation-2 relative overflow-hidden p-lg flex flex-col items-start justify-center text-left reveal">
<div class="absolute inset-0 motif-grille bg-repeat opacity-60"></div>
<div class="relative z-10 text-on-primary space-y-md">
<h3 class="font-headline-md text-headline-md"><?= te('Toutes nos annonces') ?></h3>
<p class="font-body-md text-body-md opacity-90"><?= te("Recrutements, appels d'offres et publications — la liste complète, filtrable par rubrique.") ?></p>
<a class="btn-accent inline-flex items-center gap-sm px-lg py-sm font-label-md text-label-md" href="actualites.php">
    <?= te('Voir les actualités') ?> <span class="material-symbols-outlined text-[18px]" aria-hidden="true">arrow_forward</span>
</a>
</div>
</article>
</div>
</section>

</main>

<?php require __DIR__ . '/partials/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        /* ─── Onglets Mission / Vision / Stratégie ─── */
        const onglets = document.getElementById('ongletsMvs');
        if (onglets) {
            onglets.addEventListener('click', (e) => {
                const bouton = e.target.closest('.onglet');
                if (!bouton) return;

                onglets.querySelectorAll('.onglet').forEach((b) => {
                    b.classList.remove('actif');
                    b.setAttribute('aria-selected', 'false');
                });
                bouton.classList.add('actif');
                bouton.setAttribute('aria-selected', 'true');

                const cible = bouton.getAttribute('data-panneau');
                document.querySelectorAll('.panneau-mvs').forEach((p) => {
                    p.classList.toggle('hidden', p.getAttribute('data-panneau') !== cible);
                });
            });
        }

        /* ─── Compteurs animés ───
           La valeur finale est déjà dans le HTML : on ne remet à 0 que si
           l'animation va effectivement se jouer. Sans JavaScript, ou en
           mouvement réduit, le chiffre reste simplement affiché. */
        const chiffres = document.getElementById('chiffres');
        const mouvementReduit = window.matchMedia
            && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (chiffres && !mouvementReduit && 'IntersectionObserver' in window) {
            const formater = (n) => n.toLocaleString('fr-FR');

            const animer = (el) => {
                const cible = parseInt(el.getAttribute('data-valeur'), 10) || 0;
                const suffixe = el.getAttribute('data-suffixe') || '';
                const duree = 1600;
                const debut = performance.now();

                const pas = (maintenant) => {
                    const avancement = Math.min((maintenant - debut) / duree, 1);
                    const adouci = 1 - Math.pow(1 - avancement, 3);
                    el.textContent = formater(Math.round(cible * adouci)) + suffixe;
                    if (avancement < 1) requestAnimationFrame(pas);
                };
                requestAnimationFrame(pas);
            };

            const obs = new IntersectionObserver((entrees, o) => {
                entrees.forEach((entree) => {
                    if (!entree.isIntersecting) return;
                    chiffres.querySelectorAll('[data-valeur]').forEach((el) => {
                        el.textContent = '0' + (el.getAttribute('data-suffixe') || '');
                        animer(el);
                    });
                    o.disconnect();
                });
            }, { threshold: 0.3 });

            obs.observe(chiffres);
        }
    });
</script>
</body>
</html>
