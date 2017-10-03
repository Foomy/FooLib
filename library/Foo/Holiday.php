<?php

namespace FooLib;

/**
 * Class for handling german holidays.
 *
 * This class is based on information gathered from
 * the folling web pages:
 * @url https://msdn.microsoft.com/de-de/library/bb979477.aspx
 * @url https://de.wikipedia.org/wiki/Spencers_Osterformel
 * @url https://de.wikipedia.org/wiki/Advent#Datum_des_ersten_Adventssonntags_in_der_lateinischen_Kirche
 *
 * @author   Sascha Schneider <sir.foomy@googlemail.com>
 * @category library
 * @package  Date
 */
class Holiday
{
    const FS_BADEN_WUERTEMBERG      = 'BW';
    const FS_BAYERN                 = 'BY';
    const FS_EERLIN                 = 'BE';
    const FS_BRANDENBURG            = 'BB';
    const FS_BREMEN                 = 'HB';
    const FS_HAMBURG                = 'HH';
    const FS_HESSEN                 = 'HE';
    const FS_MECKLENBURG_VORPOMMERN = 'MV';
    const FS_NIEDERSACHEN           = 'NI';
    const FS_NORDRHEIN_WESTFALEN    = 'NW';
    const FS_RHEINLAND_PFALZ        = 'RP';
    const FS_SAARLAND               = 'SL';
    const FS_SACHSEN                = 'SN';
    const FS_SACHSEN_ANHALT         = 'ST';
    const FS_SCHLESWIG_HOLSTEIN     = 'SH';
    const FS_THUERINGEN             = 'TH';

    const FORMAT_STRING_GER = 'd.m.Y';
    const FORMAT_STRING_INT = 'Y-m-d';
    const FORMAT_STRING_US  = 'm/d/Y';
    const FORMAT_ARRAY      = '[]';

    /**
     * @var int
     */
    private $year;

    /**
     * @var \DateTime
     */
    private $easterSunday;

    public function __construct($year)
    {
        $this->year = $year;
        $this->calculateEasterSunday();
    }

    /**
     * Returns all holidays for the given federal state in
     * the given format.
     *
     * @param  string $federalState  Default: Baden-Wuerttemberg
     * @param  string $format        Default: German date format (d.m.Y)
     * @return array
     */
    public function allHolidays($federalState = self::FS_BADEN_WUERTEMBERG, $format = self::FORMAT_STRING_GER)
    {
        $holidays = [
            'new-year'           => $this->newYear($format),
            'epiphany'           => $this->epiphany($federalState, $format),
            'good-friday'        => $this->goodFriday($format),
            'easter-sunday'      => $this->easterSunday($format),
            'easter-monday'      => $this->easterMonday($format),
            'may-day'            => $this->mayDay($format),
            'ascension-day'      => $this->ascensionDay($format),
            'whit-sunday'        => $this->whitSunday($format),
            'whit-monday'        => $this->whitMonday($format),
            'corpus-chrisi'      => $this->corpusChristi($federalState, $format),
            'assumption-of-mary' => $this->assumptionOfMary($federalState, $format),
            'german-unity-day'   => $this->germanUnificationDay($format),
            'reformation-day'    => $this->reformationDay($federalState, $format),
            'all-hallows-day'    => $this->allHallowsDay($federalState, $format),
            'rependance-day'     => $this->rependanceDay($federalState, $format),
            'christmas-day'      => $this->christmasDay($format),
            'boxing-day'         => $this->boxingDay($format)
        ];

        return $holidays;
    }

    /**
     * Returns the date for new year.
     *
     * @param $format
     * @return array|string
     */
    public function newYear($format)
    {
        $date = $this->createDate('01.01.' . $this->year);
        return $this->format($date, $format);
    }

    /**
     * Returns the date for Epiphany.
     *
     * @param $federalState
     * @param $format
     * @return array|bool|string
     */
    public function epiphany($federalState, $format)
    {
        if (! in_array($federalState, ['BW', 'BY', 'ST'])) {
            return false;
        }

        $date = $this->createDate('06.01.' . $this->year);
        return $this->format($date, $format);
    }

    /**
     * Returns the date for good friday.
     *
     * @param $format
     * @return array|string
     */
    public function goodFriday($format)
    {
        $date = $this->substractDaysFromEasterSunday(2);
        return $this->format($date, $format);
    }

    /**
     * Returns the date for easter sunday.
     *
     * @param $format
     * @return array|string
     */
    public function easterSunday($format)
    {
        return $this->format($this->easterSunday, $format);
    }

    /**
     * Returns the date for easter monday.
     *
     * @param $format
     * @return array|string
     */
    public function easterMonday($format)
    {
        $date = $this->addDaysToEasterSunday(1);
        return $this->format($date, $format);
    }

    /**
     * Returns the date for may day.
     *
     * @param $format
     * @return array|string
     */
    public function mayDay($format)
    {
        $date = $this->createDate('01.05.' . $this->year);
        return $this->format($date, $format);
    }

    /**
     * Returns the date for ascension day.
     *
     * @param $format
     * @return array|string
     */
    public function ascensionDay($format)
    {
        $date = $this->addDaysToEasterSunday(39);
        return $this->format($date, $format);
    }

    /**
     * Returns the date for Whitsunday.
     *
     * @param $format
     * @return array|string
     */
    public function whitSunday($format)
    {
        $date = $this->addDaysToEasterSunday(49);
        return $this->format($date, $format);
    }

    /**
     * Returns the date for Whitmonday.
     *
     * @param $format
     * @return array|string
     */
    public function whitMonday($format)
    {
        $date = $this->addDaysToEasterSunday(50);
        return $this->format($date, $format);
    }

