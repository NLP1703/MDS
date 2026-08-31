<?php
declare(strict_types=1);

$page        = 'actualites';
$titre       = 'Actualités & Insights — MDS Market Research';
$description = "Recrutements, appels d'offres et publications de MDS Market Research. "
    . "Alliez actualité et recherches approfondies pour anticiper les changements du marché.";

require __DIR__ . '/partials/config.php';
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
<section class="py-xxl px-gutter max-w-container-max mx-auto text-center reveal">
<span class="inline-block text-secondary font-label-sm text-label-sm uppercase tracking-wider mb-sm"><?= te('Dernières nouvelles & insights') ?></span>
<h1 class="mots-anime font-display-lg text-headline-lg-mobile md:text-display-lg text-primary mb-md"><?= te('Actualités') ?></h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl mx-auto">
    <?= te("Allier l'actualité aux recherches approfondies afin d'anticiper efficacement les évolutions du marché.") ?>
</p>
</section>

<!-- Filtres & liste -->
<section class="pb-xxl px-gutter max-w-container-max mx-auto">

<div aria-label="<?= te('Filtrer par catégorie') ?>" class="flex flex-wrap justify-center gap-sm mb-xl" id="filter-container" role="group">
<button aria-pressed="true" class="filter-btn active px-md py-sm rounded-full font-label-md cursor-pointer" data-filter="all" type="button"><?= te('Toutes') ?></button>
<?php /* Les autres filtres viennent de GET /actualites. */ ?>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg" id="liste-actualites"></div>

<?php /* Squelettes, à la forme des cartes à venir : la page ne saute pas à
         l'arrivée des données. */ ?>
<div aria-hidden="true" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg" id="loading-state">
<?php for ($i = 0; $i < 3; $i++): ?>
<div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-lg flex flex-col gap-sm">
<div class="squelette h-6 w-24 rounded-full"></div>
<div class="squelette h-4 w-32 mt-sm"></div>
<div class="squelette h-6 w-full mt-sm"></div>
<div class="squelette h-6 w-2/3"></div>
<div class="squelette h-4 w-full mt-md"></div>
<div class="squelette h-4 w-5/6"></div>
<div class="squelette h-5 w-28 mt-lg"></div>
</div>
<?php endfor; ?>
</div>
<p class="sr-only" aria-live="polite" id="annonce-chargement"><?= te('Chargement des actualités…') ?></p>

<div class="hidden text-center py-xxl bg-surface-container-low rounded-xl border border-outline-variant mt-lg" id="empty-state">
<span class="material-symbols-outlined text-[48px] text-outline mb-sm" aria-hidden="true">search_off</span>
<p class="font-headline-md text-headline-md text-on-surface mb-xs"><?= te('Aucun résultat') ?></p>
<p class="font-body-md text-body-md text-on-surface-variant"><?= te('Aucune actualité dans cette catégorie.') ?></p>
</div>

<div class="hidden text-center py-xxl bg-error-container rounded-xl border border-error/20 mt-lg" id="error-state">
<span class="material-symbols-outlined text-[48px] text-error mb-sm" aria-hidden="true">cloud_off</span>
<p class="font-headline-md text-headline-md text-on-error-container mb-xs"><?= te('Actualités indisponibles') ?></p>
<p class="font-body-md text-body-md text-on-error-container/80" id="error-detail"><?= te("Le serveur n'a pas répondu.") ?></p>
</div>

</section>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>

