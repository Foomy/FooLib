<?php

namespace FooLib;

/**
 * Class for handling holydays.
 *
 * @author   Sascha Schneider <sir.foomy@googlemail.com>
 * @category library
 * @package  Date
 */
class Holyday
{
    /**
     * Calculates the date of easter sunday for the given year
     * by use of Spencers easter formula.
     *
     * For more information on Spencers easter formula see:
     * @url https://de.wikipedia.org/wiki/Spencers_Osterformel

     * @param  int $year
     * @return string $easterSunday
     */
    public function calculateEasterSunday($year)
    {
        $a = $year % 19;
        $b = intval($year / 100);
        $c = $year % 100;
        $d = intval($b / 4);
        $e = $b % 4;
        $f = intval(($b + 8) / 25);
        $g = intval(($b - $f -1) / 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = intval($c / 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = intval(($a + 11 * $h + 22 * $l) / 451);
        $n = intval(($h + $l - 7 * $m + 114) / 31);
        $p = ($h + $l - 7 * $m + 114) % 31;

        $day   = $p + 1;
        $month = $n;

        $easterSunday = "$day.$month.$year";

        return $easterSunday;
    }
}