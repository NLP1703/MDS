<?php
declare(strict_types=1);

/**
 * Création de la base du site vitrine et chargement du portfolio.
 *
 *     C:\xampp\php\php.exe backend\scripts\migrate-site.php
 *     C:\xampp\php\php.exe backend\scripts\migrate-site.php --schema-seul
 *
 * Attention : `03_schema_site.sql` supprime et recrée les six tables. Les
 * réalisations et actualités saisies depuis sont perdues. Utilisez
 * `--donnees-seul` pour recharger les données de départ sans toucher au schéma.
 *
 * Les messages de contact, eux, sont protégés : le script refuse de rejouer le
 * schéma tant que `messages_contact` n'est pas vide (voir plus bas).
 */

use Mds\Core\Config;
use Mds\Core\Container;

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Ce script ne s'exécute qu'en ligne de commande.\n");
}

/** @var Container $container */
$container = require __DIR__ . '/../bootstrap.php';
$config = $container->get(Config::class);

$dossierSql = realpath(__DIR__ . '/../../database');
if ($dossierSql === false) {
    exit("Dossier database/ introuvable.\n");
}

$schemaSeul  = in_array('--schema-seul', $argv, true);
$donneesSeul = in_array('--donnees-seul', $argv, true);

if ($schemaSeul && $donneesSeul) {
    exit("--schema-seul et --donnees-seul s'excluent.\n");
}

$fichiers = match (true) {
    $schemaSeul  => ['03_schema_site.sql'],
    $donneesSeul => ['04_seed_site.sql'],
    default      => ['03_schema_site.sql', '04_seed_site.sql'],
};

/*
 * Connexion sans base sélectionnée : `03_schema_site.sql` la crée lui-même, et
 * exiger qu'elle existe déjà ferait échouer la toute première exécution.
 */
$c = $config->section('database_site');
$pdo = new PDO(
    sprintf('mysql:host=%s;port=%d;charset=%s', $c['host'], $c['port'], $c['charset']),
    $c['user'],
    $c['pass'],
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

/*
 * Garde-fou. `03_schema_site.sql` recrée les tables : rejoué sur une base en
 * service, il effacerait les messages reçus par le formulaire — des demandes
 * de prospects, qui n'existent nulle part ailleurs. Le portfolio, lui, est
 * reconstructible depuis le fichier de données ; ces messages ne le sont pas.
 */
if (!$donneesSeul) {
    $messages = 0;
    try {
        $pdo->exec('USE ' . $c['name']);
        $messages = (int) $pdo->query('SELECT COUNT(*) FROM messages_contact')->fetchColumn();
    } catch (PDOException) {
        // Base ou table absente : première installation, rien à protéger.
    }

    if ($messages > 0 && !in_array('--force', $argv, true)) {
        echo "\n  ⚠  $messages message(s) de contact en base.\n\n";
        echo "  Rejouer le schéma les supprimerait définitivement.\n";
        echo "  Sauvegardez-les d'abord :\n\n";
        echo "      C:\\xampp\\mysql\\bin\\mysqldump.exe -u {$c['user']} {$c['name']} messages_contact > messages.sql\n\n";
        echo "  Puis relancez avec --force pour confirmer, ou utilisez\n";
        echo "  --donnees-seul pour recharger le portfolio sans toucher au schéma.\n";
        exit(1);
    }
}

foreach ($fichiers as $nom) {
    $chemin = $dossierSql . DIRECTORY_SEPARATOR . $nom;
    if (!is_file($chemin)) {
        exit("Fichier manquant : $chemin\n");
    }

    echo "→ $nom\n";
    $instructions = decouper((string) file_get_contents($chemin));

    foreach ($instructions as $i => $sql) {
        try {
            $pdo->exec($sql);
        } catch (PDOException $e) {
            echo '  ✗ instruction ' . ($i + 1) . ' : ' . $e->getMessage() . "\n";
            echo '    ' . substr(preg_replace('/\s+/', ' ', $sql) ?? '', 0, 120) . "…\n";
            exit(1);
        }
    }
    echo '  ✓ ' . count($instructions) . " instructions\n";
}

// Compte rendu : un script de migration qui ne dit rien laisse toujours un
// doute sur ce qu'il a réellement fait.
$pdo->exec('USE ' . $c['name']);
$compter = static fn(string $table): int => (int) $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();

echo "\nBase « {$c['name']} » prête :\n";
foreach (['categories_realisation', 'realisations', 'categories_actualite', 'actualites', 'objets_contact', 'messages_contact'] as $table) {
    printf("  %-24s %d ligne(s)\n", $table, $compter($table));
}

echo "\nAPI  : http://localhost/mds-api/realisations\n";
echo "       http://localhost/mds-api/actualites\n";
echo "Site : http://localhost/mds-site/\n";

/**
 * Découpe un fichier SQL en instructions.
 *
 * Découpage naïf sur le point-virgule — un point-virgule dans une chaîne le
 * tromperait. Sans risque ici : les fichiers sont versionnés à côté, et leurs
 * seules chaînes littérales sont les textes des réalisations, qui n'en
 * contiennent pas. Le jour où un résumé en contiendra un, ce script le dira en
 * échouant bruyamment sur une instruction tronquée.
 *
 * @return list<string>
 */
function decouper(string $sql): array
{
    $sansCommentaires = preg_replace('/^\s*--.*$/m', '', $sql) ?? $sql;
    $morceaux = array_map('trim', explode(';', $sansCommentaires));
    return array_values(array_filter($morceaux, static fn(string $s): bool => $s !== ''));
}
