# Backend — API PHP (XAMPP)

PHP 8.2, PDO/MySQL, sans framework ni dépendance à installer. Le dossier se
pose dans XAMPP et fonctionne.

Il sert le site vitrine de `site/` : six routes, une base.

```
backend/
├── bootstrap.php          autochargement PSR-4, configuration, câblage du conteneur
├── config/
│   ├── app.php            CORS, débogage, fuseau
│   └── database_site.php  connexion MySQL (surchargeable par variables d'environnement)
├── public/                seul dossier exposé par Apache
│   ├── index.php          assemble la chaîne : middlewares → routeur → contrôleur
│   └── .htaccess          réécriture vers index.php, pas de listage de dossier
├── routes/
│   └── api.php            table des routes — toute la surface de l'API, en un fichier
├── scripts/
│   └── migrate-site.php   crée la base et charge les données de départ
└── src/
    ├── Controllers/       HTTP uniquement : lire la requête, appeler un service, coder la réponse
    ├── Core/              Config, Container, Database, Request, Response, Router, Pipeline
    ├── Middleware/        erreurs, CORS, décodage JSON
    ├── Models/            une classe par table — tout le SQL est ici, et nulle part ailleurs
    └── Services/          la logique métier : validation, anti-spam, mise en forme
```

Le code métier vit hors de `public/` : une requête HTTP ne peut atteindre ni la
configuration ni les modèles, même si PHP venait à ne plus interpréter les
fichiers.

## Le chemin d'une requête

```
POST /contact
   │
   ├─ ErreurMiddleware      capture tout et répond en JSON, jamais en HTML d'Apache
   ├─ CorsMiddleware        autorise l'origine du site ; répond seul au préflight
   ├─ JsonBodyMiddleware    décode le corps, refuse un JSON illisible en 400
   │
   ├─ Router                méthode + chemin → contrôleur (404 inconnu, 405 mauvaise méthode)
   ├─ ContactController     traduit le résultat en code HTTP
   ├─ ContactService        pot de miel, validation, limiteur de débit
   └─ MessageContactModel   l'unique endroit où il y a du SQL
```

Cette séparation a une raison précise : `ContactService` s'exécute sans serveur
web. Les règles qui comptent — un objet hors liste est refusé, six envois en
une heure sont bloqués — peuvent être vérifiées sans requête HTTP, alors
qu'elles seraient inatteignables si elles vivaient dans le contrôleur.

## Installation

1. **Base de données**

   ```bat
   C:\xampp\php\php.exe backend\scripts\migrate-site.php
   ```

   Crée `mds_site` et ses quatre tables, puis charge le portfolio de départ.
   Équivaut à importer `database/03_schema_site.sql` puis `04_seed_site.sql`
   dans phpMyAdmin.

   Le script **refuse de rejouer le schéma si `messages_contact` n'est pas
   vide** : ces messages sont de vraies demandes de prospects et n'existent
   nulle part ailleurs. `--donnees-seul` recharge le portfolio sans y toucher ;
   `--force` passe outre en connaissance de cause.

2. **Exposer l'API et le site dans Apache** — deux jonctions depuis `htdocs`
   suffisent, sans toucher à `httpd.conf` :

   ```bat
   mklink /J C:\xampp\htdocs\mds-api  "c:\Users\user\Desktop\MDS\backend\public"
   mklink /J C:\xampp\htdocs\mds-site "c:\Users\user\Desktop\MDS\site"
   ```

   Pour les retirer : `rmdir C:\xampp\htdocs\mds-api` (supprime le lien, pas le
   projet).

   *Alternative* — un alias dans `C:\xampp\apache\conf\httpd.conf` :

   ```apache
   Alias /mds-api "c:/Users/user/Desktop/MDS/backend/public"
   <Directory "c:/Users/user/Desktop/MDS/backend/public">
       AllowOverride All
       Require all granted
   </Directory>
   ```

3. **Vérifier** — Apache démarré : <http://localhost/mds-api/sante>

   Puis le site : <http://localhost/mds-site/>

   Les pages **doivent** être ouvertes par `http://localhost/…` : elles
   contiennent du PHP, et l'origine `file://` n'est pas dans `cors_origines`.

## Routes

Base : `http://localhost/mds-api` — déclarées dans `routes/api.php`.

