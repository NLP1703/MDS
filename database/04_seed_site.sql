-- ═══════════════════════════════════════════════════════════════════════════
--  MDS — Site vitrine · jeu de données initial
-- ═══════════════════════════════════════════════════════════════════════════
--
--  Reprend à l'identique les quatre réalisations qui figuraient en dur dans
--  `site/realisations.html`, pour que le passage au rendu dynamique ne change
--  rien à ce qui s'affiche. Le contenu part du HTML et arrive en base ; la page
--  reste la même.
--
--  Les URL d'images sont celles de la maquette : temporaires, à remplacer par
--  des fichiers de `site/assets/images/`.
-- ═══════════════════════════════════════════════════════════════════════════

USE mds_site;

-- Idempotent : rejouer ce fichier remet les données de départ sans créer de
-- doublon, et sans toucher aux réalisations ajoutées depuis.
-- Catégories d'actualités.
INSERT INTO categories_actualite (code, libelle, ordre) VALUES
  ('carriere', 'Carrière',        10),
  ('appel',    "Appel d'offres",  20),
  ('actu',     'Actualités',      30),
  ('certif',   'Certifications',  40)
ON DUPLICATE KEY UPDATE
  libelle = VALUES(libelle),
  ordre   = VALUES(ordre);


-- Les annonces publiées. `contenu` est laissé vide : seuls les intitulés ont
-- été fournis. La page de détail affiche alors l'annonce et invite à répondre
-- par courriel — l'action attendue pour un recrutement ou un appel d'offres.
INSERT INTO actualites
  (actualite_id, titre, categorie, date_publication, resume, contenu, lien_externe, publiee)
VALUES
  (1, 'Recrutement : Community Manager (H/F)', 'carriere', '2026-07-23',
   'MDS recherche un ou une Community Manager pour animer sa présence en ligne et valoriser ses publications.',
   NULL, NULL, 1),
  (2, 'Recrutement : Community Manager (H/F)', 'carriere', '2026-06-12',
   'MDS recherche un ou une Community Manager pour animer sa présence en ligne et valoriser ses publications.',
   NULL, NULL, 1),
  (3, "Appel d'offres : Sauvegarde, protection des données & contrôle d'accès réseau", 'appel', '2026-04-08',
   "Consultation pour la mise en place d'une solution de sauvegarde, de protection des données et de contrôle d'accès au réseau.",
   NULL, NULL, 1),
  (4, "Appel d'offres : Formation, gestion et mise à jour de site internet", 'appel', '2026-04-08',
   "Consultation pour la formation des équipes à la gestion et à la mise à jour du site internet de MDS.",
   NULL, NULL, 1),
  (5, "Appel d'offres : Maintenance énergie solaire", 'appel', '2026-04-03',
   "Consultation pour la maintenance de l'installation solaire des locaux de MDS à Douala.",
   NULL, NULL, 1)
ON DUPLICATE KEY UPDATE
  titre            = VALUES(titre),
  categorie        = VALUES(categorie),
  date_publication = VALUES(date_publication),
  resume           = VALUES(resume),
  publiee          = VALUES(publiee);


-- ═══════════════════════════════════════════════════════════════════════════
--  Corps des annonces, repris de mds-cmr.com
-- ═══════════════════════════════════════════════════════════════════════════
--  Conventions lues par `mds_rendre_contenu()` dans site/partials/config.php :
--    « ## » intertitre · « - » puce · « **…** » gras · ligne vide = paragraphe
--
--  `lien_externe` pointe sur l'annonce d'origine : le bouton « Consulter
--  l'annonce complète » y renvoie, ce qui garde une source faisant foi.
-- ═══════════════════════════════════════════════════════════════════════════

