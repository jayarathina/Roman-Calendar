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

	// Test Cases
	// 2019 Immaculate conception on sunday
	// 2018 annunciaion during holy week
	// 1967 St. Joseph during holy week
	// 2017 St. Joseph during lent sunday
	// 2014 Immaculate Hrt coincided with Saint Irenaeus, 28 June
	// 2015 Immaculate Hrt coincided with Saint Anthony of Padua, 13 June

	$year = $_GET['year'] ?? date("Y");

	$filename = 'dat/' . $year . '/calendar.json';

	$options = [
		'epiphanyOnSunday' => true,
		'ascensionOnSunday' => true,
		'corpusChristiOnSunday' => true,
	];

	// If the JSON does not exist in the specified path, then generate it
	if (!file_exists($filename)) {
		$CalcGen = new RomanCalendar($year, $options);
	}

	$rHTML = new RomanCalendarRenderHTML();
	$rHTML->printYearHTML($year);
	?>