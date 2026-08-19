<?php
declare(strict_types=1);

/**
 * Les marques clientes, au-dessus du pied de page.
 *
 * Une rangée horizontale, chaque logo affiché une seule fois. Sur petit écran
 * la rangée passe à la ligne plutôt que de rétrécir les logos jusqu'à les
 * rendre illisibles.
 *
 * Les fichiers fournis sont des JPEG à fonds opaques et différents — noir,
 * brun, blanc. D'où la tuile blanche commune : posés côte à côte sans cadre,
 * ces fonds formeraient un damier.
 */

require_once __DIR__ . '/config.php';
?>
<section aria-labelledby="titre-partenaires" class="bg-surface-container-lowest border-t border-outline-variant py-xl">
<h2 class="font-label-md text-label-md uppercase tracking-wider text-on-surface-variant text-center mb-lg px-gutter" id="titre-partenaires">
<?= te('Ils nous font confiance') ?>
</h2>

<ul class="rangee-partenaires px-gutter">
<?php foreach (MDS_PARTENAIRES as $partenaire): ?>
<li class="bandeau-tuile">
<img alt="<?= e($partenaire['nom']) ?>" class="bandeau-logo" height="120" loading="lazy" src="assets/images/<?= e($partenaire['fichier']) ?>" width="200"/>
</li>
<?php endforeach; ?>
</ul>
</section>