INSERT INTO actualites (actualite_id, resume, contenu, lien_externe) VALUES
  (1, 'MDS recherche un ou une Community Manager pour développer la visibilité numérique du cabinet, animer les communautés en ligne et contribuer à la stratégie de communication.',
   'Référence : **CM-R1-PAC-2026** — MDS, Marketing & Distribution Services, Douala (Maképè – St Tropez).

## Objet
Sélection d''un candidat au poste de Community Manager pour développer la visibilité numérique du cabinet, animer les communautés en ligne et contribuer à la stratégie de communication.

## Missions et prestations attendues
**Gestion de l''image et de la présence digitale**
- Assurer la visibilité et la crédibilité sur les réseaux sociaux et plateformes digitales
- Veiller à la cohérence de l''image de marque et du ton de communication
- Diffuser des contenus attractifs et professionnels : résultats d''études, tendances marché, analyses consommateurs

**Animation et développement de la communauté**
- Interagir régulièrement avec abonnés, clients, prospects et partenaires
- Répondre avec réactivité aux commentaires, messages et sollicitations
- Développer l''engagement et fidéliser la communauté en ligne

**Valorisation des études et communication stratégique**
- Mettre en valeur les analyses et études de marché
- Proposer des initiatives innovantes pour accroître la notoriété

## Profil recherché
**Compétences techniques**
- Maîtrise des réseaux sociaux professionnels, notamment LinkedIn
- Maîtrise d''outils de création et de planification de contenu : Canva, outils de programmation
- Connaissance des tendances digitales actuelles
- Maîtrise de l''anglais technique, lu et écrit

**Qualités personnelles**
- Créativité et sens de l''innovation
- Forte réactivité et excellentes capacités de communication
- Capacité à vulgariser des données complexes

## Dossier de candidature
- Curriculum vitae à jour
- Lettre de motivation précisant la référence CM-R1-PAC-2026, adressée au Pilote S1
- Photocopie de la carte nationale d''identité
- Photocopie du diplôme le plus élevé
- Plan de localisation du domicile

## Conditions et modalités de soumission
**Date limite de candidature : 21 août 2026.**

- **Par voie électronique :** recruit@mds-cmr.com, en indiquant CM-R1-PAC-2026 en objet
- **Par dépôt physique :** MDS – Douala, Résidence City Nikita, Maképè – St Tropez, 1er étage, 2e porte à droite',
   'https://mds-cmr.com/redesignFolder/index.php/2026/07/23/recrutement-community-manager-h-f-2/'),
  (2, 'MDS recherche un ou une Community Manager pour développer la visibilité digitale du cabinet et valoriser les études produites.',
   'Référence : **CM-R1-PAC-2026** — MDS, Marketing & Distribution Services, Douala (Maképè – St Tropez).

## Mission principale
Développer la visibilité digitale du cabinet, animer les communautés en ligne, valoriser les études et analyses produites, et veiller à la cohérence de l''image de marque et du ton de communication.

## Profil recherché
- Maîtrise des réseaux sociaux professionnels, notamment LinkedIn
- Maîtrise des outils de création (Canva) et de planification de contenu
- Bonne connaissance des tendances digitales et maîtrise de l''anglais technique, lu et écrit
- Créativité, réactivité, sens de l''innovation et capacité à vulgariser des données complexes

## Dossier de candidature
Curriculum vitae et lettre de motivation.

## Date limite
**19 juin 2026 à 15 h 00.**

## Modalités de soumission
Envoyer la candidature à recruit@mds-cmr.com, ou déposer sous pli fermé à la Direction.',
   'https://mds-cmr.com/redesignFolder/index.php/2026/06/12/recrutement-community-manager-h-f/'),
  (3, 'Consultation pour la mise en place d''une solution complète de sauvegarde, protection, restauration des données et contrôle d''accès au réseau informatique.',
   'MDS, Marketing & Distribution Services — Douala (Maképè – St Tropez), Cameroun.

## Objet
Sélection d''un prestataire pour la mise en place d''une solution complète de sauvegarde, de protection et de restauration des données, ainsi que de contrôle d''accès au réseau informatique.

## Prestations attendues
**Sauvegarde et protection**
- Sauvegardes automatiques planifiées et politique de rétention
- Chiffrement des données au repos et en transit
- Gestion des droits d''accès
- Prévention contre la perte et l''altération des données

**Restauration**
- Procédures de restauration rapides
- Plan de reprise d''activité (PRA)
- Tests réguliers

**Contrôle d''accès réseau**
- Gestion des identités et des accès (IAM)
- Authentification forte
- Segmentation réseau et gestion des droits par profil
- Journalisation des accès

## Conditions principales
- Contrat initial d''un an, renouvelable
- Délai d''intervention en cas d''incident à préciser
- Mise en œuvre selon le calendrier du prestataire

## Dossier de soumission
- Présentation de l''entreprise
- Proposition technique
- Planning de déploiement
- Offre financière

## Date limite et modalités
**Date limite de soumission : 24 avril 2026.**

Adresser l''offre à recruit@mds-cmr.com, en incluant la proposition technique, l''offre financière, les références et les délais d''intervention.',
   'https://mds-cmr.com/redesignFolder/index.php/2026/04/08/appel-offres-sauvegarde-protection-des-donnees-controle-dacces-reseau/'),
  (4, 'Consultation pour le recrutement d''un formateur chargé d''une formation pratique en gestion et mise à jour du site internet de MDS.',
   'MDS, Marketing & Distribution Services — Douala (Maképè – St Tropez).

## Objet
Recrutement d''un formateur pour assurer une formation pratique en gestion et mise à jour du site internet de l''entreprise.

## Prestations attendues
- Formation intensive de deux jours
- Prise en main de l''interface d''administration du site
- Publication et mise à jour de contenus : textes, images, vidéos
- Introduction aux bases du référencement naturel (SEO)
- Bonnes pratiques de sécurité et de maintenance du site

## Public cible
Trois à cinq participants de l''équipe marketing.

## Livrables attendus
- Supports de formation : PDF et tutoriels vidéo
- Attestations de participation
- Guide pratique pour la gestion du site

## Critères de sélection
- Expérience en formation web
- Références clients
- Méthodologie pédagogique
- Coût et conditions financières

## Calendrier
**Date limite de soumission : 24 avril 2026.** Formation prévue en mai 2026.

## Modalités de soumission
Adresser l''offre à recruit@mds-cmr.com, avec la proposition technique, l''offre financière, les références et la méthodologie.',
   'https://mds-cmr.com/redesignFolder/index.php/2026/04/08/appel-doffres-formation-gestion-et-mise-a-jour-de-site-internet/'),
  (5, 'Consultation pour l''entretien et la maintenance des installations d''énergie solaire des locaux de MDS à Douala.',
   'MDS, Marketing & Distribution Services — Douala (Maképè – St Tropez).

## Objet
Sélection d''un prestataire pour l''entretien et la maintenance des installations d''énergie solaire.

## Prestations attendues
- Nettoyage et inspection des panneaux solaires
- Vérification et réparation des systèmes électriques
- Contrôle de la production d''énergie

## Conditions principales
- Contrat de maintenance annuel
- Intervention sous 48 heures
- Garantie sur pièces et main-d''œuvre

## Critères de sélection
- Rapport qualité/prix
- Délais d''intervention
- Conditions de réalisation et de paiement
- Références et expérience

## Date limite et modalités
**Date limite de soumission : 16 avril 2026.**

Adresser l''offre à recruit@mds-cmr.com, en incluant les prix, les conditions, les références et les délais d''intervention.',
   'https://mds-cmr.com/redesignFolder/index.php/2026/04/03/appel-offres-energie-solaire/')
