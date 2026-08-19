<?php
declare(strict_types=1);

namespace Mds\Services;

use Mds\Core\Config;
use Mds\Core\Mailer;
use Throwable;

/**
 * Prévient l'entreprise qu'un message vient d'arriver.
 *
 * ─── Le point important : cette classe n'échoue jamais ───
 *
 * Le message du visiteur est déjà enregistré en base quand `prevenir()` est
 * appelée. Si le serveur SMTP est injoignable, laisser remonter l'erreur
 * afficherait « échec de l'envoi » à quelqu'un dont la demande est pourtant
 * bien arrivée — il la retaperait, ou renoncerait. La panne est donc consignée
 * dans le journal PHP, et le visiteur voit sa confirmation.
 *
 * C'est aussi la raison pour laquelle l'écriture en base précède l'envoi, et
 * non l'inverse : la base est la source de vérité, le courriel n'est qu'une
 * alerte. Une notification perdue se rattrape en consultant `messages_contact`,
 * un message jamais enregistré est perdu pour de bon.
 */
final class NotificationContact
{
    public function __construct(private Mailer $mailer, private Config $config)
    {
    }

    /**
     * @param array{prenom:string,nom:string,entreprise:?string,email:string,objet:string,message:string,ip:?string} $message
     */
    public function prevenir(array $message, int $id): void
    {
        if (!$this->mailer->actif()) {
            return;
        }

        $destinataire = (string) $this->config->get('mail.destinataire', '');

        if ($destinataire === '') {
            error_log('[contact] Aucun destinataire configuré : notification non envoyée.');
            return;
        }

        try {
            $this->mailer->envoyer(
                $destinataire,
                $this->sujet($message),
                $this->corps($message, $id),
                $message['email'],
            );
        } catch (Throwable $e) {
            /*
             * L'identifiant est consigné : il permet de retrouver le message en
             * base et de répondre à la main, ce qu'un simple « envoi échoué »
             * ne permettrait pas.
             */
            error_log(sprintf(
                '[contact] Message #%d enregistré mais notification non envoyée : %s',
                $id,
                $e->getMessage()
            ));
        }
    }

    /**
     * Le sujet porte l'objet choisi et le nom : la boîte de MDS reçoit d'autres
     * courriels, et « Nouveau message » ne se distingue de rien.
     */
    private function sujet(array $message): string
    {
        return sprintf(
            '[Site] %s — %s %s',
            $message['objet'],
            $message['prenom'],
            $message['nom'],
        );
    }

    /**
     * Corps en texte brut.
     *
     * Rien n'est échappé ni interprété : c'est du texte, lu comme du texte par
     * un logiciel de messagerie. Le HTML aurait obligé à échapper le message du
     * visiteur, pour un gain nul dans une notification interne.
     */
    private function corps(array $message, int $id): string
    {
        $lignes = [
            'Nouveau message reçu depuis le formulaire du site.',
            '',
            'Prénom      : ' . $message['prenom'],
            'Nom         : ' . $message['nom'],
            'Entreprise  : ' . ($message['entreprise'] ?? '—'),
            'Email       : ' . $message['email'],
            'Objet       : ' . $message['objet'],
            '',
            'Message :',
            '',
            $message['message'],
            '',
            str_repeat('─', 58),
            'Référence   : #' . $id . ' dans la table messages_contact',
            'Reçu le     : ' . date('d/m/Y à H:i'),
            'IP          : ' . ($message['ip'] ?? 'inconnue'),
            '',
            'Répondre à ce courriel écrit directement au visiteur.',
        ];

        return implode("\r\n", $lignes);
    }
}