    /**
     * Returns the date for Corpus Christi.
     *
     * @param $federalState
     * @param $format
     * @return array|bool|string
     */
    public function corpusChristi($federalState, $format)
    {
        if (! in_array($federalState, ['BW', 'BY', 'HE', 'NW', 'RP', 'SL', 'SN', 'TH'])) {
            return false;
        }

        $date = $this->addDaysToEasterSunday(60);
        return $this->format($date, $format);
    }

    /**
     * Returns the date for Assumption of Mary.
     *
     * @param $federalState
     * @param $format
     * @return array|bool|string
     */
    public function assumptionOfMary($federalState, $format)
    {
        if (! in_array($federalState, ['BY', 'SL'])) {
            return false;
        }

        $date = $this->createDate('15.08.' . $this->year);
        return $this->format($date, $format);
    }

    /**
     * Returns the date for the German Unification Day.
     *
     *  @param $format
     * @return array|string
     */
    public function germanUnificationDay($format)
    {
        $date = $this->createDate('03.10.' . $this->year);
        return $this->format($date, $format);
    }

    /**
     * Returns the date for Reformation Day.
     *
     * @param $federalState
     * @param $format
     * @return array|bool|string
     */
    public function reformationDay($federalState, $format)
    {
        if (! in_array($federalState, ['BB', 'MV', 'SN', 'ST', 'TH'])) {
            return false;
        }

        $date = $this->createDate('31.10.' . $this->year);
        return $this->format($date, $format);
    }

    /**
     * Returns the date for All Hallows Day.
     *
     * @param $federalState
     * @param $format
     * @return array|bool|string
     */
    public function allHallowsDay($federalState, $format)
    {
        if (! in_array($federalState, ['BW', 'BY', 'NW', 'RP', 'SL'])) {
            return false;
        }

        $date = $this->createDate('01.11.' . $this->year);
        return $this->format($date, $format);
    }

    /**
     * Returns the date for Rependance Day.
     *
     * @param $federalState
     * @param $format
     * @return array|bool|string
     */
    public function rependanceDay($federalState, $format)
    {
        if (self::FS_SACHSEN_ANHALT !== $federalState) {
            return false;
        }

        $firstAdventSunday = $this->calculateFirstAdventSunday();
        $rependanceDay = $firstAdventSunday->sub(new \DateInterval('P11D'));
        return $this->format($rependanceDay, $format);
    }

    /**
     * Returns the date for Christmas Day.
     *
     * @param $format
     * @return array|string
     */
    public function christmasDay($format)
    {
        $date = $this->createDate('25.12.' . $this->year);
        return $this->format($date, $format);
    }

    /**
     * Returns the date for Boxing Day.
     *
     * @param $format
     * @return array|string
     */
    public function boxingDay($format)
    {
        $date = $this->createDate('26.12.' . $this->year);
        return $this->format($date, $format);
    }

    /**
     * Calculates the date of easter sunday for the given year
     * by use of Spencer's easter formula, which is as far as I
     * know the most accurate. (Unless the definition for easter
     * sunday will not change.)
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

        $easterSunday = [
            'year'  => $this->year,
            'month' => $n,
            'day'   => $p + 1,
        ];

        $this->easterSunday = new \DateTime(vsprintf('%s-%s-%s', $easterSunday));
    }

    /**
     * Calculates the date of the first advent sunday.
     *
     * Advent sundays are the 4 last sunday before Christmas.
     * Therefore the first advent sunday's date cycles  year
     * by year over the dates 3rd, 2nd, 1st of december and
     * 29th, 28th, 27th of november.
     * Therefore the first sunday after 26th of november is
     * the first advent sunday.
     *
     * @return \DateTime
     */
    private function calculateFirstAdventSunday()
    {
        $date         = $this->createDate('26.11.' . $this->year);
        $weekday      = date('w', $date->getTimestamp());
        $intervalSpec = 'P' . 7 - $weekday . 'D';

        return $date->add(new \DateInterval($intervalSpec));
    }

    /**
     * Adds the given amount of days to the date
     * of easter sunday.
     *
     * @param $days
     * @return \DateTime
     */
    private function addDaysToEasterSunday($days)
    {
        $intervalSpec = 'P' . $days . 'D';
        $date = clone $this->easterSunday;

        $date->add(new \DateInterval($intervalSpec));
        return $date;
    }

    /**
     * Substracts the given amount of days from
     * the date of easeter sunday.
     *
     * @param  int $days
     * @return \DateTime
     */
    private function substractDaysFromEasterSunday($days)
    {
        $intervalSpec = 'P' . $days . 'D';
        $date = clone $this->easterSunday;

        $date->sub(new \DateInterval($intervalSpec));
        return $date;
    }

    /**
     * Changes the format of the given date to the
     * passed target format.
     *
     * @param  \DateTime $date
     * @param  string $targetFormat
     * @return string|array
     */
    private function format($date, $targetFormat)
    {
        if (self::FORMAT_ARRAY ===  $targetFormat) {
            return explode('.', $date->format(self::FORMAT_STRING_GER));
        }

        return $date->format($targetFormat);
    }

    /**
     * Creates an \DateTime object and initializes it with
     * the given date value.
     *
     * @param  string $date
     * @param  \DateTime|null $dateTime
     * @return \DateTime
     */
    protected function createDate($date, \DateTime $dateTime = null)
    {
        if (null === $dateTime) {
            $dateTime = new \DateTime($date);
        }

        return $dateTime;
    }
}