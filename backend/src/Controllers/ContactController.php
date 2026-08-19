<?php
declare(strict_types=1);

namespace Mds\Controllers;

use Mds\Core\Request;
use Mds\Core\Response;
use Mds\Services\ContactService;

final class ContactController
{
    public function __construct(private ContactService $contact)
    {
    }

    /**
     * POST /contact
     *
     * Corps : les `name` du formulaire de `site/contact.html`, tels quels —
     * firstName, lastName, company, email, subject, message, bot_field.
     *
     * Réponses : 201 message reçu · 422 champs invalides (avec le détail par
     * champ) · 429 trop d'envois depuis cette IP.
     */
    public function recevoir(Request $requete): Response
    {
        $resultat = $this->contact->recevoir(
            $requete->corps,
            $requete->ip(),
            $requete->entete('user-agent'),
        );

        return Response::json($resultat['donnees'], $resultat['statut']);
    }
}
