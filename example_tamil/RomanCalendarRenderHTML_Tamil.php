<?php
/**
 * RomanCalendar 5.0
 * @author Br. Jayarathina Madharasan SDB
 * @created 2025-08-07
 * @updated 2025-08-07
 * @description This class generates Tamil HTML for the Roman Catholic Calendar.
 * @version 5.0
 * @license MIT
 * 
 */ 

class RomanCalendarRenderHTML_Tamil {

	function printYearHTML($currentYear, $fullYear) {
	//Previous year and next year navigation
	$rows = "<tr><th colspan=2> <a class='arrowRight' href='index.php?year=" . ($currentYear - 1) . "'>◄</a> {$currentYear} <a class='arrowLeft' href='index.php?year=" . ($currentYear + 1) . "'>►</a> </th></tr>";
		
		foreach ($fullYear as $month => $value) {
			foreach ($value as $days => $feasts) {

				$tempDt2 = new \DateTime($currentYear . "-$month-$days");
				if ($days == 1) {
					$rows .= '<tr><td class="dt" colspan=2>' . $this->tamilMonthFull[$tempDt2->format('n')] . '</td></tr>';
				}
 
				$rows .= '<tr>';
				$rows .= '<td class="dt">' . $this->tamilMonthShort[$tempDt2->format('n')] . ' ' . $tempDt2->format('d') . '<br/>' . $this->tamilDayShort[$tempDt2->format('w')] . '</td>';
				$rows .= '<td class="dayTitle">';
				foreach ($feasts as $feastIndex => $fet) {
					if ($feastIndex == 'other') {
						$rows .= '<b><i>In other years:</i></b><br/>';
						foreach ($fet as $otherFet) {
							$type =  isset($otherFet['type']) ? ' (' . $this->getFeastType( $otherFet['type']) . ')' : '';
							$rows .= $otherFet['name'] . $type . '<span class="dot Col' . $otherFet["color"] . '"></span><br/>';
						}
					} else {
						$rows .= $fet['name'];
						$rows .= isset($fet['type']) ? ' (' . $this->getFeastType($fet['type']) . ')' : '';  // Day Type: Memory, Feast etc.,
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
	 * Constructor for the RomanCalendarRenderHTML_Tamil class.
	 * Initializes the parent class with the Tamil language option.
	 */
	private $epiphanyOnSunday;
	
	function computeTitle($fullYear) {
		$this->epiphanyOnSunday = ($fullYear[1][6][0]['code'] === 'CW03-Epiphany');

        foreach ($fullYear as $month => $days) {
            foreach ($days as $day => $feasts) {
				foreach ($feasts as $feastIndex => $singleFeast) {
					$cDate = &$fullYear[$month][$day][$feastIndex];
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
		return $fullYear;
	}

	/**
	 * Returns the title for a given day code.
	 * @param string $dayCode The day code to get the title for.
	 * @return string The title for the day code.
	 */
	private function getSingleTitle($dayCode): string {

		$feastTitles = [];

        $fileHandle = fopen( "calendarTamil.csv", "r") ;

        if (! $fileHandle) throw new \Exception("Could not open calendar.csv file.");
		fgetcsv($fileHandle, separator: ',', enclosure: '"', escape: ""); //Just to ignore header line
		while (($data = fgetcsv($fileHandle, separator: ',', enclosure: '"', escape: "")) !== false){
			$feastTitles [$data [0] ] = $data[1];
		}

		$RomanCalendarRanks = [
			'CW02-0Sun' => 'கிறிஸ்து பிறப்பு விழாவுக்குப் பின் 2ம் ஞாயிறு',
			'CW03-Epiphany' => 'ஆண்டவரின் திருக்காட்சி',
			'CW04-Baptism' => 'ஆண்டவரின் திருமுழுக்கு',
			
			
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

			'CW01-HolyFamily' => 'இயேசு, மரியா, யோசேப்பின் திருக்குடும்பம்',
			'Mem-Mary-Sat' => 'சனிக்கிழமையில் கன்னிமரியாவின் நினைவு',
			
			'OW34-0Sun' => 'இயேசு கிறிஸ்து அனைத்துலக அரசர்' 
		];

		$feastTitles = array_merge($feastTitles, $RomanCalendarRanks);

		$fTitle = $feastTitles[$dayCode] ?? null;

		$wkNo = intval(substr($dayCode, 2, 2));
		$wkDay = substr($dayCode, -4, 1);

		if (is_null($fTitle))
			switch (substr($dayCode, 0, 2)) {
				case 'AW':
					$fTitle = match ($wkNo) {
						5 => 'திருவருகைக் கால வார நாள்கள் - டிசம்பர் ' . substr ( $dayCode, - 2 ),
						default => 'திருவருகைக்காலம் ' . $wkNo . 'ஆம் வாரம் - ' . $this->tamilDayFull [$wkDay]
					};
					break;

				case 'CW':
					$fTitle = match ($wkNo) {
						1 => ' கிறிஸ்து பிறப்பின் எண்கிழமையில் ' . intval ( substr ( $dayCode, - 2 ) - 24 ) . 'ஆம் நாள் - டிசம்பர் ' . substr ( $dayCode, - 2 ), // Christmas Octave
						2 => 'சனவரி ' . substr ( $dayCode, - 1 ), // Before Epiphany
						3 => ($this->epiphanyOnSunday) ? 'சனவரி 0' . (6 + substr($dayCode, -1)) : 'திருக்காட்சி விழாவுக்குப் பின் ' . $this->tamilDayFull[substr($dayCode, -1)]// After Epiphany
					};
					break;

				case 'LW':
					$fTitle = match ($wkNo) {
						0 => 'திருநீற்றுப் புதனுக்குப் பின் வரும் ',
						6 => 'புனித வாரம் - ',
						default => 'தவக்காலம் ' . $wkNo . 'ஆம் வாரம் - '
					} .  $this->tamilDayFull [$wkDay];
					break;

				case 'EW':
					$fTitle = match ($wkNo) {
						1 => 'பாஸ்கா எண்கிழமை - ',
						default => 'பாஸ்கா ' . $wkNo . 'ஆம் வாரம் - '
					} . $this->tamilDayFull [$wkDay];
					break;

				case 'OW':
					$fTitle = 'பொதுக்காலம் ' . $wkNo . 'ஆம் வாரம் - ' . $this->tamilDayFull [$wkDay];
					break;
			}
		return $fTitle ?? $dayCode;
	}

    private $tamilDayFull = [
        'ஞாயிறு',
        'திங்கள்',
        'செவ்வாய்',
        'புதன்',
        'வியாழன்',
        'வெள்ளி',
        'சனி'
    ];

    private $tamilDayShort = [
        'ஞா',
        'தி',
        'செ',
        'பு',
        'வி',
        'வெ',
        'ச'
    ];

    private $tamilMonthFull = [
        '',
        'சனவரி',
        'பிப்ரவரி',
        'மார்ச்',
        'ஏப்ரல்',
        'மே',
        'ஜூன்',
        'ஜூலை',
        'ஆகஸ்ட்',
        'செப்டம்பர்',
        'அக்டோபர்',
        'நவம்பர்',
        'டிசம்பர்'
    ];

    private $tamilMonthShort = [
        '',
        'சன',
        'பிப்',
        'மார்',
        'ஏப்',
        'மே',
        'ஜூன்',
        'ஜூலை',
        'ஆக',
        'செப்',
        'அக்',
        'நவ',
        'டிச'
    ];

	private function getFeastType($fType) {
		return match ($fType) {
			'' => '',
			'Solemnity' => 'பெருவிழா',
			'Solemnity-PrincipalPartron-Place' => 'பெருவிழா',
			'Feast-Lord' => 'விழா',
			'Feast' => 'விழா',
			'Feast-PrincipalPartron-Place' => 'விழா',
			'Mem' => 'நினைவு',
			'Mem-Mary' => 'நினைவு',
			'OpMem' => 'வி.நினைவு',
            'Mem-Mary-Sat' => 'நினைவு',
            'Mem-OwnChurch' => 'நினைவு',
			'OpMem-Commemoration' => 'நினைவுக்காப்பு.',
			'Commemoration' => 'நினைவுக்காப்பு.',
			'All Souls' => ''
		};
	}


}
