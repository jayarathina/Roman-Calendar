<?php

/**
 * RomanCalendar 4.0 
 * @author Br. Jayarathina Madharasan SDR
 *
 * This is an example class where in the JSON data is processed and displayed in html format.
 * You can use it as a framework to build upon.
 * 
 */
class RomanCalendarRenderHTML {
	private $fullYear, $currentYear;

	function printYearHTML($currentYear) {
		$this->currentYear = $currentYear;

		$filename = 'dat/' . $this->currentYear . '/calendar.json';
		if (!file_exists($filename))
			return 'No data found for the year ' . $this->currentYear;

		$txtCnt = file_get_contents('dat/' . $this->currentYear . '/calendar.json');
		$this->fullYear = json_decode($txtCnt, true);

		$this->setDayNames();

		$rows = "<tr><th colspan=2> <a class='arrowRight' href='index.php?year=" . ($this->currentYear - 1) . "'>◄</a> {$this->currentYear} <a class='arrowLeft' href='index.php?year=" . ($this->currentYear + 1) . "'>►</a> </th></tr>";
		foreach ($this->fullYear as $month => $value) {

			foreach ($value as $days => $feasts) {
				$tempDt2 = new DateTime($this->currentYear . "-$month-$days");
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
			'CW02-0Sun' => 'Second Sunday after Christmas',
			'CW03-Epiphany' => 'The Epiphany of the Lord',
			'CW04-Baptism' => 'The Baptism of the Lord',

			'LW00-3Wed' => 'Ash Wednesday',
			'LW06-0Sun' => 'Palm Sunday of The Passion Of The Lord',
			'LW06-4Thu' => 'Thursday of Holy Week (Holy Thursday)',
			'LW06-5Fri' => 'Friday of the Passion of the Lord (Good Friday)',
			'LW06-6Sat' => 'Holy Saturday',

			'EW01-0Sun' => 'Easter Sunday of The Resurrection of The Lord',
			'EW07-Ascension' => 'The Ascension of the Lord',
			'EW08-Pentecost' => 'Pentecost Sunday',

			'OW00-Trinity' => 'The Most Holy Trinity',
			'OW00-CorpusChristi' => 'The Most Holy Body and Blood of Christ (Corpus Christi)',
			'OW00-SacredHeart' => 'The Most Sacred Heart of Jesus',
			'OW00-ImmaculateHeart' => 'Immaculate Heart of the Blessed Virgin Mary',
			'OW00-MaryMotherofChurch' => 'Mary, Mother of the Church',

			'CW01-HolyFamily' => 'The Holy Family of Jesus, Mary And Joseph',
			'Mem-Mary-Sat' => 'Saturday Memorial of the Blessed Virgin Mary'
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
						3 => (EPIPHANY_ON_A_SUNDAY) ? $dayEnglishFull[substr($dayCode, -1)] . ' after Epiphany' : 'Christmas Weekday: January 0' . (6 + substr($dayCode, -1)) // After Epiphany
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
	function addOrdinalNumberSuffix($num) {
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
