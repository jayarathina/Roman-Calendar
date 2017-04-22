<?php
/**
 * RomanCalendar 3.0
 * @author Br. Jayarathina Madharasan SDR
 */
require_once 'mods/Medoo.php';

require_once 'RomanCalendarRanks.php';
require_once 'RomanCalendarFixed.php';
require_once 'RomanCalendarMovable.php';
require_once 'RomanCalendarYear.php';
require_once 'RomanCalendarColor.php';

class RomanCalendar {

	public $rcy;

	function __construct($year = null, $calcConfig) {
		$currentYear = is_numeric ( $year ) ? $year : date ( "Y" );
		
		$this->rcy = new RomanCalendarYear ( $currentYear, $calcConfig ['feastSettings'] );
		
		new RomanCalendarMovable ( $this->rcy );
		
		foreach ( $calcConfig ['calendars'] as $calName ) {
			$feastDeatils = $this->getDataFromDB ( $calName, $calcConfig ['feastsListLoc'] . $calName . '.json' );
			//$feastDeatils = $this->getDataFromDAT ( $calcConfig ['feastsListLoc'] . $calName . '.json' );
			new RomanCalendarFixed ( $this->rcy, $feastDeatils, $calName );
		}
		$this->genFixes ();
		new RomanCalendarColor ( $this->rcy );
		// print_r ( $this->rcy->fullYear );
	}

	/**
	 * Get feast details from JSON file
	 *
	 * @param unknown $fileName
	 *        	- JSON File name
	 * @return array (jason decoded)
	 */
	function getDataFromDAT($fileName) {
		$txtCnt = file_get_contents ( $fileName );
		return json_decode ( $txtCnt, true );
	}

	/**
	 * Get feast details from mysql database.
	 * This function gets data from database and saved it to a JSON file.
	 * Later it calls getDataFromDAT to return the feast details.
	 *
	 * @param string $calendar
	 *        	Table name
	 * @param unknown $fileName
	 *        	to save data as JSON
	 */
	function getDataFromDB($calendar = 'calendar', $fileName) {
		$database = new Medoo ( array (
				'database_type' => 'mysql',
				'database_name' => 'liturgy_lectionary',
				'server' => 'localhost',
				'username' => 'root',
				'password' => '',
				'charset' => 'utf8' 
		) );
		// Prefix 'general' is added to table name to avoid unnecessary securtiy risk
		$FeastList = $database->select ( 'general' . $calendar, array (
				'feast_month',
				'feast_date',
				'feast_code',
				'feast_type' 
		), array (
				'ORDER' => array (
						'feast_month' => 'ASC',
						'feast_date' => 'ASC' 
				) 
		) );
		
		$t = json_encode ( $FeastList, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK );
		
		file_put_contents ( $fileName, $t );
		return $this->getDataFromDAT ( $fileName );
	}

	/**
	 * General fixes that are not done anywhere else like:
	 * - If a fixed date Memorial or Optional Memorial falls within the Lenten season, it is reduced in rank to a Commemoration.
	 * - in years when a memorial coincides with another obligatory memorial both must be considered optional for that year.
	 */
	function genFixes() {
		/**
		 * Immaculate Heart of Mary – Memorial
		 * This is the only moving celebration that has to be set seperately because
		 * in years when this memorial coincides with another obligatory memorial, as happened in
		 * 2014 [28 June, Saint Irenaeus] and 2015 [13 June, Saint Anthony of Padua],
		 * both must be considered optional for that year.
		 */
		$feastImmaculateHeart = clone $this->rcy->eastertideStarts;
		$feastImmaculateHeart->modify ( '+69 day' );
		
		$mnt = $feastImmaculateHeart->format ( 'n' );
		$dy = $feastImmaculateHeart->format ( 'j' );
		
		if ($this->rcy->fullYear [$mnt] [$dy] [0] ['rank'] > 5) {
			$this->rcy->addFeastToDate ( $mnt, $dy, 'OW00-ImmaculateHeart', 'Mem' );
		}
		
		foreach ( $this->rcy->fullYear as $monthVal => $dateList ) {
			foreach ( $dateList as $datVal => $dayFeastList ) {
				
				$memoryCount = 0;
				
				foreach ( $dayFeastList as $singleFeast ) {
					
					if (! isset ( $singleFeast ['type'] ))
						continue;
					
					if (strcmp ( $singleFeast ['type'], 'Mem' ) === 0) {
						$memoryCount ++;
						if ($memoryCount > 1)
							break;
					}
				}
				
				if ($memoryCount > 1) {
					
					foreach ( $dayFeastList as $feastIndex => $singleFeast ) {
						
						if (! isset ( $singleFeast ['type'] ))
							continue;
						
						if (strcmp ( $singleFeast ['type'], 'Mem' ) === 0) {
							$this->rcy->fullYear [$monthVal] [$datVal] [$feastIndex] ['type'] = 'OpMem';
						}
					}
				}
			}
		}
	}
}