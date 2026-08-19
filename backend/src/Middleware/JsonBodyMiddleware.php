<?php
declare(strict_types=1);

namespace Mds\Middleware;

use Mds\Core\Request;
use Mds\Core\Response;

/**
 * Décodage du corps JSON.
 *
 * Un corps illisible est refusé ici, avant tout contrôleur : c'est une erreur
 * de forme, pas de métier, et le message doit le dire — « JSON invalide » est
 * une piste, « identifiant de visite absent » sur un corps tronqué serait un
 * contresens qui enverrait chercher au mauvais endroit.
 */
final class JsonBodyMiddleware implements Middleware
{
    public function traiter(Request $requete, callable $suivant): Response
    {
        if ($requete->corpsBrut === '') {
            return $suivant($requete);
        }

        $donnees = json_decode($requete->corpsBrut, true);

        if (!is_array($donnees)) {
            return Response::erreur(
                'Corps de requête illisible : JSON attendu. (' . json_last_error_msg() . ')',
                400
            );
        }

        return $suivant($requete->avecCorps($donnees));
    }
}
