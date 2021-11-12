<?php

/**
 * RomanCalendar 3.0
 * @author Br. Jayarathina Madharasan SDR
 */
class RomanCalendarFixed {
	private $RCYr;
	function __construct(RomanCalendarYear $RCYear, $feastList) {
		$this->RCYr = $RCYear;
		$this->addSolemnityToYear ( $feastList );
		$this->addFeastToYear ( $feastList );
		$this->addMemoryToYear ( $feastList );
	}
	
	/**
	 * Adds the given Solemnity to RomanCalendarYear variable
	 *
	 * @param array $FeastList
	 */
	function addSolemnityToYear($FeastList) {
		foreach ( $FeastList as $feastDet ) {
			if (preg_match ( "/^Solemnity/", $feastDet ['feast_type'] ) !== 1)
				continue;
			
			$feastRank = RomanCalendarRanks::getRank ( $feastDet ['feast_type'] );
			
			$currentDay = $this->RCYr->fullYear [$feastDet ['feast_month']] [$feastDet ['feast_date']];
			$currentDayRank = $currentDay [0] ['rank'];
			
			if ($feastRank < $currentDayRank) {
				$this->RCYr->setDayCode ( $feastDet ['feast_month'], $feastDet ['feast_date'], $feastDet ['feast_code'], $feastDet ['feast_type'] );
			} else {
				// Conflict is between ranks 1 to 4; See RomanCalendarRanks.php for ranks
				
				// If a fixed date Solemnity occurs on a Sunday of Lent or Advent, the Solemnity is transferred to the following Monday.
				// This affects Joseph, Husband of Mary (Mar 19), Annunciation (Mar 25), and Immaculate Conception (Dec 8).
				
				$tempDate = new DateTime ( $this->RCYr->__get ( 'currentYear' ) . '-' . $feastDet ['feast_month'] . '-' . $feastDet ['feast_date'] );
				
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
					$tempDate = new DateTime ( $this->RCYr->__get ( 'currentYear' ) . '-03-21' );
					$tempDate->modify ( '+ ' . easter_days ( $this->RCYr->__get ( 'currentYear' ) ) . ' days' ); // Easter Date
					$tempDate->modify ( '+ 8 days' );
				} elseif ($feastDet['feast_code'] == 'Birth of Saint John the Baptist' && $currentDay [0] ['code'] == 'OW00-SacredHeart' && $this->RCYr->__get ( 'currentYear' ) == 2022 ){
				    // Nativity of St. John the Baptist and the Feast of the Sacred Heart clashes in 2022.
				    // CFD has determined that on June 24 the Sacred Heart should be celebrated,
				    // and the Nativity of St. John the Baptist on the 23rd
				    // This seems to be an ad hoc exception from the general rules.
				    // Usually transfer is being made to the day following if available.
				    // See http://www.cultodivino.va/content/cultodivino/it/documenti/responsa-ad-dubia/2020/de-calendario-liturgico-2022.html
				    $tempDate->modify ( '- 1 days' );
				    $currentDay_ = $this->RCYr->fullYear [$tempDate->format ( 'n' )] [$tempDate->format ( 'j' )];
				} else {

					// Other solemnities that clash
					// - with Sundays of Lent or Advent or Eastertide
					// - with each other [General Calendar: 24 June 2022, Birth of St. John the Baptist and Sacred Heart](This case is addressed in previous 'else' section)
					// - Particular Calendar (Rank 4) and General Calendar
					// These should be moved to the nearest ferial day

					do {
						// Go to next day and check for its rank
						$tempDate->modify ( '+ 1 days' );
						$currentDay_ = $this->RCYr->fullYear [$tempDate->format ( 'n' )] [$tempDate->format ( 'j' )];
					} while ( $feastRank > $currentDay_ [0] ['rank'] );
				}
				
				$this->RCYr->setDayCode ( $tempDate->format ( 'n' ), $tempDate->format ( 'j' ), $feastDet ['feast_code'], $feastDet ['feast_type'] );
			}
		}
	}
	
	/**
	 * Adds the given Feast to RomanCalendarYear variable.
	 * Feast type (feast_type) could be either feast or Feasts of the Lord
	 */
	function addFeastToYear($FeastList) {
		foreach ( $FeastList as $feastDet ) {
			if (preg_match ( "/^Feast/", $feastDet ['feast_type'] ) !== 1)
				continue;
			$feastRank = RomanCalendarRanks::getRank ( $feastDet ['feast_type'] );
			$currentDayRank = $this->RCYr->fullYear [$feastDet ['feast_month']] [$feastDet ['feast_date']] [0] ['rank'];
			if ($feastRank < $currentDayRank) {
				$this->RCYr->setDayCode ( $feastDet ['feast_month'], $feastDet ['feast_date'], $feastDet ['feast_code'], $feastDet ['feast_type'] );
			} // else Feast is supressed
		}
	}
	
	/**
	 * Adds the given Memory or optional memory to RomanCalendarYear variable.
	 */
	function addMemoryToYear($memoryList) {
		foreach ( $memoryList as $memoryDate ) {
			
			if (preg_match ( "/^(Op)?Mem/", $memoryDate ['feast_type'] ) !== 1)
				continue;
			
			$currentDay = &$this->RCYr->fullYear [$memoryDate ['feast_month']] [$memoryDate ['feast_date']];
			$currentDayRank = $currentDay [0] ['rank'];
			
			if ($currentDayRank <= 9) // Feasts and Solemnities
				continue;
			
			if (isset ( $currentDay [1] ['type'] )) {
				$feastAddedRank = RomanCalendarRanks::getRank ( $currentDay [1] ['type'] );
				$feastYetToAddRank = RomanCalendarRanks::getRank ( $memoryDate ['feast_type'] );
				
				// clash between mem and mem-Mary
				// following the liturgical tradition of pre-eminence amongst persons, the Memorial of the Blessed Virgin Mary is to prevail
				if ($feastAddedRank == 10.2 && $feastYetToAddRank == 10.1) {
					unset ( $currentDay [1] );
					$currentDay = array_values ( $currentDay );
				}
			}
			
			if (preg_match ( "/^[LW|AW05]/", $currentDay [0] ['code'] ) === 1) {
				// Optional memorials that occour between Dec17-Dec24, Dec-25-Jan1 or during Lent will become commomeration
				// If a fixed date Memorial or Optional Memorial falls within the Lenten season, it is reduced in rank to a Commemoration.
				$memoryDate ['feast_type'] = 'Commomeration';
			}

			$this->RCYr->addFeastToDate ( $memoryDate ['feast_month'], $memoryDate ['feast_date'], $memoryDate ['feast_code'], $memoryDate ['feast_type'] );
		}
	}
}