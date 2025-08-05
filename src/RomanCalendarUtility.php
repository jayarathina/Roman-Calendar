<?php
namespace RomanCalendar;

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


		/**
	 * Get rank of a given dayCode
	 *
	 * @param string $dayCode
	 * @return number
	 */
	public static function getRank($dayCode) {
		$RomanCalendarRanks = array(
			// Easter Tridum
			'EW01-0Sun' => 1, // Easter
			'LW06-6Sat' => 1.1,
			'LW06-5Fri' => 1.1,
			'LW06-4Thu' => 1.1,

			'Nativity of the Lord' => 2, // Christmas Code generated in movable feast is CW01-Dec25; will be superceeded later
			'CW03-Epiphany' => 2, // Epiphany
			'EW07-Ascension' => 2, // Assension
			'EW08-Pentecost' => 2, // Pentecost

			'AW\d{2}-0Sun' => 2.1, // Sundays of Advent
			'LW\d{2}-0Sun' => 2.1, // Sundays of Lent
			'EW\d{2}-0Sun' => 2.1, // Sundays of Easter
			'LW00-3Wed' => 2.2, // Ash Wednesday
			'LW06' => 2.3, // Weekdays of the Holy Week, Monday to Thursday inclusive; Palm sunday, Good friday and Holy Saturday ranked above
			'EW01' => 2.4, // Easter Octave EW02-0Sun is ranked above

			// Solemnities of the Lord, the Blessed Virgin Mary, and saints listed in the general calendar and All Souls Day
			'Solemnity' => 3.1,
			'OW00-Trinity' => 3.1, // Trinity Sunday
			'OW00-CorpusChristi' => 3.1, // Corpus Christi
			'OW00-SacredHeart' => 3.1, // Sacred Heart
			'OW34-0Sun' => 3.1, // Christ the King
			'All Souls' => 3.2,

			// Solemities of Particular Calendars
			'Solemnity-PrincipalPartron-Place' => 4.1, // Principal Patron of a town, city or a similar place
			'Solemnity-ChurchDedication' => 4.2, // The dedication and Anniversay of the Dedication of one's own church
			'Solemnity-OwnChurch' => 4.3, // The titular of one's own church
			'Solemnity-Religious' => 4.3, // The titular or Holy Founder or Principal Patron of a Religious Order or Congregation

			// Feasts of the Lord in the general calendar: (Baptism of the Lord, Presentation of the Lord, Transfiguration, Triumph of the Cross, Holy Family)
			'Feast-Lord' => 5,
			'CW04-Baptism' => 5,
			'CW01-HolyFamily' => 5,

			// Sundays of the Christmas season and Sundays in ordinary time
			'OW\d{2}-0Sun' => 6,
			'CW\d{2}-Sun' => 6,

			'Feast' => 7, // Feasts of the Blessed Virgin Mary and of the saints in the general calendar

			// Feasts of Particular Calendar
			'Feast-PrincipalPartron-Diocese' => 8.1, // The principal patron of a diocese
			'Feast-CathedralDedication' => 8.2, // The anniversary of the dedication of the cathedral
			'Feast-PrincipalPartron-Place' => 8.3, // The principal patron of the territory, province, country, or more extensive territory
			'Feast-Religious' => 8.4, // The titular saint, founder, or principal patron of an order or congregation and religious province, observing the directives
			'Feast-OwnChurch' => 8.5, // Other feasts proper to one's own church
			'Feast-Other' => 8.6, // Other feasts listed in the calendar of the diocese, order, or congregation

			'AW05' => 9.1, // Weekdays of Advent from December 17 to December 24 inclusive
			'CW01' => 9.2, // Days within the octave of Christmas - Jan 1 is ranked as solemnity
			'LW' => 9.3, // Weekdays of Lent

			'Mem-Mary' => 10.1, // Obligatory memorials of BVM in the general calendar
			'Mem' => 10.2, // Obligatory memorials in the general calendar

			// Obligatory memorials of Particular Calendar
			'Mem-SecondaryPatron' => 11.1, // Memorial of a secondary patron of the place, diocese, region or province, country, or more extensive territory; or of an order, congregation, or religious province
			'Mem-OwnChurch' => 11.2, // Obligatory memorials proper to one's own church
			'Mem-Other' => 11.3, // Obligatory memorials listed in the calendar of a diocese, order, or congregation

			'OpMem' => 12, // Optional memorials, may be observed even on the days in no. 9.

			'AW' => 13.1, // Weekdays of Advent up to December 16 inclusive
			'CW' => 13.2, // Weekdays of the Christmas season from January 2 until the Saturday after Epiphany
			'EW' => 13.3, // Weekdays of the Easter season from Monday after the octave of Easter until the Saturday before Pentecost inclusive
			'OW' => 13.4, // Weekdays in ordinary time

			'Commomeration' => 14
		);

		$keys = array_keys($RomanCalendarRanks);
		foreach ($keys as $str) {
			if (str_starts_with($dayCode, $str)) {
				return $RomanCalendarRanks[$str];
			}
		}
		die('ERROR: Invalid Feast Code : ' . $dayCode); // This should never happen
	}

}
