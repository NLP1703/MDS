<?php
declare(strict_types=1);

namespace Mds\Core;

use RuntimeException;

/**
 * Configuration. Chaque fichier de `config/` devient une section : `app.php`
 * alimente `app.*`, `database.php` alimente `database.*`.
 *
 * L'accès se fait par chemin pointé (`database.host`) avec valeur par défaut :
 * une clé oubliée ne doit pas produire une notice PHP au milieu d'une réponse
 * JSON, mais une valeur prévisible ou une erreur nette.
 */
final class Config
{
    private function __construct(private array $valeurs)
    {
    }

    public static function depuisDossier(string $dossier): self
    {
        if (!is_dir($dossier)) {
            throw new RuntimeException("Dossier de configuration introuvable : $dossier");
        }

        $valeurs = [];
        foreach (glob($dossier . '/*.php') ?: [] as $fichier) {
            $section = basename($fichier, '.php');
            $contenu = require $fichier;
            $valeurs[$section] = is_array($contenu) ? $contenu : [];
        }

        return new self($valeurs);
    }

    public function get(string $chemin, mixed $defaut = null): mixed
    {
        $courant = $this->valeurs;
        foreach (explode('.', $chemin) as $cle) {
            if (!is_array($courant) || !array_key_exists($cle, $courant)) {
                return $defaut;
            }
            $courant = $courant[$cle];
        }
        return $courant;
    }

    /** @return array<string,mixed> */
    public function section(string $nom): array
    {
        $v = $this->get($nom, []);
        return is_array($v) ? $v : [];
    }
}
