<?php

/**
 * RomanCalendar 4.0
 * @author Br. Jayarathina Madharasan SDR
 */

require_once 'lib/config.php';

require_once 'RomanCalendarFixed.php';
require_once 'RomanCalendarColor.php';
class RomanCalendar extends RomanCalendarFixed {

	function __construct($currentYear) {
		parent::__construct($currentYear);

		$calCol = new RomanCalendarColor();
		$this->fullYear = $calCol->colourizeYear($this->fullYear);

		$dirName = 'dat/' . $this->currentYear;
		if (!is_dir($dirName)) {
			mkdir($dirName, 0744);
		}

		$t = json_encode($this->fullYear, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
		file_put_contents($dirName . '/calendar.json', $t);
	}
}
