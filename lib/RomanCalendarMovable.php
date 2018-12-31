<?php

/**
 * RomanCalendar 3.0
 * @author Br. Jayarathina Madharasan SDR
 *
 * TODO All of the date vars should be DateTimeImmutable() class. Change this when PHP 5.5 gets more than 80% market share
 */
class RomanCalendarMovable {
	private $dayRanks, $RCYr;
	function __construct(RomanCalendarYear $RCYear) {
		$this->RCYr = $RCYear;
		
		$this->dayRanks = new RomanCalendarRanks ();
		
		$this->generateAdvent ();
		$this->generateChristmastide2 ();
		$this->generateChristmastide1 ();
		$this->generateLent ();
		$this->generateEastertide ();
		$this->generateOrdinaryTime1 ();
		$this->generateOrdinaryTime2 ();
	}
	public function __get($name) {
		return $this->RCYr->__get ( $name );
	}
	
	/**
	 * Function to Generate Advent season
	 */
	private function generateAdvent() {
		$code = 'AW';
		
		// We are filling upto the christmas (and not just upto 17 dec) because sundays have to be filled properly. There can be atmost two sundays after dec 16.
		$this->fillInWeek ( $this->adventStart, $this->christmastide1Start, $code );
		
		$AW05 = new DateTime ( $this->currentYear . '-12-17' ); // Final week of Advent Dec 17-24; For Programming sake we call it the 5th week
		while ( $AW05 < $this->christmastide1Start ) {
			if ($AW05->format ( 'w' ) > 0) {
				$cd = $code . $AW05->format ( '05-Mj' );
				$this->setDayCode ( $AW05, $cd );
			}
			$AW05->modify ( '+1 day' );
		}
	}
	
	/**
	 * Function to generate Christmas season from December 25 - 31
	 */
	private function generateChristmastide1() {
		$code = 'CW01';
		
		// The code returned here for Dec 25 is wrong. It will be superseded later in movable feast class.
		$tempDate = clone $this->christmastide1Start;
		$tempDt2 = new DateTime ( ($this->currentYear + 1) . '-01-01' );
		
		while ( $tempDate < $tempDt2 ) {
			$this->setDayCode ( $tempDate, $code . $tempDate->format ( '-Mj' ) );
			$tempDate->modify ( '+1 day' );
		}
		
		// If christmas is on a sunday then holyfamily is on next friday, ie., Dec 30
		$HolyFamilyDate = clone $this->christmastide1Start;
		(0 == $HolyFamilyDate->format ( 'w' )) ? $HolyFamilyDate->modify ( 'next Friday' ) : $HolyFamilyDate->modify ( 'next Sunday' );
		
		$this->setDayCode ( $HolyFamilyDate, $code . '-HolyFamily' );
	}
	
	/**
	 * Function to generate Christmas season from Jan 1 - Baptism
	 */
	private function generateChristmastide2() {
		$code = 'CW';
		
		// The code returned here for Jan 1 is wrong. It will be superseded later in movable feast class.
		
		$tempDate = clone $this->christmastide2Start;
		// $this->setDayCode ( $tempDate, 'Mary, Mother of God' );
		// $tempDate->modify ( '+1 day' );
		
		while ( $tempDate < $this->epiphanyDate ) { // Days before Epiphany - CW02
			if ($tempDate->format ( 'w' ) == 0) {
				// If a Sunday occurs during this period, it is called the "Second Sunday of Christmas".
				$this->setDayCode ( $tempDate, $code . '02-0Sun' );
			} else {
				$this->setDayCode ( $tempDate, $code . $tempDate->format ( '02-Mj' ) );
			}
			$tempDate->modify ( '+1 day' );
		}
		
		$this->setDayCode ( $this->epiphanyDate, $code . '03-Epiphany' );
		
		$baptismDate = clone $this->ordinaryTime1Starts;
		$baptismDate->modify ( '-1 days' ); // Baptism is a day before Ordinary Time 1
		$this->setDayCode ( $baptismDate, 'CW04-Baptism' );
		
		$tempDate = clone $this->epiphanyDate;
		$tempDate->modify ( '+1 day' );
		$dayCnt = 1;
		while ( $tempDate < $baptismDate ) {
			// the days of the week following Epiphany are called "n-th day after Epiphany" - CW03
			$this->setDayCode ( $tempDate, $code . '03-Day' . ($dayCnt ++) );
			$tempDate->modify ( '+1 day' );
		}
	}
	
