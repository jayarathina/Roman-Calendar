<?php

/**
 * RomanCalendar 4.0
 * @author Br. Jayarathina Madharasan SDR
 */
require_once 'RomanCalendarYear.php';
class RomanCalendarMovable extends RomanCalendarYear
{

	/*
	 * For each day the name of the feast (if any) acts as a unique identifier for that feast. For a ferial weekday (with no feast) a code is generated in the following syntax: `<SEASON CODE><WEEK NUMBER>-<DAY NUMBER><DAY NAME>`. They stand for the following:
	 * `SEASON CODE` can be one of the following:
	 * EW – Easter Week
	 * OW – Ordinary Week
	 * AW – Advent Week
	 * LW – Lent Week
	 * CW – Christmas week
	 * `WEEK NUMBER` is the number of week in that season. (Length is 2 chars). This may have some special cases like the days between Ash Wednesday and first Sunday of lent is counted as week 0 of lent. Similarly days between Dec 17 and Dec 24 is counted as week 5 of Advent. Similar rule applies to the weeks before and after epiphany too. This is done for easier calculation purposes, since for these days week number does not appear in the title of the day.
	 * `DAY NUMBER DAY NAME` gives the number and three letter abbreviation of the weekday within that week. (0Sun, 1Mon, 2Tue etc.,). This is to help in sorting and readability.
	 */
	function __construct($currentYear)
	{
		parent::__construct($currentYear);

		$this->generateAdvent();
		$this->generateChristmastide1();
		$this->generateChristmastide2();
		$this->generateLent();
		$this->generateEastertide();
		$this->generateOrdinaryTime1();
		$this->generateOrdinaryTime2();
	}

	/**
	 * Function to Generate Advent season
	 */
	private function generateAdvent()
	{
		$code = 'AW';

		// We are filling upto the christmas (and not just upto 17 dec) because sundays have to be filled properly. There can be atmost two sundays after dec 16.
		$this->fillInWeek($this->adventStart, $this->christmastide1Start, $code);

		// Final week of Advent Dec 17-24; For Programming sake we call it the 5th week
		$AW05 = new DateTime($this->currentYear . '-12-17');
		while ($AW05 < $this->christmastide1Start) {
			if ($AW05->format('w') > 0) {
				$this->setDayCode($AW05, $code . $AW05->format('05-Mj'));
			}
			$AW05->modify('+1 day');
		}
	}

	/**
	 * Function to generate Christmas season from December 25 - 31
	 */
	private function generateChristmastide1()
	{
		$code = 'CW01';

		// Days after (not including) Christmas till 31 Dec
		for ($i = 1; $i < 7; $i++) {
			$CW01Date = $this->christmastide1Start->modify("+$i day");
			$this->setDayCode($CW01Date, $code . $CW01Date->format('-Mj'));
		}

		// If christmas is on a sunday then holyfamily is on next friday, ie., Dec 30
		$HolyFamilyDate = (0 == $this->christmastide1Start->format('w')) ? $this->christmastide1Start->modify('next Friday') : $this->christmastide1Start->modify('next Sunday');
		$this->setDayCode($HolyFamilyDate, $code . '-HolyFamily');
	}

	/**
	 * Function to generate Christmas season from Jan 1 - Baptism
	 */
	private function generateChristmastide2()
	{
		$code = 'CW';

		$this->setDayCode($this->epiphanyDate, $code . '03-Epiphany');

		$baptismDate = $this->ordinaryTime1Starts->modify('-1 days'); // Baptism is a day before Ordinary Time 1
		$this->setDayCode($baptismDate, 'CW04-Baptism');

		// Days before Epiphany - CW02
		for ($i = 1;; $i++) {

			$CW02Date = $this->christmastide2Start->modify("+$i day");
			if ($CW02Date == $this->epiphanyDate)
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
			$CW03Date = $this->epiphanyDate->modify("+$i day");
			if ($CW03Date == $baptismDate)
				break;
			$this->setDayCode($CW03Date, $code . "03-Day$i");
		}
	}

	/**
	 * Function to generate Ordinary season before lent
	 */
	private function generateOrdinaryTime1()
	{
		$this->fillInWeek($this->ordinaryTime1Starts, $this->lentStart, 'OW', 1);
	}

	/**
	 * Function to generate Lent
	 */
	private function generateLent()
	{
		$this->fillInWeek($this->lentStart, $this->eastertideStarts, 'LW');
	}

	/**
	 * Function to generate Pascha
	 */
	private function generateEastertide()
	{
		$this->fillInWeek($this->eastertideStarts, $this->ordinaryTime2Starts, 'EW');

		if (ASCENSION_ON_A_SUNDAY) {
			$this->setDayCode($this->eastertideStarts->modify('+42 days'), 'EW07-Ascension');
		} else {
			$this->setDayCode($this->eastertideStarts->modify('+39 days'), 'EW07-Ascension');
		}

		$this->setDayCode($this->eastertideStarts->modify('+49 days'), 'EW08-Pentecost');
	}

	/**
	 * Function to generate Ordinary season after Easter
	 */
	private function generateOrdinaryTime2()
	{
		$code = 'OW';

		// First Calculate the Week Number
		$trinitySunday = $this->ordinaryTime2Starts->modify('next sunday'); // Sunday after pentecost
		$ChristKing = $this->adventStart->modify('last sunday'); // Last sunday of ordinary season

		$wk = $trinitySunday->diff($ChristKing); // Number of days between these two
		$wk = 33 - ((intval($wk->format('%a')) / 7)); // 33 - (No of Days / 7)

		// Fill it up Calculate the Week Number
		$this->fillInWeek($this->ordinaryTime2Starts, $this->adventStart, $code, $wk);

		// Add Special Solemnities and Feasts in the Ordinary Season
		$this->setDayCode($trinitySunday, 'OW00-Trinity');

		$corpusChristiSunday = new DateTime($trinitySunday->format(DateTime::ATOM) . ' next thursday');
		if (!CORPUSCHRISTI_ON_A_SUNDAY) {
			$corpusChristiSunday->modify('next sunday');
		}
		$this->setDayCode($corpusChristiSunday, 'OW00-CorpusChristi');

		// We are first moving to sunday then to next friday because if corpuschristi falls on a thuresday,
		// next friday would be the next day, which is not the correct date for sacred heart
		$this->setDayCode($corpusChristiSunday->modify('next sunday +5 days'), 'OW00-SacredHeart');
	}

	/**
	 * To fill in the week code with proper season prefix from $startDate till a day before $endDate specified.
	 *
	 * @param DateTime $startDate
	 *        	- Start of the season
	 * @param DateTime $endDate
	 *        	- Start of the next season. Only dates before this day will be tagged. (i.e., this day will not be tagged).
	 * @param String $code
	 *        	- The season code. (AW-Advent, CW-Christmas, LW-Lent, EW-Easter, OW-Ordinary)
	 * @param int $wk
	 *        	- The serial number for the first week in the specified range. Will be useful especially for ordinary season 2
	 */
	private function fillInWeek($startDate, $endDate, $code, $wk = 0)
	{
		$startDt = DateTime::createFromImmutable($startDate);
		$endDt = DateTime::createFromImmutable($endDate);

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
	private function setDayCode($cDate, $cd)
	{
		$mth = $cDate->format('n');
		$day = $cDate->format('j');

		$this->fullYear[$mth][$day][0]['code'] = $cd;
		$this->fullYear[$mth][$day][0]['rank'] = RomanCalendarRanks::getRank($cd);

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
