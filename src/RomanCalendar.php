<?php
namespace RomanCalendar;
/**
 * RomanCalendar 5.0
 * @author Br. Jayarathina Madharasan SDB
 * @created 2025-08-03
 * @updated 2025-08-03
 * @description This class generates the Roman Catholic Calendar for a given year and saves it to a JSON file.
 *  
 * @version 5.0
 * @license MIT
 */ 

include_once 'RomanCalendarMovable.php';
include_once 'RomanCalendarFixed.php';
include_once 'RomanCalendarColor.php';
include_once 'RomanCalendarTitle.php';

class RomanCalendar{

     /**
     * RomanCalendar constructor.
     * @param int $year The year for which to generate the calendar.
     * @param array $options Options for movable feasts.
     */

    public function __construct(int $year, array $options) {
        RomanCalendarUtility::validateYear($year);
        $fullYear = [];
        $fullYear = (new RomanCalendarMovable())->computeMovableDayCodes($year, $options);
        $fullYear = (new RomanCalendarFixed())->computeFixedDayCodes($year, $fullYear, $options);
        $fullYear = RomanCalendarTitle::computeTitle($fullYear);
        $fullYear = RomanCalendarColor::colourizeYear($fullYear);

        $dirName = 'dat/' . $year;
        if (!is_dir($dirName)) {
			mkdir($dirName, 0744);
		}
        $t = json_encode($fullYear, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
		file_put_contents($dirName . '/calendar.json', $t);
    }
}