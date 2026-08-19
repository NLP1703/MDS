/**
 * Configuration Tailwind — source unique du design system MDS.
 *
 * Ce fichier remplace les 547 lignes de configuration qui étaient recopiées
 * dans chaque page. Une couleur se change désormais ici, et nulle part
 * ailleurs.
 *
 * Il doit être chargé APRÈS `vendor/tailwind.js` et AVANT le rendu : Tailwind
 * lit `tailwind.config` au démarrage, puis observe le DOM pour générer les
 * classes rencontrées.
 *
 * ─── Les teintes viennent du logo, pas d'un nuancier générique ───
 *
 * Échantillonnage de `logo-mds.png` : le bleu #183C84 occupe 35 % de sa
 * surface, l'orange #F0A83C en occupe 15 %, le cyan #189CD8 vient du dégradé
 * du « S ». La palette précédente utilisait un bleu plus sombre et un blanc
 * bleuté (#faf8ff) absents de l'identité.
 *
 * L'ambre est promu en **accent des appels à l'action**. Auparavant, les
 * boutons étaient bleus sur fond bleu et se fondaient dans le décor.
 *
 * Les noms de rôles Material 3 sont conservés : seules les valeurs changent,
 * ce qui évite de reprendre les classes de chaque page.
 */
tailwind.config = {
    theme: {
        extend: {
            colors: {
                // Primaire — encre profonde : titres et fonds pleins
                'primary': '#0b1f49',
                'on-primary': '#ffffff',
                'primary-container': '#183c84',
                'on-primary-container': '#b3c8f0',
                'primary-fixed': '#dde6f8',
                'primary-fixed-dim': '#a8bde4',
                'on-primary-fixed': '#08152f',
                'on-primary-fixed-variant': '#183c84',
                'inverse-primary': '#a8bde4',

                // Secondaire — le cyan du logo : liens, signatures, focus
                'secondary': '#0f7fb4',
                'on-secondary': '#ffffff',
                'secondary-container': '#189cd8',
                'on-secondary-container': '#00435f',
                'secondary-fixed': '#cbeafa',
                'secondary-fixed-dim': '#7fd0ef',
                'on-secondary-fixed': '#001e2d',
                'on-secondary-fixed-variant': '#00587c',

                // Accent — l'ambre du logo. Réservé aux appels à l'action :
                // employé partout, il cesserait d'attirer l'œil quelque part.
                'accent': '#f0a83c',
                'on-accent': '#22160a',
                'accent-dark': '#c8801a',
                'accent-soft': '#fdf1dd',

                // Tertiaire — conservé pour les badges de catégorie existants
                'tertiary': '#5c3c05',
                'on-tertiary': '#22160a',
                'tertiary-container': '#c8801a',
                'on-tertiary-container': '#f0a83c',
                'tertiary-fixed': '#fce3bd',
                'tertiary-fixed-dim': '#f0a83c',
                'on-tertiary-fixed': '#2a1800',
                'on-tertiary-fixed-variant': '#5c3c05',

                // Erreur — messages de validation
                'error': '#ba1a1a',
                'on-error': '#ffffff',
                'error-container': '#ffdad6',
                'on-error-container': '#93000a',

                // Surfaces — fond blanc, neutres légèrement bleutés pour
                // rester dans la famille du bleu de marque.
                'background': '#ffffff',
                'on-background': '#14192b',
                'surface': '#ffffff',
                'on-surface': '#14192b',
                'surface-variant': '#e6ebf2',
                'on-surface-variant': '#5c6274',
                'surface-dim': '#e9edf3',
                'surface-bright': '#ffffff',
                'surface-container-lowest': '#ffffff',
                'surface-container-low': '#f6f8fb',
                'surface-container': '#eff3f8',
                'surface-container-high': '#e6ebf2',
                'surface-container-highest': '#dde4ee',
                'inverse-surface': '#1b2338',
                'inverse-on-surface': '#eef1f8',
                'surface-tint': '#183c84',

                // Contours
                'outline': '#6b7285',
                'outline-variant': '#dce3ec',
            },

            borderRadius: {
                DEFAULT: '0.25rem',
                lg: '0.5rem',
                xl: '0.75rem',
                full: '9999px',
            },

            // Échelle d'espacement en multiples de 8 px. `gutter` est la marge
            // latérale des sections, `container-max` la largeur du contenu.
            spacing: {
                xs: '4px',
                sm: '8px',
                base: '8px',
                md: '16px',
                lg: '24px',
                gutter: '24px',
                xl: '32px',
                xxl: '48px',
                'container-max': '1280px',
            },

            // Archivo porte les titres, Inter le texte courant. Le contraste
            // entre les deux donne au site une voix propre — Inter seul, pour
            // tout, reste lisible mais anonyme.
            fontFamily: {
                'label-sm': ['Inter', 'system-ui', 'sans-serif'],
                'label-md': ['Inter', 'system-ui', 'sans-serif'],
                'body-md': ['Inter', 'system-ui', 'sans-serif'],
                'body-lg': ['Inter', 'system-ui', 'sans-serif'],
                'headline-md': ['Archivo', 'Inter', 'system-ui', 'sans-serif'],
                'headline-lg': ['Archivo', 'Inter', 'system-ui', 'sans-serif'],
                'headline-lg-mobile': ['Archivo', 'Inter', 'system-ui', 'sans-serif'],
                'display-lg': ['Archivo', 'Inter', 'system-ui', 'sans-serif'],
            },

            fontSize: {
                'label-sm': ['12px', { lineHeight: '16px', fontWeight: '600' }],
                'label-md': ['14px', { lineHeight: '20px', letterSpacing: '0.01em', fontWeight: '500' }],
                'body-md': ['16px', { lineHeight: '24px', fontWeight: '400' }],
                'body-lg': ['18px', { lineHeight: '28px', fontWeight: '400' }],
                'headline-md': ['24px', { lineHeight: '32px', fontWeight: '600' }],
                'headline-lg': ['32px', { lineHeight: '38px', letterSpacing: '-0.022em', fontWeight: '800' }],
                'headline-lg-mobile': ['28px', { lineHeight: '34px', letterSpacing: '-0.02em', fontWeight: '800' }],
                'display-lg': ['48px', { lineHeight: '52px', letterSpacing: '-0.03em', fontWeight: '800' }],
            },

            boxShadow: {
                'level-1': '0 1px 2px rgba(11, 31, 73, 0.06), 0 4px 12px rgba(11, 31, 73, 0.05)',
                'level-2': '0 12px 32px rgba(11, 31, 73, 0.14)',
                'level-3': '0 20px 44px rgba(11, 31, 73, 0.18)',
            },
        },
    },
};
