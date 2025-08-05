<?php
namespace RomanCalendar;
/**
 * RomanCalendar 5.0
 * @author Br. Jayarathina Madharasan SDB
 * @created 2025-08-04
 * @updated 2025-08-04
 * @description This class generates the Roman Catholic Calendar for a given year.
 * @version 5.0
 * @license MIT
 * 
 */ 

include_once 'RomanCalendarUtility.php';

class RomanCalendarMovable{
	private $year;
    private $fullYear;
	private $seasonLimits = []; // Array to hold the beginning dates of each liturgical season
	/*
	 * For each ferial weekday (with no feast) a code is generated in the following syntax:
	 * `<SEASON CODE><WEEK NUMBER>-<DAY NUMBER><DAY NAME>`. 
	 * 
	 * They stand for the following:
	 * `SEASON CODE` can be one of the following:
	 * EW – Easter Week
	 * OW – Ordinary Week
	 * AW – Advent Week
	 * LW – Lent Week
	 * CW – Christmas week
	 * 
	 * `WEEK NUMBER` is the number of week in that season. (Length is 2 chars). 
	 * This may have some special cases like the days between Ash Wednesday and first Sunday of lent is counted as week 0 of lent.
	 * Similarly days between Dec 17 and Dec 24 is counted as week 5 of Advent.
	 * Similar rule applies to the weeks before and after epiphany too.
	 * 
	 * `DAY NUMBER DAY NAME` gives the number and three letter abbreviation of the weekday within that week. (0Sun, 1Mon, 2Tue etc.,). This is to help in sorting and readability.
	 */
	function __construct(int $year, array $options) {

        $this->year = $year;
        $this->fullYear = RomanCalendarUtility::initializeCalendar($year);
        $this->seasonLimits = RomanCalendarUtility::getSeasonLimits($year,  $options['epiphanyOnSunday']);

		$this->generateAdvent();
		$this->generateChristmastide1();
		$this->generateChristmastide2();
		$this->generateLent();
		$this->generateEastertide($options['ascensionOnSunday']);
		$this->generateOrdinaryTime1();
		$this->generateOrdinaryTime2($options['corpusChristiOnSunday']);

		print_r($this->fullYear);
	}

	/**
	 * Function to Generate Advent season
	 */
	private function generateAdvent() {
		$code = 'AW';

		// We are filling upto the christmas (and not just upto 17 dec) because sundays have to be filled properly. There can be atmost two sundays after dec 16.
		$this->fillInWeek($this->seasonLimits['advent'], $this->seasonLimits['christmastide1'], $code);

		// Final week of Advent Dec 17-24; For Programming sake we call it the 5th week
		$AW05 = new \DateTime($this->year . '-12-17');
		while ($AW05 < $this->seasonLimits['christmastide1']) {
			if ($AW05->format('w') != 0) { 
				// If it is not a Sunday, set the code
				// Sundays in this week are not counted as a week 5, but as week 4.
				$this->setDayCode($AW05, $code . $AW05->format('05-Mj'));
			}
			$AW05->modify('+1 day');
		}
	}

	/**
	 * Function to generate Christmas season from December 25 - 31
	 */
	private function generateChristmastide1() {
		$code = 'CW01';

		// Days after (not including) Christmas till 31 Dec
		for ($i = 1; $i < 7; $i++) {
			$CW01Date = $this->seasonLimits['christmastide1']->modify("+$i day");
			$this->setDayCode($CW01Date, $code . $CW01Date->format('-Mj'));
		}

		// If christmas is on a sunday then holyfamily is on next friday, ie., Dec 30
		$HolyFamilyDate = (0 == $this->seasonLimits['christmastide1']->format('w')) ? $this->seasonLimits['christmastide1']->modify('next Friday') : $this->seasonLimits['christmastide1']->modify('next Sunday');
		$this->setDayCode($HolyFamilyDate, $code . '-HolyFamily');
	}

	/**
	 * Function to generate Christmas season from Jan 1 - Baptism
	 */
	private function generateChristmastide2() {
		$code = 'CW';

		$this->setDayCode($this->seasonLimits['epiphany'], $code . '03-Epiphany');
		$this->setDayCode($this->seasonLimits['baptism'], 'CW04-Baptism');

		// Days before Epiphany - CW02
		for ($i = 1;; $i++) {
			$CW02Date = $this->seasonLimits['christmastide2']->modify("+$i day");
			if ($CW02Date == $this->seasonLimits['epiphany'])
				break;

			if ($CW02Date->format('w') == 0) {
				// If a Sunday occurs during this period, it is called the "Second Sunday of Christmas".
				$this->setDayCode($CW02Date, $code . '02-0Sun');
			} else {
				$this->setDayCode($CW02Date, $code . $CW02Date->format('02-Mj'));
			}
		}

		// the days of the week following Epiphany are called "n-th day after Epiphany" - CW03
		for ($i = 1;; $i++) {
			$CW03Date = $this->seasonLimits['epiphany']->modify("+$i day");
			if ($CW03Date == $this->seasonLimits['baptism'])
				break;
			$this->setDayCode($CW03Date, $code . "03-Day$i");
		}
	}

