<?php
declare(strict_types=1);

namespace Mds\Middleware;

use InvalidArgumentException;
use Mds\Core\Config;
use Mds\Core\Request;
use Mds\Core\Response;
use PDOException;
use Throwable;

/**
 * Capture des erreurs — le middleware le plus extérieur.
 *
 * Sans lui, une exception se traduit par une page d'erreur HTML d'Apache, que
 * le frontend ne peut pas distinguer d'une panne réseau : la visite repartirait
 * indéfiniment dans la file au lieu de signaler un problème de données.
 *
 * Trois familles, trois traitements :
 *   InvalidArgumentException — charge utile invalide (422) : rejouer telle
 *   quelle n'arrangera rien, et le message dit quoi corriger ;
 *   PDOException 1452       — référence inconnue (422) : erreur de données,
 *   pas panne de serveur ;
 *   le reste                — 500, avec le détail seulement si `app.debug`.
 */
final class ErreurMiddleware implements Middleware
{
    public function __construct(private Config $config)
    {
    }

    public function traiter(Request $requete, callable $suivant): Response
    {
        try {
            return $suivant($requete);
        } catch (InvalidArgumentException $e) {
            return Response::erreur($e->getMessage(), 422);
        } catch (PDOException $e) {
            return $this->depuisPdo($e);
        } catch (Throwable $e) {
            return Response::erreur('Erreur serveur.', 500, $this->detail($e));
        }
    }

    private function depuisPdo(PDOException $e): Response
    {
        // 1452 : contrainte de clé étrangère non satisfaite — catégorie de
        // réalisation ou objet de contact absent de son référentiel.
        if (str_contains($e->getMessage(), '1452')) {
            return Response::erreur(
                'Référence inconnue en base : vérifiez que les catégories et les objets de '
                . 'contact sont bien présents (php backend/scripts/migrate-site.php --donnees-seul).',
                422,
                $this->detail($e)
            );
        }

        return Response::erreur('Erreur de base de données.', 500, $this->detail($e));
    }

    /** @return array<string,string> */
    private function detail(Throwable $e): array
    {
        if (!$this->config->get('app.debug', false)) {
            return [];
        }
        return [
            'detail' => $e->getMessage(),
            'ou'     => $e->getFile() . ':' . $e->getLine(),
        ];
    }
}
