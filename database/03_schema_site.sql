-- ═══════════════════════════════════════════════════════════════════════════
--  MDS — Site vitrine · schéma MySQL / MariaDB (XAMPP)
-- ═══════════════════════════════════════════════════════════════════════════
--
--  Base du site vitrine : portfolio publié et messages reçus par le
--  formulaire de contact. Rien d'autre.
--
--  Import : phpMyAdmin → Importer, ou
--  C:\xampp\php\php.exe backend\scripts\migrate-site.php
-- ═══════════════════════════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS mds_site
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE mds_site;

-- Ordre inverse des dépendances : les tables filles d'abord.
--
--  ⚠  `messages_contact` contient de vrais messages de prospects. Le script
--  `migrate-site.php` refuse de rejouer ce fichier si la table n'est pas vide,
--  et ne passe outre que sur `--force` explicite. Ne retirez pas ce garde-fou.
DROP TABLE IF EXISTS messages_contact;
DROP TABLE IF EXISTS objets_contact;
DROP TABLE IF EXISTS actualites;
DROP TABLE IF EXISTS categories_actualite;
DROP TABLE IF EXISTS realisations;
DROP TABLE IF EXISTS categories_realisation;


-- ─────────────────────────────────────────────────────────────────────────
--  Categories_realisation — les onglets de filtre de la page
-- ─────────────────────────────────────────────────────────────────────────
--  Table plutôt qu'énumération figée dans le code : ajouter un pôle d'activité
--  au site ne doit demander qu'une ligne en base. `code` est la clé métier —
--  c'est lui qui voyage dans l'URL (`?categorie=retail`) et dans l'attribut
--  data-category du HTML, donc il reste court et sans accent.
-- ─────────────────────────────────────────────────────────────────────────
CREATE TABLE categories_realisation (
  code    VARCHAR(32)       NOT NULL,
  libelle VARCHAR(80)       NOT NULL,
  ordre   SMALLINT UNSIGNED NOT NULL DEFAULT 100,
  PRIMARY KEY (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ─────────────────────────────────────────────────────────────────────────
--  Realisations — les études de cas publiées
-- ─────────────────────────────────────────────────────────────────────────
--  La clé étrangère sur `categorie` interdit de publier une réalisation dans
--  un filtre qui n'existe pas : la carte serait invisible sur le site sans
--  qu'aucune erreur ne le signale.
--
--  `publiee` permet de préparer une étude de cas sans l'exposer. L'API ne rend
--  que les lignes à 1 ; le brouillon reste en base, visible du seul back-office.
-- ─────────────────────────────────────────────────────────────────────────
CREATE TABLE realisations (
  realisation_id   INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  titre            VARCHAR(160)      NOT NULL,
  categorie        VARCHAR(32)       NOT NULL,
  resume           TEXT              NOT NULL,
  -- Date réelle, pas la chaîne « Mars 2024 » : elle sert aussi au tri, et le
  -- libellé français est produit à l'affichage.
  date_publication DATE              NOT NULL,
  -- Chemin relatif au site (assets/images/…) ou URL absolue.
  image_url        VARCHAR(500)          NULL,
  image_alt        TEXT                  NULL,
  -- Fiche PDF téléchargeable. NULL tant qu'elle n'existe pas : le bouton est
  -- alors désactivé côté page plutôt que de pointer dans le vide.
  fiche_pdf        VARCHAR(500)          NULL,
  publiee          TINYINT(1)        NOT NULL DEFAULT 1,
  ordre            SMALLINT UNSIGNED NOT NULL DEFAULT 100,
  cree_le          TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modifie_le       TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (realisation_id),
  KEY idx_realisations_categorie (categorie),
  KEY idx_realisations_tri (publiee, ordre, date_publication),
  CONSTRAINT fk_realisations_categorie
    FOREIGN KEY (categorie) REFERENCES categories_realisation (code)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ─────────────────────────────────────────────────────────────────────────
--  Categories_actualite — les onglets de filtre de la page Actualités
-- ─────────────────────────────────────────────────────────────────────────
CREATE TABLE categories_actualite (
  code    VARCHAR(32)       NOT NULL,
  libelle VARCHAR(80)       NOT NULL,
  ordre   SMALLINT UNSIGNED NOT NULL DEFAULT 100,
  PRIMARY KEY (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ─────────────────────────────────────────────────────────────────────────
--  Actualites — annonces, appels d'offres, recrutements
-- ─────────────────────────────────────────────────────────────────────────
--  `contenu` et `lien_externe` décident de ce que fait « Lire plus » :
--
--    · `contenu` renseigné    → la page de détail affiche le texte complet ;
--    · `lien_externe` seul    → le bouton pointe vers l'article d'origine ;
--    · les deux à NULL        → la page de détail affiche l'annonce et invite
--                               à répondre par courriel. Pour un appel d'offres
--                               ou un recrutement, c'est l'action attendue —
--                               mieux vaut cela qu'un bouton mort.
--
--  `date_publication` est une vraie DATE : elle sert au tri, et le libellé
--  français est composé à l'affichage.
-- ─────────────────────────────────────────────────────────────────────────
CREATE TABLE actualites (
  actualite_id     INT UNSIGNED      NOT NULL AUTO_INCREMENT,
  titre            VARCHAR(200)      NOT NULL,
  categorie        VARCHAR(32)       NOT NULL,
  date_publication DATE              NOT NULL,
  resume           TEXT                  NULL,
  contenu          MEDIUMTEXT            NULL,
  lien_externe     VARCHAR(500)          NULL,
  image_url        VARCHAR(500)          NULL,
  image_alt        TEXT                  NULL,
  publiee          TINYINT(1)        NOT NULL DEFAULT 1,
  cree_le          TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
  modifie_le       TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (actualite_id),
  KEY idx_actualites_categorie (categorie),
  KEY idx_actualites_tri (publiee, date_publication),
  CONSTRAINT fk_actualites_categorie
    FOREIGN KEY (categorie) REFERENCES categories_actualite (code)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ─────────────────────────────────────────────────────────────────────────
--  Objets_contact — les entrées de la liste « Objet » du formulaire
-- ─────────────────────────────────────────────────────────────────────────
--  Les codes reprennent exactement les `value` du <select> de contact.html.
--  Le serveur refuse tout objet absent de cette table : un formulaire soumis
--  hors navigateur ne peut pas inventer une catégorie.
-- ─────────────────────────────────────────────────────────────────────────
CREATE TABLE objets_contact (
  code    VARCHAR(32)       NOT NULL,
  libelle VARCHAR(80)       NOT NULL,
  ordre   SMALLINT UNSIGNED NOT NULL DEFAULT 100,
  PRIMARY KEY (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- ─────────────────────────────────────────────────────────────────────────
--  Messages_contact — les demandes reçues par le formulaire
-- ─────────────────────────────────────────────────────────────────────────
--  Seule table du projet écrite depuis l'internet public. Trois conséquences
--  sont inscrites dans le schéma :
--
--  · les longueurs sont bornées — sans quoi un robot y déverserait des mégaoctets ;
--  · `ip` et `recu_le` portent un index commun, car c'est la requête du
--    limiteur de débit, exécutée à chaque envoi ;
--  · `statut` existe pour que l'équipe sache ce qui a déjà été traité. Un
--    formulaire dont personne ne suit les réponses ne sert à rien.
--
--  Données personnelles : nom, email et IP sont nominatifs. Prévoir une purge
--  des messages traités au-delà de la durée de conservation retenue.
-- ─────────────────────────────────────────────────────────────────────────
CREATE TABLE messages_contact (
  message_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  prenom     VARCHAR(80)  NOT NULL,
  nom        VARCHAR(80)  NOT NULL,
  entreprise VARCHAR(160)     NULL,
  -- 254 caractères : la longueur maximale d'une adresse email (RFC 5321).
  email      VARCHAR(254) NOT NULL,
  objet      VARCHAR(32)  NOT NULL,
  message    TEXT         NOT NULL,
  -- 45 caractères : une IPv6 en notation longue.
  ip         VARCHAR(45)      NULL,
  user_agent VARCHAR(255)     NULL,
  statut     ENUM('nouveau', 'lu', 'traite') NOT NULL DEFAULT 'nouveau',
  recu_le    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (message_id),
  KEY idx_messages_statut (statut, recu_le),
  KEY idx_messages_debit (ip, recu_le),
  CONSTRAINT fk_messages_objet
    FOREIGN KEY (objet) REFERENCES objets_contact (code)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
