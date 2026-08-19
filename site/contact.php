<?php
declare(strict_types=1);

$page        = 'contact';
$titre       = 'Contactez-nous — MDS Market Research';
$description = "Discutons de vos besoins en recherche de marché. MDS Market Research, "
    . "Makepe St Tropez, Douala — du lundi au vendredi, 9 h à 17 h.";

require __DIR__ . '/partials/config.php';
?>
<!DOCTYPE html>
<html lang="<?= e(mds_langue()) ?>">
<head>
<?php require __DIR__ . '/partials/head.php'; ?>
</head>
<body class="bg-background text-on-surface font-body-md antialiased min-h-screen flex flex-col overflow-x-hidden">

<?php require __DIR__ . '/partials/header.php'; ?>

<main class="flex-grow px-gutter pb-xxl w-full max-w-container-max mx-auto" id="contenu">

<section class="text-center py-xl md:py-xxl mb-xl reveal">
<h1 class="font-display-lg text-headline-lg-mobile md:text-display-lg text-primary-container mb-md"><?= te('Contactez-nous') ?></h1>
<p class="font-body-lg text-body-lg text-on-surface-variant max-w-2xl mx-auto">
    <?= te('Nous sommes à votre écoute. Discutons de vos besoins en recherche de marché et découvrons comment nos données peuvent propulser votre stratégie.') ?>
</p>
</section>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-lg lg:gap-xl">

<!-- Coordonnées & carte -->
<div class="lg:col-span-4 flex flex-col gap-lg">
<div class="bg-surface-container-lowest p-xl rounded-xl card-elevation-1 bento-card border border-surface-variant reveal">
<h2 class="font-headline-md text-headline-md text-primary-container mb-lg flex items-center gap-sm">
<span class="material-symbols-outlined icon-fill text-secondary-container" aria-hidden="true">domain</span>
        <?= te('Nos Coordonnées') ?>
    </h2>
<ul class="flex flex-col gap-md">
<li class="flex items-start gap-md">
<span class="material-symbols-outlined text-outline mt-xs" aria-hidden="true">location_on</span>
<div>
<strong class="block font-label-md text-label-md text-on-surface"><?= te('Adresse') ?></strong>
<span class="font-body-md text-body-md text-on-surface-variant"><?= te(MDS['adresse']) ?><br/><?= te(MDS['ville']) ?></span>
</div>
</li>
<li class="flex items-start gap-md">
<span class="material-symbols-outlined text-outline mt-xs" aria-hidden="true">call</span>
<div>
<strong class="block font-label-md text-label-md text-on-surface"><?= te('Téléphone') ?></strong>
<a class="font-body-md text-body-md text-secondary hover:underline" href="<?= e(mds_tel(MDS['tel_fixe'])) ?>"><?= e(MDS['tel_fixe']) ?></a><br/>
<a class="font-body-md text-body-md text-secondary hover:underline" href="<?= e(mds_tel(MDS['tel_mobile'])) ?>"><?= e(MDS['tel_mobile']) ?></a>
</div>
</li>
<li class="flex items-start gap-md">
<span class="material-symbols-outlined text-outline mt-xs" aria-hidden="true">mail</span>
<div>
<strong class="block font-label-md text-label-md text-on-surface"><?= te('Email') ?></strong>
<a class="font-body-md text-body-md text-secondary hover:underline" href="mailto:<?= e(MDS['email']) ?>"><?= e(MDS['email']) ?></a>
</div>
</li>
<li class="flex items-start gap-md">
<span class="material-symbols-outlined text-outline mt-xs" aria-hidden="true">schedule</span>
<div>
<strong class="block font-label-md text-label-md text-on-surface"><?= te("Heures d'ouverture") ?></strong>
<span class="font-body-md text-body-md text-on-surface-variant"><?= te(MDS['horaires']) ?></span>
</div>
</li>
</ul>
</div>

<?php /* Carte réelle du quartier, assemblée depuis les tuiles OpenStreetMap et
         servie en local. L'image générée par IA qu'elle remplace montrait une
         ville imaginaire, et son URL temporaire allait expirer. */ ?>
