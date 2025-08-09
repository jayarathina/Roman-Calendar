<?php
namespace RomanCalendar;
/**
 * RomanCalendar 5.0
 * @author Br. Jayarathina Madharasan SDB
 * @created 2025-08-03
 * @updated 2025-08-03
 * @description This class generates the Roman Catholic Calendar for a given year.
 *  
 * @version 5.0
 * @license MIT
 */ 

include_once 'RomanCalendarMovable.php';
include_once 'RomanCalendarFixed.php';
include_once 'RomanCalendarColor.php';
include_once 'RomanCalendarTitle.php';

class RomanCalendar{

    private array $fullYear = [];

     /**
     * RomanCalendar constructor.
     * @param int $year The year for which to generate the calendar.
     * @param array $options Options for movable feasts.
     */

    public function __construct(int $year, array $options) {
        RomanCalendarUtility::validateYear($year);
        $this->fullYear = [];
        $this->fullYear = (new RomanCalendarMovable())->computeMovableDayCodes($year, $options);
        $this->fullYear = (new RomanCalendarFixed())->computeFixedDayCodes($year, $this->fullYear, $options);
        $this->fullYear = RomanCalendarTitle::computeTitle($this->fullYear);
        $this->fullYear = RomanCalendarColor::colourizeYear($this->fullYear);
    }

    /*
     * Gets the fullYear array containing the calendar data.
     * @return array
     */
    public function getFullYear(): array
    {
        return $this->fullYear;
    }
}