<?php
declare(strict_types=1);

namespace Mds\Models;

use PDO;

/**
 * Tables `actualites` et `categories_actualite` — annonces, appels d'offres
 * et recrutements du site.
 *
 * Lecture seule, comme le portfolio : le site publie, il n'écrit pas.
 */
final class ActualiteModel
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Les annonces publiées, la plus récente d'abord.
     *
     * @param string|null $categorie Code de catégorie, ou null pour toutes.
     * @return list<array<string,mixed>>
     */
    public function publiees(?string $categorie = null): array
    {
        $sql = 'SELECT a.actualite_id AS id,
                       a.titre,
                       a.categorie,
                       c.libelle      AS categorie_libelle,
                       a.date_publication,
                       a.resume,
                       a.lien_externe,
                       a.image_url,
                       a.image_alt,
                       -- Le corps n\'est pas ramené dans la liste : inutile de
                       -- transporter des articles entiers pour n\'afficher que
                       -- des titres. Seule sa présence intéresse l\'appelant,
                       -- pour savoir si « Lire plus » a une destination.
                       (a.contenu IS NOT NULL AND a.contenu <> \'\') AS a_contenu
                  FROM actualites a
                  JOIN categories_actualite c ON c.code = a.categorie
                 WHERE a.publiee = 1';

        $parametres = [];

        if ($categorie !== null) {
            $sql .= ' AND a.categorie = :categorie';
            $parametres['categorie'] = $categorie;
        }

        $sql .= ' ORDER BY a.date_publication DESC, a.actualite_id DESC';

        $requete = $this->pdo->prepare($sql);
        $requete->execute($parametres);

        return $requete->fetchAll();
    }

    /** Une annonce complète, corps compris. */
    public function parId(int $id): ?array
    {
        $requete = $this->pdo->prepare(
            'SELECT a.actualite_id AS id,
                    a.titre,
                    a.categorie,
                    c.libelle      AS categorie_libelle,
                    a.date_publication,
                    a.resume,
                    a.contenu,
                    a.lien_externe,
                    a.image_url,
                    a.image_alt
               FROM actualites a
               JOIN categories_actualite c ON c.code = a.categorie
              WHERE a.actualite_id = :id
                AND a.publiee = 1'
        );
        $requete->execute(['id' => $id]);

        $ligne = $requete->fetch();

        return $ligne === false ? null : $ligne;
    }

    /**
     * Les catégories, avec le nombre d'annonces publiées dans chacune.
     *
     * @return list<array<string,mixed>>
     */
    public function categories(): array
    {
        return $this->pdo->query(
            'SELECT c.code,
                    c.libelle,
                    COUNT(a.actualite_id) AS nombre
               FROM categories_actualite c
               LEFT JOIN actualites a
                      ON a.categorie = c.code AND a.publiee = 1
              GROUP BY c.code, c.libelle, c.ordre
              ORDER BY c.ordre, c.libelle'
        )->fetchAll();
    }

    public function existeCategorie(string $code): bool
    {
        $requete = $this->pdo->prepare('SELECT 1 FROM categories_actualite WHERE code = :code');
        $requete->execute(['code' => $code]);

        return $requete->fetchColumn() !== false;
    }
}
