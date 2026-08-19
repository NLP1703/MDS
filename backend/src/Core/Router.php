<?php
declare(strict_types=1);

namespace Mds\Core;

/**
 * Routeur. Une table méthode + chemin → contrôleur, remplie par
 * `routes/api.php`.
 *
 * Il distingue « route inconnue » (404) de « mauvaise méthode » (405) : sur
 * une API qu'on explore au navigateur, recevoir 405 sur un `GET /session` dit
 * immédiatement que la route existe et attend un POST, là où un 404 enverrait
 * chercher une faute de frappe qui n'existe pas.
 */
final class Router
{
    /** @var array<string, array<string, callable|array>> chemin → méthode → cible */
    private array $routes = [];

    public function ajouter(string $methode, string $chemin, callable|array $cible): void
    {
        $this->routes['/' . trim($chemin, '/')][strtoupper($methode)] = $cible;
    }

    public function get(string $chemin, callable|array $cible): void
    {
        $this->ajouter('GET', $chemin, $cible);
    }

    public function post(string $chemin, callable|array $cible): void
    {
        $this->ajouter('POST', $chemin, $cible);
    }

    /** @return array{0:'ok',1:callable|array}|array{0:'methode',1:list<string>}|array{0:'inconnue',1:null} */
    public function resoudre(Request $requete): array
    {
        $parMethode = $this->routes[$requete->chemin] ?? null;
        if ($parMethode === null) {
            return ['inconnue', null];
        }
        $cible = $parMethode[$requete->methode] ?? null;
        if ($cible === null) {
            return ['methode', array_keys($parMethode)];
        }
        return ['ok', $cible];
    }

    public function dispatcher(Request $requete, Container $container): Response
    {
        [$etat, $donnee] = $this->resoudre($requete);

        if ($etat === 'inconnue') {
            return Response::erreur(
                "Route inconnue : {$requete->methode} {$requete->chemin}",
                404
            );
        }

        if ($etat === 'methode') {
            /** @var list<string> $donnee */
            return Response::erreur(
                "Méthode {$requete->methode} non autorisée sur {$requete->chemin}.",
                405
            )->avecEntete('Allow', implode(', ', $donnee));
        }

        // [Controleur::class, 'methode'] : le contrôleur est construit par le
        // conteneur, jamais par le routeur — il n'a pas à savoir ce qu'il faut
        // injecter.
        if (is_array($donnee)) {
            [$classe, $action] = $donnee;
            $controleur = $container->get($classe);
            return $controleur->$action($requete);
        }

        return $donnee($requete);
    }
}
