<?php
declare(strict_types=1);

namespace Mds\Core;

use RuntimeException;

/**
 * Conteneur minimal : un registre de fabriques, résolues une fois et
 * mémorisées.
 *
 * Il n'auto-résout rien par réflexion — le câblage est écrit à la main dans
 * `bootstrap.php`, où il se lit d'un coup d'œil. Pour une API de six routes,
 * un conteneur qui devine les dépendances coûterait plus à déboguer qu'à
 * écrire.
 */
final class Container
{
    /** @var array<string, callable(Container): mixed> */
    private array $fabriques = [];

    /** @var array<string, mixed> */
    private array $instances = [];

    public function partage(string $cle, callable $fabrique): void
    {
        $this->fabriques[$cle] = $fabrique;
    }

    public function get(string $cle): mixed
    {
        if (array_key_exists($cle, $this->instances)) {
            return $this->instances[$cle];
        }
        if (!isset($this->fabriques[$cle])) {
            throw new RuntimeException("Service non enregistré : $cle");
        }
        return $this->instances[$cle] = ($this->fabriques[$cle])($this);
    }
}
