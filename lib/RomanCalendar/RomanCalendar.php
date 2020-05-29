<?php
/**
 * RomanCalendar 3.0
 * @author Br. Jayarathina Madharasan SDR
 */
use Medoo\Medoo;
require_once 'lib/Medoo.php';
require_once 'lib/dbConfig.php';

require_once 'RomanCalendarRanks.php';
require_once 'RomanCalendarFixed.php';
require_once 'RomanCalendarMovable.php';
require_once 'RomanCalendarYear.php';
require_once 'RomanCalendarColor.php';
class RomanCalendar {
	public $rcy;
	function __construct($year = null, $calcConfig) {
		$currentYear = is_numeric ( $year ) ? $year : date ( "Y" );
		
		$this->rcy = new RomanCalendarYear ( $currentYear, $calcConfig );
		new RomanCalendarMovable ( $this->rcy );
		
		$dirName = $this->rcy->calcConfig ['feastsListLoc'] . $this->rcy->currentYear;
		if (! is_dir ( $dirName )) {
			mkdir ( $dirName, 0744 );
		}
		
		foreach ( $calcConfig ['calendars'] as $calName ) {
			$filename = $dirName . '/' . $calName . '.json';
			
			if (! file_exists ( $filename )) { // If the JSON does not exist in the specified path, then create it from DB
				if ($this->createJSONFromDB ( $calName, $filename ) === FALSE) {
					die ( 'Error in writing JSON file' );
				}
			}
			$feastDeatils = $this->getDataFromDAT ( $filename );
			new RomanCalendarFixed ( $this->rcy, $feastDeatils );
		}
		$this->genFixes ();
		new RomanCalendarColor ( $this->rcy );
	}
	
	/**
	 * Get feast details from JSON file
	 *
	 * @param string $fileName
	 *        	- JSON File name
	 * @return array (jason decoded)
	 */
	function getDataFromDAT($fileName) {
		$txtCnt = file_get_contents ( $fileName );
		return json_decode ( $txtCnt, true );
	}
	
	/**
	 * Get feast details from mysql database and creates JSON file in the path specified.
	 * Call this function if you have changed the database and want to refresh the JSON file.
	 *
	 * @param string $calendar
	 *        	Table name
	 * @param string $fileName
	 *        	to save data as JSON
	 */
	function createJSONFromDB($calendar = 'calendar', $fileName) {
		$database = new Medoo ( [ 
				'database_type' => 'mysql',
				'database_name' => DB_NAME,
				'server' => 'localhost',
				'username' => DB_USER,
				'password' => DB_PASSWORD,
				'charset' => 'utf8' 
		] );
		// Prefix 'general' is added to table name to avoid unnecessary securtiy risk
		// Change it to whatever prefix you want it to be.
		$FeastList = $database->select ( 'general' . $calendar, [ 
				'feast_month',
				'feast_date',
				'feast_code',
				'feast_type' 
		], [ 
				'ORDER' => [ 
						'feast_month' => 'ASC',
						'feast_date' => 'ASC' 
				] 
		] );
		$t = json_encode ( $FeastList, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK );
		return file_put_contents ( $fileName, $t );
	}
	
	/**
	 * General fixes that are not done anywhere else:
	 * - Set Immaculate Heart of Mary – Memorial; This is the only moving celebration that has to be set seperately because
	 */
	function genFixes() {
		$feastDeatils = [ ];
		
		$feastImmaculateHeart = clone $this->rcy->eastertideStarts;
		$feastImmaculateHeart->modify ( '+69 day' );
		$mnt = $feastImmaculateHeart->format ( 'n' );
		$dy = $feastImmaculateHeart->format ( 'j' );
		
		if ($this->rcy->fullYear [$mnt] [$dy] [0] ['rank'] > 5) { // Some local calendar solemnity might occour
			array_push ( $feastDeatils, [ 
					'feast_month' => $mnt,
					'feast_date' => $dy,
					'feast_code' => 'OW00-ImmaculateHeart',
					'feast_type' => 'Mem' //Not set as Mem-Mary because it has a specific exception. See below.
			] );
		}
		
		$memMaryMotherofChurch = clone $this->rcy->ordinaryTime2Starts;
		$mnt = $memMaryMotherofChurch->format ( 'n' );
		$dy = $memMaryMotherofChurch->format ( 'j' );
		if ($this->rcy->fullYear [$mnt] [$dy] [0] ['rank'] > 5) { // Some local calendar solemnity might occour
			array_push ( $feastDeatils, [ 
					'feast_month' => $mnt,
					'feast_date' => $dy,
					'feast_code' => 'OW00-MaryMotherofChurch',
					'feast_type' => 'Mem-Mary' 
			] );
		}
		// Add to calendar
		new RomanCalendarFixed ( $this->rcy, $feastDeatils );
		
		/**
		 * In years when Immaculate Heart memorial coincides with another obligatory memorial,
		 * as happened in 2014 [28 June, Saint Irenaeus] and 2015 [13 June, Saint Anthony of Padua], both must be considered optional for that year.
		 *
		 * The above guidance from the Congregation for Divine Worship and the Discipline of the Sacraments gives rise to the following rule:
		 * "In years when a memorial coincides with another obligatory memorial both must be considered optional for that year."
		 *
		 * This does not apply to other Memorials of BVM, as following the liturgical tradition of pre-eminence amongst persons, 
		 * the Memorial of the Blessed Virgin Mary is to prevail. (This logic is set in RomanCalendarFixed->addMemoryToYear)
		 */
		
		foreach ( $this->rcy->fullYear as $monthVal => $dateList ) {
			foreach ( $dateList as $datVal => $dayFeastList ) {
				$memoryCount = 0;
				foreach ( $dayFeastList as $singleFeast ) {
					if (isset ( $singleFeast ['type'] ) && strcmp ( $singleFeast ['type'], 'Mem' ) === 0) {
						$memoryCount ++;
						if ($memoryCount > 1) {
							foreach ( $dayFeastList as $feastIndex => $singleFeast ) {
								if (isset ( $singleFeast ['type'] ) && strcmp ( $singleFeast ['type'], 'Mem' ) === 0)
									$this->rcy->fullYear [$monthVal] [$datVal] [$feastIndex] ['type'] = 'OpMem';
							}
							break;
						}
					}
				}
			}
		}
	}
}