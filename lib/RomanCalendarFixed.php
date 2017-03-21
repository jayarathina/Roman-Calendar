<?php

/**
 * RomanCalendar 3.0
 * @author Br. Jayarathina Madharasan SDR
 * 
 */
class RomanCalendarFixed {
	private $dayRanks;
	private $RCYr;
	private $calName;
	function __construct(RomanCalendarYear $RCYear, $feastList, $calName) {
		$this->RCYr = $RCYear;
		$this->dayRanks = new RomanCalendarRanks ();
		$this->calName = $calName;
		
		/*
		$this->database = new medoo ( array (
				'database_type' => 'mysql',
				'database_name' => 'liturgy_romancalendar',
				'server' => 'localhost',
				'username' => 'root',
				'password' => '',
				'charset' => 'utf8' 
		) );
		*/
		$this->addSolemnityToYear ( $feastList );
		$this->addFeastToYear ( $feastList );
		$this->addMemoryToYear ( $feastList );
	}
	public function ff__get3($name) {
		return $this->RCYr->__get ( $name );
	}
	function addSolemnityToYear($FeastList) {
		foreach ( $FeastList as $feastDet ) {
			if (preg_match ( "/^Solemnity/", $feastDet ['feast_type'] ) !== 1)
				continue;
			
			$feastRank = $this->dayRanks->getRank ( $feastDet ['feast_type'] );
			
			$currentDay = $this->RCYr->fullYear [$feastDet ['feast_month']] [$feastDet ['feast_date']];
			$currentDayRank = $currentDay [0] ['rank'];
			
			if ($feastRank < $currentDayRank) {
				$this->RCYr->setDayCode ( $feastDet ['feast_month'], $feastDet ['feast_date'], $feastDet ['feast_code'], $feastDet ['feast_type'] );
			} else {
				// If a fixed date Solemnity occurs on a Sunday of Lent or Advent, the Solemnity is transferred to the following Monday.
				// This affects Joseph, Husband of Mary (Mar 19), Annunciation (Mar 25), and Immaculate Conception (Dec 8).
				
				$tempDate = new DateTime ( $this->RCYr->__get('currentYear') . '-' . $feastDet ['feast_month'] . '-' . $feastDet ['feast_date'] );
				
				if ($feastDet ['feast_date'] == 19 && preg_match ( "/^LW06/", $currentDay [0] ['code'] ) === 1) {
					// If Joseph, Husband of Mary (Mar 19) falls on Palm Sunday or during Holy Week, it is moved to the Saturday preceding Palm Sunday.
					// This Solemnity can never occur on Holy Sat
					$tempDate->modify ( 'last saturday' );
				} elseif ($feastDet ['feast_date'] == 25 && preg_match ( "/^LW06-0Sun/", $currentDay [0] ['code'] ) === 1) {
					// If the Annunciation (Mar 25) falls on Palm Sunday, it is celebrated on the Saturday preceding.
					$tempDate->modify ( 'last saturday' );
				} elseif ($feastDet ['feast_date'] == 25 && (preg_match ( "/^LW06|EW01/", $currentDay [0] ['code'] ) === 1)) {
					// If Annunciation falls during Holy Week or within the Octave of Easter, it is transferred to the Monday of the Second Week of Easter.
					// 2008, 2013, 2016
					
					$tempDate = new DateTime ( $this->RCYr->__get('currentYear') . '-03-21' );
					$tempDate->modify ( '+ ' . easter_days ( $this->RCYr->__get('currentYear') ) . ' days' ); // Easter Date
					$tempDate->modify ( '+ 8 days' );
				} elseif ($tempDate->format ( 'w' ) == 0) { // Make sure it is a sunday
					$tempDate->modify ( '+ 1 days' );
				} else {
					
					print_r ( $feastDet );
					print_r ( $currentDay );
					
					// TODO Solemities of Particular Calendars (Rank 4) should be implemented seperately
					// Those solemnities should be moved to the nearest day where there is a free space
					die ( 'Solemities of Particular Calendars overwriting is not yet implemented' );
				}
				
				$this->RCYr->setDayCode ( $tempDate->format ( 'n' ), $tempDate->format ( 'j' ), $feastDet ['feast_code'], $feastDet ['feast_type'] );
			}
		}
	}
	
	function addFeastToYear($FeastList) {
		foreach ( $FeastList as $feastDet ) {
			if (preg_match ( "/^Feast/", $feastDet ['feast_type'] ) !== 1)
				continue;

			$feastRank = $this->dayRanks->getRank ( $feastDet ['feast_type'] );
			$currentDayRank = $this->RCYr->fullYear [$feastDet ['feast_month']] [$feastDet ['feast_date']] [0] ['rank'];
			if ($feastRank < $currentDayRank) {
				$this->RCYr->setDayCode ( $feastDet ['feast_month'], $feastDet ['feast_date'], $feastDet ['feast_code'], $feastDet ['feast_type'] );
			} // else Feast is supressed
		}
	}
	function addMemoryToYear($memoryList) {

		// FIXME: in years when one obligatory memorial coincides with another obligatory memorial, both are to be considered optional
		foreach ( $memoryList as $memoryDate ) {
			
			if (preg_match ( "/^(Op)?Mem/", $memoryDate ['feast_type'] ) !== 1)
				continue;

			$currentDay = $this->RCYr->fullYear [$memoryDate ['feast_month']] [$memoryDate ['feast_date']];
			$currentDayRank = $currentDay [0] ['rank'];
			
			if ($currentDayRank > 3) {
				// TODO Optional memorials that occour between Dec17-Dec24, Dec-25-Jan1 or during Lent will become commomeration
				// If a fixed date Memorial or Optional Memorial falls within the Lenten season, it is reduced in rank to a Commemoration.
				if (preg_match ( "/^[LW]/", $currentDay [0] ['code'] ) === 1) {
					$memoryDate ['feastType'] = 'Commomeration';
				}
				
				$this->RCYr->addFeastToDate( $memoryDate ['feast_month'], $memoryDate ['feast_date'], $memoryDate ['feast_code'], $memoryDate ['feast_type'] );
			}
		}
		
	}

}