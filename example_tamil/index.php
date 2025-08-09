<?php
header('Content-Type: text/html; charset=utf-8');
include_once '../src/RomanCalendar.php';
include_once '../src/RomanCalendarRenderHTML.php';

include_once '../example_tamil/RomanCalendarRenderHTML_Tamil.php';

use RomanCalendar\RomanCalendar;
?>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="../css/RomanCalendar.css">
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
		
		$rHTML = new RomanCalendarRenderHTML_Tamil();
		$fullYear = $rHTML->computeTitle($fullYear);
		$rHTML->printYearHTML($year, $fullYear);
	?>
</body>
</html>