<?php

/**
 * RomanCalendar 3.0
 * @author Br. Jayarathina Madharasan SDR
 *
 */
class RomanCalendarColor {

	private $RCYr;

	function __construct(RomanCalendarYear $RCYear) {
		$this->RCYr = $RCYear;
		
		foreach ( $this->RCYr->fullYear as $mnthNo => $mnth ) {
			foreach ( $mnth as $dateNo => $dats ) {
				$lastfeastCol = '';
				foreach ( $dats as $ids => $singleFeasts ) {
					$cd = $dats [$ids] ['code'];
					$tp = (isset ( $dats [$ids] ['type'] )) ? $dats [$ids] ['type'] : null;
					if ($tp !== 'OpMem') { // The proper color of an Optional Memorial is the color of the season.
							$lastfeastCol = $this->getColor ( $cd, $tp );
					}
					$this->RCYr->fullYear [$mnthNo] [$dateNo] [$ids] ['color'] = $lastfeastCol;
				}
			}
		}
	}

	/**
	 * Get color for a particular feast type or code
	 *
	 * @param string $feastCode        	
	 * @param string $feastType        	
	 * @return string
	 */
	private function getColor($feastCode, $feastType = null) {
		$feastClr = '*-*';
		$feastType = explode ( '-', $feastType );
		$feastType = $feastType [0];
		
		$feastClrr = array (
				// The proper color for Solemnities is white except Pentecost and Peter and Paul (Jun 29) in which cases it is red
				'Solemnity' => 'white',
				'Saints Peter and Paul, Apostles' => 'red',
				'EW08-Pentecost' => 'red',
				
				// The proper color of the Third Sunday of Advent and the Fourth Sunday of Lent is rose
				'AW03-0Sun' => 'rose',
				'LW04-0Sun' => 'rose',
				
				// The proper color for Feasts of the Lord is white except the Triumph of the Cross in which case it is red
				'Feast-Lord' => 'white',
				// 'Triumph of the Cross' => 'red',
				'Exaltation of the Holy Cross' => 'red',
				
				// The proper color of a Feast or a Memorial is white except for martyrs in which case it is red
				'Feast' => 'white',
				'Mem' => 'white',
				'martyr' => 'red',
				
				// The proper color for the Chair of Peter (Feast, Feb 22) and the Conversion of St. Paul (Feast, Jan 25) is white
				'Chair of Peter' => 'white',
				'Conversion of Paul' => 'white',
				
				// The proper colors of Holy Week
				'LW06-0Sun' => 'red', // Palm Sunday
				'LW06-4Thu' => 'white', // Holy Thursday
				'LW06-5Fri' => 'red', // Good Friday
				'LW06-6Sat' => 'white', // Easter Vigil
				                        
				// 'OpMem' => '*' //The proper color of an Optional Memorial is the color of the season.
				                        
				// The proper color of a Commemoration is the color of the season. As Commemorations only occur during Lent, their proper color is purple.
				'Commomeration' => 'purple',
				'All Souls' => 'purple' 
		);
		
		if (array_key_exists ( $feastCode, $feastClrr )) {
			$feastClr = $feastClrr [$feastCode];
		} elseif (array_key_exists ( $feastType, $feastClrr )) {
			$feastClr = $feastClrr [$feastType];
		} else {
			switch (substr ( $feastCode, 0, 2 )) {
				case 'AW' :
				case 'LW' :
					$feastClr = 'purple';
					break;
				case 'CW' :
				case 'EW' :
					$feastClr = 'white';
					break;
				case 'OW' :
					$feastClr = 'green';
					break;
			}
		}
		
		if (stripos ( $feastCode, 'martyr' ) !== false) {
			$feastClr = $feastClrr ['martyr'];
		}
		
		return $feastClr;
	}
}
