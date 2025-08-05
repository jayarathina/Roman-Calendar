<?php
namespace RomanCalendar;
/**
 * RomanCalendar 5.0
 * @author Br. Jayarathina Madharasan SDB
 * @created 2025-08-03
 * @updated 2025-08-03
 * @description This class generates the Roman Catholic Calendar for a given year.
 * @version 5.0
 * @license MIT
 * 
 */ 

include_once 'romanCalendarmovable.php';

class RomanCalendar{

// private $adventStart, $christmastide1Start, $epiphanyDate, $christmastide2Start, $lentStart, $eastertideStarts, $ordinaryTime1Starts, $ordinaryTime2Starts;
    public function __construct(int $year, array $options) {
        RomanCalendarUtility::validateYear($year);
		$movableCalendar = new RomanCalendarMovable($year, $options);

        /*
        // Save the calendar to a JSON file
        $filename = 'dat/' . $year . '/calendar.json';
        if (!file_exists(dirname($filename))) {
            mkdir(dirname($filename), 0777, true);
        }
        file_put_contents($filename, json_encode($movableCalendar->getFullYear(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        
        // Optionally, you can return the generated calendar or any other data
        // return $movableCalendar->getFullYear();
        */
       // $this->saveCalendar();
    }
}