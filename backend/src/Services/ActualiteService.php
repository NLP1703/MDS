<?php
declare(strict_types=1);

namespace Mds\Services;

use Mds\Models\ActualiteModel;

/**
 * Les actualités, mises en forme pour `actualites.php` et `actualite.php`.
 *
 * Comme pour le portfolio, le libellé de date (« 23 juillet 2026 ») est composé
 * ici : la base garde une vraie DATE pour le tri, et le rendu ne dépend pas de
 * la locale du visiteur.
 */
final class ActualiteService
{
    private const MOIS = [
        1 => 'janvier', 'février', 'mars', 'avril', 'mai', 'juin',
        'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre',
    ];

    public function __construct(private ActualiteModel $actualites)
    {
    }

    /**
     * @return array{categories: list<array<string,mixed>>, actualites: list<array<string,mixed>>}
     */
    public function liste(?string $categorie = null): array
    {
        return [
            'categories' => array_map(
                static fn(array $c): array => [
                    'code'    => (string) $c['code'],
                    'libelle' => (string) $c['libelle'],
                    'nombre'  => (int) $c['nombre'],
                ],
                $this->actualites->categories()
            ),
            'actualites' => array_map($this->presenter(...), $this->actualites->publiees($categorie)),
        ];
    }

    /** @return array<string,mixed>|null */
    public function detail(int $id): ?array
    {
        $ligne = $this->actualites->parId($id);

        if ($ligne === null) {
            return null;
        }

        $sortie = $this->presenter($ligne);
        $contenu = isset($ligne['contenu']) ? trim((string) $ligne['contenu']) : '';
        $sortie['contenu'] = $contenu === '' ? null : $contenu;

        return $sortie;
    }

    public function categorieExiste(string $code): bool
    {
        return $this->actualites->existeCategorie($code);
    }

    /**
     * @param array<string,mixed> $ligne
     * @return array<string,mixed>
     */
    private function presenter(array $ligne): array
    {
        $date = (string) $ligne['date_publication'];

        return [
            'id'               => (int) $ligne['id'],
            'titre'            => (string) $ligne['titre'],
            'categorie'        => (string) $ligne['categorie'],
            'categorieLibelle' => (string) $ligne['categorie_libelle'],
            'date'             => $date,
            'dateLibelle'      => $this->dateLongue($date),
            'resume'           => isset($ligne['resume']) && $ligne['resume'] !== null
                ? (string) $ligne['resume'] : null,
            'lienExterne'      => isset($ligne['lien_externe']) && $ligne['lien_externe'] !== null
                ? (string) $ligne['lien_externe'] : null,
            'image'            => isset($ligne['image_url']) && $ligne['image_url'] !== null
                ? (string) $ligne['image_url'] : null,
            'imageAlt'         => isset($ligne['image_alt']) && $ligne['image_alt'] !== null
                ? (string) $ligne['image_alt'] : null,
            // Présent uniquement dans la liste : dit si la page de détail a un
            // texte à montrer, sans transporter ce texte.
            'aContenu'         => isset($ligne['a_contenu']) ? (bool) $ligne['a_contenu'] : null,
        ];
    }

    /** « 2026-07-23 » → « 23 juillet 2026 ». */
    private function dateLongue(string $date): string
    {
        [$annee, $mois, $jour] = array_pad(explode('-', $date), 3, '');
        $numero = (int) $mois;

        if (!isset(self::MOIS[$numero])) {
            return $date;
        }

        // « 1er avril », et non « 1 avril ».
        $j = (int) $jour;
        $quantieme = $j === 1 ? '1er' : (string) $j;

        return $quantieme . ' ' . self::MOIS[$numero] . ' ' . $annee;
    }
}