<a class="bg-surface-container-lowest rounded-xl card-elevation-1 bento-card overflow-hidden h-[300px] border border-surface-variant relative reveal delay-200 block zoom-doux" href="https://www.openstreetmap.org/?mlat=4.0778&amp;mlon=9.7386#map=15/4.0778/9.7386" rel="noopener" target="_blank">
<img alt="<?= te('Carte de Makepe, Douala — les bureaux de MDS y sont repérés') ?>" class="w-full h-full object-cover" height="512" loading="lazy" src="assets/images/carte-douala.jpg" width="768"/>
<span class="absolute right-xs bottom-xs bg-surface/85 text-on-surface-variant font-label-sm text-[10px] px-xs py-[2px] rounded">© OpenStreetMap</span>
</a>
</div>

<!-- Formulaire -->
<div class="lg:col-span-8">
<div class="bg-surface-container-lowest p-xl rounded-xl card-elevation-1 border border-surface-variant h-full reveal delay-300">
<h2 class="font-headline-md text-headline-md text-primary-container mb-xs"><?= te('Envoyez-nous un message') ?></h2>
<p class="font-body-md text-body-md text-on-surface-variant mb-lg"><?= te('Remplissez le formulaire ci-dessous et notre équipe vous recontactera dans les plus brefs délais.') ?></p>

<form class="flex flex-col gap-md" id="contactForm" novalidate>

<?php /* Pot de miel : invisible pour l'humain, rempli par les robots.
         `aria-hidden` et `tabindex` l'écartent aussi des lecteurs d'écran. */ ?>
<div aria-hidden="true" class="hidden">
<label><?= te('Ne pas remplir ce champ') ?> <input autocomplete="off" name="bot_field" tabindex="-1" type="text"/></label>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-md">
<div class="flex flex-col gap-xs">
<label class="font-label-sm text-label-sm text-on-surface-variant" for="firstName"><?= te('Prénom') ?> <span aria-hidden="true">*</span></label>
<input autocomplete="given-name" class="bg-surface border border-outline-variant rounded-lg p-md font-body-md focus:border-primary-container outline-none transition-all" id="firstName" name="firstName" required type="text"/>
</div>
<div class="flex flex-col gap-xs">
<label class="font-label-sm text-label-sm text-on-surface-variant" for="lastName"><?= te('Nom') ?> <span aria-hidden="true">*</span></label>
<input autocomplete="family-name" class="bg-surface border border-outline-variant rounded-lg p-md font-body-md focus:border-primary-container outline-none transition-all" id="lastName" name="lastName" required type="text"/>
</div>
</div>

<div class="flex flex-col gap-xs">
<label class="font-label-sm text-label-sm text-on-surface-variant" for="company"><?= te('Entreprise') ?></label>
<input autocomplete="organization" class="bg-surface border border-outline-variant rounded-lg p-md font-body-md focus:border-primary-container outline-none transition-all" id="company" name="company" type="text"/>
</div>

<div class="flex flex-col gap-xs">
<label class="font-label-sm text-label-sm text-on-surface-variant" for="email"><?= te('Email professionnel') ?> <span aria-hidden="true">*</span></label>
<input autocomplete="email" class="bg-surface border border-outline-variant rounded-lg p-md font-body-md focus:border-primary-container outline-none transition-all" id="email" name="email" required type="email"/>
</div>

<div class="flex flex-col gap-xs">
<label class="font-label-sm text-label-sm text-on-surface-variant" for="subject"><?= te('Objet') ?> <span aria-hidden="true">*</span></label>
<select class="bg-surface border border-outline-variant rounded-lg p-md font-body-md focus:border-primary-container outline-none transition-all cursor-pointer" id="subject" name="subject" required>
<option value=""><?= te('Sélectionnez un objet') ?></option>
<option value="research"><?= te('Étude de marché') ?></option>
<option value="data"><?= te('Analyse de données') ?></option>
<option value="consulting"><?= te('Conseil stratégique') ?></option>
<option value="other"><?= te('Autre demande') ?></option>
</select>
</div>

