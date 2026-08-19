<?php
declare(strict_types=1);

namespace Mds\Controllers;

use Mds\Core\Request;
use Mds\Core\Response;
use Mds\Services\RealisationService;

final class RealisationController
{
    public function __construct(private RealisationService $realisations)
    {
    }

    /**
     * GET /realisations
     * GET /realisations?categorie=retail
     *
     * Une catégorie inconnue reçoit un 404 plutôt qu'une liste vide : sans
     * cela, une faute de frappe dans l'URL ressemblerait à un portfolio qui
     * s'est vidé, et l'on chercherait le problème en base.
     */
    public function lister(Request $requete): Response
    {
        $categorie = $requete->query('categorie');

        if ($categorie !== null && !$this->realisations->categorieExiste($categorie)) {
            return Response::erreur("Catégorie inconnue : $categorie", 404);
        }

        return Response::json($this->realisations->portfolio($categorie));
    }
}
