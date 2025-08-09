<?php
namespace RomanCalendar;
/**
 * RomanCalendar 5.0
 * @author Br. Jayarathina Madharasan SDB
 * @created 2025-08-07
 * @updated 2025-08-07
 * @description This class generates HTML for the Roman Catholic Calendar.
 * @version 5.0
 * @license MIT
 * 
 */ 

class RomanCalendarRenderHTML {

	function printYearHTML($currentYear, $fullYear) {
		//Previous year and next year navigation
		$rows = "<tr><th colspan=2> <a class='arrowRight' href='index.php?year=" . ($currentYear - 1) . "'>◄</a> {$currentYear} <a class='arrowLeft' href='index.php?year=" . ($currentYear + 1) . "'>►</a> </th></tr>";
		
		foreach ($fullYear as $month => $value) {
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

	private function getFeastType($fType) {
		return match ($fType) {
			'' => '',
			'Solemnity' => 'Sol.',
			'Solemnity-PrincipalPartron-Place' => 'Sol.',
			'Feast-Lord' => 'Feast',
			'Feast' => 'Feast',
			'Feast-PrincipalPartron-Place' => 'Feast',
			'Mem' => 'Mem',
			'Mem-Mary' => 'Mem',
			'OpMem' => 'Op. Mem',
            'Mem-Mary-Sat' => 'Mem',
            'Mem-OwnChurch' => 'Mem.',
			'OpMem-Commemoration' => 'Commem.',
			'Commemoration' => 'Commem.',
			'All Souls' => ''
		};
	}
}
