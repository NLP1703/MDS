<?php
declare(strict_types=1);

namespace Mds\Core;

/**
 * La réponse HTTP.
 *
 * Tout ce qui sort de l'API est du JSON UTF-8, y compris les erreurs : une
 * application de terrain doit distinguer « le serveur a refusé » de « le
 * serveur n'a pas répondu », et une page d'erreur HTML d'Apache ne le permet
 * pas.
 */
final class Response
{
    private function __construct(
        public readonly int $statut,
        public readonly mixed $donnees,
        /** @var array<string,string> */
        private array $entetes = [],
        public readonly bool $vide = false,
    ) {
    }

    public static function json(mixed $donnees, int $statut = 200): self
    {
        return new self($statut, $donnees);
    }

    public static function erreur(string $message, int $statut = 400, array $extra = []): self
    {
        return new self($statut, ['erreur' => $message] + $extra);
    }

    /** Réponse sans corps — le préflight CORS, notamment. */
    public static function vide(int $statut = 204): self
    {
        return new self($statut, null, [], true);
    }

    public function avecEntete(string $nom, string $valeur): self
    {
        $copie = clone $this;
        $copie->entetes[$nom] = $valeur;
        return $copie;
    }

    public function envoyer(): void
    {
        http_response_code($this->statut);
        foreach ($this->entetes as $nom => $valeur) {
            header("$nom: $valeur");
        }
        if ($this->vide) {
            return;
        }
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');
        echo json_encode($this->donnees, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
