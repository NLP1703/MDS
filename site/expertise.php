<?php
declare(strict_types=1);

$page        = 'expertise';
$titre       = 'Notre Expertise — MDS Market Research';
$description = "Les 5 pôles d'excellence de MDS : marketing & branding, innovation, "
    . "trade & distribution, études sociales et médias, data analytics.";

require __DIR__ . '/partials/config.php';
require __DIR__ . '/partials/drapeaux.php';

/**
 * Les pôles d'expertise, et le détail des prestations de chacun.
 *
 * Les trois cartes ont la même forme : aucune prestation n'est mise en avant
 * par sa taille. L'icône et la couleur ne font que distinguer les pôles — ce
 * sont les seules variations d'une carte à l'autre.
 *
 * Format : [icône, fond de la pastille, couleur de l'icône, nom, prestations].
 */
$poles = [
    [
        'bar_chart', 'bg-primary-fixed', 'text-primary',
        'MDS Marketing',
        [
            'Elaboration des plans marketing stratégique',
            "Enquêtes sur l'usage et l'attitude des consommateurs",
            'Estimation de la taille du marché',
            'Image de marque & positionnement',
            'Segmentation du marché',
            'Mesure de la Relation Client',
        ],
    ],
    [
        'shopping_cart', 'bg-secondary-fixed', 'text-secondary',
        'MDS Distribution & activités sur le Trade',
        [
            'Recensement/Dénombrement des Point de vente',
            'Études de distribution dans les PDV',
            'Retail audit',
            'Activités de soutien à la marque dans les PDV',
        ],
    ],
    [
        'database', 'bg-tertiary-fixed', 'text-tertiary-container',
        'MDS Analytics & Data Management',
        [
            "Plan d'échantillonnage",
            'Numérisation des questionnaire et monitoring des sondages',
            'Pondération et extrapolation des données, tabulation',
            'Test statistique et analyse multivariées',
        ],
    ],
];

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

/**
 * Les capacités de terrain, pays par pays.
 *
 * L'ordre suit la taille du dispositif — Cameroun d'abord, où se trouve le
 * siège.
 *
 * **Le total est exactement la somme des lignes de détail**, et il est écrit
 * en toutes lettres dans le paragraphe qui suit. Trois nombres à tenir
 * ensemble : `total`, les `lignes`, et la phrase. Modifier un effectif oblige
 * à reprendre les trois — ainsi que la traduction anglaise, dont la phrase
 * française est la clé.
 *
 * Le chiffre s'affiche en très gros caractères : un visiteur qui additionne
 * les lignes doit retomber dessus.
 *
 * Chaque ligne de détail porte son icône : la même information rangée sous
 * trois pictogrammes identiques ne se lirait plus en diagonale.
 */
