<?php
declare(strict_types=1);

namespace Mds\Controllers;

use Mds\Core\Request;
use Mds\Core\Response;
use Mds\Services\ActualiteService;

final class ActualiteController
{
    public function __construct(private ActualiteService $actualites)
    {
    }

    /**
     * GET /actualites
     * GET /actualites?categorie=carriere
     *
     * Comme pour le portfolio, une catégorie inconnue reçoit un 404 plutôt
     * qu'une liste vide : sans cela, une faute de frappe dans l'URL
     * ressemblerait à une rubrique qui s'est vidée.
     */
    public function lister(Request $requete): Response
    {
        $categorie = $requete->query('categorie');

        if ($categorie !== null && !$this->actualites->categorieExiste($categorie)) {
            return Response::erreur("Catégorie inconnue : $categorie", 404);
        }

        return Response::json($this->actualites->liste($categorie, $requete->langue()));
    }

    /**
     * GET /actualite?id=3
     *
     * Le paramètre passe par la requête et non par le chemin : le routeur fait
     * une correspondance exacte, il ne sait pas extraire de segment variable.
     */
    public function detail(Request $requete): Response
    {
        $brut = $requete->query('id');

        if ($brut === null || !ctype_digit($brut)) {
            return Response::erreur('Identifiant d\'actualité manquant ou invalide.', 400);
        }

        $actualite = $this->actualites->detail((int) $brut, $requete->langue());

        if ($actualite === null) {
            return Response::erreur('Actualité introuvable.', 404);
        }

        return Response::json($actualite);
    }
}
