<?php
namespace RomanCalendar;
/**
 * RomanCalendar 5.0
 * @author Br. Jayarathina Madharasan SDB
 * @created 2025-08-07
 * @updated 2025-08-07
 * @description This class generates HTML for the Roman Catholic Calendar based on JSON data.
 * @version 5.0
 * @license MIT
 * 
 */ 

class RomanCalendarRenderHTML {
	private $fullYear;

	function printYearHTML($currentYear) {

		$filename = 'dat/' . $currentYear . '/calendar.json';
		if (!file_exists($filename))
            throw new \Exception('No data found for the year ' . $currentYear);

		$txtCnt = file_get_contents($filename);
		$this->fullYear = json_decode($txtCnt, true);

		$this->setDayNames();

		$fl = $this->fullYear;

		$rows = "<tr><th colspan=2> <a class='arrowRight' href='index.php?year=" . ($currentYear - 1) . "'>◄</a> {$currentYear} <a class='arrowLeft' href='index.php?year=" . ($currentYear + 1) . "'>►</a> </th></tr>";
		
		foreach ($this->fullYear as $month => $value) {

			foreach ($value as $days => $feasts) {
				$tempDt2 = new \DateTime($currentYear . "-$month-$days");
				if ($days == 1) {
					$rows .= '<tr><td class="dt" colspan=2>' . $tempDt2->format('F') . '</td></tr>';
				}

				$rows .= '<tr>';
				$rows .= '<td class="dt">' . $tempDt2->format('d M D') . '</td>';
				$rows .= '<td class="dayTitle">';
				foreach ($feasts as $feastIndex => $fet) {
					if ($feastIndex == 'other') {
						$rows .= '<b><i>In other years:</i></b><br/>';
						foreach ($fet as $otherFet) {
							$type = isset($otherFet['type']) ? ' (' . $otherFet['type'] . ')' : '';
							$rows .= $this->getSingleTitle($otherFet['code']) . $type . '<span class="dot Col' . $otherFet["color"] . '"></span><br/>';
						}
					} else {
						$rows .= $this->getSingleTitle($fet['code']);
						$rows .= isset($fet['type']) ? ' (' . $fet['type'] . ')' : ''; // Day Type: Memory, Feast etc.,
						$rows .= '<span class="dot Col' . $fet["color"] . '"></span><br/>';
					}
				}
				$rows .= '</td>';
				$rows .= '</tr>';
			}
		}
		echo "<table>$rows</table>";
	}

	/**
	 * Computes the human readable names of feasts based on day code.
	 * This has to be language specific. Here an english language example is given.
	 */
	function setDayNames() {
		foreach ($this->fullYear as $month => $dates) {
			foreach ($dates as $date => $dayFeastList) {
				foreach ($dayFeastList as $feastIndex => $singleFeast) {
					$cDate = &$this->fullYear[$month][$date][$feastIndex];
					if ($feastIndex == 'other') {
						foreach ($singleFeast as $other_key => $other_value) {
							$cDate[$other_key]['name'] = $this->getSingleTitle($cDate[$other_key]['code']);
						}
					} else {
						$cDate['name'] = $this->getSingleTitle($cDate['code']);
					}
				}
			}
		}
	}
	
