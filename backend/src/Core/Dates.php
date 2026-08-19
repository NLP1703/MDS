<?php
declare(strict_types=1);

namespace Mds\Core;

/**
 * Dates en toutes lettres, dans la langue demandée.
 *
 * Rassemblées ici parce que deux services les composaient chacun de son côté :
 * ajouter une langue obligeait à modifier deux tables de mois, avec une
 * occasion d'oubli à chaque fois.
 *
 * Ni `strftime`, déprécié depuis PHP 8.1, ni `IntlDateFormatter`, qui suppose
 * l'extension `intl` — absente de bien des hébergements mutualisés. Douze noms
 * par langue coûtent moins cher qu'une dépendance à vérifier chez l'hébergeur.
 *
 * Le rendu ne dépend pas de la locale du visiteur mais de la langue choisie
 * sur le site : deux personnes lisant la même page dans la même langue voient
 * la même date, quel que soit le réglage de leur système.
 */
final class Dates
{
    /** @var array<string, array<int, string>> */
    private const MOIS = [
        'fr' => [
            1 => 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
            'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre',
        ],
        'en' => [
            1 => 'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December',
        ],
    ];

    /** « Mars 2024 » · « March 2024 ». */
    public static function moisAnnee(string $date, string $langue = 'fr'): string
    {
        [$annee, $mois] = array_pad(explode('-', $date), 2, '');
        $noms = self::noms($langue);
        $numero = (int) $mois;

        if (!isset($noms[$numero])) {
            return $date;
        }

        return $noms[$numero] . ' ' . $annee;
    }

    /**
     * « 1er avril 2026 » · « 1 April 2026 ».
     *
     * Le français distingue le premier du mois — « 1er avril », jamais
     * « 1 avril ». L'anglais ignore cette règle, d'où le test sur la langue
     * plutôt que sur le seul quantième.
     */
    public static function dateLongue(string $date, string $langue = 'fr'): string
    {
        [$annee, $mois, $jour] = array_pad(explode('-', $date), 3, '');
        $noms = self::noms($langue);
        $numero = (int) $mois;

        if (!isset($noms[$numero])) {
            return $date;
        }

        $j = (int) $jour;

        if ($langue === 'fr') {
            $quantieme = $j === 1 ? '1er' : (string) $j;

            return $quantieme . ' ' . mb_strtolower($noms[$numero]) . ' ' . $annee;
        }

        return $j . ' ' . $noms[$numero] . ' ' . $annee;
    }

    /** @return array<int,string> Les mois d'une langue, français par défaut. */
    private static function noms(string $langue): array
    {
        return self::MOIS[$langue] ?? self::MOIS['fr'];
    }
}
