<?php
namespace RomanCalendar;
/**
 * RomanCalendar 5.0
 * @author Br. Jayarathina Madharasan SDB
 * @created 2025-08-05
 * @updated 2025-08-05
 * @description This class adds titles to solemnities, feasts and memorials based on their codes.
 * @version 5.0
 * @license MIT
 * 
 */ 
require_once 'RomanCalendarRanks.php';

class RomanCalendarTitle{

    private static $epiphanyOnSunday;

    public static function computeTitle(array $fullYear): array {
        self::$epiphanyOnSunday = ($fullYear[1][6][0]['code'] === 'CW03-Epiphany');

        foreach ($fullYear as $month => $days) {
            foreach ($days as $day => $feasts) {
				foreach ($feasts as $feastIndex => $singleFeast) {
					$cDate = &$fullYear[$month][$day][$feastIndex];
					if ($feastIndex == 'other') {
						foreach ($singleFeast as $other_key => $other_value) {
							$cDate[$other_key]['name'] = self::getSingleTitle($cDate[$other_key]['code']);
						}
					} else {
						$cDate['name'] = self::getSingleTitle($cDate['code']);
					}
				}
            }
        }
        return $fullYear;
    }

    /**
	 * Returns the title for a given day code.
	 * @param string $dayCode The day code to get the title for.
	 * @return string The title for the day code.
	 */
	private static function getSingleTitle($dayCode): string {
		$dayEnglishFull = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

		$RomanCalendarRanks = [
			'CW02-0Sun' => 'Second Sunday after Christmas',
			'CW03-Epiphany' => 'The Epiphany of the Lord',
			'CW04-Baptism' => 'The Baptism of the Lord',

			'LW00-3Wed' => 'Ash Wednesday',
			'LW06-0Sun' => 'Palm Sunday of The Passion Of The Lord',
			'LW06-4Thu' => 'Thursday of Holy Week (Holy Thursday)',
			'LW06-5Fri' => 'Friday of the Passion of the Lord (Good Friday)',
			'LW06-6Sat' => 'Holy Saturday',

			'EW01-0Sun' => 'Easter Sunday of The Resurrection of The Lord',
			'EW07-Ascension' => 'The Ascension of the Lord',
			'EW08-Pentecost' => 'Pentecost Sunday',

			'OW00-Trinity' => 'The Most Holy Trinity',
			'OW00-CorpusChristi' => 'The Most Holy Body and Blood of Christ (Corpus Christi)',
			'OW00-SacredHeart' => 'The Most Sacred Heart of Jesus',
			'OW00-ImmaculateHeart' => 'Immaculate Heart of the Blessed Virgin Mary',
			'OW00-MaryMotherofChurch' => 'Mary, Mother of the Church',

			'CW01-HolyFamily' => 'The Holy Family of Jesus, Mary And Joseph',
			'Mem-Mary-Sat' => 'Saturday Memorial of the Blessed Virgin Mary'
		];

		$fTitle = $RomanCalendarRanks[$dayCode] ?? null;

		$wkNo = intval(substr($dayCode, 2, 2));
		$wkDay = substr($dayCode, -4, 1);

		if (is_null($fTitle))
			switch (substr($dayCode, 0, 2)) {
				case 'AW':
					$fTitle = match ($wkNo) {
						5 => 'Advent Weekday: December - ' . substr($dayCode, -2),
						default => $dayEnglishFull[$wkDay] . ' of the ' . self::addOrdinalNumberSuffix($wkNo) . ' Week of Advent'
					};
					break;
				case 'CW':
					$fTitle = match ($wkNo) {
						1 => self::addOrdinalNumberSuffix(intval(substr($dayCode, -2) - 24)) . ' Day in the Octave of Christmas', // Christmas Octave
						2 => 'Christmas Weekday: January 0' . substr($dayCode, -1), // Before Epiphany
						3 => (self::$epiphanyOnSunday) ? 'Christmas Weekday: January 0' . (6 + substr($dayCode, -1)) : $dayEnglishFull[substr($dayCode, -1)] . ' after Epiphany' // After Epiphany
					};
					break;
				case 'LW':
					$fTitle = $dayEnglishFull[$wkDay] . match ($wkNo) {
						0 => ' after Ash Wednesday',
						6 => ' of Holy Week',
						default => ' of the ' . self::addOrdinalNumberSuffix($wkNo) . ' Week of Lent'
					};
					break;
				case 'EW':
					$fTitle = $dayEnglishFull[$wkDay] . match ($wkNo) {
						1 => ' in the Octave of Easter',
						default => ' of the ' . self::addOrdinalNumberSuffix($wkNo) . ' Week of Easter'
					};
					break;
				case 'OW':
					$fTitle = $dayEnglishFull[$wkDay] . ' of the ' . self::addOrdinalNumberSuffix($wkNo) . ' Week in Ordinary Time';
					break;
			}
		return $fTitle ?? $dayCode;
	}

	/**
	 * Returns the given number with ordinal suffix
	 * @param int $number
	 * @return string number with ordinal suffix
	 *
	 */
	private static function addOrdinalNumberSuffix($num): string {
		if (!in_array(($num % 100), [11, 12, 13])) {
			return $num . match ($num % 10) {
				1 => '<sup>st</sup>',
				2 => '<sup>nd</sup>',
				3 => '<sup>rd</sup>',
				default => '<sup>th</sup>'
			};
		}
		return $num . '<sup>th</sup>';
	}
}