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
     * Le suffixe de colonne d'une langue.
     *
     * Le français est la langue de saisie, sans suffixe. Toute autre langue lit
     * `_xx` et retombe sur le français quand la case est vide : une annonce
     * non encore traduite reste lisible plutôt que de s'afficher vide.
     *
     * Le suffixe est concaténé au SQL — un nom de colonne ne peut pas être lié
     * comme un paramètre. Il vient d'une liste fermée validée par le
     * contrôleur, jamais directement de l'URL.
     */
    private function colonne(string $expression, string $langue): string
    {
        return $langue === 'fr'
            ? $expression
            : sprintf("COALESCE(NULLIF(%s_%s, ''), %s)", $expression, $langue, $expression);
    }

    /**
     * Les annonces publiées, la plus récente d'abord.
     *
     * @param string|null $categorie Code de catégorie, ou null pour toutes.
     * @param string      $langue    Code déjà validé par le contrôleur.
     * @return list<array<string,mixed>>
     */
    public function publiees(?string $categorie = null, string $langue = 'fr'): array
    {
        $titre   = $this->colonne('a.titre', $langue);
        $resume  = $this->colonne('a.resume', $langue);
        $libelle = $this->colonne('c.libelle', $langue);
        // La présence du corps suit le même repli : en anglais sans traduction,
        // c'est le texte français qui s'affichera, donc « Lire plus » a bien
        // une destination.
        $contenu = $this->colonne('a.contenu', $langue);

        $sql = "SELECT a.actualite_id AS id,
                       $titre         AS titre,
                       a.categorie,
                       $libelle       AS categorie_libelle,
                       a.date_publication,
                       $resume        AS resume,
                       a.lien_externe,
                       a.image_url,
                       a.image_alt,
                       -- Le corps n'est pas ramené dans la liste : inutile de
                       -- transporter des articles entiers pour n'afficher que
                       -- des titres. Seule sa présence intéresse l'appelant,
                       -- pour savoir si « Lire plus » a une destination.
                       ($contenu IS NOT NULL AND $contenu <> '') AS a_contenu
                  FROM actualites a
                  JOIN categories_actualite c ON c.code = a.categorie
                 WHERE a.publiee = 1";

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
    public function parId(int $id, string $langue = 'fr'): ?array
    {
        $titre   = $this->colonne('a.titre', $langue);
        $resume  = $this->colonne('a.resume', $langue);
        $contenu = $this->colonne('a.contenu', $langue);
        $libelle = $this->colonne('c.libelle', $langue);

        $requete = $this->pdo->prepare(
            "SELECT a.actualite_id AS id,
                    $titre         AS titre,
                    a.categorie,
                    $libelle       AS categorie_libelle,
                    a.date_publication,
                    $resume        AS resume,
                    $contenu       AS contenu,
                    a.lien_externe,
                    a.image_url,
                    a.image_alt
               FROM actualites a
               JOIN categories_actualite c ON c.code = a.categorie
              WHERE a.actualite_id = :id
                AND a.publiee = 1"
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
    public function categories(string $langue = 'fr'): array
    {
        $libelle = $this->colonne('c.libelle', $langue);

        return $this->pdo->query(
            "SELECT c.code,
                    $libelle AS libelle,
                    COUNT(a.actualite_id) AS nombre
               FROM categories_actualite c
               LEFT JOIN actualites a
                      ON a.categorie = c.code AND a.publiee = 1
              GROUP BY c.code, c.libelle, c.libelle_en, c.ordre
              ORDER BY c.ordre, c.libelle"
        )->fetchAll();
    }

    public function existeCategorie(string $code): bool
    {
        $requete = $this->pdo->prepare('SELECT 1 FROM categories_actualite WHERE code = :code');
        $requete->execute(['code' => $code]);

        return $requete->fetchColumn() !== false;
    }
}
