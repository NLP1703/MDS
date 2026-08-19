<?php
/**
 * Envoi des notifications du formulaire de contact.
 *
 * Tout vient de l'environnement : un mot de passe SMTP n'a pas sa place dans
 * un dépôt, et celui-ci est public. Aucune valeur de repli n'est fournie pour
 * les identifiants — une valeur par défaut ne ferait qu'échouer plus tard, et
 * plus discrètement.
 *
 * `MDS_SMTP_HOST` non renseigné désactive l'envoi : le message est alors
 * seulement enregistré en base, comme avant. C'est le comportement en local,
 * où XAMPP n'a pas de serveur de courrier.
 */

$hote = getenv('MDS_SMTP_HOST') ?: '';

return [
    // L'envoi ne s'active que si un serveur est déclaré.
    'actif'       => $hote !== '',

    'hote'        => $hote,
    'port'        => (int) (getenv('MDS_SMTP_PORT') ?: 587),

    /*
     * `tls` : connexion en clair puis STARTTLS — c'est le port 587, le cas
     * courant. `ssl` : chiffré d'emblée, port 465. `aucun` : en clair, réservé
     * aux tests locaux, jamais vers un serveur réel — les identifiants
     * circuleraient en clair sur le réseau.
     */
    'securite'    => getenv('MDS_SMTP_SECURITE') ?: 'tls',

    'utilisateur' => getenv('MDS_SMTP_USER') ?: '',
    'motdepasse'  => getenv('MDS_SMTP_PASS') ?: '',

    /*
     * L'expéditeur doit appartenir à un domaine que le serveur SMTP est
     * autorisé à utiliser. Mettre l'adresse du visiteur ici ferait échouer les
     * contrôles SPF et enverrait le message en indésirable : elle est placée
     * en `Reply-To`, ce qui permet de répondre d'un clic sans usurper personne.
     */
    'expediteur'     => getenv('MDS_MAIL_FROM') ?: 'site@mds-cmr.com',
    'expediteur_nom' => 'Site MDS Market Research',

    // Boîte de l'entreprise qui reçoit les demandes.
    'destinataire'   => getenv('MDS_MAIL_TO') ?: 'contact@mds-cmr.com',

    // Secondes. Court volontairement : un visiteur attend sa confirmation.
    'delai'       => (int) (getenv('MDS_SMTP_DELAI') ?: 10),
];
