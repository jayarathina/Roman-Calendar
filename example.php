<?php
header('Content-Type: text/html; charset=utf-8');
include_once ('Lib/RomanCalendar.php');
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	</head>
<body>

<?php 

//Test Cases
//2019 Immaculate conception on sunday
//2018 annunciaion during holy week
//1967 st joseph during holy week
//2017 St. Joseph during lent sunday

$CalcGen = new RomanCalendar();

$CalcGen->printYearHTML();

?>