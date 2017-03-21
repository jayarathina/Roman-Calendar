<?php
/**
 * RomanCalendar 3.0
 * @author Br. Jayarathina Madharasan SDR
 */
require_once 'mods/medoo.php';

require_once 'lib/RomanCalendarRanks.php';
require_once 'lib/RomanCalendarFixed.php';
require_once 'lib/RomanCalendarMovable.php';
require_once 'lib/RomanCalendarYear.php';
require_once 'lib/RomanCalendarColor.php';
class RomanCalendar {

	function __construct($year, $settingsFileName = 'settings.ini') {
		$currentYear = is_numeric ( $year ) ? $year : date ( "Y" );
		$calcConfig = parse_ini_file ( $settingsFileName );
		
		$rcy = new RomanCalendarYear ( $currentYear, $calcConfig ['feastSettings'] );
		
		new RomanCalendarMovable ( $rcy );
		
		foreach ( $calcConfig ['calendars'] as $calName ) {			
			//$feastDeatils = $this->getDataFromDB ( $calName, $calcConfig ['feastsListLoc'] . $calName . '.json' );
			$feastDeatils = $this->getDataFromDAT ( $calcConfig ['feastsListLoc'] . $calName . '.json' );
			new RomanCalendarFixed ( $rcy, $feastDeatils, $calName );
		}
		die();
		new RomanCalendarColor ( $rcy );
		$this->genFixes ( $rcy );
		print_r ( $rcy->fullYear );
	}

	function getDataFromDAT($fileName) {
		$txtCnt = file_get_contents ( $fileName );
		return json_decode ( $txtCnt, true );
	}

	function getDataFromDB($calendar = 'generalcalendar', $fileName) {
		$database = new medoo ( array (
				'database_type' => 'mysql',
				'database_name' => 'liturgy_romancalendar',
				'server' => 'localhost',
				'username' => 'root',
				'password' => '',
				'charset' => 'utf8' 
		) );
		
		$FeastList = $database->select ( $calendar, array (
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

	function genFixes(RomanCalendarYear $rcy) {
		/**
		 * Immaculate Heart of Mary – Memorial
		 * This is the only moving celebration that has to be set seperately because
		 * in years when this memorial coincides with another obligatory memorial, as happened in
		 * 2014 [28 June, Saint Irenaeus] and 2015 [13 June, Saint Anthony of Padua],
		 * both must be considered optional for that year.
		 */
		$feastImmaculateHeart = clone $rcy->eastertideStarts;
		$feastImmaculateHeart->modify ( '+69 day' );
		
		$mnt =  $feastImmaculateHeart->format ( 'n' );
		$dy =  $feastImmaculateHeart->format ( 'j' );

		$rcy->addFeastToDate ($mnt, $dy, 'OW00-ImmaculateHeart', 'Mem' );
		foreach ( $rcy->fullYear as $monthVal => $dateList ) {
			foreach ( $dateList as $datVal => $dayFeastList ) {
				
				$memoryCount = 0;
				
				foreach ( $dayFeastList as $singleFeast ) {
					
					if(! isset($singleFeast ['type'])) continue;
					
					if (strcmp ( $singleFeast ['type'], 'Mem' ) === 0) {
						$memoryCount ++;
						if ($memoryCount > 1)
							break;
					}
				}

				if ($memoryCount > 1) {
					
					foreach ( $dayFeastList as $feastIndex => $singleFeast ) {
						
						if(! isset($singleFeast ['type'])) continue;
						
						if (strcmp ( $singleFeast ['type'], 'Mem' ) === 0) {
							$rcy->fullYear [$monthVal] [$datVal] [$feastIndex] ['type'] = 'OpMem';
						}
					}
				}
			}
		}
	

		/*
		 if($memoryDate ['feast_type'] == 'Mem' ){
		
		 if($currentDay[0]['code'] == 'OW10-6Sat'){
		 foreach ($currentDay as $dayFeasts) {
		 	
		 if(isset($dayFeasts['type']) && $dayFeasts['type'] == 'Mem'){
		
		 	
		 print_r($memoryDate);
		 die('*********************');
		 }
		 	
		
		 	
		 }
		
		
		
		 }
		 }
		 	
		 */
				
	}
}