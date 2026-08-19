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
     * Le suffixe de colonne d'une langue.
     *
     * Le français est la langue de saisie : ses colonnes n'ont pas de suffixe.
     * Toute autre langue lit `_xx`, et retombe sur le français si la case est
     * vide — d'où le `COALESCE` plus bas. `NULLIF` traite la chaîne vide comme
     * une absence : une traduction effacée dans un back-office laisserait
     * autrement un titre blanc sur le site.
     *
     * Le suffixe entre dans le SQL par concaténation, jamais par paramètre —
     * un nom de colonne ne peut pas être lié. Il est donc filtré ici sur une
     * liste fermée : rien de ce qui vient de l'URL n'atteint la requête.
     */
    private function colonne(string $expression, string $langue): string
    {
        return $langue === 'fr'
            ? $expression
            : sprintf("COALESCE(NULLIF(%s_%s, ''), %s)", $expression, $langue, $expression);
    }

    /**
     * Les réalisations publiées, la plus mise en avant d'abord.
     *
     * @param string|null $categorie Code de catégorie, ou null pour toutes.
     * @param string      $langue    Code déjà validé par le contrôleur.
     * @return list<array<string,mixed>>
     */
    public function publiees(?string $categorie = null, string $langue = 'fr'): array
    {
        $titre    = $this->colonne('r.titre', $langue);
        $resume   = $this->colonne('r.resume', $langue);
        $libelle  = $this->colonne('c.libelle', $langue);

        $sql = "SELECT realisation_id   AS id,
                       $titre           AS titre,
                       r.categorie,
                       $libelle         AS categorie_libelle,
                       $resume          AS resume,
                       date_publication,
                       image_url,
                       image_alt,
                       fiche_pdf
                  FROM realisations r
                  JOIN categories_realisation c ON c.code = r.categorie
                 WHERE r.publiee = 1";

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
    public function categories(string $langue = 'fr'): array
    {
        $libelle = $this->colonne('c.libelle', $langue);

        return $this->pdo->query(
            "SELECT c.code,
                    $libelle AS libelle,
                    COUNT(r.realisation_id) AS nombre
               FROM categories_realisation c
               LEFT JOIN realisations r
                      ON r.categorie = c.code AND r.publiee = 1
              GROUP BY c.code, c.libelle, c.libelle_en, c.ordre
              ORDER BY c.ordre, c.libelle"
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