ON DUPLICATE KEY UPDATE
  resume       = VALUES(resume),
  contenu      = VALUES(contenu),
  lien_externe = VALUES(lien_externe);

-- Les objets du formulaire de contact. Les codes doivent rester alignés sur
-- les `value` du <select> de `site/contact.html` : le serveur rejette tout
-- objet qui ne figure pas ici.
INSERT INTO objets_contact (code, libelle, ordre) VALUES
  ('research',   'Étude de marché',    10),
  ('data',       'Analyse de données', 20),
  ('consulting', 'Conseil stratégique', 30),
  ('other',      'Autre demande',      40)
ON DUPLICATE KEY UPDATE
  libelle = VALUES(libelle),
  ordre   = VALUES(ordre);


INSERT INTO categories_realisation (code, libelle, ordre) VALUES
  ('retail',        'Retail Audit',      10),
  ('market',        'Études de marché',  20),
  ('training',      'Formation',         30),
  ('certification', 'Certification',     40)
ON DUPLICATE KEY UPDATE
  libelle = VALUES(libelle),
  ordre   = VALUES(ordre);


INSERT INTO realisations
  (realisation_id, titre, categorie, resume, date_publication, image_url, image_alt, fiche_pdf, publiee, ordre)
VALUES
  (
    1,
    'Audit Retail National - Boissons',
    'retail',
    'Analyse exhaustive de la distribution numérique et physique des boissons gazeuses sur l''ensemble du territoire national pour un leader de l''industrie.',
    '2024-03-01',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuD5oLbihgeiu7dHMlsMp3t5-AZXcmhaK2mVIe-T0sYO3Xu3958yCiv8NEHcTNHLNmFkB3yKnnlbWYprL92Kgs4oZ-alLCyJvOOXy5DqRfMZ6duNF-WqlYkEOKnvZFNPa9TUsXO0544lPH2is2ufVZgK9Bes3G8aPlSvN3LYMD5fuK25VP0VfJFgqRpacqNgaRIwMTLAW-nEinxOjyknnYN3_niiB4JKOLBUhcIZ2lqPILrbZs55YR91',
    'A modern supermarket aisle viewed from a high angle, well-lit with fluorescent lights, showing meticulously organized shelves stacked with various colorful beverage bottles. A person holding a digital tablet is inspecting the products, representing retail auditing and data collection in a professional corporate context. The lighting is bright and clean, emphasizing a precise, analytical market research environment.',
    NULL,
    1,
    10
  ),
  (
    2,
    'Étude d''Usage et Attitude - Télécoms',
    'market',
    'Compréhension approfondie des habitudes de consommation de data mobile et identification des leviers de fidélisation pour un opérateur majeur.',
    '2024-01-01',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuDcHhq-8GidnktKbFRCdvAgSOTG1sz-UctpAoACBW_BxYLD1C5Db4lLIVmstUBiaxI1Tr-UVfbgXjSOocbU4XOFvR6Ta8txGurvLC93sfgIzch0b-KMEf_Do2UW34NPam0BbV4sH2nvC2YX3mWXaJIuqvSx79qlwAeoeI8B1gHRDVHAobX72mCLDWO9QjQD2R4HktC9u6_bFOOO_IEX2Tu7nxJDNC0YfWlqFp2otydJhxR0nAFnvDAj',
    'A diverse group of professionals sitting around a modern, bright conference table looking at charts and graphs on digital tablets and a large screen. The environment is a sleek corporate office with large glass windows and soft natural light. The color palette leans towards professional blues and crisp whites, conveying a sense of strategic planning and market analysis.',
    NULL,
    1,
    20
  ),
  (
    3,
    'Formation Force de Vente - FMCG',
    'training',
    'Programme intensif de renforcement des capacités pour 150 commerciaux, axé sur les techniques de négociation et la collecte de données terrain.',
    '2023-12-01',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuAx0jQw7wFuEVikPwhNbV09ew85WVncfn_wmWB75UcmqzLpl6xBIyFSEzl3NmFf7TwU_1OR9aWidJx1dmsmhJ_H2L0mgxPcxL-s6zZsQ8sfBy47u0NfclpbOXIIgDd85cBcLsi_4bErbq2GDU3ZkO-ctiaTkTKKULBz0EDY3oCwgE86i9xhlp-A1vPsDnAkSwP2RHYyLllBFc_-fIOFuDCx-L3JId_attGzS5gL7rCNWHb5wqISj3OT',
    'A dynamic corporate training session in a well-lit, contemporary boardroom. A confident presenter stands before a digital whiteboard displaying sales metrics, engaging with a seated audience of attentive professionals. The atmosphere is energetic and educational, characterized by clean lines, modern furniture, and an airy, professional light-mode aesthetic.',
    NULL,
    1,
    30
  ),
  (
    4,
    'Accompagnement Certification ISO',
    'certification',
    'Audit à blanc et mise en conformité des processus internes d''une entreprise industrielle locale pour l''obtention de la norme ISO 9001.',
    '2023-10-01',
    'https://lh3.googleusercontent.com/aida-public/AB6AXuAzc93BLKLQiHjsgIiKx-ajYK8Xnzk6xYCafqHvruDyejNty3Xr5TlSeq_BBDutXlox_Oe3QINrHnaIpzAL2VPVFXRHcN2bd3p6aCWWI1qWhT4HqJqc3wOdB6xT6rv6Oc1JnwM_EsK0qYaCO73mn8amQj5BGjefmMg5Zw5kx6BVoHD0MKA5gOPagVXgpvpyP1EuuJrG7oiYZqBQckggL-qMMOWtroJ7GsitNFUGvKyMNDKI6TDhGN8b',
    'A close-up of a professional document with an official gold ISO certification seal being stamped or reviewed. The setting is a minimalist, modern office desk with subtle analytical tools in the background. The lighting is crisp and bright, emphasizing trust, quality assurance, and institutional reliability inherent in corporate certification processes.',
    NULL,
    1,
    40
  )
