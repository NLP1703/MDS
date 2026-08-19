<?php
declare(strict_types=1);

namespace Mds\Services;

use Mds\Models\RealisationModel;

/**
 * Le portfolio, mis en forme pour la page `realisations.html`.
 *
 * Le service porte une seule décision de fond : le libellé de date affiché
 * (« Mars 2024 ») est produit ici, pas dans le navigateur. La base garde une
 * vraie DATE — indispensable au tri — et le site reçoit la chaîne française
 * déjà écrite, ce qui évite d'entretenir une table des mois en JavaScript et
 * garantit le même rendu quelle que soit la locale du visiteur.
 */
final class RealisationService
{
    /** Les mois en toutes lettres. `strftime` est déprécié, `IntlDateFormatter` suppose ext-intl. */
    private const MOIS = [
        1 => 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
        'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre',
    ];

    public function __construct(private RealisationModel $realisations)
    {
    }

    /**
     * @return array{categories: list<array<string,mixed>>, realisations: list<array<string,mixed>>}
     */
    public function portfolio(?string $categorie = null): array
    {
        $lignes = $this->realisations->publiees($categorie);

        return [
            'categories'   => array_map(
                static fn(array $c): array => [
                    'code'    => (string) $c['code'],
                    'libelle' => (string) $c['libelle'],
                    'nombre'  => (int) $c['nombre'],
                ],
                $this->realisations->categories()
            ),
            'realisations' => array_map($this->presenter(...), $lignes),
        ];
    }

    public function categorieExiste(string $code): bool
    {
        return $this->realisations->existeCategorie($code);
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
            'resume'           => (string) $ligne['resume'],
            'date'             => $date,
            'dateLibelle'      => $this->moisAnnee($date),
            'image'            => $ligne['image_url'] !== null ? (string) $ligne['image_url'] : null,
            'imageAlt'         => $ligne['image_alt'] !== null ? (string) $ligne['image_alt'] : null,
            'fichePdf'         => $ligne['fiche_pdf'] !== null ? (string) $ligne['fiche_pdf'] : null,
        ];
    }

    /** « 2024-03-01 » → « Mars 2024 ». Une date illisible est rendue telle quelle plutôt que masquée. */
    private function moisAnnee(string $date): string
    {
        [$annee, $mois] = array_pad(explode('-', $date), 2, '');
        $numero = (int) $mois;

        if (!isset(self::MOIS[$numero])) {
            return $date;
        }

        return self::MOIS[$numero] . ' ' . $annee;
    }
}
