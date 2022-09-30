<?php

/**
 * RomanCalendar 4.0
 * @author Br. Jayarathina Madharasan SDR
 *
 */

class RomanCalendarYear {
	public $fullYear, $currentYear;
	protected $adventStart, $christmastide1Start, $epiphanyDate, $christmastide2Start, $lentStart, $eastertideStarts, $ordinaryTime1Starts, $ordinaryTime2Starts;

	function __construct($year) {
		// Years between 1900-2099
		$this->currentYear = (preg_match("/^(19|20)\d{2}$/", $year) == 1) ? $year : date("Y");
		$this->fullYear = [];

		// Create empty array for each day of the year
		for ($mnth = 1; $mnth <= 12; $mnth++) {
			$days_in_month = cal_days_in_month(CAL_GREGORIAN, $mnth, $this->currentYear);
			$this->fullYear[$mnth] = array_fill(1, $days_in_month, []);
		}
		$this->generateSeasonLimits();
	}

	/**
	 * Sets the start dates of various seasons of the liturgical year.
	 */
	private function generateSeasonLimits() {
		$curYear = $this->currentYear;

		// Sunday after last thur of Nov is Advent
		$this->adventStart = new DateTimeImmutable("last thu of Nov $curYear next sunday");
		$this->christmastide1Start = new DateTimeImmutable($curYear . '-12-25');
		$this->christmastide2Start = new DateTimeImmutable($curYear . '-01-01');

		// Epiphany is celebrated on Jan 6 The Baptism of the Lord (ordinaryTime1Starts) occurs on the Sunday following Jan 6
		$this->epiphanyDate = new DateTimeImmutable($curYear . '-01-06');
		$baptismDate = $this->epiphanyDate->modify('next sunday');

		if (EPIPHANY_ON_A_SUNDAY == true) {
			// Epiphany is celebrated on the Sunday occurring from Jan. 2 through Jan. 8 (Both inclusive).
			$this->epiphanyDate = new DateTimeImmutable($curYear . '-01-01 next sunday');

			if ($this->epiphanyDate->format('j') > 6) {
				// If Epiphany occurs on Jan 7 or Jan 8, then the Baptism of the Lord (Ordinary Time) is the next day
				$baptismDate = $this->epiphanyDate->modify('+1 days');
			} else {
				// If Epiphany occurs on or before Jan 6, the Sunday following Epiphany is the Baptism of the Lord (Ordinary Time)
				$baptismDate = $this->epiphanyDate->modify('next sunday');
			}
		}

		$this->ordinaryTime1Starts = $baptismDate->modify('+1 days'); // Ordinary Times Starts day after baptism
		$this->eastertideStarts = new DateTimeImmutable($curYear . '-03-21 + ' . easter_days($curYear) . ' days');
		$this->lentStart = $this->eastertideStarts->modify('-46 days');
		$this->ordinaryTime2Starts = $this->eastertideStarts->modify('+50 days');
	}

	/**
	 * Test Function for checking the generated season's dates
	 */
	public function printDates() {
		echo '<hr/>' . $this->currentYear;

		echo "<br/>christmastide2Start \t-> " . $this->christmastide2Start->format('jS F Y');
		echo "<br/>epiphanyDate \t\t-> " . $this->epiphanyDate->format('jS F Y');
		echo "<br/>ordinaryTime1Starts \t-> " . $this->ordinaryTime1Starts->format('jS F Y');
		echo "<br/>lentStart \t\t-> " . $this->lentStart->format('jS F Y');
		echo "<br/>EastertideStarts \t-> " . $this->eastertideStarts->format('jS F Y');
		echo "<br/>ordinaryTime2Starts \t-> " . $this->ordinaryTime2Starts->format('jS F Y');
		echo "<br/>adventStart \t\t-> " . $this->adventStart->format('jS F Y');
		echo "<br/>christmastide1Start \t-> " . $this->christmastide1Start->format('jS F Y');

		echo '<hr/>';
	}
}
