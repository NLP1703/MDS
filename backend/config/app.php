<?php
/**
 * Réglages de l'application.
 */

return [
    'nom' => 'MDS Retail Audit Tracker — API',

    /**
     * Origines autorisées à appeler l'API depuis un navigateur. Le serveur de
     * développement Vite tourne sur un autre port qu'Apache : sans cette
     * liste, le navigateur reçoit la réponse et la jette.
     *
     * `['*']` accepte tout — acceptable en local, à restreindre dès que l'API
     * devient joignable depuis un autre poste.
     */
    'cors_origines' => [
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        // Le site vitrine, servi par Apache sur le port 80. Ouvrir les pages
        // en double-cliquant le fichier (origine `file://`) n'appellera pas
        // l'API : le navigateur envoie alors l'origine « null », qu'il serait
        // imprudent d'autoriser. Passer par http://localhost/… pour tester.
        'http://localhost',
        'http://127.0.0.1',
    ],

    /**
     * Joint le détail technique des erreurs à la réponse JSON. Précieux en
     * local, à couper ailleurs : un message d'erreur SQL renseigne autant
     * l'équipe que l'attaquant.
     */
    'debug' => true,

    /** Fuseau du terrain — les horodatages sont écrits en base dans celui-ci. */
    'fuseau' => 'Africa/Douala',
];