ON DUPLICATE KEY UPDATE
  titre            = VALUES(titre),
  categorie        = VALUES(categorie),
  resume           = VALUES(resume),
  date_publication = VALUES(date_publication),
  image_url        = VALUES(image_url),
  image_alt        = VALUES(image_alt),
  publiee          = VALUES(publiee),
  ordre            = VALUES(ordre);


-- ═════════════════════════════════════════════════════════════════════════
--  Traductions anglaises
-- ═════════════════════════════════════════════════════════════════════════
--  Rassemblées ici plutôt que mêlées aux insertions : le français reste la
--  langue de saisie, l'anglais s'ajoute par-dessus. Une colonne `_en` laissée
--  NULL fait retomber l'API sur le français — voir `colonne()` dans les
--  modèles. Rien ne s'affiche jamais vide.
--
--  Attention : `migrate-site.php` découpe ce fichier sur le point-virgule.
--  Aucun texte ci-dessous n'en contient, et il ne faut pas en introduire.
-- ═════════════════════════════════════════════════════════════════════════

UPDATE categories_realisation SET libelle_en = 'Retail Audit'    WHERE code = 'retail';
UPDATE categories_realisation SET libelle_en = 'Market Research' WHERE code = 'market';
UPDATE categories_realisation SET libelle_en = 'Training'        WHERE code = 'training';
UPDATE categories_realisation SET libelle_en = 'Certification'   WHERE code = 'certification';

