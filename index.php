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
		$debug = true; // Set to false in production

		$year = filter_input(INPUT_GET, 'year', FILTER_VALIDATE_INT) ?? (int)date("Y");
		$cacheFile = 'dat/' . $year . '/calendar.json';

		if (file_exists($cacheFile) && !$debug) {
			$fullYear = json_decode(file_get_contents($cacheFile), true);
		} else {
			$options = [
				'epiphanyOnSunday'     => true,
				'ascensionOnSunday'    => true,
				'corpusChristiOnSunday' => true,
			];
			$fullYear = (new RomanCalendar($year, $options))->getFullYear();

			if (!is_dir('dat/' . $year)) {
				mkdir('dat/' . $year, 0744, true);
			}
			file_put_contents($cacheFile, json_encode($fullYear, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK));
		}

		$rHTML = new RomanCalendarRenderHTML();
		$rHTML->printYearHTML($year, $fullYear);
	?>
</body>
</html>