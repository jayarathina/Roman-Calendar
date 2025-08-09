<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'src/RomanCalendar.php';
include_once 'src/RomanCalendarRenderHTML.php';

use RomanCalendar\RomanCalendar;
use RomanCalendar\RomanCalendarRenderHTML;
?>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="css/RomanCalendar.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
</head>

<body>
	<?php
		$year = $_GET['year'] ?? date("Y");
		
		$options = [
			'epiphanyOnSunday' => true,
			'ascensionOnSunday' => true,
			'corpusChristiOnSunday' => true,
		];
		$CalcGen = new RomanCalendar($year, $options);
		$fullYear = $CalcGen->getFullYear(); //$fullYear has the liturgical calendar data
		
		$rHTML = new RomanCalendarRenderHTML();
		$rHTML->printYearHTML($year, $fullYear);

		// If you dont want to regenerate the calendar everytime, you can save data to JSON file
		/* */
		$dirName = 'dat/' . $year;
		if (!is_dir($dirName)) {
			mkdir($dirName, 0744);
		}
		$temp = json_encode($fullYear, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK);
		file_put_contents($dirName . '/calendar.json', $temp);
		/* */

		//To use the JSON data for future requests
		/*
		$dirName = 'dat/' . $year;
		$cachedData = json_decode(file_get_contents($dirName . '/calendar.json'), true);
		if ($cachedData) {
			$fullYear = $cachedData;
			$rHTML = new RomanCalendarRenderHTML();
			$rHTML->printYearHTML($year, $fullYear);
		}
		*/

	?>
</body>
</html>