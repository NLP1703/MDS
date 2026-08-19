<?php
declare(strict_types=1);

/**
 * Point d'entrée de l'API pour un hébergement sans Apache (Vercel).
 *
 * Ce fichier ne contient aucune logique : il délègue au routeur réel, qui reste
 * dans `backend/public/`. Sa seule raison d'être est son emplacement.
 *
 * ─── Pourquoi `/api/` et pas `/mds-api/` ───
 *
 * `Mds\Core\Request` retire de l'URL le dossier du script courant, lu dans
 * `SCRIPT_NAME` : c'est ce qui permet à l'API de vivre sous n'importe quel
 * alias sans réécrire une seule route. Servi depuis `api/index.php`, le
 * préfixe retiré vaut `/api`, et `/api/sante` arrive au routeur sous `/sante`.
 *
 * Router `/mds-api/*` vers `backend/public/index.php` aurait donné un préfixe
 * calculé de `/backend/public` — qui ne correspond à rien dans l'URL reçue.
 * Aucune route n'aurait été trouvée, et toute l'API aurait répondu 404.
 *
 * En local, rien de tout cela ne sert : Apache expose déjà `backend/public/`
 * sous l'alias `/mds-api`, et ce fichier n'est jamais atteint.
 */

require __DIR__ . '/../backend/public/index.php';
