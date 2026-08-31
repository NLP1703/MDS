<?php
declare(strict_types=1);

/**
 * Drapeaux des pays où MDS opère, dessinés en SVG.
 *
 * ─── Pourquoi pas les émojis ───
 *
 * 🇨🇲 s'écrit en une ligne, mais **Windows n'affiche pas les drapeaux** : la
 * police Segoe UI Emoji ne dessine pas ces paires de caractères et rend les
 * deux lettres du code pays à la place. Une bonne partie des visiteurs verrait
 * « CM » et « CD » au lieu des couleurs. Le SVG, lui, s'affiche partout et de
 * la même façon.
 *
 * ─── Pourquoi pas des images ───
 *
 * Quatre fichiers de plus à servir, quatre requêtes, et des couleurs qui ne
 * suivraient pas la mise à l'échelle. Le tracé est ici du texte : il pèse
 * quelques centaines d'octets et reste net à toute taille.
 *
 * ─── Les proportions ───
 *
 * Les quatre drapeaux n'ont pas le même rapport officiel : 2:3 pour le
 * Cameroun et le Tchad, 3:4 pour le Gabon et la RDC. Ils sont tous ramenés
 * ici à **3:2**, pour qu'alignés en tête de carte ils forment une colonne
 * régulière — quatre vignettes de largeurs différentes se liraient comme un
 * défaut de mise en page.
 */

/**
 * L'étoile à cinq branches, en coordonnées unitaires.
 *
 * Rayon extérieur 1, rayon intérieur 0,382 (le rapport qui donne la branche
 * régulière), pointe vers le haut. Elle est positionnée et dimensionnée par un
 * `transform` : un seul tracé sert ainsi au Cameroun et à la RDC.
 */
const MDS_ETOILE = '0,-1 0.2245,-0.309 0.9511,-0.309 0.3633,0.118 0.5878,0.809 '
    . '0,0.382 -0.5878,0.809 -0.3633,0.118 -0.9511,-0.309 -0.2245,-0.309';

/**
 * Le tracé d'un drapeau, sans les balises `<svg>`.
 *
 * @return array{0:string, 1:string} Le contenu SVG et le nom du pays.
 */
function mds_drapeau_trace(string $code): array
{
    $etoile = MDS_ETOILE;

    switch ($code) {
        // Trois bandes verticales, étoile d'or au centre de la bande rouge.
        case 'cm':
            return [
                '<rect width="300" height="600" fill="#007a5e"/>'
                . '<rect x="300" width="300" height="600" fill="#ce1126"/>'
                . '<rect x="600" width="300" height="600" fill="#fcd116"/>'
                . '<polygon points="' . $etoile . '" fill="#fcd116"'
                . ' transform="translate(450 300) scale(88)"/>',
                'Cameroun',
            ];

        /* Champ bleu ciel, barre rouge en diagonale bordée de jaune, étoile au
           canton. La diagonale est tracée au trait plutôt qu'en polygone : deux
           traits superposés d'épaisseurs différentes donnent la bordure sans
           calculer huit sommets. Le débord est coupé par le cadre du SVG. */
        case 'cd':
            return [
                '<rect width="900" height="600" fill="#007fff"/>'
                . '<line x1="-60" y1="660" x2="960" y2="-60" stroke="#f7d618" stroke-width="190"/>'
                . '<line x1="-60" y1="660" x2="960" y2="-60" stroke="#ce1021" stroke-width="120"/>'
                . '<polygon points="' . $etoile . '" fill="#f7d618"'
                . ' transform="translate(150 140) scale(92)"/>',
                'RDC',
            ];

        // Trois bandes verticales, sans emblème.
        case 'td':
            return [
                '<rect width="300" height="600" fill="#002664"/>'
                . '<rect x="300" width="300" height="600" fill="#fecb00"/>'
                . '<rect x="600" width="300" height="600" fill="#c60c30"/>',
                'Tchad',
            ];

        // Trois bandes horizontales.
        case 'ga':
            return [
                '<rect width="900" height="200" fill="#009e60"/>'
                . '<rect y="200" width="900" height="200" fill="#fcd856"/>'
                . '<rect y="400" width="900" height="200" fill="#3a75c4"/>',
                'Gabon',
            ];
    }

    return ['', ''];
}

/**
 * Le drapeau prêt à poser dans la page.
 *
 * Décoratif : le nom du pays est écrit juste à côté, en toutes lettres et
 * traduit. Le répéter au lecteur d'écran le ferait entendre deux fois — d'où
 * `aria-hidden`. Le liseré n'est pas un ornement : sans lui, la bande jaune du
 * Gabon et celle du Tchad se fondraient dans le fond clair de la carte.
 */
function mds_drapeau(string $code, string $classes = ''): string
{
    [$trace] = mds_drapeau_trace($code);

    if ($trace === '') {
        return '';
    }

    return '<svg class="' . e(rtrim('drapeau-pays ' . $classes)) . '" viewBox="0 0 900 600"'
        . ' width="45" height="30" aria-hidden="true" focusable="false"'
        . ' preserveAspectRatio="xMidYMid slice">'
        . $trace
        . '<rect width="900" height="600" fill="none" stroke="rgba(11,31,73,0.22)" stroke-width="16"/>'
        . '</svg>';
}
