# Base de données — MySQL / MariaDB (XAMPP)

Deux fichiers, à jouer dans l'ordre :

| Fichier | Contenu |
|---|---|
| `03_schema_site.sql` | Création de la base `mds_site` et de ses quatre tables. **Supprime et recrée les tables** — voir l'avertissement ci-dessous. |
| `04_seed_site.sql` | Catégories de réalisations, objets du formulaire de contact, et les quatre réalisations de départ. Idempotent, rejouable sans risque. |

La numérotation commence à 03 : les fichiers 01 et 02 décrivaient la base de
l'application de collecte terrain, retirée du projet. Les numéros n'ont pas été
décalés pour que les scripts et la documentation restent cohérents.

## Tables

| Table | Rôle |
|---|---|
| `categories_realisation` | Les onglets de filtre de la page Réalisations |
| `realisations` | Les études de cas publiées (`publiee = 0` pour un brouillon) |
| `categories_actualite` | Les rubriques de la page Actualités |
| `actualites` | Recrutements, appels d'offres et publications |
| `objets_contact` | Les entrées de la liste « Objet » du formulaire |
| `messages_contact` | Les demandes reçues |

## Import

**Par script** — c'est la voie recommandée : elle vérifie l'état de la base
avant d'écrire.

```bat
C:\xampp\php\php.exe backend\scripts\migrate-site.php
C:\xampp\php\php.exe backend\scripts\migrate-site.php --donnees-seul
C:\xampp\php\php.exe backend\scripts\migrate-site.php --schema-seul
```

**Par phpMyAdmin** — démarrez Apache et MySQL dans le panneau XAMPP, ouvrez
<http://localhost/phpmyadmin>, onglet *Importer*, choisissez
`03_schema_site.sql`, exécutez, puis recommencez avec `04_seed_site.sql`.

## ⚠ Les messages de contact ne se reconstruisent pas

`03_schema_site.sql` supprime et recrée les tables. Le portfolio, lui, se
recharge depuis `04_seed_site.sql` ; **les messages reçus par le formulaire
n'existent nulle part ailleurs.**

`migrate-site.php` refuse donc de rejouer le schéma tant que
`messages_contact` n'est pas vide. Pour sauvegarder d'abord :

```bat
C:\xampp\mysql\bin\mysqldump.exe -u root mds_site messages_contact > messages.sql
```

Puis `--force` pour confirmer, ou `--donnees-seul` pour recharger le
portfolio sans toucher au schéma.

## Alimenter le site

Ni le portfolio ni les images ne sont écrits dans le HTML : tout se modifie en
base, sans redéploiement.

```sql
-- Publier une réalisation
INSERT INTO realisations (titre, categorie, resume, date_publication, image_url, fiche_pdf, ordre)
VALUES ('Titre', 'retail', 'Résumé…', '2026-05-01', 'assets/images/x.jpg', 'assets/pdf/x.pdf', 50);

-- Retirer une réalisation sans la supprimer
UPDATE realisations SET publiee = 0 WHERE realisation_id = 3;

-- Publier une actualité. Sans `contenu` ni `lien_externe`, la page de détail
-- affiche l'annonce et invite à répondre par courriel.
INSERT INTO actualites (titre, categorie, date_publication, resume, contenu)
VALUES ('Recrutement : Statisticien (H/F)', 'carriere', '2026-09-01',
        'Résumé affiché sur la carte…', 'Texte complet de l''annonce…');

-- Suivre les demandes reçues
SELECT message_id, prenom, nom, email, objet, recu_le FROM messages_contact WHERE statut = 'nouveau';
UPDATE messages_contact SET statut = 'traite' WHERE message_id = 12;
```
