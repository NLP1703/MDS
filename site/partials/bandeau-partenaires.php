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
 * Chaque moitié répète la liste deux fois. Une seule suffirait sur un écran
 * ordinaire, pas sur un très grand : dès que la moitié est plus étroite que
 * la fenêtre, un vide traverse le bandeau à chaque tour.
 *
 * ─── Chargement ───
 *
 * Pas de `loading="lazy"` ici, contrairement au reste du site. Le chargement
 * différé se règle sur la fenêtre : les logos qui attendent leur tour à trois
 * mille pixels sur la droite ne sont jamais « proches », et arriveraient
 * blancs sous les yeux du visiteur. Les onze fichiers pèsent ensemble moins
 * qu'une photographie de la page, et la seconde moitié n'en télécharge aucun
 * puisqu'elle réemploie les mêmes adresses.
 *
 * ─── Accessibilité ───
 *
 * La seconde moitié est un doublon visuel : elle porte `aria-hidden`, faute de
 * quoi un lecteur d'écran annoncerait chaque marque deux fois — quatre fois
 * même, avec les répétitions. Le défilement s'interrompt au survol et au
 * focus, pour laisser lire un nom ; et il ne démarre pas du tout si le système
 * demande un mouvement réduit.
 */

require_once __DIR__ . '/config.php';

/* Nombre de copies de la liste dans chaque moitié de piste. Onze marques
   d'environ 190 px : deux copies couvrent plus de 4 000 px, soit au-delà des
   écrans les plus larges. */
$repetitions = 2;
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
<img alt="<?= e($partenaire['nom']) ?>" class="bandeau-logo" decoding="async" height="240" src="assets/images/<?= e($partenaire['fichier']) ?>" width="480"/>
</li>
<?php endforeach; ?>
<?php endfor; ?>
</ul>
<?php endfor; ?>
</div>
</div>
</section>