$capacites = [
    [
        'pays'  => 'Cameroun',
        'code'  => 'cm',
        'total' => '725',
        'lignes' => [
            ['groups',      '676 enquêteurs et 39 superviseurs'],
            ['location_on', '9 villes stratégiques'],
            ['insights',    '10 spécialistes dédiés'],
        ],
        'texte' => "Au Cameroun, nous mobilisons une équipe de 725 professionnels, dont 676 enquêteurs et 39 superviseurs, pour garantir une collecte de données fiable et étendue à travers le pays. Basés dans 9 villes stratégiques, nos experts en traitement des données, localisés à Douala, optimisent chaque jour l'analyse avec 10 spécialistes dédiés. Grâce à cette force opérationnelle, nous assurons des solutions de qualité et adaptées aux besoins de nos partenaires en Afrique centrale.",
    ],
    [
        'pays'  => 'RDC',
        'code'  => 'cd',
        'total' => '270',
        'lignes' => [
            ['groups',      '215 enquêteurs et 36 superviseurs'],
            ['location_on', '9 localités stratégiques'],
            ['insights',    '12 coordonateurs régionaux & 7 agents de terrain sénior'],
        ],
        'texte' => "En République Démocratique du Congo (RDC), nous mobilisons une équipe de 270 professionnels, dont 215 enquêteurs et 36 superviseurs, répartis dans 9 localités clés. Ces équipes sont appuyées par 12 coordinateurs régionaux et 7 agents de terrain senior pour garantir une collecte de données efficace et fiable. Avec des opérations centralisées à Kinshasa et des ressources réparties dans des villes comme Lubumbashi, Kisangani et Goma, nous assurons une couverture nationale et des solutions adaptées aux besoins de nos partenaires en Afrique centrale.",
    ],
    [
        'pays'  => 'Tchad',
        'code'  => 'td',
        'total' => '136',
        'lignes' => [
            ['groups',      '106 enquêteurs et 19 superviseurs'],
            ['location_on', '9 localités clés'],
            ['insights',    '10 coordonnateurs régionaux & 1 expert'],
        ],
        'texte' => "Au Tchad, nous mobilisons une équipe de 136 professionnels, dont 106 enquêteurs et 19 superviseurs, pour garantir une collecte de données fiable à travers le pays. Basés dans 9 localités clés, nos équipes, appuyées par 10 coordinateurs régionaux et un expert en contrôle qualité, assurent une couverture optimale et une maîtrise des spécificités locales. Grâce à cette organisation, nous fournissons des solutions adaptées et de haute qualité pour répondre aux besoins de nos partenaires en Afrique centrale.",
    ],
    [
        'pays'  => 'Gabon',
        'code'  => 'ga',
        'total' => '60',
        'lignes' => [
            ['groups',      '50 enquêteurs et 10 superviseurs'],
            ['location_on', 'Libreville et Franceville'],
        ],
        'texte' => "Au Gabon, nous disposons d'une équipe dédiée de 60 professionnels, incluant 50 enquêteurs et 10 superviseurs, opérant depuis Libreville et Franceville. Nos collaborateurs locaux maîtrisent parfaitement le terrain et les spécificités culturelles et linguistiques de chaque province. Avec des recherches qualitatives et quantitatives sur mesure, nous garantissons des données fiables pour des décisions éclairées.",
    ],
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

<!-- Les pôles d'expertise -->
<?php /* Trois cartes de même forme, chacune une prestation détaillée en
         puces. La carte large de la version précédente est retirée : elle
         hiérarchisait un pôle au-dessus des autres, ce que la liste des
         prestations ne justifie plus. */ ?>
<section class="pb-xxl px-gutter max-w-container-max mx-auto">
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
<?php foreach ($poles as [$icone, $fond, $couleur, $nom, $prestations]): ?>
<div class="bg-surface-container-lowest rounded-xl p-lg card-elevation-1 bento-card reveal flex flex-col gap-md">
<div class="p-sm <?= $fond ?> rounded-lg <?= $couleur ?> self-start">
<span class="material-symbols-outlined text-3xl" aria-hidden="true"><?= $icone ?></span>
</div>
<h2 class="font-headline-md text-headline-md text-on-surface"><?= te($nom) ?></h2>
<ul class="list-disc list-outside pl-md space-y-xs text-on-surface-variant font-body-md text-body-md">
<?php foreach ($prestations as $prestation): ?>
<li><?= te($prestation) ?></li>
<?php endforeach; ?>
</ul>
</div>
<?php endforeach; ?>
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

<!-- Capacités opérationnelles -->
<?php /* `perspective` est portée par la section, pas par les cartes : posée sur
         chaque carte, chacune aurait son propre point de fuite et l'inclinaison
         paraîtrait identique partout au lieu de suivre le regard. */ ?>
<section class="py-xxl px-gutter max-w-container-max mx-auto capacites">
<div class="text-center max-w-3xl mx-auto mb-xl reveal">
<span class="inline-block text-secondary font-label-sm text-label-sm uppercase tracking-wider mb-sm"><?= te('Afrique centrale') ?></span>
<h2 class="font-headline-lg text-headline-lg text-primary"><?= te('Nos Capacités Opérationnelles') ?></h2>
</div>

<div class="grille-pays grid grid-cols-1 md:grid-cols-2 gap-gutter">
<?php foreach ($capacites as $rang => $pays): ?>
<article class="carte-pays bg-surface-container-lowest rounded-xl p-lg card-elevation-1 bento-card border border-outline-variant border-opacity-30 flex flex-col gap-md" style="--retard-carte: <?= ($rang % 2) * 140 ?>ms;">

<div class="flex items-center gap-sm">
<?= mds_drapeau($pays['code']) ?>
<h3 class="font-headline-md text-headline-md text-on-surface"><?= te($pays['pays']) ?></h3>
</div>

<?php /* Le chiffre est écrit en clair dans la page : sans JavaScript, il
         s'affiche tel quel. Le rouleau ne le remplace qu'une fois le script
         passé — l'inverse laisserait un blanc à qui n'exécute rien. */ ?>
<div>
<p class="font-display-lg text-headline-lg-mobile md:text-display-lg text-primary leading-none">
<span class="compteur-rouleau" data-valeur="<?= e($pays['total']) ?>"><?= e($pays['total']) ?></span>
</p>
<span class="trait-accent block h-[3px] w-16 bg-accent rounded-full mt-sm" aria-hidden="true"></span>
<p class="font-label-md text-label-md text-on-surface-variant mt-sm"><?= te('professionnels') ?></p>
</div>

<ul class="space-y-xs list-none p-0 m-0">
<?php foreach ($pays['lignes'] as [$icone, $ligne]): ?>
<li class="flex items-start gap-sm font-body-md text-body-md text-on-surface-variant">
<span class="material-symbols-outlined text-secondary text-xl shrink-0" aria-hidden="true"><?= $icone ?></span>
<span><?= te($ligne) ?></span>
</li>
<?php endforeach; ?>
</ul>

<p class="font-body-md text-body-md text-on-surface-variant"><?= te($pays['texte']) ?></p>

</article>
<?php endforeach; ?>
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

<script>
/**
 * Capacités opérationnelles — les cartes se redressent, les nombres roulent.
 *
 * Le nombre est déjà écrit dans la page. Le ruban de chiffres ne le remplace
 * que si l'animation va réellement se jouer : sans JavaScript, sans
 * IntersectionObserver ou en mouvement réduit, le nombre reste tel quel.
 */
document.addEventListener('DOMContentLoaded', () => {
    'use strict';

    const cartes = Array.prototype.slice.call(document.querySelectorAll('.carte-pays'));
    if (!cartes.length) return;

    const mouvementReduit = window.matchMedia
        && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (mouvementReduit || !('IntersectionObserver' in window)) {
        cartes.forEach((c) => c.classList.add('active'));
        return;
    }

    /**
     * Construit le ruban d'un nombre.
     *
     * Une colonne par chiffre. Chaque colonne contient plusieurs cycles 0→9
     * suivis du chiffre final : c'est la longueur du ruban, et non la vitesse,
     * qui fait la différence entre les colonnes. Les colonnes de droite ont
     * plus de tours à parcourir dans le même temps, donc tournent plus vite —
     * exactement comme les unités d'un compteur mécanique.
     */
    const construire = (compteur) => {
        const valeur = compteur.getAttribute('data-valeur') || compteur.textContent.trim();
        compteur.textContent = '';

        // Le ruban est illisible au lecteur d'écran : il n'annonce que la
        // valeur, et les chiffres en mouvement lui restent masqués.
        compteur.setAttribute('role', 'img');
        compteur.setAttribute('aria-label', valeur);

        valeur.split('').forEach((caractere, i) => {
            if (!/[0-9]/.test(caractere)) {
                // Un séparateur (espace, signe) ne roule pas : il est posé.
                const fixe = document.createElement('span');
                fixe.className = 'rouleau';
                fixe.setAttribute('aria-hidden', 'true');
                fixe.innerHTML = '<span>' + caractere + '</span>';
                compteur.appendChild(fixe);
                return;
            }

            const tours = 2 + i;
            const colonne = document.createElement('span');
            colonne.className = 'rouleau';
            colonne.setAttribute('aria-hidden', 'true');

            let ruban = '';
            for (let t = 0; t < tours; t++) {
                for (let d = 0; d <= 9; d++) ruban += '<span>' + d + '</span>';
            }
            ruban += '<span>' + caractere + '</span>';
            colonne.innerHTML = ruban;

            // Le chiffre final est le dernier de la colonne : on remonte le
            // ruban d'autant de hauteurs de chiffre qu'il y a de chiffres
            // au-dessus de lui.
            colonne.style.setProperty('--arrivee', 'calc(var(--hauteur-chiffre) * -' + (tours * 10) + ')');

            // Le retard s'ajoute à celui de la carte : le nombre ne démarre
            // qu'une fois la carte redressée, et se pose de gauche à droite.
            colonne.style.setProperty('--retard-rouleau', 'calc(var(--retard-carte, 0ms) + ' + (300 + i * 90) + 'ms)');

            compteur.appendChild(colonne);
        });
    };

    cartes.forEach((carte) => {
        carte.querySelectorAll('.compteur-rouleau').forEach(construire);
    });

    /* Seuil bas et marge négative : la carte s'anime quand elle est vraiment
       entrée dans le champ de lecture, pas au premier pixel visible. */
    const observateur = new IntersectionObserver((entrees, obs) => {
        entrees.forEach((entree) => {
            if (!entree.isIntersecting) return;
            entree.target.classList.add('active');
            obs.unobserve(entree.target);
        });
    }, { threshold: 0.25, rootMargin: '0px 0px -10% 0px' });

    cartes.forEach((carte) => observateur.observe(carte));
});
</script>
</body>
</html>
