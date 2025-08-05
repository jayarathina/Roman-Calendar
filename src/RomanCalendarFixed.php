<?php
namespace RomanCalendar;
/**
 * RomanCalendar 5.0
 * @author Br. Jayarathina Madharasan SDB
 * @created 2025-08-05
 * @updated 2025-08-05
 * @description This class generates the Roman Catholic Calendar for a given year.
 * @version 5.0
 * @license MIT
 * 
 */ 

require_once 'RomanCalendarRanks.php';

class RomanCalendarFixed{
    private $fileHandle, $fullYear,$currentYear;
    private RomanCalendarRanks $rcr;
    function __construct() {
        //The file name is hardcoded for standardization.
        $this->fileHandle = fopen(__DIR__ . DIRECTORY_SEPARATOR . "calendar.csv", "r") ;
        if (! $this->fileHandle) throw new \Exception("Could not open calendar.csv file.");

        $this->rcr = new RomanCalendarRanks();
    }

    function __destruct() {
        if ($this->fileHandle) {
            fclose($this->fileHandle);
        }
    }

    function computeFixedDayCodes(int $year, array $fullYear, array $options) : array {
        $this->fullYear = $fullYear;
        $this->currentYear = $year;

        $this->addSolemnityToYear();

		$this->addFeastToYear();

        return $this->fullYear;
    }

    /**
	 * Adds the given Solemnity to RomanCalendarYear variable
	 *
	 * @param array $FeastList
	 */
	function addSolemnityToYear() {

        $feastList = $this->getCalendar("/Solemnity*/");

        foreach ($feastList as $feast) {
            $month = $feast[0];
            $date = $feast[1];
            $name = $feast[2];
            $name_ta = $feast[3];
            $type = $feast[4];

			if ($month == 11 && $date == 2) {
				$type = 'All Souls';
			}
			$feastRank = $this->rcr->getRank($type);

            $currentDay = $this->fullYear[$month][$date];
			$currentDayRank = (!empty($currentDay)) ? $currentDay[0]['rank'] : 50;

            $tempDate = new \DateTime($this->currentYear . '-' . $month . '-' . $date);
            if (!($feastRank < $currentDayRank)) {
				// If a fixed date Solemnity occurs on a Sunday of Lent or Advent,
                // the Solemnity is transferred to the following Monday.
				// This affects Joseph, Husband of Mary (Mar 19), Annunciation (Mar 25), and Immaculate Conception (Dec 8).
				if (($date  == 19 && $month == 3) && preg_match("/^LW06/", $currentDay[0]['code']) === 1) {
					// If Joseph, Husband of Mary (Mar 19) falls on Palm Sunday or during Holy Week,
                    // it is moved to the Saturday preceding Palm Sunday.
					// This Solemnity can never occur on Holy Sat
					// 2035
					$tempDate->modify('last saturday');
				}  elseif ($name == 'Birth of Saint John the Baptist' && $currentDay[0]['code'] == 'OW00-SacredHeart' && $this->currentYear == 2022) {
					// Nativity of St. John the Baptist and the Feast of the Sacred Heart clashes in 2022.
					// CFD has determined that on June 24 the Sacred Heart should be celebrated,
					// and the Nativity of St. John the Baptist on the 23rd
					// This seems to be an ad hoc exception from the general rules.
					// Usually transfer is being made to the day following if available.
					// See http://www.cultodivino.va/content/cultodivino/it/documenti/responsa-ad-dubia/2020/de-calendario-liturgico-2022.html
					$tempDate->modify('- 1 days');
				} else {
					// Other solemnities that clash
					// - with Sundays of Lent or Advent or Eastertide
					// - with each other 
					// - Particular Calendar (Rank 4) and General Calendar
					// These should be moved to the nearest ferial day
					do {
						// Go to next day and check for its rank
						$tempDate->modify('+ 1 days');
						$currentDay_ = $this->fullYear[$tempDate->format('n')][$tempDate->format('j')];
					} while ($feastRank > $currentDay_[0]['rank']);
				}

            }
			$newFeast = ['code' => $name, 'rank' => $feastRank, 'type' => $type, 'name_ta' => $name_ta];
			$this->pushDayCode($tempDate, $newFeast);
       }
	}

	/**
	 * Adds the given Feast to RomanCalendarYear variable.
	 * Feast type (feast_type) could be either feast or Feasts of the Lord
	 *
	 * @param array $FeastList
	 */
	function addFeastToYear() {
		// Get all the feasts from the calendar.csv file
		$feastList = $this->getCalendar("/Feast*/");

		foreach ($feastList as $feast) {

            $month = $feast[0];
            $date = $feast[1];
            $name = $feast[2];
            $name_ta = $feast[3];
            $type = $feast[4];


			$currDate = new \DateTime($this->currentYear . '-' . $month . '-' . $date);
			$currentDayRank = $this->fullYear[$month][$date][0]['rank'];

			$newFeast = ['code' => $name, 'rank' => $this->rcr->getRank($type), 'type' => $type, 'name_ta' => $name_ta];

			if ($newFeast['rank'] < $currentDayRank) {
				$this->pushDayCode($currDate, $newFeast);
			} else {
				$this->addOtherFeast($currDate, [$newFeast]);
			}
		}
	}

    private function getCalendar($filter='/*/'): array {
        $rows = [];
        rewind($this->fileHandle);
        while (($data = fgetcsv($this->fileHandle, separator: ',', enclosure: '"', escape: "")) !== false) {
            if (preg_match($filter, $data[4]) === 1) {
                $rows[] = $data;
            }
        }
        return $rows;
    }


    /**
	 * Add feast/solemnity to a given date and month
	 *
	 * @param DateTime $feastDet
	 *        	- Date to be tagged
	 * @param array $feastDet
	 *        	- New feast to be added
	 */
	function pushDayCode($currDate, $feastDet) {
		$mth = $currDate->format('n');
		$day = $currDate->format('j');

		$temp = $this->fullYear[$mth][$day];
		$this->fullYear[$mth][$day] = [0 => $feastDet];

		$this->addOtherFeast($currDate, $temp);
	}

	/**
	 * Added to tempDate feasts/memoria that are supressed in this year
	 *
	 * @param DateTime $currDate
	 *        	- Date to be tagged
	 * @param array $feastDet
	 *        	- New feast to be added
	 */
	function addOtherFeast($currDate, $feastDet) {

		if (empty($feastDet))	return;

        //Christmas Week no supression
		if (str_starts_with($feastDet[0]['code'], 'CW01-')) return;
		
		//The Feast to be added to 'other' has 'other' in itself
		if(isset($feastDet['other'])){
			foreach ($feastDet['other'] as $value) {
				$feastDet []= $value;
			}
			unset($feastDet['other']);
		}

		$mth = $currDate->format('n');
		$day = $currDate->format('j');
		if (!isset($this->fullYear[$mth][$day]['other'])) {
			$this->fullYear[$mth][$day]['other'] = [];
		}

		foreach ($feastDet as $value) {
			$this->fullYear[$mth][$day]['other'] []= $value;
		}

	}

}