	/**
	 * Function to generate Ordinary season before lent
	 */
	private function generateOrdinaryTime1() {
		$this->fillInWeek($this->seasonLimits['ordinaryTime1'], $this->seasonLimits['lent'], 'OW', 1);
	}

	/**
	 * Function to generate Lent
	 */
	private function generateLent() {
		$this->fillInWeek($this->seasonLimits['lent'], $this->seasonLimits['easter'], 'LW');
	}

	/**
	 * Function to generate Pascha
	 */
	private function generateEastertide(bool $ascension_on_sunday) {
		$this->fillInWeek($this->seasonLimits['easter'], $this->seasonLimits['ordinaryTime2'], 'EW');

		if ($ascension_on_sunday) {
			$this->setDayCode($this->seasonLimits['easter']->modify('+42 days'), 'EW07-Ascension');
		} else {
			$this->setDayCode($this->seasonLimits['easter']->modify('+39 days'), 'EW07-Ascension');
		}

		$this->setDayCode($this->seasonLimits['easter']->modify('+49 days'), 'EW08-Pentecost');
	}

	/**
	 * Function to generate Ordinary season after Easter
	 */
	private function generateOrdinaryTime2(bool $corpuschristi_on_sunday) {
		$code = 'OW';

		// First Calculate the Week Number
		$trinitySunday = $this->seasonLimits['ordinaryTime2']->modify('next sunday'); // Sunday after pentecost
		$ChristKing = $this->seasonLimits['advent']->modify('last sunday'); // Last sunday of ordinary season

		$wk = $trinitySunday->diff($ChristKing); // Number of days between these two
		$wk = 33 - ((intval($wk->format('%a')) / 7)); // 33 - (No of Days / 7)

		// Fill it up Calculate the Week Number
		$this->fillInWeek($this->seasonLimits['ordinaryTime2'], $this->seasonLimits['advent'], $code, $wk);

		// Add Special Solemnities and Feasts in the Ordinary Season
		$this->setDayCode($trinitySunday, 'OW00-Trinity');

		$corpusChristi = new \DateTime($trinitySunday->format(\DateTime::ATOM) . ' next thursday');
		if ($corpuschristi_on_sunday) {
			$corpusChristi->modify('next sunday');
		}
		$this->setDayCode($corpusChristi, 'OW00-CorpusChristi');

		// We are first moving to sunday then to next friday because if corpuschristi falls on a thuresday,
		// next friday would be the next day, which is not the correct date for sacred heart
		$this->setDayCode($corpusChristi->modify('next sunday +5 days'), 'OW00-SacredHeart');
	}

	/**
	 * To fill in the week code with proper season prefix from $startDate till a day before $endDate specified.
	 *
	 * @param \DateTimeImmutable $startDate
	 *        	- Start of the season
	 * @param \DateTimeImmutable $endDate
	 *        	- Start of the next season. Only dates before this day will be tagged. (i.e., this day will not be tagged).
	 * @param String $code
	 *        	- The season code. (AW-Advent, CW-Christmas, LW-Lent, EW-Easter, OW-Ordinary)
	 * @param int $wk
	 *        	- The serial number for the first week in the specified range. Will be useful especially for ordinary season 2
	 */
	private function fillInWeek(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate, $code, $wk = 0) {
		$startDt = \DateTime::createFromImmutable($startDate);
		$endDt = \DateTime::createFromImmutable($endDate);

		while ($startDt < $endDt) {
			if ($startDt->format('w') == 0) { // If it is a sunday
				$wk++;
			}

			$cd = $code . str_pad($wk, 2, '0', STR_PAD_LEFT) . $startDt->format('-wD');
			$this->setDayCode($startDt, $cd);
			$startDt->modify('+1 day');
		}
	}

	/**
	 * Tag a given date with its code
	 *
	 * @param DateTime $cDate
	 *        	- Date to be tagged
	 * @param string $cd
	 *        	- Tag
	 */
	private function setDayCode($cDate, $cd) {
		$mth = $cDate->format('n');
		$day = $cDate->format('j');

		$this->fullYear[$mth][$day][0]['code'] = $cd;
		$this->fullYear[$mth][$day][0]['rank'] = RomanCalendarUtility::getRank($cd);

		$this->fullYear[$mth][$day][0]['type'] = match ($this->fullYear[$mth][$day][0]['rank']) {
			1, 2, 2.4, 3.1, 4.1, 4.2, 4.3 => 'Solemnity',
			5 => 'Feast-Lord',
			7 => 'Feast',
			default => false
		};
		if ($this->fullYear[$mth][$day][0]['type'] === false)
			unset($this->fullYear[$mth][$day][0]['type']);
	}
}
