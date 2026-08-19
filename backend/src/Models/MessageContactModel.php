<?php
declare(strict_types=1);

namespace Mds\Models;

use PDO;

/**
 * Table `messages_contact` — les demandes reçues par le formulaire du site.
 *
 * Seule table du projet alimentée depuis l'internet public. Tout ce qui entre
 * ici passe par une requête préparée : le message d'un visiteur est du texte,
 * jamais du SQL.
 */
final class MessageContactModel
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @param array{prenom:string,nom:string,entreprise:?string,email:string,objet:string,message:string,ip:?string,userAgent:?string} $message
     * @return int Identifiant du message créé.
     */
    public function enregistrer(array $message): int
    {
        $requete = $this->pdo->prepare(
            'INSERT INTO messages_contact
                (prenom, nom, entreprise, email, objet, message, ip, user_agent)
             VALUES
                (:prenom, :nom, :entreprise, :email, :objet, :message, :ip, :user_agent)'
        );

        $requete->execute([
            'prenom'     => $message['prenom'],
            'nom'        => $message['nom'],
            'entreprise' => $message['entreprise'],
            'email'      => $message['email'],
            'objet'      => $message['objet'],
            'message'    => $message['message'],
            'ip'         => $message['ip'],
            'user_agent' => $message['userAgent'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }

    /** Les codes d'objet acceptés par le formulaire. @return list<string> */
    public function objetsValides(): array
    {
        return $this->pdo
            ->query('SELECT code FROM objets_contact ORDER BY ordre, code')
            ->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Nombre de messages reçus d'une IP depuis N minutes.
     *
     * Sert au limiteur de débit. S'appuie sur l'index (ip, recu_le) : la
     * requête est exécutée à chaque envoi, elle ne doit pas parcourir la table.
     */
    public function comptesRecents(string $ip, int $minutes): int
    {
        /*
         * `INTERVAL ? MINUTE` est refusé en requête préparée côté serveur, et
         * `EMULATE_PREPARES` vaut false ici. La durée est donc interpolée —
         * mais seulement après un transtypage entier, ce qui ne laisse passer
         * aucun caractère : elle ne vient de toute façon pas de la requête HTTP.
         */
        $fenetre = max(1, $minutes);

        $requete = $this->pdo->prepare(
            "SELECT COUNT(*)
               FROM messages_contact
              WHERE ip = :ip
                AND recu_le > (NOW() - INTERVAL $fenetre MINUTE)"
        );

        $requete->execute(['ip' => $ip]);

        return (int) $requete->fetchColumn();
    }
}
