<?php
namespace RomanCalendar;

/**
 * RomanCalendar 5.0
 * @author Br. Jayarathina Madharasan SDB
 * @created 2025-08-05
 * @updated 2025-08-05
 * 
 * @description
 * Utility class for generating and managing the structure of the Roman Catholic liturgical calendar.
 * Provides methods to validate input years, initialize the calendar array for a given year,
 * and calculate the start dates of key liturgical seasons (Advent, Christmas, Epiphany, Baptism, Ordinary Time, Lent, Easter).
 * All methods are static and designed to support the main calendar generation logic.

 * @version 5.0
 * @license MIT
 * 
  */

class RomanCalendarUtility {

    public static function validateYear(int $year): void {
        if ($year < 2000) {
            throw new \InvalidArgumentException('Year must be greater than 2000');
        }
    }

    /**
	 * Initialises the calendar structure for the year.
	 * @return array The initialized calendar structure for the year.
	 */
	public static function initializeCalendar(int $year): array {
		$calendar = [];
		for ($mnth = 1; $mnth <= 12; $mnth++) {
			$days_in_month = cal_days_in_month(CAL_GREGORIAN, $mnth, $year);
			$calendar[$mnth] = array_fill(1, $days_in_month, []);
		}
		return $calendar;
	}

    /**
	 * Sets the start dates of various seasons of the liturgical year.
	 */
	public static function getSeasonLimits(int $year, bool $epiphany_on_sunday): array {
		$seasonLimits = [];

		// Sunday after last thur of Nov is Advent
		$seasonLimits['advent'] = new \DateTimeImmutable("last thu of Nov $year next sunday");
		$seasonLimits['christmastide1'] = new \DateTimeImmutable($year . '-12-25');
		$seasonLimits['christmastide2'] = new \DateTimeImmutable($year . '-01-01');

		// Epiphany is celebrated on Jan 6, but if it falls on a Sunday, it is celebrated on the next Sunday
		// The Baptism of the Lord occurs on the Sunday following Jan 6
		$seasonLimits['epiphany'] = new \DateTimeImmutable($year . '-01-06');
		$seasonLimits['baptism'] = $seasonLimits['epiphany']->modify('next sunday');

		if ($epiphany_on_sunday == true) {
			// Epiphany is celebrated on the Sunday occurring from Jan. 2 through Jan. 8 (Both inclusive).
			$seasonLimits['epiphany'] = new \DateTimeImmutable($year . '-01-01 next sunday');

			if ($seasonLimits['epiphany']->format('j') > 6) {
				// If Epiphany occurs on Jan 7 or Jan 8, then the Baptism of the Lord (Ordinary Time) is the next day
				$seasonLimits['baptism'] = $seasonLimits['epiphany']->modify('+1 days');
			} else {
				// If Epiphany occurs on or before Jan 6, the Sunday following Epiphany is the Baptism of the Lord (Ordinary Time)
				$seasonLimits['baptism'] = $seasonLimits['epiphany']->modify('next sunday');
			}
		}
		$seasonLimits['ordinaryTime1'] = $seasonLimits['baptism']->modify('+1 days'); // Ordinary Time 1 starts the day after Baptism of the Lord
		$seasonLimits['easter'] = new \DateTimeImmutable($year . '-03-21 + ' . easter_days($year) . ' days'); // Easter Sunday is the Sunday following the first full moon after the vernal equinox
		$seasonLimits['lent'] = $seasonLimits['easter'] ->modify('-46 days'); // Lent starts 46 days before Easter Sunday
		$seasonLimits['ordinaryTime2'] = $seasonLimits['easter']->modify('+50 days');

		return $seasonLimits;
	}
}
