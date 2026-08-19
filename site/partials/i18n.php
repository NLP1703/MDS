<?php
declare(strict_types=1);

/**
 * Bilinguisme français / anglais.
 *
 * ─── Pourquoi le français sert de clé ───
 *
 * Les entrées du dictionnaire sont les phrases françaises elles-mêmes, et non
 * des identifiants abstraits (`accueil.hero.titre`). Deux raisons :
 *
 *   · le code reste lisible — `t('Nos Réalisations')` se comprend sans aller
 *     consulter une table de correspondance ;
 *   · l'absence de traduction se dégrade proprement — une phrase non traduite
 *     s'affiche en français plutôt qu'en clé technique nue devant le visiteur.
 *
 * Contrepartie assumée : retoucher une phrase française casse le lien avec sa
 * traduction, qui redevient alors du français. C'est visible, donc réparable ;
 * l'inverse — une clé orpheline affichée telle quelle — ne l'est pas.
 *
 * ─── Choix de la langue ───
 *
 * Par ordre de priorité : le paramètre `?lang=`, puis le cookie posé lors d'un
 * choix précédent, puis la langue déclarée par le navigateur. Le français reste
 * le défaut : c'est la langue de travail de MDS et de la majorité de sa
 * clientèle.
 */

const MDS_LANGUES = [
    'fr' => ['nom' => 'Français', 'court' => 'FR', 'locale' => 'fr_FR'],
    'en' => ['nom' => 'English',  'court' => 'EN', 'locale' => 'en_US'],
];

const MDS_LANGUE_DEFAUT = 'fr';
const MDS_COOKIE_LANGUE = 'mds_lang';


/**
 * La langue de la page en cours, résolue une seule fois.
 *
 * Le cookie n'est posé que sur un choix explicite (`?lang=`), jamais sur une
 * simple détection : un visiteur anglophone de passage ne doit pas voir son
 * choix figé sans l'avoir demandé.
 */
function mds_langue(): string
{
    static $langue = null;

    if ($langue !== null) {
        return $langue;
    }

    $demandee = $_GET['lang'] ?? null;

    if (is_string($demandee) && isset(MDS_LANGUES[$demandee])) {
        $langue = $demandee;

        // 1 an : le choix de langue n'a pas de raison d'expirer plus tôt.
        if (!headers_sent()) {
            setcookie(MDS_COOKIE_LANGUE, $langue, [
                'expires'  => time() + 31536000,
                'path'     => '/',
                'samesite' => 'Lax',
            ]);
        }

        return $langue;
    }

    $cookie = $_COOKIE[MDS_COOKIE_LANGUE] ?? null;
    if (is_string($cookie) && isset(MDS_LANGUES[$cookie])) {
        return $langue = $cookie;
    }

    // Accept-Language : on ne lit que les deux premières lettres du premier
    // choix. Une négociation complète (avec les poids `q=`) serait du zèle
    // pour un site qui n'a que deux langues.
    $entete = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
    if (is_string($entete) && str_starts_with(strtolower(ltrim($entete)), 'en')) {
        return $langue = 'en';
    }

    return $langue = MDS_LANGUE_DEFAUT;
}


/** L'autre langue — celle vers laquelle le bouton bascule. */
function mds_autre_langue(): string
{
    return mds_langue() === 'fr' ? 'en' : 'fr';
}


/**
 * Traduit une phrase.
 *
 * En français, la phrase est rendue telle quelle : le dictionnaire ne contient
 * que l'anglais, ce qui évite d'entretenir deux fois le même texte source.
 */
function t(string $phrase): string
{
    if (mds_langue() === 'fr') {
        return $phrase;
    }

    static $dictionnaire = null;
    if ($dictionnaire === null) {
        $dictionnaire = require __DIR__ . '/traductions-en.php';
    }

    return $dictionnaire[$phrase] ?? $phrase;
}


/**
 * Traduit puis échappe — le raccourci le plus fréquent dans les gabarits.
 *
 * La phrase est du **texte**, jamais du HTML : `&` s'écrit `&`, et l'espace
 * insécable `\u{A0}` (en chaîne double quote). Une entité comme `&nbsp;` ou
 * `&amp;` ressortirait telle quelle à l'écran — `e()` échapperait son `&`.
 */
function te(string $phrase): string
{
    return e(t($phrase));
}


/**
 * L'adresse de la page courante dans une autre langue.
 *
 * Les autres paramètres sont conservés : basculer la langue depuis le détail
 * d'une actualité doit rester sur cette actualité, pas renvoyer à l'accueil.
 */
function mds_url_langue(string $langue): string
{
    $chemin = basename(parse_url($_SERVER['REQUEST_URI'] ?? 'index.php', PHP_URL_PATH) ?: 'index.php');

    if ($chemin === '' || $chemin === '/') {
        $chemin = 'index.php';
    }

    $parametres = $_GET;
    $parametres['lang'] = $langue;

    return $chemin . '?' . http_build_query($parametres);
}


/**
 * Les langues disponibles, pour les balises `hreflang`.
 *
 * @return array<string,string> code => URL absolue
 */
function mds_alternatives(): array
{
    $base = rtrim(MDS['origine'], '/') . '/';
    $sorties = [];

    foreach (array_keys(MDS_LANGUES) as $code) {
        $sorties[$code] = $base . mds_url_langue($code);
    }

    return $sorties;
}
