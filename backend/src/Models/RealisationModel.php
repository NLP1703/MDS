<?php
declare(strict_types=1);

namespace Mds\Models;

use PDO;

/**
 * Tables `realisations` et `categories_realisation` — le portfolio du site.
 *
 * Lecture seule : le site publie, il n'écrit pas. Toute alimentation passe par
 * `04_seed_site.sql` ou un back-office, jamais par une requête venue du
 * navigateur d'un visiteur.
 *
 * Le filtre par catégorie est fait ici en SQL plutôt qu'en PHP après coup :
 * `?categorie=retail` doit peser sur la requête, pas trier un tableau déjà
 * ramené en entier.
 */
final class RealisationModel
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Les réalisations publiées, la plus mise en avant d'abord.
     *
     * @param string|null $categorie Code de catégorie, ou null pour toutes.
     * @return list<array<string,mixed>>
     */
    public function publiees(?string $categorie = null): array
    {
        $sql = 'SELECT realisation_id   AS id,
                       titre,
                       r.categorie,
                       c.libelle        AS categorie_libelle,
                       resume,
                       date_publication,
                       image_url,
                       image_alt,
                       fiche_pdf
                  FROM realisations r
                  JOIN categories_realisation c ON c.code = r.categorie
                 WHERE r.publiee = 1';

        $parametres = [];

        if ($categorie !== null) {
            $sql .= ' AND r.categorie = :categorie';
            $parametres['categorie'] = $categorie;
        }

        // `ordre` d'abord : il donne la main sur la mise en avant. La date ne
        // départage que les réalisations laissées au même rang.
        $sql .= ' ORDER BY r.ordre, r.date_publication DESC, r.realisation_id';

        $requete = $this->pdo->prepare($sql);
        $requete->execute($parametres);

        return $requete->fetchAll();
    }

    /**
     * Les catégories qui alimentent la barre de filtres.
     *
     * `nombre` accompagne chaque catégorie : une catégorie sans aucune
     * réalisation publiée ne mérite pas un onglet qui ne mène qu'à un écran
     * vide, et c'est à l'appelant d'en décider.
     *
     * @return list<array<string,mixed>>
     */
    public function categories(): array
    {
        return $this->pdo->query(
            'SELECT c.code,
                    c.libelle,
                    COUNT(r.realisation_id) AS nombre
               FROM categories_realisation c
               LEFT JOIN realisations r
                      ON r.categorie = c.code AND r.publiee = 1
              GROUP BY c.code, c.libelle, c.ordre
              ORDER BY c.ordre, c.libelle'
        )->fetchAll();
    }

    public function existeCategorie(string $code): bool
    {
        $requete = $this->pdo->prepare(
            'SELECT 1 FROM categories_realisation WHERE code = :code'
        );
        $requete->execute(['code' => $code]);

        return $requete->fetchColumn() !== false;
    }
}
