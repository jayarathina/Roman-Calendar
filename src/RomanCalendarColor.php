<?php
namespace RomanCalendar;
/**
 * RomanCalendar 5.0
 * @author Br. Jayarathina Madharasan SDB
 * @created 2025-08-05
 * @updated 2025-08-05
 * @description This class generates the Roman Catholic Calendar for a given year.
 * @version 5.0
 * @license MIT
 * 
 */ 

class RomanCalendarColor {

	public static function colourizeYear($fullYear) {

        for ($mnth = 1; $mnth <= 12; $mnth++) {
			foreach ($fullYear[$mnth] as $date => $feasts) {
				foreach ($feasts as $key => $singleFeast) {
					if ($key == 'other') {
						foreach ($singleFeast as $key_other => $other) {
							$fullYear[$mnth][$date][$key][$key_other]['color'] = self::getColor($other['code'], $other['type'] ?? null);
						}
					} else {
						$fullYear[$mnth][$date][$key]['color'] = self::getColor($singleFeast['code'], $singleFeast['type'] ?? null);
					}
				}

                if(isset($feasts[1])) {
					if (preg_match('/^Mem/', $feasts[1]['type']) === 1) {
                        $fullYear[$mnth][$date][0]['color'] =  $fullYear[$mnth][$date][1]['color'];
                    }
                }


			}
		}
		return $fullYear;
	}

	public static function getColor($feastCode, $feastType = null) {
		$feastType = explode('-', $feastType, 2);
		$feastType = $feastType[0];

		//@formatter:off
		$feastClrr = [
			// The proper color for Solemnities is white except Pentecost and Peter and Paul (Jun 29) in which cases it is red
			'Solemnity' => 'white',
			'Saints Peter and Paul, Apostles' => 'red',
			'EW08-Pentecost' => 'red',

			// The proper color of the Third Sunday of Advent and the Fourth Sunday of Lent is rose
			'AW03-0Sun' => 'rose',
			'LW04-0Sun' => 'rose',

			// The proper color for Feasts of the Lord is white except the Triumph of the Cross in which case it is red
			'Feast-Lord' => 'white',
			'Exaltation of the Holy Cross' => 'red',

			// The proper color of a Feast or a Memorial is white except for martyrs in which case it is red
			'Feast' => 'white',
			'Mem' => 'white',
			'OpMem' => 'white',
			'martyr' => 'red',

			// Some exceptions for feasts
			'Chair of Saint Peter, apostle' => 'white',
			'The Conversion of Saint Paul, apostle' => 'white',
			'Saint John the Apostle and evangelist' => 'white',

			// The proper colors of Holy Week
			'LW06-0Sun' => 'red', // Palm Sunday
			'LW06-4Thu' => 'white', // Holy Thursday
			'LW06-5Fri' => 'red', // Good Friday
			'LW06-6Sat' => 'white', // Easter Vigil

			// The proper color of a Commemoration is the color of the season. As Commemorations only occur during Lent, their proper color is purple.
			'Commomeration' => 'purple',
			'All Souls' => 'purple'
		];

		$feastClr = $feastClrr[$feastCode] ?? $feastClrr[$feastType] ?? match (substr($feastCode, 0, 2)) {
			'AW', 'LW' => 'purple',
			'CW', 'EW' => 'white',
			'OW' => 'green',
			default => null
		} ?? null;

		// Even if feast is set above, we replace it with the color for martyr below, 
		// unless explicitly set for a particular feast in above array
		if (!array_key_exists($feastCode, $feastClrr)) {
			$feastCode_ = strtolower($feastCode); // str_contains is case sensitive, hence strtolower is required
			if (str_contains($feastCode_, 'martyr') || str_contains($feastCode_, 'apostle') || str_contains($feastCode_, 'evangelist')) {
				// martyr and apostle are red. Except, St. John, excluded via $feastClrr
				$feastClr = $feastClrr['martyr'];
			}
		}
		return $feastClr;
	}


}