	/**
	 * Function to generate Ordinary season before lent
	 */
	private function generateOrdinaryTime1() {
		$this->fillInWeek ( $this->ordinaryTime1Starts, $this->lentStart, 'OW', 1 );
	}
	
	/**
	 * Function to generate Lent
	 */
	private function generateLent() {
		$this->fillInWeek ( $this->lentStart, $this->eastertideStarts, 'LW' );
	}
	
	/**
	 * Function to generate Pascha
	 */
	private function generateEastertide() {
		$this->fillInWeek ( $this->eastertideStarts, $this->ordinaryTime2Starts, 'EW' );
		
		$tempDate = clone $this->eastertideStarts;
		($this->calcConfig ['feastSettings'] ['ASCENSION_ON_A_SUNDAY'] == true) ? $tempDate->modify ( '+42 days' ) : $tempDate->modify ( '+39 days' );
		$this->setDayCode ( $tempDate, 'EW07-Ascension' );
		
		$tempDate = clone $this->eastertideStarts;
		$tempDate->modify ( '+49 days' );
		$this->setDayCode ( $tempDate, 'EW08-Pentecost' );
	}
	
	/**
	 * Function to generate Ordinary season after Easter
	 */
	private function generateOrdinaryTime2() {
		$code = 'OW';
		
		// First Calculate the Week Number
		$tempDate1 = clone $this->ordinaryTime2Starts;
		$tempDate1->modify ( 'next sunday' ); // Sunday after pentacost
		
		$tempDate2 = clone $this->adventStart;
		$tempDate2->modify ( 'last sunday' ); // Last sunday of ordinary season
		
		$interval = $tempDate1->diff ( $tempDate2 ); // Number of days between these two
		$wk = 33 - ((intval ( $interval->format ( '%a' ) ) / 7)); // 33 - (No of Days / 7)
		                                                          
		// Now Fill it up Calculate the Week Number
		$this->fillInWeek ( $this->ordinaryTime2Starts, $this->adventStart, $code, $wk );
		
		// Add Special Solemnities and Feasts in the Ordinary Season
		$tempDate1 = clone $this->ordinaryTime2Starts;
		
		$tempDate1->modify ( 'next sunday' );
		$this->setDayCode ( $tempDate1, 'OW00-Trinity' );
		
		($this->calcConfig ['feastSettings'] ['CORPUSCHRISTI_ON_A_SUNDAY'] == true) ? $tempDate1->modify ( 'next sunday' ) : $tempDate1->modify ( 'next thursday' );
		$this->setDayCode ( $tempDate1, 'OW00-CorpusChristi' );
		
		$tempDate1->modify ( 'next monday' ); // We are first moving to monday then to next friday because if corpuschristi falls on a thuresday, next friday would be the next day, which is not the correct date for sacred heart
		$tempDate1->modify ( 'next friday' );
		
		$this->setDayCode ( $tempDate1, 'OW00-SacredHeart' );
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
	private function fillInWeek($startDate, $endDate, $code, $wk = 0) {
		$startDt = clone $startDate;
		$endDt = clone $endDate;
		
		while ( $startDt < $endDt ) {
			if ($startDt->format ( 'w' ) == 0) { // If it is a sunday
				$wk ++;
			}
			
			$cd = $code . str_pad ( $wk, 2, '0', STR_PAD_LEFT ) . $startDt->format ( '-wD' );
			$this->setDayCode ( $startDt, $cd );
			$startDt->modify ( '+1 day' );
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
		$mth = $cDate->format ( 'n' );
		$day = $cDate->format ( 'j' );
		
		$this->RCYr->fullYear [$mth] [$day] [0] ['code'] = $cd;
		$this->RCYr->fullYear [$mth] [$day] [0] ['rank'] = $this->dayRanks->getRank ( $cd );
		
		switch ($this->RCYr->fullYear [$mth] [$day] [0] ['rank']) {
			case 1 :
			case 2 :
			case 2.4 :
			case 3 :
			case 4.1 :
			case 4.2 :
			case 4.3 :
				$this->RCYr->fullYear [$mth] [$day] [0] ['type'] = 'Solemnity';
				break;
			
			case 5 :
				$this->RCYr->fullYear [$mth] [$day] [0] ['type'] = 'Feast-Lord';
				break;
			
			case 7 :
				$this->RCYr->fullYear [$mth] [$day] [0] ['type'] = 'Feast';
				break;
			default :
				break;
		}
	}
}