<div class="flex flex-col gap-xs">
<label class="font-label-sm text-label-sm text-on-surface-variant" for="message"><?= te('Message') ?> <span aria-hidden="true">*</span></label>
<textarea class="bg-surface border border-outline-variant rounded-lg p-md font-body-md focus:border-primary-container outline-none transition-all resize-y" id="message" name="message" required rows="5"></textarea>
<span class="font-label-sm text-label-sm text-on-surface-variant self-end" id="compteurMessage">0 / 5000</span>
</div>

<div class="flex flex-col md:flex-row items-center justify-between mt-sm gap-md">
<div aria-live="polite" class="font-label-sm text-label-sm hidden px-md py-sm rounded-md w-full md:w-auto" id="formStatus" role="status"></div>
<button class="btn-accent w-full md:w-auto font-label-md text-label-md px-xl py-md card-elevation-1 flex items-center justify-center gap-sm" id="submitBtn" type="submit">
<span><?= te('Envoyer le message') ?></span>
<span class="material-symbols-outlined text-[18px]" aria-hidden="true">send</span>
</button>
</div>

</form>
</div>
</div>

</div>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>

<script>
    const API_BASE = <?= json_encode(MDS['api'], JSON_UNESCAPED_SLASHES) ?>;
    const EMAIL_MDS = <?= json_encode(MDS['email']) ?>;

    /* Les libellés du script sont traduits par PHP au rendu : le
       navigateur reçoit déjà la bonne langue. */
    const L = <?= json_encode([
        'requis'   => t('Ce champ est obligatoire.'),
        'depasse'  => t('Ce champ dépasse'),
        'carac'    => t('caractères.'),
        'email'    => t('Cette adresse email ne semble pas valide.'),
        'objet'    => t('Merci de sélectionner un objet.'),
        'detail'   => t('Merci de détailler votre demande'),
        'minimum'  => t('caractères minimum'),
        'invalides'=> t('Certains champs sont invalides.'),
        'echec'    => t("Le message n'a pas pu être envoyé."),
        'succes'   => t('Message envoyé avec succès. Nous vous contacterons bientôt.'),
        'injoignable' => t('Serveur injoignable. Votre message n\'a pas été envoyé — réessayez, ou écrivez à'),
        'envoi'    => t('Envoi…'),
        'envoyer'  => t('Envoyer le message'),
    ], JSON_UNESCAPED_UNICODE) ?>;

    document.addEventListener('DOMContentLoaded', () => {
        const form       = document.getElementById('contactForm');
        const submitBtn  = document.getElementById('submitBtn');
        const formStatus = document.getElementById('formStatus');
        const compteur   = document.getElementById('compteurMessage');
        const champMsg   = form.querySelector('[name="message"]');

        const CLASSES_STATUT = 'font-label-sm text-label-sm hidden px-md py-sm rounded-md w-full md:w-auto';
        const MESSAGE_MIN = 10, MESSAGE_MAX = 5000;

        /* ─── Règles de validation ───
           Elles doublent celles du serveur, qui reste l'autorité : ces
           contrôles n'existent que pour prévenir plus tôt. Un envoi direct,
           hors navigateur, les ignore — et se heurte au serveur. */
        const REGLES = {
            firstName: (v) => !v.trim() ? L.requis
                : v.trim().length > 80 ? L.depasse + ' 80 ' + L.carac : '',
            lastName:  (v) => !v.trim() ? L.requis
                : v.trim().length > 80 ? L.depasse + ' 80 ' + L.carac : '',
            company:   (v) => v.trim().length > 160 ? L.depasse + ' 160 ' + L.carac : '',
            email:     (v) => !v.trim() ? L.requis
                : !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v.trim()) ? L.email : '',
            subject:   (v) => !v ? L.objet : '',
            message:   (v) => !v.trim() ? L.requis
                : v.trim().length < MESSAGE_MIN ? L.detail + ' (' + MESSAGE_MIN + ' ' + L.minimum + ').'
                : v.trim().length > MESSAGE_MAX ? L.depasse + ' ' + MESSAGE_MAX + ' ' + L.carac : '',
        };

        function marquer(champ, message) {
            const id = 'err-' + champ.name;
            let note = document.getElementById(id);

            if (!message) {
                champ.classList.remove('champ-invalide');
                champ.removeAttribute('aria-invalid');
                champ.removeAttribute('aria-describedby');
                if (note) note.remove();
                return;
            }

            champ.classList.add('champ-invalide');
            champ.setAttribute('aria-invalid', 'true');
            champ.setAttribute('aria-describedby', id);

            if (!note) {
                note = document.createElement('p');
                note.id = id;
                note.className = 'erreur-champ';
                champ.insertAdjacentElement('afterend', note);
            }
            note.textContent = message;
        }

        /* Validation à la sortie du champ, puis à chaque frappe une fois qu'il
           est signalé fautif : on ne réprimande pas quelqu'un qui est encore en
           train de taper, mais on le libère dès qu'il a corrigé. */
        Object.keys(REGLES).forEach((nom) => {
            const champ = form.querySelector('[name="' + nom + '"]');
            if (!champ) return;

            champ.addEventListener('blur', () => marquer(champ, REGLES[nom](champ.value)));
            champ.addEventListener('input', () => {
                if (champ.getAttribute('aria-invalid')) marquer(champ, REGLES[nom](champ.value));
            });
        });

        champMsg.addEventListener('input', () => {
            const n = champMsg.value.length;
            compteur.textContent = n.toLocaleString('fr-FR') + ' / ' + MESSAGE_MAX.toLocaleString('fr-FR');
            compteur.classList.toggle('text-error', n > MESSAGE_MAX);
        });

        function afficherStatut(texte, succes) {
            formStatus.textContent = texte;
            formStatus.className = CLASSES_STATUT;
            formStatus.classList.remove('hidden');
            formStatus.classList.add('block', 'border', ...(succes
                ? ['bg-[#e8f5e9]', 'text-[#2e7d32]', 'border-[#a5d6a7]']
                : ['bg-error-container', 'text-on-error-container', 'border-error/30']));
        }

        function reinitialiserBouton() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span><?= te('Envoyer le message') ?></span>'
                + '<span class="material-symbols-outlined text-[18px]" aria-hidden="true">send</span>';
            submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            if (form.querySelector('[name="bot_field"]').value) return;

            // Contrôle complet avant tout aller-retour réseau.
            let premierFautif = null;
            Object.keys(REGLES).forEach((nom) => {
                const champ = form.querySelector('[name="' + nom + '"]');
                if (!champ) return;
                const err = REGLES[nom](champ.value);
                marquer(champ, err);
                if (err && !premierFautif) premierFautif = champ;
            });

            if (premierFautif) {
                afficherStatut(L.invalides, false);
                premierFautif.focus();
                return;
            }

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[18px]" aria-hidden="true">progress_activity</span><span>' + L.envoi + '</span>';
            submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
            formStatus.className = CLASSES_STATUT;

            const donnees = Object.fromEntries(new FormData(form).entries());

            try {
                const reponse = await fetch(API_BASE + '/contact', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
                    body: JSON.stringify(donnees),
                });

                const corps = await reponse.json().catch(() => ({}));

                if (!reponse.ok) {
                    if (corps.champs) {
                        Object.entries(corps.champs).forEach(([nom, msg]) => {
                            const champ = form.querySelector('[name="' + nom + '"]');
                            if (champ) marquer(champ, msg);
                        });
                    }
                    afficherStatut(corps.erreur || L.echec, false);
                    reinitialiserBouton();
                    return;
                }

                afficherStatut(corps.message || L.succes, true);
                form.reset();
                compteur.textContent = '0 / ' + MESSAGE_MAX.toLocaleString('fr-FR');
                reinitialiserBouton();
                setTimeout(() => formStatus.classList.add('hidden'), 6000);
            } catch (err) {
                /* Le message n'est pas parti : ne pas l'annoncer comme envoyé,
                   ne pas vider le formulaire — l'utilisateur perdrait ce qu'il
                   vient d'écrire. */
                afficherStatut(
                    L.injoignable + ' ' + EMAIL_MDS + '.',
                    false
                );
                reinitialiserBouton();
            }
        });
    });
</script>
</body>
</html>
