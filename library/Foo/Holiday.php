<?php

namespace FooLib;

/**
 * Class for handling holidays.
 *
 * This class is based on information gathered from
 * the folling web pages:
 * @url https://msdn.microsoft.com/de-de/library/bb979477.aspx
 * @url https://de.wikipedia.org/wiki/Spencers_Osterformel
 *
 * @author   Sascha Schneider <sir.foomy@googlemail.com>
 * @category library
 * @package  Date
 *
 * @todo Use PHP date and time classes for calculation and also validation
 */
class Holiday
{
    private $year;
    private $easterSunday;

    private $ultimos;

    public function __construct($year)
    {
        $this->ultimos = [
            1  => 31, 2  => 28, 3  => 31, 4  => 30, 5  => 31, 6  => 30,
            7  => 31, 8  => 31, 9  => 30, 10 => 31, 11 => 30, 12 => 31
        ];

        $this->year = $year;
        $this->calculateEasterSunday();
    }

    public function allHolidays($federalState = 'BW')
    {

    }

    public function newYear()
    {
        return [
            'day'   => 1,
            'month' => 1
        ];
    }

    public function epiphany($federalState)
    {
        if (in_array($federalState, ['BW', 'BY', 'ST'])) {
            return [
                'day'   => 6,
                'month' => 1
            ];
        }

        return [];
    }

    public function goodFriday()
    {
        return $this->substractDaysFromEasterSunday(2);
    }

    public function easterSunday()
    {
        return $this->easterSunday;
    }

    public function easterMonday()
    {
        return $this->addDaysToEasterSunday(1);
    }

    public function mayDay()
    {
        return [
            'day'   => 1,
            'month' => 5
        ];
    }

    public function ascensionDay()
    {
        return $this->addDaysToEasterSunday(39);
    }

    public function whitMonday()
    {
        return $this->addDaysToEasterSunday(50);
    }

    public function corpusChristi($federalState)
    {
        if (in_array($federalState, ['BW', 'BY', 'HE', 'NW', 'RP', 'SL', 'SA', 'TH'])) {
            return $this->addDaysToEasterSunday(60);
        }

        return [];
    }

    public function assumptionOfMary($federalState)
    {
        if (in_array($federalState, ['BY', 'SL'])) {
            return [
                'day'   => 15,
                'month' => 8
            ];
        }

        return [];
    }

    public function germanUnityDay()
    {
        return [
            'day'   => 3,
            'month' => 10
        ];
    }

    public function reformationDay($federalState)
    {
        if (in_array($federalState, ['BB', 'MV', 'SA', 'ST', 'TH'])) {
            return [
                'day'   => 31,
                'month' => 10
            ];
        }

        return [];
    }

    public function allHallowsDay($federalState)
    {
        if (in_array($federalState, ['BW', 'BY', 'NW', 'RP', 'SL'])) {
            return [
                'day'   => 1,
                'month' => 11
            ];
        }

        return [];
    }

    public function rependanceDay($federalState)
    {
        if ('SA' === $federalState) {
            return [
                'day'   => 0,
                'month' => 0
            ];
        }

        return [];
    }

    public function christmasDay()
    {
        return [
            'day'   => 25,
            'month' => 12
        ];
    }

    public function boxingDay()
    {
        return [
            'day'   => 26,
            'month' => 12
        ];
    }

    /**
     * Calculates the date of easter sunday for the given year
     * by use of Spencers easter formula.
     *
     * For more information on Spencers easter formula see:
     * @url https://de.wikipedia.org/wiki/Spencers_Osterformel
     */
    private function calculateEasterSunday()
    {
        $a = $this->year % 19;
        $b = intval($this->year / 100);
        $c = $this->year % 100;
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

        $this->easterSunday = [
            'day'   => $p + 1,
            'month' => $n
        ];
    }

    private function addDaysToEasterSunday($days)
    {
        $intervalSpec = 'P' . $days . 'D';
        $easterDate = new \DateTime($this->easterSundayAsString($this->easterSunday));
        $easterDate->add(new \DateInterval($intervalSpec));
        list(,$month, $day) = $easterDate->format('Y-m-d');

        return [
            'day'   => $day,
            'month' => $month
        ];
    }

    private function substractDaysFromEasterSunday($days)
    {
        $intervalSpec = 'P' . $days . 'D';
        $easterDate = new \DateTime($this->easterSundayAsString($this->easterSunday));
        $easterDate->sub(new \DateInterval($intervalSpec));
        list(,$month, $day) = $easterDate->format('Y-m-d');

        return [
            'day'   => $day,
            'month' => $month
        ];
    }

    private function easterSundayAsString($date)
    {
        array_unshift($date, $this->year);
        return vsprintf('%s-%s-%s', $date);
    }
}