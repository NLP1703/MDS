/**
 * Menu mobile — partagé par les quatre pages.
 *
 * Sous 768 px, la barre de navigation est masquée par Tailwind (`hidden
 * md:flex`) et seul le bouton hamburger reste visible. Ce script lui donne un
 * effet : sans lui, le bouton est décoratif et le site n'offre aucune
 * navigation sur téléphone.
 *
 * Le panneau est positionné en `fixed`, sous l'en-tête, plutôt qu'inséré dans
 * son flux : les quatre pages ont des en-têtes de hauteurs et de structures
 * différentes (l'une est `sticky`, l'autre `fixed`), et un enfant de plus y
 * casserait leur mise en page. Sa position verticale est donc mesurée sur
 * l'en-tête réel, ce qui marche partout sans rien supposer.
 */
(function () {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {
        var entete = document.querySelector('header');
        var bouton = document.getElementById('menuMobile');
        var panneau = document.getElementById('navMobile');

        if (!entete || !bouton || !panneau) {
            return;
        }

        var icone = bouton.querySelector('.material-symbols-outlined');

        function positionner() {
            panneau.style.top = Math.round(entete.getBoundingClientRect().bottom) + 'px';
        }

        function basculer(ouvert) {
            panneau.classList.toggle('hidden', !ouvert);
            bouton.setAttribute('aria-expanded', String(ouvert));
            bouton.setAttribute('aria-label', ouvert ? 'Fermer le menu' : 'Ouvrir le menu');
            if (icone) {
                icone.textContent = ouvert ? 'close' : 'menu';
            }
            if (ouvert) {
                positionner();
            }
        }

        function estOuvert() {
            return !panneau.classList.contains('hidden');
        }

        bouton.addEventListener('click', function (e) {
            e.stopPropagation();
            basculer(!estOuvert());
        });

        // Un clic ailleurs referme : sur un téléphone, chercher le bouton pour
        // sortir d'un menu qu'on a ouvert par erreur est une friction inutile.
        document.addEventListener('click', function (e) {
            if (estOuvert() && !panneau.contains(e.target)) {
                basculer(false);
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && estOuvert()) {
                basculer(false);
                bouton.focus();
            }
        });

        // Passage en écran large : la barre normale réapparaît, le panneau doit
        // disparaître — sans quoi il resterait affiché par-dessus la page.
        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768) {
                basculer(false);
            } else if (estOuvert()) {
                positionner();
            }
        });

        // L'en-tête d'une page est `sticky`, celui d'une autre `fixed` : sa
        // position peut bouger au défilement, le panneau la suit.
        window.addEventListener('scroll', function () {
            if (estOuvert()) {
                positionner();
            }
        }, { passive: true });
    });
})();
