<?php
/**
 * RomanCalendar 3.0
 * @author Br. Jayarathina Madharasan SDR
 *
 */
class RomanCalendarYear {
	public $fullYear;
	private $currentYear, $calcConfig;
	private $adventStart, $christmastide1Start, $epiphanyDate, $christmastide2Start, $lentStart, $eastertideStarts, $ordinaryTime1Starts, $ordinaryTime2Starts;
	function __construct($year = null, $settings) {
		
		$this->currentYear = is_numeric ( $year ) ? $year : date ( "Y" );
		
		$this->calcConfig = $settings;
		
		for($mnth = 1; $mnth <= 12; $mnth ++) {
			$days_in_month = cal_days_in_month ( CAL_GREGORIAN, $mnth, $this->currentYear );
			$this->fullYear [$mnth] = array_fill ( 1, $days_in_month, array () );
		}
		$this->generateSeasonLimits ();
	}
	
	/**
	 * Sets the start dates of various seasons of the liturgical year.
	 */
	private function generateSeasonLimits() {
		$fullYear = &$this->fullYear;
		$curYear = $this->currentYear;
		
		$this->adventStart = new DateTime ( "last thu of Nov $curYear" ); // Next sunday is advent week 1
		$this->adventStart->modify ( 'next sunday' );
		
		$this->christmastide1Start = new DateTime ( $curYear . '-12-25' );
		$this->christmastide2Start = new DateTime ( $curYear . '-01-01' );
		
		$baptismDate = new DateTime ();
		
		if ($this->calcConfig ['EPIPHANY_ON_A_SUNDAY'] == true) {
			
			// Epiphany is celebrated on the Sunday occurring from Jan. 2 through Jan. 8 (Both inclusive).
			$this->epiphanyDate = new DateTime ( $curYear . '-01-01' );
			$this->epiphanyDate->modify ( 'next sunday' );
			
			$baptismDate = clone $this->epiphanyDate;
			if ($this->epiphanyDate->format ( 'j' ) > 6) {
				// If Epiphany occurs on Jan 7 or Jan 8, then the Baptism of the Lord (Ordinary Time) is the next day
				$baptismDate->modify ( '+1 days' );
			} else {
				// If Epiphany occurs on or before Jan 6, the Sunday following Epiphany is the Baptism of the Lord (Ordinary Time)
				$baptismDate->modify ( 'next sunday' );
			}
		} else {
			// Epiphany is celebrated on Jan 6 The Baptism of the Lord (ordinaryTime1Starts) occurs on the Sunday following Jan 6
			$this->epiphanyDate = new DateTime ( $curYear . '-01-06' );
			$baptismDate = clone $this->epiphanyDate;
			$baptismDate->modify ( 'next sunday' );
		}
		$baptismDate->modify ( '+1 days' ); // Ordinary Times Starts day after baptism
		$this->ordinaryTime1Starts = clone $baptismDate;
		
		$this->eastertideStarts = new DateTime ( $curYear . '-03-21' );
		$this->eastertideStarts->modify ( '+ ' . easter_days ( $curYear ) . ' days' );
		
		$this->lentStart = clone $this->eastertideStarts;
		$this->lentStart->modify ( '-46 days' );
		
		$this->ordinaryTime2Starts = clone $this->eastertideStarts;
		$this->ordinaryTime2Starts->modify ( '+50 days' );
	}
	
	/**
	 * Function to get season limits dates
	 */
	public function __get($name) {
		return isset ( $this->$name ) ? $this->$name : null;
	}
	
	/**
	 * Test Function for checking the generated season's dates
	 */
	public function printDates() {
		echo '<hr/>' . $this->currentYear;
		
		echo "<br/>christmastide2Start \t-> " . $this->christmastide2Start->format ( 'jS F Y' );
		echo "<br/>epiphanyDate \t\t-> " . $this->epiphanyDate->format ( 'jS F Y' );
		echo "<br/>ordinaryTime1Starts \t-> " . $this->ordinaryTime1Starts->format ( 'jS F Y' );
		echo "<br/>lentStart \t\t-> " . $this->lentStart->format ( 'jS F Y' );
		echo "<br/>EastertideStarts \t-> " . $this->eastertideStarts->format ( 'jS F Y' );
		echo "<br/>ordinaryTime2Starts \t-> " . $this->ordinaryTime2Starts->format ( 'jS F Y' );
		echo "<br/>adventStart \t\t-> " . $this->adventStart->format ( 'jS F Y' );
		echo "<br/>christmastide1Start \t-> " . $this->christmastide1Start->format ( 'jS F Y' );
		
		echo '<hr/>';
	}
	
	function printYear() {
		$rows = '';
		
		foreach ( $this->fullYear as $month => $value ) {
			foreach ( $value as $days => $feasts ) {
				$tempDt2 = new DateTime ( "{$this->currentYear}-$month-$days" );
				
				foreach ( $feasts as $fet ) {
					$rows .= '<tr>';
					$rows .= '<td>' . $tempDt2->format ( 'j F Y' ) . '</td>';
					$rows .= '<td>' . $fet ['code'] . '</td>';
					$rows .= '<td>' . $fet ['rank'] . '</td>';
					$rows .= '</tr>';
				}
			}
		}
		
		echo "<table>$rows</table>";
	}




	/**
	 * Tag a given date and month with its code and append it to current date.
	 * (If there is more than one commomeration)
	 *
	 * @param DateTime $cDate
	 *        	- Date to be tagged
	 * @param string $cd
	 *        	- Tag
	 * @param int $rank
	 *        	- Rank of the day
	 */
	function addFeastToDate($mth, $day, $cd, $type) {
		$dayFeast = array ();
		
		$dayRanks = new RomanCalendarRanks ();
	
		$dayFeast ['code'] = $cd;
		$dayFeast ['rank'] = $dayRanks->getRank ( $type );
		$dayFeast ['type'] = $type;
	
		array_push ( $this->fullYear [$mth] [$day], $dayFeast );
	}
	
	


	/**
	 * Tag a given date and month with its code
	 *
	 * @param DateTime $cDate
	 *        	- Date to be tagged
	 * @param string $cd
	 *        	- Tag
	 * @param int $rank
	 *        	- Rank of the day
	 */
	function setDayCode($mth, $day, $cd, $type) {
		
		$dayRanks = new RomanCalendarRanks ();
		
		$rnk = $dayRanks->getRank ( $type );
	
		if (sizeof ( $this->fullYear [$mth] [$day] ) > 1) {
				
			foreach ( $this->fullYear [$mth] [$day] as $val ) {
				if ($val ['rank'] <= $rnk) {
					die ( 'More than one feast is here; Delete it first b4 setting it' );
				}
			}
				
			$this->fullYear [$mth] [$day] = array ();
			$this->fullYear [$mth] [$day] [0] ['rank'] = 100;
		}
	
		if ($mth == 11 && $day == 2) {
			$type = 'All Souls';
		}
	
		$this->fullYear [$mth] [$day] [0] ['code'] = $cd;
		$this->fullYear [$mth] [$day] [0] ['rank'] = $rnk;
		$this->fullYear [$mth] [$day] [0] ['type'] = $type;
	}
	

}

?>