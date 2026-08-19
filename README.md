# MDS Market Research — site vitrine

Site institutionnel de MDS Market Research (Douala, Cameroun) : cabinet
d'études de marché, sociales et d'opinion, certifié ISO 20252.

Sept pages. Trois d'entre elles — Réalisations, Actualités et Contact — sont
alimentées par une petite API PHP ; les autres sont statiques.

```
MDS/
├── site/        Les pages — PHP + Tailwind (local), servies par Apache
│   ├── partials/    en-tête, pied de page, configuration — partagés
│   └── assets/      Tailwind, polices, styles, scripts, images
├── backend/     API PHP 8 / PDO                     — servie par Apache
└── database/    Schéma et données de départ MySQL   — importés dans MariaDB
```

| Page | Contenu | Backend |
|---|---|---|
| `index.php` | Accueil : activités, valeurs, dirigeant, chiffres, actualités | — statique |
| `expertise.php` | Les 5 pôles d'expertise | — statique |
| `realisations.php` | Portfolio filtrable | `GET /realisations` |
| `actualites.php` | Recrutements, appels d'offres | `GET /actualites` |
| `actualite.php?id=N` | Détail d'une annonce | `GET /actualite` |
| `a-propos.php` | Histoire, valeurs, ISO 20252, dirigeant | — statique |
| `contact.php` | Formulaire de contact | `POST /contact` |
| `404.php` | Page d'erreur | — statique |

### Pourquoi du PHP plutôt que du HTML

Les pages partagent leur en-tête, leur pied de page et leur configuration
Tailwind. En HTML, ces blocs étaient recopiés dans chaque fichier : **547 lignes
dupliquées**, et trois pieds de page différents avaient fini par diverger, avec
deux adresses e-mail contradictoires.

Les `include` PHP règlent cela sans rien installer — Apache et PHP servent déjà
l'API. Une coordonnée se change désormais dans
[`partials/config.php`](site/partials/config.php), et nulle part ailleurs.

React aurait donné le même résultat au prix de Node, de `npm install` et d'une
compilation avant chaque mise en ligne. Pour cinq pages majoritairement
statiques, l'échange n'était pas favorable.

## Démarrage

**Prérequis** : XAMPP (Apache + MySQL). Aucun Node, aucun `npm install` : les
pages sont du HTML servi tel quel.

```bat
:: 1. Base de données — panneau XAMPP : démarrer MySQL, puis
C:\xampp\php\php.exe backend\scripts\migrate-site.php

:: 2. Exposer l'API et le site dans htdocs, puis démarrer Apache
mklink /J C:\xampp\htdocs\mds-api  "%CD%\backend\public"
mklink /J C:\xampp\htdocs\mds-site "%CD%\site"
```

Puis :

- Site : <http://localhost/mds-site/>
- API : <http://localhost/mds-api/sante>

Les pages **doivent** être ouvertes par `http://localhost/…`. Elles contiennent
du PHP : double-cliquer le fichier afficherait son code source au lieu de la
page, et l'origine `file://` n'est de toute façon pas autorisée par l'API.

## Modifier le site

| Ce que vous voulez changer | Où |
|---|---|
| Téléphone, e-mail, adresse, horaires | [`site/partials/config.php`](site/partials/config.php) |
| Entrées du menu | `MDS_MENU` dans le même fichier |
| Couleurs, typographie, espacements | [`site/assets/mds-tailwind.js`](site/assets/mds-tailwind.js) |
| En-tête, pied de page | [`site/partials/`](site/partials/) |
| Réalisations affichées | base `mds_site`, table `realisations` |
| Actualités publiées | base `mds_site`, table `actualites` |

## Mise en ligne sur Vercel

Vercel n'exécute pas PHP nativement et n'héberge aucune base : le déploiement
repose sur le runtime communautaire [`vercel-php`](https://github.com/vercel-community/php)
et sur un **MySQL externe**, à provisionner séparément (Aiven, TiDB Cloud,
Clever Cloud, ou le MySQL d'un hébergeur classique).

`vercel.json` remplace ce que faisaient les `.htaccess`, qui sont ignorés :
routage des pages, blocage de `partials/`, en-têtes de sécurité et de cache.
`api/index.php` expose l'API sous `/api` — voir le commentaire du fichier, son
emplacement n'est pas anodin.

Variables d'environnement à déclarer dans Vercel (Settings → Environment
Variables) :

| Variable | Rôle |
|---|---|
| `MDS_SITE_DB_HOST` | hôte MySQL externe |
| `MDS_SITE_DB_PORT` | port, si différent de 3306 |
| `MDS_SITE_DB_NAME` | nom de la base |
| `MDS_SITE_DB_USER` | compte — `SELECT` suffit, sauf pour le formulaire |
| `MDS_SITE_DB_PASS` | mot de passe |
| `MDS_API_URL` | `https://<domaine>/api` |
| `MDS_SITE_ORIGINE` | `https://<domaine>` — URL absolue, exigée par Open Graph |
| `MDS_ENTETE_IP` | `x-vercel-forwarded-for` — sans quoi le limiteur du formulaire compte tous les visiteurs comme un seul |

Le schéma et les données de départ s'importent une fois dans la base externe
depuis [`database/`](database/).

**Ce que ce déploiement coûte**, par rapport à un hébergement PHP classique :
le runtime PHP n'est pas officiellement supporté par Vercel, les pages
deviennent des fonctions serverless démarrées à froid, et la base est chez un
tiers — donc un aller-retour réseau à chaque requête. Un hébergement mutualisé
Apache + PHP + MySQL fait tourner ce code sans une ligne de configuration.

## Notes

Aucune étape de compilation, aucun `npm`. Tailwind est servi depuis
`assets/vendor/`, les polices depuis `assets/fonts/` : **le site s'affiche sans
accès réseau**.

Contrepartie assumée : Tailwind génère les classes dans le navigateur, ce qui
ajoute environ 450 Ko au premier chargement. C'est le prix de pages modifiables
avec un simple éditeur de texte. Le jour où ce coût devient gênant, la parade
est un vrai build Tailwind — qui, lui, réintroduit Node.

Ce dossier a d'abord hébergé une application de collecte terrain (React +
Vite, base `mds_retail_audit`), retirée du projet. La base existe peut-être
encore sur le poste, inutilisée ; plus aucun code ne s'y connecte.
