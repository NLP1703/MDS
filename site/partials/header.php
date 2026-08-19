<?php
declare(strict_types=1);

/**
 * En-tête du site : logo, menu, WhatsApp, menu mobile.
 *
 * Un seul modèle pour les cinq pages. Auparavant, quatre en-têtes étaient en
 * `sticky` et un en `fixed`, avec deux valeurs de padding différentes ; la
 * barre changeait de hauteur en naviguant.
 *
 * Variable attendue : $page — clé du menu, pour marquer l'entrée active.
 */

require_once __DIR__ . '/config.php';

$page = $page ?? '';

/**
 * Classes d'une entrée de menu.
 *
 * Le trait sous le lien est dessiné par `.lien-menu::after` : il glisse en
 * `scaleX` au survol, plutôt que d'être une bordure qui apparaît d'un coup.
 * D'où l'absence de `border-b` ici — le CSS s'en charge.
 */
function mds_lien_menu(string $cle, string $page_courante, bool $mobile = false): string
{
    $actif = $cle === $page_courante;

    if ($mobile) {
        return 'font-label-md text-label-md py-md border-b border-outline-variant '
            . ($actif ? 'text-primary font-semibold' : 'text-on-surface-variant');
    }

    return 'lien-menu font-label-md text-label-md '
        . ($actif ? 'text-primary font-semibold' : 'text-on-surface-variant hover:text-primary');
}
?>
<?php
$autre = mds_autre_langue();
$libelle_bascule = $autre === 'en' ? 'Switch to English' : 'Passer en français';
?>
<a class="lien-evitement" href="#contenu"><?= te('Aller au contenu') ?></a>

<?php /* Deux barres fines en haut de l'écran, purement décoratives :
         `barre-lecture` suit la progression dans la page, `barre-navigation`
         s'anime pendant le chargement de la page suivante. Masquées en
         mouvement réduit. */ ?>
<div aria-hidden="true" class="barre-lecture"></div>
<div aria-hidden="true" class="barre-navigation"></div>

<header class="entete-site bg-surface sticky top-0 z-50 w-full">
<div class="flex justify-between items-center gap-md px-gutter py-md w-full max-w-container-max mx-auto">

    <a aria-label="<?= e(MDS['nom']) ?> — accueil" class="block shrink-0" href="index.php">
<img alt="<?= e(MDS['nom_long']) ?>" class="h-12 w-auto object-contain" height="171" src="assets/images/logo-mds.png" width="243"/>
</a>

    <nav aria-label="<?= te('Navigation principale') ?>" class="hidden md:flex gap-lg items-center">
<?php foreach (MDS_MENU as $cle => $item): ?>
<a class="<?= mds_lien_menu($cle, $page) ?>"<?= $cle === $page ? ' aria-current="page"' : '' ?> href="<?= e($item['url']) ?>"><?= te($item['libelle']) ?></a>
<?php endforeach; ?>
</nav>

<?php /* Bascule de langue. Un lien et non un bouton : il mène à une autre
         adresse, et doit donc s'ouvrir dans un nouvel onglet au clic milieu,
         se copier, s'indexer — ce qu'un bouton ne permet pas. */ ?>
    <a aria-label="<?= e($libelle_bascule) ?>" class="pressable hidden md:flex items-center gap-xs shrink-0 border border-outline-variant rounded-lg px-md py-sm font-label-md text-label-md text-on-surface-variant hover:text-primary hover:border-secondary transition-colors" href="<?= e(mds_url_langue($autre)) ?>" hreflang="<?= e($autre) ?>" lang="<?= e($autre) ?>">
<span class="material-symbols-outlined text-[18px]" aria-hidden="true">language</span>
<?= e(MDS_LANGUES[$autre]['court']) ?>
</a>

    <a class="btn-accent hidden md:flex items-center gap-sm font-label-md text-label-md px-lg py-sm shrink-0" href="<?= e(mds_whatsapp()) ?>" rel="noopener" target="_blank">
<span class="material-symbols-outlined" aria-hidden="true">forum</span>
        WhatsApp
    </a>

<?php /* Sur mobile, la bascule reste visible à côté du hamburger : l'enfouir
         dans le menu obligerait à l'ouvrir pour changer de langue. */ ?>
    <a aria-label="<?= e($libelle_bascule) ?>" class="pressable md:hidden flex items-center gap-xs border border-outline-variant rounded-lg px-sm py-xs font-label-sm text-label-sm text-on-surface-variant ml-auto mr-sm" href="<?= e(mds_url_langue($autre)) ?>" hreflang="<?= e($autre) ?>" lang="<?= e($autre) ?>">
<span class="material-symbols-outlined text-[16px]" aria-hidden="true">language</span>
<?= e(MDS_LANGUES[$autre]['court']) ?>
</a>

    <button aria-controls="navMobile" aria-expanded="false" aria-label="<?= te('Ouvrir le menu') ?>" class="pressable md:hidden text-primary p-xs" id="menuMobile" type="button">
<span class="material-symbols-outlined" aria-hidden="true">menu</span>
</button>
</div>

<?php /* Panneau mobile. Positionné en `fixed` sous l'en-tête par
         assets/menu-mobile.js, qui mesure sa hauteur réelle. */ ?>
<div class="hidden md:hidden fixed inset-x-0 z-40 bg-surface border-t border-outline-variant shadow-lg" id="navMobile">
<nav aria-label="<?= te('Navigation principale (mobile)') ?>" class="flex flex-col px-gutter py-sm max-w-container-max mx-auto">
<?php foreach (MDS_MENU as $cle => $item): ?>
<a class="<?= mds_lien_menu($cle, $page, true) ?>"<?= $cle === $page ? ' aria-current="page"' : '' ?> href="<?= e($item['url']) ?>"><?= te($item['libelle']) ?></a>
<?php endforeach; ?>
<a class="btn-accent px-lg py-sm my-md font-label-md text-label-md flex items-center justify-center gap-sm" href="<?= e(mds_whatsapp()) ?>" rel="noopener" target="_blank">
<span class="material-symbols-outlined" aria-hidden="true">forum</span>
            WhatsApp
        </a>
</nav>
</div>
</header>