	/**
	 * 
	 */
	function getSingleTitle($dayCode) {
		$dayEnglishFull = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

		$RomanCalendarRanks = [
			'CW02-0Sun' => 'கிறிஸ்து பிறப்பு விழாவுக்குப் பின் 2ம் ஞாயிறு',
			'CW03-Epiphany' => 'ஆண்டவரின் திருக்காட்சி',
			'CW04-Baptism' => 'ஆண்டவரின் திருமுழுக்கு',
			'CW01-HolyFamily' => 'இயேசு, மரியா, யோசேப்பின் திருக்குடும்பம்',
			
			'LW00-3Wed' => 'திருநீற்றுப் புதன்',
			'LW06-0Sun' => 'ஆண்டவருடைய திருப்பாடுகளின் குருத்து ஞாயிறு',
			'LW06-4Thu' => 'பெரிய வியாழன்',//'ஆண்டவரின் இராவுணவுத் திருப்பலி',
			'LW06-5Fri' => 'திருப்பாடுகளின் வெள்ளி',
			'LW06-6Sat' => 'பெரிய சனி', //பாஸ்கா  திருவிழிப்பு
			
			'EW01-0Sun' => 'ஆண்டவருடைய உயிர்ப்பின் பாஸ்கா ஞாயிறு',
			'EW07-Ascension' => 'ஆண்டவரின் விண்ணேற்றம்',
			'EW08-Pentecost' => 'தூய ஆவி ஞாயிறு',
			
			'OW00-Trinity' => 'மூவொரு கடவுள்',
			'OW00-CorpusChristi' => 'கிறிஸ்துவின் திருவுடல், திருஇரத்தம்',
			'OW00-SacredHeart' => 'இயேசுவின் திருஇதயம்',
			'OW00-ImmaculateHeart' => 'தூய கன்னி மரியாவின் மாசற்ற இதயம்',
			'OW00-MaryMotherofChurch' => 'தூய கன்னி மரியா, திரு அவையின் அன்னை',

			'Mem-Mary-Sat' => 'சனிக்கிழமையில் கன்னிமரியாவின் நினைவு',
			
			'OW34-0Sun' => 'இயேசு கிறிஸ்து அனைத்துலக அரசர்' 
		];

		$fTitle = $RomanCalendarRanks[$dayCode] ?? null;

		$wkNo = intval(substr($dayCode, 2, 2));
		$wkDay = substr($dayCode, -4, 1);

		if (is_null($fTitle))
			switch (substr($dayCode, 0, 2)) {
				case 'AW':
					$fTitle = match ($wkNo) {
						5 => 'Advent Weekday: December - ' . substr($dayCode, -2),
						default => $dayEnglishFull[$wkDay] . ' of the ' . $this->addOrdinalNumberSuffix($wkNo) . ' Week of Advent'
					};
					break;
				case 'CW':
					$fTitle = match ($wkNo) {
						1 => $this->addOrdinalNumberSuffix(intval(substr($dayCode, -2) - 24)) . ' Day in the Octave of Christmas', // Christmas Octave
						2 => 'Christmas Weekday: January 0' . substr($dayCode, -1), // Before Epiphany
						3 => ($this->fullYear[1][6][0]['code'] === 'CW03-Epiphany') ? 'Christmas Weekday: January 0' . (6 + substr($dayCode, -1)) : $dayEnglishFull[substr($dayCode, -1)] . ' after Epiphany' // After Epiphany
					};
					break;
				case 'LW':
					$fTitle = $dayEnglishFull[$wkDay] . match ($wkNo) {
						0 => ' after Ash Wednesday',
						6 => ' of Holy Week',
						default => ' of the ' . $this->addOrdinalNumberSuffix($wkNo) . ' Week of Lent'
					};
					break;
				case 'EW':
					$fTitle = $dayEnglishFull[$wkDay] . match ($wkNo) {
						1 => ' in the Octave of Easter',
						default => ' of the ' . $this->addOrdinalNumberSuffix($wkNo) . ' Week of Easter'
					};
					break;
				case 'OW':
					$fTitle = $dayEnglishFull[$wkDay] . ' of the ' . $this->addOrdinalNumberSuffix($wkNo) . ' Week in Ordinary Time';
					break;
			}
		return $fTitle ?? $dayCode;
	}

	/**
	 * Returns the given number with ordinal suffix
	 * @param int $number
	 * @return string number with ordinal suffix
	 *
	 */
	function addOrdinalNumberSuffix($num): string {
		if (!in_array(($num % 100), [11, 12, 13])) {
			return $num . match ($num % 10) {
				1 => '<sup>st</sup>',
				2 => '<sup>nd</sup>',
				3 => '<sup>rd</sup>',
				default => '<sup>th</sup>'
			};
		}
		return $num . '<sup>th</sup>';
	}
}
