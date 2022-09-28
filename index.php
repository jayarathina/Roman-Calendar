<?php
header('Content-Type: text/html; charset=utf-8');
include_once ('lib/RomanCalendar/RomanCalendar.php');
include_once ('lib/RomanCalendar/RomanCalendarRenderHTML.php');
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/RomanCalendar.css?<?=rand()?>">
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

$year = $_GET ['year'] ?? date ( "Y" );

$filename = 'dat/' . $year . '/calendar.json';

if (! file_exists ( $filename )) { // If the JSON does not exist in the specified path, then generate it
	$CalcGen = new RomanCalendar ($year);
}

$rHTML = new RomanCalendarRenderHTML ();
$rHTML->printYearHTML ( $year );

?>