<?php
declare(strict_types=1);

/**
 * Pied de page : logo blanc, navigation, coordonnées, certification.
 *
 * Un seul modèle à quatre colonnes pour les cinq pages — il en existait trois
 * différents, dont deux à deux colonnes.
 *
 * Le logo est ici la variante **blanche** : le fond du pied est bleu marine, et
 * le logo couleur y posait un rectangle clair, son fond d'origine n'étant pas
 * transparent.
 */

require_once __DIR__ . '/config.php';
?>
<a aria-label="<?= te('Contactez-nous sur WhatsApp') ?>" class="fixed bottom-lg right-lg z-40 bg-[#25D366] text-white p-md rounded-full shadow-lg hover:scale-110 transition-transform flex items-center justify-center fab-pulse" href="<?= e(mds_whatsapp()) ?>" rel="noopener" target="_blank">
<span class="material-symbols-outlined icon-fill" aria-hidden="true" style="font-size: 28px;">forum</span>
</a>

<footer class="bg-primary mt-auto">
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-xl px-gutter py-xxl w-full max-w-container-max mx-auto">

    <div class="flex flex-col gap-lg">
<a class="block" href="index.php">
<img alt="<?= e(MDS['nom_long']) ?>" class="h-14 w-auto object-contain" height="171" loading="lazy" src="assets/images/logo-mds-blanc.png" width="243"/>
</a>
<?php /* Les deux marques sont calées sur la même hauteur, pas sur la même
         largeur : leurs proportions diffèrent, et une largeur commune aurait
         donné deux cartouches blancs de hauteurs inégales — l'œil lit ici une
         ligne de base, pas une colonne. `flex-wrap` les empile plutôt que de
         les écraser quand la colonne du pied de page se resserre. */ ?>
<div class="flex flex-wrap items-center gap-sm self-start">
<img alt="<?= te("MDS est certifié ISO 20252 — études de marché, sociales et d'opinion") ?>" class="h-20 w-auto object-contain bg-white rounded-md p-1" height="605" loading="lazy" src="assets/images/certification-iso-20252.png" width="857"/>
<img alt="<?= te('MDS est membre de la MSPA, Mystery Shopping Professionals Association') ?>" class="h-20 w-auto object-contain bg-white rounded-md p-1" height="179" loading="lazy" src="assets/images/membre-mspa.png" width="303"/>
</div>
</div>

    <nav aria-label="<?= te('Navigation du pied de page') ?>" class="flex flex-col gap-sm">
<h2 class="font-label-md text-label-md text-on-primary font-bold mb-xs"><?= te('Navigation') ?></h2>
<?php foreach (MDS_MENU as $item): ?>
<a class="font-label-md text-label-md text-on-primary/80 hover:text-secondary-fixed transition-colors" href="<?= e($item['url']) ?>"><?= te($item['libelle']) ?></a>
<?php endforeach; ?>
</nav>

    <div class="flex flex-col gap-sm">
<h2 class="font-label-md text-label-md text-on-primary font-bold mb-xs"><?= te('Nous joindre') ?></h2>
<a class="font-label-md text-label-md text-on-primary/80 hover:text-secondary-fixed transition-colors flex items-center gap-xs" href="<?= e(mds_tel(MDS['tel_fixe'])) ?>">
<span class="material-symbols-outlined text-[16px]" aria-hidden="true">call</span><?= e(MDS['tel_fixe']) ?>
</a>
<a class="font-label-md text-label-md text-on-primary/80 hover:text-secondary-fixed transition-colors flex items-center gap-xs" href="<?= e(mds_tel(MDS['tel_mobile'])) ?>">
<span class="material-symbols-outlined text-[16px]" aria-hidden="true">smartphone</span><?= e(MDS['tel_mobile']) ?>
</a>
<a class="font-label-md text-label-md text-on-primary/80 hover:text-secondary-fixed transition-colors flex items-center gap-xs" href="mailto:<?= e(MDS['email']) ?>">
<span class="material-symbols-outlined text-[16px]" aria-hidden="true">mail</span><?= e(MDS['email']) ?>
</a>
<a class="font-label-md text-label-md text-on-primary/80 hover:text-secondary-fixed transition-colors flex items-center gap-xs" href="<?= e(mds_whatsapp()) ?>" rel="noopener" target="_blank">
<span class="material-symbols-outlined text-[16px]" aria-hidden="true">forum</span>WhatsApp
    </a>
</div>

    <div class="flex flex-col gap-sm">
<h2 class="font-label-md text-label-md text-on-primary font-bold mb-xs"><?= te('Nous trouver') ?></h2>
<p class="font-label-md text-label-md text-on-primary/80 flex items-start gap-xs">
<span class="material-symbols-outlined text-[16px] mt-xs" aria-hidden="true">location_on</span>
<span><?= te(MDS['adresse']) ?><br/><?= te(MDS['ville']) ?></span>
</p>
<p class="font-label-md text-label-md text-on-primary/80 flex items-start gap-xs">
<span class="material-symbols-outlined text-[16px] mt-xs" aria-hidden="true">schedule</span>
<span><?= te(MDS['horaires']) ?></span>
</p>
</div>
</div>

<div class="border-t border-on-primary/20">
<p class="px-gutter py-lg w-full max-w-container-max mx-auto font-label-sm text-label-sm text-on-primary/60 text-center">
        © <?= date('Y') ?> <?= te('Tous droits réservés') ?> • MDS – <?= e(MDS['nom_long']) ?>
    </p>
</div>
</footer>

<script src="<?= e(mds_ressource('assets/menu-mobile.js')) ?>"></script>
<script src="<?= e(mds_ressource('assets/mds.js')) ?>"></script>
