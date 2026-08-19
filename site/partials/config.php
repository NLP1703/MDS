<?php
declare(strict_types=1);

/**
 * Réglages partagés par les cinq pages du site.
 *
 * Coordonnées, menu et adresse de l'API vivent ici et nulle part ailleurs :
 * changer un numéro de téléphone se faisait auparavant en cinq endroits, avec
 * une occasion d'oubli à chaque fois — c'est ainsi que deux adresses e-mail
 * différentes avaient fini par cohabiter sur le site.
 */

/*
 * `define` et non `const` : les deux dernières entrées lisent l'environnement,
 * ce qu'une constante de compilation n'autorise pas. L'usage ne change pas —
 * `MDS['email']` s'écrit toujours de la même façon.
 */
define('MDS', [
    'nom'        => 'MDS Market Research',
    'nom_long'   => 'Marketing & Distribution Services',
    'email'      => 'contact@mds-cmr.com',
    'tel_fixe'   => '+237 233 47 00 21',
    'tel_mobile' => '+237 696 96 77 43',
    'whatsapp'   => '237696967743',
    'adresse'    => 'Makepe St Tropez',
    'ville'      => 'Douala, Cameroun',
    'horaires'   => 'Lundi à vendredi, 9 h – 17 h',

    /*
     * Base de l'API et origine publique du site.
     *
     * En local, le site et l'API sont deux alias du même Apache, d'où les
     * valeurs de repli. En ligne, l'hébergeur les fournit par l'environnement :
     * elles changent à chaque domaine, et les coder en dur obligerait à
     * modifier le fichier avant chaque mise en ligne — donc à l'oublier.
     *
     * `origine` doit rester une URL absolue : Open Graph refuse les chemins
     * relatifs, et `actualite.php` appelle l'API depuis le serveur.
     */
    'api'        => getenv('MDS_API_URL') ?: 'http://localhost/mds-api',
    'origine'    => getenv('MDS_SITE_ORIGINE') ?: 'http://localhost/mds-site',
]);

/**
 * Le menu, dans l'ordre. La clé sert à marquer la page courante.
 *
 * Les libellés restent en français ici : ils passent par `t()` à l'affichage,
 * comme le reste du site. Les URL, elles, ne changent pas d'une langue à
 * l'autre — un lien partagé reste valable quelle que soit la langue du
 * destinataire, qui verra la page dans la sienne.
 */
const MDS_MENU = [
    'accueil'      => ['libelle' => 'Accueil',      'url' => 'index.php'],
    'expertise'    => ['libelle' => 'Expertise',    'url' => 'expertise.php'],
    'realisations' => ['libelle' => 'Réalisations', 'url' => 'realisations.php'],
    'actualites'   => ['libelle' => 'Actualités',   'url' => 'actualites.php'],
    'a-propos'     => ['libelle' => 'À propos',     'url' => 'a-propos.php'],
    'contact'      => ['libelle' => 'Contact',      'url' => 'contact.php'],
];

/**
 * Échappement HTML.
 *
 * Nommée court parce qu'elle est appelée partout : toute valeur qui entre dans
 * le HTML passe par elle, y compris celles de ce fichier. Une apostrophe dans
 * un nom de rue ne doit pas pouvoir casser un attribut.
 */
function e(?string $valeur): string
{
    return htmlspecialchars($valeur ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/* Le bilinguisme est chargé ici, après `e()` dont il dépend, et avant que la
   moindre page n'écrive quoi que ce soit : `mds_langue()` pose un cookie, ce
   qui exige que rien n'ait encore été envoyé au navigateur. */
require_once __DIR__ . '/i18n.php';

/** Lien WhatsApp complet, à partir du numéro déclaré plus haut. */
function mds_whatsapp(): string
{
    return 'https://wa.me/' . MDS['whatsapp'];
}

/** Numéro de téléphone au format `tel:` — sans espaces, que les navigateurs refusent. */
function mds_tel(string $numero): string
{
    return 'tel:' . preg_replace('/\s+/', '', $numero);
}

/**
 * Initiales d'un nom, pour les membres d'équipe sans photo.
 *
 * Deux lettres au plus : « Simplice NGAMPOU » → « SN ». Un cadre vide dans une
 * grille de portraits se remarque plus qu'une pastille assumée.
 */
function mds_initiales(string $nom): string
{
    $mots = preg_split('/[\s\-]+/u', trim($nom), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $lettres = array_map(
        static fn(string $m): string => mb_strtoupper(mb_substr($m, 0, 1)),
        array_slice($mots, 0, 2)
    );

    return implode('', $lettres);
}

/**
 * Met en forme le corps d'une actualité.
 *
 * Les annonces de MDS — appels d'offres, recrutements — ont une structure
 * constante : des intertitres, des listes de prestations, des paragraphes.
 * Les stocker en HTML exposerait la base à recevoir du balisage arbitraire ;
 * on retient donc trois conventions de texte brut, et **tout est échappé avant
 * d'être interprété** — un `<script>` saisi en base s'affiche, il ne s'exécute
 * jamais.
 *
 *   `## Titre`   → intertitre
 *   `- élément`  → puce (les lignes consécutives forment une liste)
 *   `**gras**`   → mise en évidence
 *   ligne vide   → nouveau paragraphe
 */
function mds_rendre_contenu(string $texte): string
{
    $sortie = '';
    $liste  = false;

    $fermerListe = static function () use (&$sortie, &$liste): void {
        if ($liste) {
            $sortie .= "</ul>\n";
            $liste = false;
        }
    };

    /* Le gras est appliqué APRÈS l'échappement : la balise que l'on insère est
       la seule du document qui ne vienne pas de la base. */
    $gras = static fn(string $t): string => preg_replace(
        '/\*\*(.+?)\*\*/u',
        '<strong class="text-on-surface font-semibold">$1</strong>',
        e($t)
    ) ?? e($t);

    $paragraphe = [];

    $viderParagraphe = static function () use (&$sortie, &$paragraphe, $gras): void {
        if ($paragraphe === []) {
            return;
        }
        $sortie .= '<p>' . implode('<br/>', array_map($gras, $paragraphe)) . "</p>\n";
        $paragraphe = [];
    };

    foreach (preg_split('/\R/', trim($texte)) ?: [] as $ligne) {
        $ligne = rtrim($ligne);

        if (trim($ligne) === '') {
            $viderParagraphe();
            $fermerListe();
            continue;
        }

        if (str_starts_with($ligne, '## ')) {
            $viderParagraphe();
            $fermerListe();
            $sortie .= '<h2 class="font-headline-md text-headline-md text-primary pt-md">'
                . $gras(substr($ligne, 3)) . "</h2>\n";
            continue;
        }

        if (str_starts_with($ligne, '- ')) {
            $viderParagraphe();
            if (!$liste) {
                $sortie .= '<ul class="list-disc list-outside pl-lg space-y-xs">' . "\n";
                $liste = true;
            }
            $sortie .= '<li>' . $gras(substr($ligne, 2)) . "</li>\n";
            continue;
        }

        $fermerListe();
        $paragraphe[] = $ligne;
    }

    $viderParagraphe();
    $fermerListe();

    return $sortie;
}
