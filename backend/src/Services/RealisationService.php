<?php
declare(strict_types=1);

namespace Mds\Services;

use Mds\Core\Dates;
use Mds\Models\RealisationModel;

/**
 * Le portfolio, mis en forme pour la page `realisations.php`.
 *
 * Le service porte une seule décision de fond : le libellé de date affiché
 * (« Mars 2024 », « March 2024 ») est produit ici, pas dans le navigateur. La
 * base garde une vraie DATE — indispensable au tri — et le site reçoit la
 * chaîne déjà écrite, ce qui évite d'entretenir une table des mois en
 * JavaScript. Elle suit la langue demandée, non la locale du visiteur : deux
 * personnes lisant la même page voient la même date.
 */
final class RealisationService
{
    public function __construct(private RealisationModel $realisations)
    {
    }

    /**
     * @param string $langue Code déjà validé par le contrôleur.
     * @return array{categories: list<array<string,mixed>>, realisations: list<array<string,mixed>>}
     */
    public function portfolio(?string $categorie = null, string $langue = 'fr'): array
    {
        $lignes = $this->realisations->publiees($categorie, $langue);

        return [
            'categories'   => array_map(
                static fn(array $c): array => [
                    'code'    => (string) $c['code'],
                    'libelle' => (string) $c['libelle'],
                    'nombre'  => (int) $c['nombre'],
                ],
                $this->realisations->categories($langue)
            ),
            'realisations' => array_map(
                fn(array $ligne): array => $this->presenter($ligne, $langue),
                $lignes
            ),
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
    private function presenter(array $ligne, string $langue): array
    {
        $date = (string) $ligne['date_publication'];

        return [
            'id'               => (int) $ligne['id'],
            'titre'            => (string) $ligne['titre'],
            'categorie'        => (string) $ligne['categorie'],
            'categorieLibelle' => (string) $ligne['categorie_libelle'],
            'resume'           => (string) $ligne['resume'],
            'date'             => $date,
            'dateLibelle'      => Dates::moisAnnee($date, $langue),
            'image'            => $ligne['image_url'] !== null ? (string) $ligne['image_url'] : null,
            'imageAlt'         => $ligne['image_alt'] !== null ? (string) $ligne['image_alt'] : null,
            'fichePdf'         => $ligne['fiche_pdf'] !== null ? (string) $ligne['fiche_pdf'] : null,
        ];
    }
}
