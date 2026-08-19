<?php
declare(strict_types=1);

$page        = 'realisations';
$titre       = 'Nos Réalisations — MDS Market Research';
$description = "Études de cas MDS : retail audit, études de marché, formation et "
    . "accompagnement à la certification. Découvrez nos méthodologies appliquées.";

require __DIR__ . '/partials/config.php';
?>
<!DOCTYPE html>
<html lang="<?= e(mds_langue()) ?>">
<head>
<?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body class="bg-surface text-on-surface font-body-md antialiased min-h-screen flex flex-col overflow-x-hidden">

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="flex-grow" id="contenu">

<!-- Hero -->
<section class="py-xxl px-gutter max-w-container-max mx-auto text-center reveal">
<h1 class="font-display-lg text-headline-lg-mobile md:text-display-lg text-primary mb-md"><?= te('Nos Réalisations') ?></h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-3xl mx-auto">
    <?= te("Découvrez comment MDS Market Research transforme les données en décisions stratégiques. Explorez nos études de cas et méthodologies appliquées à travers divers secteurs pour garantir la croissance et l'excellence opérationnelle de nos partenaires.") ?>
</p>
</section>

<!-- Filtres & portfolio -->
<section class="pb-xxl px-gutter max-w-container-max mx-auto">

<div aria-label="<?= te('Filtrer par catégorie') ?>" class="flex flex-wrap justify-center gap-sm mb-xl" id="filter-container" role="group">
<button aria-pressed="true" class="filter-btn active px-md py-sm rounded-full font-label-md cursor-pointer transition-colors" data-filter="all" type="button"><?= te('Toutes') ?></button>
<?php /* Les autres filtres sont ajoutés depuis GET /realisations. */ ?>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg" id="projects-grid"></div>

<?php /* Squelettes : ils occupent la place et la forme des cartes à venir, pour
         que la page ne saute pas à l'arrivée des données. */ ?>
<div aria-hidden="true" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg" id="loading-state">
<?php for ($i = 0; $i < 3; $i++): ?>
<div class="bg-surface-container-lowest rounded-xl overflow-hidden border border-outline-variant/30 flex flex-col">
<div class="squelette h-48 w-full rounded-none"></div>
<div class="p-md flex flex-col gap-sm">
<div class="squelette h-4 w-24"></div>
<div class="squelette h-6 w-3/4"></div>
<div class="squelette h-4 w-full"></div>
<div class="squelette h-4 w-5/6"></div>
<div class="squelette h-10 w-full mt-md"></div>
</div>
</div>
<?php endfor; ?>
</div>
<p class="sr-only" aria-live="polite" id="annonce-chargement"><?= te('Chargement des réalisations…') ?></p>

<div class="hidden text-center py-xxl bg-surface-container-low rounded-xl border border-outline-variant/30 mt-lg" id="empty-state">
<span class="material-symbols-outlined text-[48px] text-outline mb-sm" aria-hidden="true">search_off</span>
<p class="font-headline-md text-headline-md text-on-surface mb-xs"><?= te('Aucun résultat') ?></p>
<p class="font-body-md text-body-md text-on-surface-variant"><?= te('Aucune réalisation trouvée dans cette catégorie.') ?></p>
</div>

<div class="hidden text-center py-xxl bg-error-container rounded-xl border border-error/20 mt-lg" id="error-state">
<span class="material-symbols-outlined text-[48px] text-error mb-sm" aria-hidden="true">cloud_off</span>
<p class="font-headline-md text-headline-md text-on-error-container mb-xs"><?= te('Réalisations indisponibles') ?></p>
<p class="font-body-md text-body-md text-on-error-container/80" id="error-detail"><?= te("Le serveur n'a pas répondu.") ?></p>
</div>

</section>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>

