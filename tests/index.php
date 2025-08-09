<?php
/**
 * RomanCalendar 5.0
 * @author Br. Jayarathina Madharasan SDB
 * @created 2025-08-09
 * @updated 2025-08-09
 * @description This file contains tests for the RomanCalendar class.
 * @version 5.0
 * @license MIT
 * 
 */

header('Content-Type: text/html; charset=utf-8');
include_once '../src/RomanCalendar.php';
include_once '../src/RomanCalendarRenderHTML.php';

use RomanCalendar\RomanCalendar;
use RomanCalendar\RomanCalendarRenderHTML;

$options = [
    'epiphanyOnSunday' => true,
    'ascensionOnSunday' => true,
    'corpusChristiOnSunday' => true,
];

echo "<ul>";
// 2019 Immaculate conception on monday
echo "<li>2019 Immaculate Conception of the Blessed Virgin Mary falls on Sunday so moved to Monday: ";
$CalcGen = new RomanCalendar(2019, $options);
$fullYear = $CalcGen->getFullYear();
if($fullYear[12][9][0]['code'] == 'Immaculate Conception of the Blessed Virgin Mary'){
    echo "✔️";
}else{
    echo "❌";
}
echo "</li>";

// 2014 Clash between Immaculate Heart and Memorials
echo "<li>2014 Clash between Immaculate Heart and Obligatory Memorials: Both become optional Memorials: ";
$CalcGen = new RomanCalendar(2014, $options);
$fullYear = $CalcGen->getFullYear();
if( ($fullYear[6][28][1]['type'] == 'OpMem' && $fullYear[6][28][1]['code'] == 'Saint Irenaeus, bishop, martyr') &&
    ($fullYear[6][28][2]['type'] == 'OpMem' && $fullYear[6][28][2]['code'] == 'OW00-ImmaculateHeart')
    ) {
    echo "✔️";
}else{
    echo "❌";
}
echo "</li>";

// 2018 Annunciation during Holy Week
echo "<li>2018 Annunciation during Holy Week, (moved to April 9): ";
$CalcGen = new RomanCalendar(2018, $options);
$fullYear = $CalcGen->getFullYear();
if( $fullYear[3][25][0]['code'] == 'LW06-0Sun' && $fullYear[4][9][0]['code'] == 'Annunciation of the Lord' ){
    echo "✔️";
}else{
    echo "❌";
}
echo "</li>";

// 2017 St. Joseph Sunday of the 3rd Week of Lent
echo "<li>2017 St. Joseph Sunday of the 3rd Week of Lent (moved to March 20): ";
$CalcGen = new RomanCalendar(2017, $options);
$fullYear = $CalcGen->getFullYear();
if( $fullYear[3][19][0]['code'] == 'LW03-0Sun' && $fullYear[3][20][0]['code'] == 'Saint Joseph Husband of the Blessed Virgin Mary' ){
    echo "✔️";
}else{
    echo "❌";
}
echo "</li>";

// 2008 St. Joseph during holy week
echo "<li>2008 St. Joseph during Holy Week (Anticipated to March 15, the Saturday before Holy Week): ";
$CalcGen = new RomanCalendar(2008, $options);
$fullYear = $CalcGen->getFullYear();
if( $fullYear[3][19][0]['code'] == 'LW06-3Wed' && $fullYear[3][15][0]['code'] == 'Saint Joseph Husband of the Blessed Virgin Mary' ){
    echo "✔️";
}else{
    echo "❌";
}
echo "</li>";

// 2022 Nativity of St. John the Baptist and the Feast of the Sacred Heart clashes: 
// Nativity of St. John the Baptist and the Feast of the Sacred Heart clashes in 2022.
// CFD has determined that on June 24 the Sacred Heart should be celebrated,
// and the Nativity of St. John the Baptist on the 23rd
// This seems to be an ad hoc exception from the general rules.
// Usually transfer is being made to the day following if available.
// See http://www.cultodivino.va/content/cultodivino/it/documenti/responsa-ad-dubia/2020/de-calendario-liturgico-2022.html

echo "<li>2022 Nativity of St. John the Baptist and the Feast of the Sacred Heart clashes: ";
$CalcGen = new RomanCalendar(2022, $options);
$fullYear = $CalcGen->getFullYear();
if( $fullYear[6][24][0]['code'] == 'OW00-SacredHeart' && $fullYear[6][23][0]['code'] == 'Birth of Saint John the Baptist' ){
    echo "✔️";
}else{
    echo "❌";
}
echo "</li>";

echo "</ul>";
// $fullYear