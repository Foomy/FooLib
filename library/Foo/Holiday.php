<?php

namespace FooLib;

use DateInterval;
use DateTime;
use Exception;

/**
 * Class for handling german holidays.
 *
 * This class is based on information gathered from
 * the following web pages:
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
     * @var DateTime
     */
    private $easterSunday;

    /**
     * Holiday constructor.
     *
     * @param $year
     *
     * @throws Exception
     */
    public function __construct($year)
    {
        $this->year = $year;
        $this->calculateEasterSunday();
    }

    /**
     * Returns all holidays for the given federal state in
     * the given format.
     *
     * @param string $federalState Default: Baden-Württemberg
     * @param string $format       Default: German date format (d.m.Y)
     *
     * @return array
     *
     * @throws Exception
     */
    public function allHolidays(string $federalState = self::FS_BADEN_WUERTEMBERG, string $format = self::FORMAT_STRING_GER): array
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
            'corpus-christi'      => $this->corpusChristi($federalState, $format),
            'assumption-of-mary' => $this->assumptionOfMary($federalState, $format),
            'german-unity-day'   => $this->germanUnificationDay($format),
            'reformation-day'    => $this->reformationDay($federalState, $format),
            'all-hallows-day'    => $this->allHallowsDay($federalState, $format),
            'repentance-day'     => $this->repentanceDay($federalState, $format),
            'christmas-day'      => $this->christmasDay($format),
            'boxing-day'         => $this->boxingDay($format)
        ];

        return $holidays;
    }

    /**
     * Returns the date for new year.
     *
     * @param $format
     *
     * @return string|array
     *
     * @throws Exception
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
     *
     * @return string|array|bool
     *
     * @throws Exception
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
     *
     * @return string|array
     *
     * @throws Exception
     */
    public function goodFriday($format)
    {
        $date = $this->subtractDaysFromEasterSunday(2);
        return $this->format($date, $format);
    }

    /**
     * Returns the date for easter sunday.
     *
     * @param $format
     *
     * @return string|array
     */
    public function easterSunday($format)
    {
        return $this->format($this->easterSunday, $format);
    }

    /**
     * Returns the date for easter monday.
     *
     * @param $format
     *
     * @return string|array
     *
     * @throws Exception
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
     *
     * @return string|array
     *
     * @throws Exception
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
     *
     * @return string|array
     *
     * @throws Exception
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
     *
     * @return string|array
     *
     * @throws Exception
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
     *
     * @return string|array
     *
     * @throws Exception
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
     *
     * @return string|array|bool
     *
     * @throws Exception
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
     *
     * @return string|array|bool
     *
     * @throws Exception
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
     * @param $format
     *
     * @return string|array
     *
     * @throws Exception
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
     *
     * @return string|array|bool
     *
     * @throws Exception
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
     *
     * @return string|array|bool
     *
     * @throws Exception
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
     *
     * @return string|array|bool
     *
     * @throws Exception
     */
    public function repentanceDay($federalState, $format)
    {
        if (self::FS_SACHSEN !== $federalState) {
            return false;
        }

        $firstAdventSunday = $this->calculateFirstAdventSunday();
        $rependanceDay = $firstAdventSunday->sub(new DateInterval('P11D'));
        return $this->format($rependanceDay, $format);
    }

    /**
     * Returns the date for Christmas Day.
     *
     * @param $format
     *
     * @return string|array
     *
     * @throws Exception
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
     *
     * @return string|array
     *
     * @throws Exception
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
     *
     * @throws Exception
     */
    private function calculateEasterSunday()
    {
        $a = $this->year % 19;
        $b = (int)($this->year / 100);
        $c = $this->year % 100;
        $d = (int)($b / 4);
        $e = $b % 4;
        $f = (int)(($b + 8) / 25);
        $g = (int)(($b - $f - 1) / 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = (int)($c / 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = (int)(($a + 11 * $h + 22 * $l) / 451);
        $n = (int)(($h + $l - 7 * $m + 114) / 31);
        $p = ($h + $l - 7 * $m + 114) % 31;

        $easterSunday = [
            'year'  => $this->year,
            'month' => $n,
            'day'   => $p + 1,
        ];

        // @ToDo Consider to drag
        $this->easterSunday = new DateTime(vsprintf('%s-%s-%s', $easterSunday));
    }

    /**
     * Calculates the date of the first advent sunday.
     *
     * Advent sundays are the 4 last sunday before Christmas.
     * Therefore the first advent sunday's date cycles  year
     * by year over the dates 3rd, 2nd, 1st of december and
     * 29th, 28th, 27th of november and therefore the first
     * sunday after 26th of november is the first advent sunday.
     *
     * @return DateTime
     *
     * @throws Exception
     */
    private function calculateFirstAdventSunday(): DateTime
    {
        $date         = $this->createDate('26.11.' . $this->year);
        $weekday      = date('w', $date->getTimestamp());
        $days = 7 - $weekday;
        $intervalSpec = 'P' . $days . 'D';

        return $date->add(new DateInterval($intervalSpec));
    }

    /**
     * Adds the given amount of days to the date
     * of easter sunday.
     *
     * @param $days
     *
     * @return DateTime
     *
     * @throws Exception
     */
    private function addDaysToEasterSunday($days): DateTime
    {
        $intervalSpec = 'P' . $days . 'D';
        $date = clone $this->easterSunday;

        $date->add(new DateInterval($intervalSpec));
        return $date;
    }

    /**
     * Subtracts the given amount of days from
     * the date of easter sunday.
     *
     * @param int $days
     *
     * @return DateTime
     *
     * @throws Exception
     */
    private function subtractDaysFromEasterSunday($days): DateTime
    {
        $intervalSpec = 'P' . $days . 'D';
        $date = clone $this->easterSunday;

        $date->sub(new DateInterval($intervalSpec));
        return $date;
    }

    /**
     * Changes the format of the given date to the
     * passed target format.
     *
     * @param  DateTime $date
     * @param  string $targetFormat
     *
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
     * @param string        $date
     * @param DateTime|null $dateTime
     *
     * @return DateTime
     *
     * @throws Exception
     */
    protected function createDate($date, DateTime $dateTime = null): DateTime
    {
        if (null === $dateTime) {
            $dateTime = new DateTime($date);
        }

        return $dateTime;
    }
}