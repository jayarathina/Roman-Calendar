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

include_once 'RomanCalendarMovable.php';
include_once 'RomanCalendarFixed.php';
include_once 'RomanCalendarColor.php';

class RomanCalendar{

// private $adventStart, $christmastide1Start, $epiphanyDate, $christmastide2Start, $lentStart, $eastertideStarts, $ordinaryTime1Starts, $ordinaryTime2Starts;
    public function __construct(int $year, array $options) {
        RomanCalendarUtility::validateYear($year);
        $fullYear = (new RomanCalendarMovable())->computeMovableDayCodes($year, $options);
        $fullYear = (new RomanCalendarFixed())->computeFixedDayCodes($year, $fullYear, $options);
        $fullYear = RomanCalendarColor::colourizeYear($fullYear);

        print_r($fullYear);
       // $this->saveCalendar();
    }
}