UPDATE categories_actualite SET libelle_en = 'News'           WHERE code = 'actu';
UPDATE categories_actualite SET libelle_en = 'Tenders'        WHERE code = 'appel';
UPDATE categories_actualite SET libelle_en = 'Careers'        WHERE code = 'carriere';
UPDATE categories_actualite SET libelle_en = 'Certifications' WHERE code = 'certif';

UPDATE realisations SET
  titre_en  = 'National Retail Audit - Soft Drinks',
  resume_en = 'Comprehensive analysis of the numeric and physical distribution of carbonated soft drinks across the whole country, for an industry leader.'
WHERE realisation_id = 1;

UPDATE realisations SET
  titre_en  = 'Usage and Attitude Study - Telecoms',
  resume_en = 'In-depth understanding of mobile data consumption habits and identification of loyalty drivers for a major operator.'
WHERE realisation_id = 2;

UPDATE realisations SET
  titre_en  = 'Sales Force Training - FMCG',
  resume_en = 'Intensive capacity-building programme for 150 sales representatives, focused on negotiation techniques and field data collection.'
WHERE realisation_id = 3;

UPDATE realisations SET
  titre_en  = 'ISO Certification Support',
  resume_en = 'Mock audit and alignment of the internal processes of a local industrial company, to obtain the ISO 9001 standard.'
WHERE realisation_id = 4;

UPDATE actualites SET
  titre_en  = 'Vacancy: Community Manager (M/F)',
  resume_en = 'MDS is looking for a Community Manager to grow the firm''s digital visibility, engage its online communities and contribute to its communication strategy.',
  contenu_en = 'Reference: **CM-R1-PAC-2026** — MDS, Marketing & Distribution Services, Douala (Maképè – St Tropez).

## Purpose
Selection of a candidate for the position of Community Manager, to grow the digital visibility of the firm, engage its online communities and contribute to its communication strategy.

## Duties and expected work
**Managing brand image and digital presence**
- Maintain visibility and credibility across social networks and digital platforms
- Keep the brand image and tone of communication consistent
- Publish attractive, professional content: study results, market trends, consumer analysis

**Community engagement and growth**
- Interact regularly with followers, clients, prospects and partners
- Respond promptly to comments, messages and requests
- Build engagement and retain the online community

**Promoting research and strategic communication**
- Showcase the analyses and market studies produced by the firm
- Propose innovative initiatives to raise awareness

## Candidate profile
**Technical skills**
- Command of professional social networks, LinkedIn in particular
- Command of content creation and scheduling tools: Canva, scheduling software
- Knowledge of current digital trends
- Command of technical English, read and written

**Personal qualities**
- Creativity and a sense of innovation
- Strong responsiveness and excellent communication skills
- Ability to make complex data easy to grasp

## Application file
- Up-to-date curriculum vitae
- Cover letter quoting reference CM-R1-PAC-2026, addressed to the S1 Lead
- Photocopy of the national identity card
- Photocopy of the highest diploma obtained
- Map showing the location of the applicant''s home

## Terms and how to apply
**Application deadline: 21 August 2026.**

- **By email:** recruit@mds-cmr.com, quoting CM-R1-PAC-2026 in the subject line
- **In person:** MDS – Douala, Résidence City Nikita, Maképè – St Tropez, 1st floor, 2nd door on the right'
WHERE actualite_id = 1;

