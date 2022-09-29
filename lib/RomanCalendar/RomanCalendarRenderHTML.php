<?php

/**
 * RomanCalendar 4.0
 * @author Br. Jayarathina Madharasan SDR
 *
 * This is an example class where in the data recieved is processed and displayed in html format.
 * You can use it as a framework to build upon.
 * 
 */
class RomanCalendarRenderHTML
{
	private $fullYear, $currentYear;

	function printYearHTML($currentYear)
	{
		$this->currentYear = $currentYear;

		$filename = 'dat/' . $this->currentYear . '/calendar.json';
		if (!file_exists($filename))
			return 'No Data Found for the year ' . $this->currentYear;

		$txtCnt = file_get_contents('dat/' . $this->currentYear . '/calendar.json');
		$this->fullYear = json_decode($txtCnt, true);

		$this->setDayNames();

		$rows = "<tr><th colspan=3> <a class='arrowRight' href='index.php?year=" . ($this->currentYear - 1) . "'>◄</a> {$this->currentYear} <a class='arrowLeft' href='index.php?year=" . ($this->currentYear + 1) . "'>►</a> </th></tr>";
		foreach ($this->fullYear as $month => $value) {
			foreach ($value as $days => $feasts) {
				$tempDt2 = new DateTime($this->currentYear . "-$month-$days");

				$rows .= '<tr>';
				$rows .= '<td class="dt">' . $tempDt2->format('d M') . '</td>';
				$rows .= '<td class="dayTitle">';
				foreach ($feasts as $feastIndex => $fet) {

					if ($feastIndex == 'other') {
						$rows .= '<b>In other years:</b><br/>';

						foreach ($fet as $otherFet) {
							$type = isset($otherFet['type']) ? ' (' . $otherFet['type'] . ')' : '';
							$rows .= $otherFet['name'] . $type . '<span class="dot ColD' . $otherFet["color"] . '"></span><br/>';
						}
					} else {
						$type = isset($fet['type']) ? ' (' . $fet['type'] . ')' : '';
						$rows .= $fet['name'] . $type . '<span class="dot ColD' . $fet["color"] . '"></span><br/>';
					}
				}

				$rows .= '</td>';
				$rows .= '</tr>';
			}
		}
		echo "<table>$rows</table>";
	}

	/**
	 * Set names in the place of codes.
	 * This has to be language specific. Here an english language example is given.
	 * For feast names, one has to derive it from the database. For weekday codes names can be set here.
	 */
	function setDayNames()
	{
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

	function getSingleTitle($dayCode)
	{

		// @formatter:off
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
			'Sat-Mem-Mary' => 'Saturday Memorial of the Blessed Virgin Mary'
		];
		// @formatter:on

		$fTitle = $RomanCalendarRanks[$dayCode] ?? null;

		$wkNo = intval(substr($dayCode, 2, 2));
		$wkDay = substr($dayCode, -4, 1);

		if (is_null($fTitle))
			switch (substr($dayCode, 0, 2)) {
				case 'AW':
					if ($wkNo == 5)
						$fTitle = 'Advent Weekday: ' . ' December - ' . substr($dayCode, -2);
					else
						$fTitle = $dayEnglishFull[$wkDay] . ' of the ' . $this->ordinalSuffix(intval($wkNo), 1) . ' Week of Advent';
					break;
				case 'CW':
					switch ($wkNo) {
						case 1: // Christmas Octave
							$fTitle = $this->ordinalSuffix(intval(substr($dayCode, -2) - 24), 1) . ' Day in the Octave of Christmas';
							break;
						case 2: // Before Epiphany
							$fTitle = 'Christmas Weekday: January 0' . substr($dayCode, -1);
							break;
						case 3: // After Epiphany
							if (EPIPHANY_ON_A_SUNDAY) {
								$fTitle = $dayEnglishFull[substr($dayCode, -1)] . ' after Epiphany';
							} else {
								$fTitle = 'Christmas Weekday: January 0' . (6 + substr($dayCode, -1));
							}
							break;
					}
					break;
				case 'LW':
					switch ($wkNo) {
						case 0:
							$fTitle = $dayEnglishFull[$wkDay] . ' after Ash Wednesday';
							break;
						case 6:
							$fTitle = $dayEnglishFull[$wkDay] . ' of Holy Week';
							break;
						default:
							$fTitle = $dayEnglishFull[$wkDay] . ' of the ' . $this->ordinalSuffix(intval($wkNo), 1) . ' Week of Lent';
							break;
					}
					break;
				case 'EW':
					if ($wkNo == 1)
						$fTitle = $dayEnglishFull[$wkDay] . ' in the Octave of Easter';
					else
						$fTitle = $dayEnglishFull[$wkDay] . ' of the ' . $this->ordinalSuffix(intval($wkNo), 1) . ' Week of Easter';
					break;
				case 'OW':
					$fTitle = $dayEnglishFull[$wkDay] . ' of the ' . $this->ordinalSuffix(intval($wkNo), 1) . ' Week in Ordinary Time';
					break;
			}
		return $fTitle ?? $dayCode;
	}

	/**
	 *
	 * @return number with ordinal suffix
	 * @param int $number
	 * @param int $ss
	 *        	Turn super script on/off
	 * @return string
	 *
	 */
	function ordinalSuffix($number, $ss = 0)
	{
		if ($number % 100 > 10 && $number % 100 < 14) {
			$os = 'th';
		} elseif ($number == 0) {
			$os = '';
		} else {
			switch (substr($number, -1, 1)) {
				case "1":
					$os = 'st';
					break;

				case "2":
					$os = 'nd';
					break;

				case "3":
					$os = 'rd';
					break;

				default:
					$os = 'th';
			}
		}
		$os = $ss == 0 ? $os : '<sup>' . $os . '</sup>';
		return $number . $os;
	}
}
