/**
 * MDS — comportements partagés par les six pages.
 *
 * Trois rôles : la transition entre pages, l'apparition en cascade au
 * défilement, et l'ombre de l'en-tête.
 *
 * Comme la feuille de style, ce script ne touche qu'à `opacity` et
 * `transform` — les seules propriétés que le navigateur peut animer sans
 * recalculer la mise en page.
 */
(function () {
    'use strict';

    var mouvementReduit = window.matchMedia
        && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* Les navigateurs qui gèrent l'API View Transitions enchaînent les pages
       nativement : le repli JavaScript ci-dessous ne doit alors pas s'y
       superposer, sous peine d'un double fondu. */
    var transitionsNatives = typeof document.startViewTransition === 'function';

    /* ═══════════════════════════════════════════════════════════════════
       1 · Transition de page — repli « fade & slide »
       ═══════════════════════════════════════════════════════════════════ */

    /* Posé le plus tôt possible, avant le premier rendu : la page part
       transparente et 10 px plus bas, puis remonte. Placer cette classe dans
       le HTML la laisserait invisible si le script échouait — ici, elle n'est
       posée que si le script tourne. */
    if (!mouvementReduit && !transitionsNatives) {
        document.documentElement.classList.add('page-entrante');
    }

    function reveler() {
        var racine = document.documentElement;
        if (racine.classList.contains('page-entrante')) {
            racine.classList.add('page-prete');
            racine.classList.remove('page-entrante');
        }
    }

    window.addEventListener('pageshow', reveler);
    document.addEventListener('DOMContentLoaded', function () {
        // Deux images d'attente : le navigateur applique l'état initial, puis
        // la transition. Sans ce délai, il fusionne les deux et rien ne bouge.
        requestAnimationFrame(function () { requestAnimationFrame(reveler); });
    });

    /**
     * Sortie en fondu sur les liens internes.
     *
     * Ignoré pour : les clics à modificateur (nouvel onglet), les liens
     * externes, les ancres, les téléchargements, et les protocoles mailto/tel
     * — dans tous ces cas la page courante reste affichée, la faire disparaître
     * serait un défaut.
     */
    document.addEventListener('click', function (e) {
        if (mouvementReduit || transitionsNatives) return;
        if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

        var lien = e.target.closest && e.target.closest('a[href]');
        if (!lien) return;
        if (lien.target && lien.target !== '_self') return;
        if (lien.hasAttribute('download')) return;

        var href = lien.getAttribute('href');
        if (!href || href.charAt(0) === '#') return;
        if (/^(mailto:|tel:|https?:\/\/)/i.test(href) && lien.origin !== window.location.origin) return;

        e.preventDefault();

        /* La barre de navigation démarre avant le fondu : sur une connexion
           lente, elle est le seul signe que le clic a été pris en compte. */
        var barre = document.querySelector('.barre-navigation');
        if (barre) barre.classList.add('active');

        document.documentElement.classList.remove('page-prete');
        document.documentElement.classList.add('page-sortante');

        // Filet de sécurité : si `transitionend` ne se déclenche pas (onglet en
        // arrière-plan, transition annulée), on navigue quand même.
        var parti = false;
        var partir = function () {
            if (parti) return;
            parti = true;
            window.location.href = lien.href;
        };

        document.documentElement.addEventListener('transitionend', partir, { once: true });
        setTimeout(partir, 220);
    });

    document.addEventListener('DOMContentLoaded', function () {

        /* ═══════════════════════════════════════════════════════════════
           2 · Apparition en cascade
           ═══════════════════════════════════════════════════════════════ */

        /**
         * Le décalage est calculé ici, pas écrit dans le HTML : les éléments
         * d'un même parent s'ouvrent l'un après l'autre, quel que soit leur
         * nombre. Il est plafonné — au-delà de six cartes, attendre une seconde
         * de plus pour voir la dernière n'est plus un effet, c'est une lenteur.
         */
        /* ─── Découpage en mots ───
           Le texte reste entier dans le HTML : on ne fait qu'envelopper chaque
           mot après coup. Les moteurs de recherche voient le titre complet, et
           il s'affiche normalement si ce script ne s'exécute pas.
           `textContent` et non `innerHTML` : les caractères spéciaux du titre
           — le « & » de « Marketing & Distribution Services » — ne doivent pas
           être réinterprétés comme du balisage. */
        document.querySelectorAll('.mots-anime').forEach(function (titre) {
            if (mouvementReduit) { titre.classList.add('active'); return; }

            var mots = titre.textContent.trim().split(/\s+/);
            titre.textContent = '';

            mots.forEach(function (mot, i) {
                var span = document.createElement('span');
                span.className = 'mot';
                span.textContent = mot;
                // 110 ms entre chaque mot : la phrase se compose à la vitesse
                // où on la lirait, sans qu'on ait le temps de s'impatienter.
                span.style.setProperty('--retard', i * 110 + 'ms');
                titre.appendChild(span);
                if (i < mots.length - 1) titre.appendChild(document.createTextNode(' '));
            });
        });

        var aReveler = Array.prototype.slice.call(
            document.querySelectorAll('.reveal, .reveal-gauche, .reveal-droite, .reveal-zoom, .mots-anime')
        );

        if (mouvementReduit || !('IntersectionObserver' in window)) {
            aReveler.forEach(function (el) { el.classList.add('active'); });
        } else {
            var groupes = new Map();
            aReveler.forEach(function (el) {
                // Un délai déjà posé dans la page (classe .delay-*) fait foi.
                if (el.className.indexOf('delay-') !== -1) return;
                var parent = el.parentElement;
                var rang = groupes.get(parent) || 0;
                groupes.set(parent, rang + 1);
                if (rang > 0) {
                    // 150 ms de décalage entre voisins : assez pour lire une
                    // cascade, assez court pour que la dernière carte d'une
                    // rangée n'accuse pas un retard qui passerait pour un bug.
                    el.style.setProperty('--retard', Math.min(rang, 5) * 150 + 'ms');
                }
            });

            var observateur = new IntersectionObserver(function (entrees, obs) {
                entrees.forEach(function (entree) {
                    if (!entree.isIntersecting) return;
                    entree.target.classList.add('active');
                    obs.unobserve(entree.target);
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -60px 0px' });

            aReveler.forEach(function (el) { observateur.observe(el); });
        }

        /* ═══════════════════════════════════════════════════════════════
           3 · Effets liés au défilement
           ═══════════════════════════════════════════════════════════════

           Ombre de l'en-tête, barre de lecture, remplissage de la frise et
           parallaxe partagent **une seule** boucle. Quatre écouteurs `scroll`
           séparés liraient quatre fois les positions et provoqueraient autant
           de recalculs de mise en page par image ; ici tout est lu puis écrit
           en un seul passage. */

        var entete = document.querySelector('.entete-site');
        var barreLecture = document.querySelector('.barre-lecture');
        var frises = Array.prototype.slice.call(document.querySelectorAll('.frise'));
        var parallaxes = Array.prototype.slice.call(document.querySelectorAll('.parallaxe'));

        var actifs = entete || barreLecture || frises.length || parallaxes.length;

        if (actifs) {
            var attente = false;

            var majDefilement = function () {
                attente = false;
                var hauteurVue = window.innerHeight;
                var y = window.scrollY;

                if (entete) {
                    entete.classList.toggle('defile', y > 8);
                }

                if (barreLecture) {
                    var course = document.documentElement.scrollHeight - hauteurVue;
                    barreLecture.style.setProperty('--lu', course > 0 ? Math.min(y / course, 1) : 0);
                }

                if (!mouvementReduit) {
                    frises.forEach(function (frise) {
                        var r = frise.getBoundingClientRect();
                        /* Le trait suit le tiers haut de l'écran : il est
                           rempli jusqu'à l'étape que l'œil est en train de
                           lire, pas jusqu'au bas de la fenêtre. */
                        var repere = hauteurVue * 0.66;
                        var avance = (repere - r.top) / r.height;
                        frise.style.setProperty('--avancement', Math.max(0, Math.min(avance, 1)));
                    });

                    parallaxes.forEach(function (el) {
                        var r = el.parentElement.getBoundingClientRect();
                        if (r.bottom < 0 || r.top > hauteurVue) return;
                        // Centre du bloc par rapport au centre de l'écran,
                        // ramené à ±36 px de décalage.
                        var ecart = (r.top + r.height / 2 - hauteurVue / 2) / hauteurVue;
                        el.style.setProperty('--decalage', (ecart * 36).toFixed(1) + 'px');
                    });
                }
            };

            majDefilement();
            window.addEventListener('scroll', function () {
                if (attente) return;
                attente = true;
                requestAnimationFrame(majDefilement);
            }, { passive: true });
            window.addEventListener('resize', majDefilement, { passive: true });
        }

        /* ═══════════════════════════════════════════════════════════════
           4 · Étapes de la frise
           ═══════════════════════════════════════════════════════════════ */

        var etapes = Array.prototype.slice.call(document.querySelectorAll('.etape'));
        if (etapes.length) {
            if (mouvementReduit || !('IntersectionObserver' in window)) {
                etapes.forEach(function (e) { e.classList.add('active'); });
            } else {
                var obsEtapes = new IntersectionObserver(function (entrees, obs) {
                    entrees.forEach(function (entree) {
                        if (!entree.isIntersecting) return;
                        entree.target.classList.add('active');
                        obs.unobserve(entree.target);
                    });
                }, { threshold: 0.4, rootMargin: '0px 0px -20% 0px' });

                etapes.forEach(function (e) { obsEtapes.observe(e); });
            }
        }
    });
})();