UPDATE actualites SET
  titre_en  = 'Vacancy: Community Manager (M/F)',
  resume_en = 'MDS is looking for a Community Manager to grow the firm''s digital visibility and showcase the studies it produces.',
  contenu_en = 'Reference: **CM-R1-PAC-2026** — MDS, Marketing & Distribution Services, Douala (Maképè – St Tropez).

## Main assignment
Grow the digital visibility of the firm, engage its online communities, showcase the studies and analyses produced, and keep the brand image and tone of communication consistent.

## Candidate profile
- Command of professional social networks, LinkedIn in particular
- Command of creation tools (Canva) and content scheduling
- Good knowledge of digital trends and command of technical English, read and written
- Creativity, responsiveness, a sense of innovation and the ability to make complex data easy to grasp

## Application file
Curriculum vitae and cover letter.

## Deadline
**19 June 2026 at 3:00 pm.**

## How to apply
Send the application to recruit@mds-cmr.com, or deliver it in a sealed envelope to the Management office.'
WHERE actualite_id = 2;

UPDATE actualites SET
  titre_en  = 'Tender: Data backup, protection & network access control',
  resume_en = 'Call for bids for a complete solution covering data backup, protection and recovery, together with control of access to the computer network.',
  contenu_en = 'MDS, Marketing & Distribution Services — Douala (Maképè – St Tropez), Cameroon.

## Purpose
Selection of a supplier to put in place a complete solution for backing up, protecting and restoring data, together with control of access to the computer network.

## Expected work
**Backup and protection**
- Scheduled automatic backups and a retention policy
- Encryption of data at rest and in transit
- Management of access rights
- Prevention of data loss and corruption

**Restoration**
- Fast restoration procedures
- Business continuity plan
- Regular testing

**Network access control**
- Identity and access management (IAM)
- Strong authentication
- Network segmentation and rights management by profile
- Access logging

## Main terms
- Initial one-year contract, renewable
- Response time in the event of an incident to be stated
- Roll-out according to the supplier schedule

## Bid file
- Presentation of the company
- Technical proposal
- Deployment schedule
- Financial offer

## Deadline and how to bid
**Submission deadline: 24 April 2026.**

Send the offer to recruit@mds-cmr.com, including the technical proposal, the financial offer, references and response times.'
WHERE actualite_id = 3;

UPDATE actualites SET
  titre_en  = 'Tender: Website training, management and updating',
  resume_en = 'Call for bids to appoint a trainer to deliver hands-on training in managing and updating the MDS website.',
  contenu_en = 'MDS, Marketing & Distribution Services — Douala (Maképè – St Tropez).

## Purpose
Appointment of a trainer to deliver hands-on training in managing and updating the company website.

## Expected work
- Intensive two-day training
- Getting to grips with the site administration interface
- Publishing and updating content: text, images, video
- Introduction to the basics of search engine optimisation (SEO)
- Good practice in site security and maintenance

## Audience
Three to five participants from the marketing team.

## Expected deliverables
- Training material: PDF and video tutorials
- Certificates of attendance
- Practical guide to managing the site

## Selection criteria
- Experience in web training
- Client references
- Teaching method
- Cost and payment terms

## Schedule
**Submission deadline: 24 April 2026.** Training planned for May 2026.

## How to bid
Send the offer to recruit@mds-cmr.com, with the technical proposal, the financial offer, references and the teaching method.'
WHERE actualite_id = 4;

UPDATE actualites SET
  titre_en  = 'Tender: Solar energy maintenance',
  resume_en = 'Call for bids for the upkeep and maintenance of the solar energy installations at the MDS premises in Douala.',
  contenu_en = 'MDS, Marketing & Distribution Services — Douala (Maképè – St Tropez).

## Purpose
Selection of a supplier for the upkeep and maintenance of the solar energy installations.

## Expected work
- Cleaning and inspection of the solar panels
- Checking and repair of the electrical systems
- Monitoring of energy output

## Main terms
- Annual maintenance contract
- Response within 48 hours
- Warranty on parts and labour

## Selection criteria
- Value for money
- Response times
- Terms of delivery and payment
- References and experience

## Deadline and how to bid
**Submission deadline: 16 April 2026.**

Send the offer to recruit@mds-cmr.com, including prices, terms, references and response times.'
WHERE actualite_id = 5;