| Méthode | Route | Rôle |
|---|---|---|
| `GET` | `/sante` | Le service répond-il, et la base est-elle vraiment là (nombre de tables) |
| `GET` | `/realisations` | Portfolio publié + catégories de filtre |
| `GET` | `/realisations?categorie=retail` | Idem, restreint à une catégorie (404 si le code n'existe pas) |
| `GET` | `/actualites` | Annonces publiées + catégories de filtre |
| `GET` | `/actualites?categorie=carriere` | Idem, restreint à une catégorie (404 si le code n'existe pas) |
| `GET` | `/actualite?id=3` | Détail d'une annonce (400 si l'identifiant manque, 404 si elle n'existe pas) |
| `POST` | `/contact` | Message du formulaire → `messages_contact` |

### `?lang=` — la langue du contenu

Les trois routes de lecture acceptent `?lang=fr` (défaut) ou `?lang=en`. Elles
rendent alors les titres, résumés, corps d'annonce, libellés de catégorie **et
les dates** dans cette langue.

Les traductions vivent dans des colonnes `_en` à côté du français. Une colonne
vide ou `NULL` fait retomber la réponse sur le français : une annonce publiée
avant d'être traduite reste lisible, au lieu de s'afficher vide.

Toute valeur non reconnue est ramenée au français. Ce filtre, dans
[`Request::langue()`](src/Core/Request.php), **est un garde-fou de sécurité** :
les modèles composent un nom de colonne (`titre_en`) à partir de ce code, et un
nom de colonne ne peut pas être passé en paramètre lié — il entre donc dans le
SQL par concaténation. La liste fermée est ce qui garantit que rien venu de
l'URL n'atteint la requête.

### `GET /realisations`

```json
{
  "categories": [{ "code": "retail", "libelle": "Retail Audit", "nombre": 1 }],
  "realisations": [{
    "id": 1, "titre": "Audit Retail National - Boissons",
    "categorie": "retail", "categorieLibelle": "Retail Audit",
    "resume": "…", "date": "2024-03-01", "dateLibelle": "Mars 2024",
    "image": "…", "imageAlt": "…", "fichePdf": null
  }]
}
```

`dateLibelle` est produit côté serveur par [`Core\Dates`](src/Core/Dates.php) :
la base garde une vraie `DATE` pour le tri, et le rendu suit la langue demandée
plutôt que la locale du visiteur — deux personnes lisant la même page dans la
même langue voient la même date. Une catégorie dont
`nombre` vaut 0 n'obtient pas d'onglet — il ne mènerait qu'à un écran vide.

### `POST /contact` — la seule route écrite depuis l'internet public

Corps : les `name` du formulaire de `site/contact.php`, tels quels
(`firstName`, `lastName`, `company`, `email`, `subject`, `message`, `bot_field`).

| Statut | Cas |
|---|---|
| `201` | Message enregistré |
| `422` | Champs invalides — le détail est dans `champs`, indexé par nom de champ HTML |
| `429` | Plus de 5 messages en une heure depuis la même IP |

```json
{ "erreur": "Certains champs sont invalides.",
  "champs": { "email": "Cette adresse email ne semble pas valide." } }
```

Trois barrages, dans `ContactService` : le pot de miel `bot_field` (un robot qui
le remplit reçoit `201` mais rien n'est écrit — lui dire qu'il est rejeté lui
apprendrait quel champ éviter), la validation de forme, puis le limiteur de
débit. **La validation du navigateur ne compte pas** : `required` et
`type="email"` sont ignorés par un envoi direct, tout est revérifié côté serveur.

Le limiteur lit `REMOTE_ADDR` et jamais `X-Forwarded-For`, que le client écrit
lui-même : le croire donnerait à un robot le moyen de changer d'identité à
chaque envoi. Derrière un reverse proxy, `Request::ip()` devra lire l'en-tête
que ce proxy garantit, et lui seul.

## Vérifier en ligne de commande

```bash
curl http://localhost/mds-api/sante
curl http://localhost/mds-api/realisations

curl -X POST -H "Content-Type: application/json" \
     -d '{"firstName":"Awa","lastName":"Ndiaye","email":"a@example.cm",
          "subject":"research","message":"Une demande suffisamment détaillée."}' \
     http://localhost/mds-api/contact
```

## Sans Apache

Utile pour un test rapide, ou si le port 80 est déjà pris :

```bat
C:\xampp\php\php.exe -S 127.0.0.1:8123 -t backend\public backend\public\index.php
```

Pensez alors à ajouter l'origine correspondante à `cors_origines` dans
`config/app.php`, ou à appeler l'API directement en `curl`.

## Étendre

- **Une route** — une ligne dans `routes/api.php`, une méthode de contrôleur.
- **Une règle métier** — un service ; les contrôleurs ne décident de rien.
- **Une table** — un modèle ; le SQL ne sort jamais de `src/Models/`.
- **Un traitement transverse** (authentification, journal, limitation de débit
  globale) — un middleware, ajouté à la chaîne de `public/index.php`, dans
  l'ordre voulu.

## Ce que ce backend n'est pas

Il n'a ni authentification, ni back-office : les réalisations se publient par
`INSERT`, les messages se consultent par `SELECT`. C'est suffisant tant qu'une
seule personne administre le contenu ; le jour où ce n'est plus le cas, il
faudra une interface d'administration et donc une session.

`config/database_site.php` utilise `root` sans mot de passe — le réglage par
défaut de XAMPP. À remplacer par un compte n'ayant que `SELECT` sur les tables
de lecture et `INSERT` sur `messages_contact` dès que l'API sort du poste
local. Et `debug` doit alors passer à `false` dans `config/app.php` : un
message d'erreur SQL renseigne autant l'équipe que l'attaquant.

## Historique

Ce backend a d'abord servi une application de collecte terrain (React + Vite,
base `mds_retail_audit`), retirée du projet le 17 août 2026 avec son dossier
`frontend/`. Les routes `/session`, `/visites`, `/operateurs` et
`/statistiques` n'existent plus. La base `mds_retail_audit` existe peut-être
encore sur le poste, inutilisée : plus aucun code ne s'y connecte, et ses
fichiers SQL ont été supprimés — elle n'est plus reconstructible.
