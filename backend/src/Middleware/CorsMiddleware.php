<?php
declare(strict_types=1);

namespace Mds\Middleware;

use Mds\Core\Config;
use Mds\Core\Request;
use Mds\Core\Response;

/**
 * Autorisation d'origine croisée.
 *
 * Le frontend tourne sur le port 5173 en développement, l'API sur le port 80
 * d'Apache : sans ces en-têtes, le navigateur reçoit la réponse et la jette
 * sans la donner à l'application. En production, servir les deux depuis la
 * même origine rend ce middleware inutile — c'est le but.
 *
 * Le préflight `OPTIONS` est traité ici et ne va pas plus loin : il n'a aucune
 * raison de traverser le routeur ni de réveiller la base.
 */
final class CorsMiddleware implements Middleware
{
    public function __construct(private Config $config)
    {
    }

    public function traiter(Request $requete, callable $suivant): Response
    {
        $origine = $requete->entete('origin') ?? '';
        /** @var list<string> $autorisees */
        $autorisees = $this->config->get('app.cors_origines', []);

        $reponse = $requete->methode === 'OPTIONS'
            ? Response::vide(204)
            : $suivant($requete);

        if ($autorisees === ['*']) {
            $reponse = $reponse->avecEntete('Access-Control-Allow-Origin', '*');
        } elseif ($origine !== '' && in_array($origine, $autorisees, true)) {
            $reponse = $reponse
                ->avecEntete('Access-Control-Allow-Origin', $origine)
                ->avecEntete('Vary', 'Origin');
        }

        return $reponse
            ->avecEntete('Access-Control-Allow-Methods', 'GET, POST, OPTIONS')
            ->avecEntete('Access-Control-Allow-Headers', 'Content-Type')
            ->avecEntete('Access-Control-Max-Age', '86400');
    }
}