<script>
    const API_BASE = <?= json_encode(MDS['api'], JSON_UNESCAPED_SLASHES) ?>;
    // La langue voyage jusqu'à l'API : titres, résumés, libellés de catégorie
    // et dates sont rendus par le serveur, pas traduits dans le navigateur.
    const LANGUE = <?= json_encode(mds_langue()) ?>;

    /* Libellés traduits par PHP au rendu. */
    const L = <?= json_encode([
        'telecharger' => t('Télécharger la fiche PDF'),
        'aVenir'      => t('Fiche PDF à venir'),
        'indispo'     => t("La fiche de cette réalisation n'est pas encore disponible."),
        'aucune'      => t('Aucune réalisation trouvée dans cette catégorie.'),
        'affichees'   => t('réalisation(s) affichée(s).'),
        'injoignable' => t("Le serveur est injoignable. Vérifiez qu'Apache et MySQL tournent dans XAMPP."),
        'echecCharge' => t("Les réalisations n'ont pas pu être chargées."),
        'repondu'     => t('Le serveur a répondu'),
    ], JSON_UNESCAPED_UNICODE) ?>;

    /* Habillage du badge de catégorie : décision de mise en page, pas donnée
       métier. Les chaînes sont écrites en entier — Tailwind ne génère que les
       classes qu'il voit littéralement. */
    const BADGES = {
        retail:        'bg-secondary/10 text-secondary border-secondary/20',
        market:        'bg-tertiary-container/10 text-tertiary border-tertiary-container/20',
        training:      'bg-primary/10 text-primary border-primary/20',
        certification: 'bg-on-tertiary-container/10 text-tertiary-container border-on-tertiary-container/20',
    };
    const BADGE_DEFAUT = 'bg-primary/10 text-primary border-primary/20';

    /* Le contenu vient de la base : il est échappé avant d'entrer dans le DOM.
       Un résumé contenant « <script> » doit s'afficher, pas s'exécuter. */
    const echapper = (v) => String(v ?? '').replace(/[&<>"']/g, (c) => (
        { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]
    ));

    /* url(...) en CSS : apostrophes et parenthèses d'une URL mal formée
       permettraient d'en sortir et d'injecter d'autres déclarations. */
    const echapperUrlCss = (v) => String(v ?? '').replace(/["'()\\\s]/g, encodeURIComponent);

    function carte(realisation, index) {
        const badge = BADGES[realisation.categorie] ?? BADGE_DEFAUT;
        const delai = ((index % 3) + 1) / 10;

        /* Le bouton est toujours présent — il fait partie de la carte. Tant
           qu'aucune fiche n'est renseignée en base, il est rendu désactivé
           plutôt qu'en lien mort : le visiteur voit ce qui existera, sans
           cliquer dans le vide. Renseigner `fiche_pdf` l'active. */
        const bouton = realisation.fichePdf
            ? '<a class="inline-flex items-center justify-center gap-sm btn-accent w-full py-sm font-label-md mt-auto" download href="'
                + echapper(realisation.fichePdf) + '">'
                + '<span class="material-symbols-outlined text-[20px]" aria-hidden="true">download</span>'
                + L.telecharger + '</a>'
            : '<button class="inline-flex items-center justify-center gap-sm w-full py-sm font-label-md mt-auto rounded-lg '
                + 'bg-surface-container text-on-surface-variant cursor-not-allowed" disabled '
                + 'title="' + L.indispo + '">'
                + '<span class="material-symbols-outlined text-[20px]" aria-hidden="true">schedule</span>'
                + L.aVenir + '</button>';

        const image = realisation.image
            ? '<div class="bg-cover bg-center w-full h-full" role="img" aria-label="' + echapper(realisation.imageAlt)
                + '" style="background-image: url(\'' + echapperUrlCss(realisation.image) + '\')"></div>'
            : '<div class="w-full h-full bg-surface-container-high"></div>';

        return ''
            + '<article class="project-card reveal bg-surface-container-lowest rounded-xl overflow-hidden card-elevation-1 bento-card flex flex-col border border-outline-variant/30" data-category="'
            + echapper(realisation.categorie) + '" style="transition-delay: ' + delai + 's;">'
            + '<div class="relative h-48 w-full">' + image
            + '<div class="absolute top-md left-md ' + badge
            + ' border px-sm py-xs rounded-[6px] font-label-sm backdrop-blur-sm bg-surface-container-lowest/90">'
            + echapper(realisation.categorieLibelle) + '</div></div>'
            + '<div class="p-md flex flex-col flex-grow">'
            + '<div class="text-on-surface-variant font-label-sm mb-xs flex items-center gap-xs">'
            + '<span class="material-symbols-outlined text-[16px]" aria-hidden="true">calendar_month</span>'
            + echapper(realisation.dateLibelle) + '</div>'
            + '<h2 class="font-headline-md text-headline-md text-on-surface mb-sm">' + echapper(realisation.titre) + '</h2>'
            + '<p class="font-body-md text-body-md text-on-surface-variant mb-lg flex-grow">' + echapper(realisation.resume) + '</p>'
            + bouton
            + '</div></article>';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const filterContainer = document.getElementById('filter-container');
        const projectsGrid    = document.getElementById('projects-grid');
        const emptyState      = document.getElementById('empty-state');
        const loadingState    = document.getElementById('loading-state');
        const errorState      = document.getElementById('error-state');
        const errorDetail     = document.getElementById('error-detail');
        const annonce         = document.getElementById('annonce-chargement');

        let portfolio = [];

        function afficher(filtre) {
            const visibles = filtre === 'all'
                ? portfolio
                : portfolio.filter((r) => r.categorie === filtre);

            projectsGrid.innerHTML = visibles.map(carte).join('');
            projectsGrid.classList.toggle('hidden', visibles.length === 0);
            emptyState.classList.toggle('hidden', visibles.length !== 0);

            annonce.textContent = visibles.length === 0
                ? L.aucune
                : visibles.length + ' ' + L.affichees;

            if (visibles.length) {
                setTimeout(() => {
                    projectsGrid.querySelectorAll('.reveal').forEach((el) => el.classList.add('active'));
                }, 50);
            }
        }

        filterContainer.addEventListener('click', (e) => {
            const btn = e.target.closest('.filter-btn');
            if (!btn) return;
            filterContainer.querySelectorAll('.filter-btn').forEach((b) => {
                b.classList.remove('active');
                b.setAttribute('aria-pressed', 'false');
            });
            btn.classList.add('active');
            btn.setAttribute('aria-pressed', 'true');
            afficher(btn.getAttribute('data-filter'));
        });

        function ajouterFiltres(categories) {
            /* Une catégorie sans réalisation publiée n'aurait qu'un onglet
               menant à un écran vide : elle n'est pas proposée. Le compte est
               affiché, pour situer d'un coup d'œil le volume de chaque pôle. */
            categories.filter((c) => c.nombre > 0).forEach((c) => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'filter-btn px-md py-sm rounded-full font-label-md cursor-pointer transition-colors';
                btn.setAttribute('data-filter', c.code);
                btn.setAttribute('aria-pressed', 'false');
                btn.textContent = c.libelle + ' (' + c.nombre + ')';
                filterContainer.appendChild(btn);
            });
        }

        (async function charger() {
            try {
                const reponse = await fetch(API_BASE + '/realisations?lang=' + encodeURIComponent(LANGUE), { headers: { Accept: 'application/json' } });
                if (!reponse.ok) {
                    const corps = await reponse.json().catch(() => ({}));
                    throw new Error(corps.erreur || (L.repondu + ' ' + reponse.status + '.'));
                }

                const donnees = await reponse.json();
                portfolio = donnees.realisations ?? [];

                loadingState.remove();
                ajouterFiltres(donnees.categories ?? []);
                afficher('all');
            } catch (e) {
                /* L'échec est dit, pas masqué : une grille vide laisserait
                   croire que MDS n'a aucune réalisation à montrer. */
                loadingState.remove();
                projectsGrid.classList.add('hidden');
                errorState.classList.remove('hidden');
                errorDetail.textContent = e instanceof TypeError
                    ? L.injoignable
                    : e.message;
                annonce.textContent = L.echecCharge;
            }
        })();
    });
</script>
</body>
</html>