<script>
    // Chemin relatif en local, URL absolue en ligne : voir
    // `mds_api_navigateur()` dans partials/config.php.
    const API_BASE = <?= json_encode(mds_api_navigateur(), JSON_UNESCAPED_SLASHES) ?>;
    // La langue voyage jusqu'à l'API : titres, résumés, libellés de catégorie
    // et dates sont rendus par le serveur, pas traduits dans le navigateur.
    const LANGUE = <?= json_encode(mds_langue()) ?>;

    /* Libellés traduits par PHP au rendu. */
    const L = <?= json_encode([
        'lirePlus'    => t('Lire plus'),
        'aucune'      => t('Aucune actualité dans cette catégorie.'),
        'affichees'   => t('actualité(s) affichée(s).'),
        'injoignable' => t("Le serveur est injoignable. Vérifiez qu'Apache et MySQL tournent dans XAMPP."),
        'echecCharge' => t("Les actualités n'ont pas pu être chargées."),
        'repondu'     => t('Le serveur a répondu'),
    ], JSON_UNESCAPED_UNICODE) ?>;

    /* Habillage des catégories — décision de mise en page, pas donnée métier.
       Chaînes écrites en entier : Tailwind ne génère que ce qu'il voit. */
    const BADGES = {
        carriere: 'bg-secondary/10 text-secondary border-secondary/25',
        appel:    'bg-accent/15 text-accent-dark border-accent/30',
        actu:     'bg-primary/10 text-primary border-primary/20',
        certif:   'bg-tertiary-container/10 text-tertiary-container border-tertiary-container/25',
    };
    const BADGE_DEFAUT = 'bg-primary/10 text-primary border-primary/20';

    const echapper = (v) => String(v ?? '').replace(/[&<>"']/g, (c) => (
        { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
    ));

    function carte(actualite) {
        const badge = BADGES[actualite.categorie] ?? BADGE_DEFAUT;

        /* « Lire plus » reste sur le site dès qu'une annonce a un corps : le
           visiteur n'a aucune raison d'être renvoyé ailleurs pour lire ce qui
           est déjà ici. Le lien d'origine reste accessible depuis la page de
           détail, comme source.

           Sortie vers l'extérieur uniquement quand il n'y a rien à montrer sur
           place : pas de corps, mais un article publié ailleurs. */
        const sortirDuSite = !actualite.aContenu && actualite.lienExterne;

        const lien = sortirDuSite
            ? '<a class="inline-flex items-center gap-xs text-secondary font-label-md text-label-md hover:gap-sm transition-all mt-auto" '
                + 'href="' + echapper(actualite.lienExterne) + '" rel="noopener" target="_blank">'
                + L.lirePlus + ' <span class="material-symbols-outlined text-[18px]" aria-hidden="true">open_in_new</span></a>'
            : '<a class="inline-flex items-center gap-xs text-secondary font-label-md text-label-md hover:gap-sm transition-all mt-auto" '
                + 'href="actualite.php?id=' + encodeURIComponent(actualite.id) + '">'
                + L.lirePlus + ' <span class="material-symbols-outlined text-[18px]" aria-hidden="true">arrow_forward</span></a>';

        const resume = actualite.resume
            ? '<p class="font-body-md text-body-md text-on-surface-variant mb-lg">' + echapper(actualite.resume) + '</p>'
            : '';

        return ''
            + '<article class="reveal bg-surface-container-lowest rounded-xl card-elevation-1 bento-card border border-outline-variant p-lg flex flex-col" '
            + 'data-category="' + echapper(actualite.categorie) + '">'
            + '<span class="inline-block self-start ' + badge + ' border px-sm py-xs rounded-[6px] font-label-sm text-label-sm mb-md">'
            + echapper(actualite.categorieLibelle) + '</span>'
            + '<time class="font-label-sm text-label-sm text-on-surface-variant mb-sm flex items-center gap-xs" datetime="'
            + echapper(actualite.date) + '">'
            + '<span class="material-symbols-outlined text-[16px]" aria-hidden="true">calendar_month</span>'
            + echapper(actualite.dateLibelle) + '</time>'
            + '<h2 class="font-headline-md text-headline-md text-on-surface mb-sm flex-grow">' + echapper(actualite.titre) + '</h2>'
            + resume
            + lien
            + '</article>';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const filtres   = document.getElementById('filter-container');
        const liste     = document.getElementById('liste-actualites');
        const vide      = document.getElementById('empty-state');
        const chargement = document.getElementById('loading-state');
        const erreur    = document.getElementById('error-state');
        const detail    = document.getElementById('error-detail');
        const annonce   = document.getElementById('annonce-chargement');

        let toutes = [];

        function afficher(filtre) {
            const visibles = filtre === 'all' ? toutes : toutes.filter((a) => a.categorie === filtre);

            liste.innerHTML = visibles.map(carte).join('');
            liste.classList.toggle('hidden', visibles.length === 0);
            vide.classList.toggle('hidden', visibles.length !== 0);

            annonce.textContent = visibles.length === 0
                ? L.aucune
                : visibles.length + ' ' + L.affichees;

            if (visibles.length) {
                /* La cascade est posée ici : le script partagé a déjà fait son
                   passage avant que ces cartes n'existent. */
                liste.querySelectorAll('.reveal').forEach((el, i) => {
                    el.style.setProperty('--retard', Math.min(i, 5) * 150 + 'ms');
                });
                requestAnimationFrame(() => {
                    liste.querySelectorAll('.reveal').forEach((el) => el.classList.add('active'));
                });
            }
        }

        filtres.addEventListener('click', (e) => {
            const btn = e.target.closest('.filter-btn');
            if (!btn) return;
            filtres.querySelectorAll('.filter-btn').forEach((b) => {
                b.classList.remove('active');
                b.setAttribute('aria-pressed', 'false');
            });
            btn.classList.add('active');
            btn.setAttribute('aria-pressed', 'true');
            afficher(btn.getAttribute('data-filter'));
        });

        function ajouterFiltres(categories) {
            categories.filter((c) => c.nombre > 0).forEach((c) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'filter-btn px-md py-sm rounded-full font-label-md cursor-pointer';
                btn.setAttribute('data-filter', c.code);
                btn.setAttribute('aria-pressed', 'false');
                btn.textContent = c.libelle + ' (' + c.nombre + ')';
                filtres.appendChild(btn);
            });
        }

        (async function charger() {
            try {
                const r = await fetch(API_BASE + '/actualites?lang=' + encodeURIComponent(LANGUE), { headers: { Accept: 'application/json' } });
                if (!r.ok) {
                    const corps = await r.json().catch(() => ({}));
                    throw new Error(corps.erreur || (L.repondu + ' ' + r.status + '.'));
                }

                const donnees = await r.json();
                toutes = donnees.actualites ?? [];

                chargement.remove();
                ajouterFiltres(donnees.categories ?? []);
                afficher('all');
            } catch (e) {
                chargement.remove();
                liste.classList.add('hidden');
                erreur.classList.remove('hidden');
                detail.textContent = e instanceof TypeError
                    ? L.injoignable
                    : e.message;
                annonce.textContent = L.echecCharge;
            }
        })();
    });
</script>
</body>
</html>
