<?php
declare(strict_types=1);

/**
 * Bandeau défilant des marques clientes, au-dessus du pied de page.
 *
 * ─── Comment la boucle se referme sans saut ───
 *
 * La piste contient **deux fois** la même suite de logos et se déplace de
 * `-50 %` de sa propre largeur. Arrivée au bout, la seconde moitié occupe
 * exactement la position qu'avait la première au départ : l'animation peut
 * repartir de zéro, l'œil ne voit aucune rupture.
 *
 * Chaque moitié répète la liste plusieurs fois. Avec trois marques seulement,
 * une moitié ne remplirait pas un écran large et le bandeau montrerait un vide
 * en se déplaçant.
 *
 * ─── Accessibilité ───
 *
 * La seconde moitié est un doublon visuel : elle porte `aria-hidden`, faute de
 * quoi un lecteur d'écran annoncerait chaque marque deux fois — six fois même,
 * avec les répétitions. Le défilement s'interrompt au survol et au focus,
 * pour laisser lire un nom ; et il ne démarre pas du tout si le système
 * demande un mouvement réduit.
 */

require_once __DIR__ . '/config.php';

/* Nombre de copies de la liste dans chaque moitié de piste. Trois marques
   d'environ 200 px : quatre copies couvrent 2 400 px, soit plus large que la
   quasi-totalité des écrans. */
$repetitions = 4;
?>
<section aria-labelledby="titre-partenaires" class="bg-surface-container-lowest border-t border-outline-variant py-xl overflow-hidden">
<h2 class="font-label-md text-label-md uppercase tracking-wider text-on-surface-variant text-center mb-lg px-gutter" id="titre-partenaires">
<?= te('Ils nous font confiance') ?>
</h2>

<div class="bandeau-partenaires">
<div class="bandeau-piste">
<?php for ($moitie = 0; $moitie < 2; $moitie++): ?>
<ul class="bandeau-groupe"<?= $moitie === 1 ? ' aria-hidden="true"' : '' ?>>
<?php for ($copie = 0; $copie < $repetitions; $copie++): ?>
<?php foreach (MDS_PARTENAIRES as $partenaire): ?>
<li class="bandeau-tuile">
<img alt="<?= e($partenaire['nom']) ?>" class="bandeau-logo" height="120" loading="lazy" src="assets/images/<?= e($partenaire['fichier']) ?>" width="200"/>
</li>
<?php endforeach; ?>
<?php endfor; ?>
</ul>
<?php endfor; ?>
</div>
</div>
</section>
