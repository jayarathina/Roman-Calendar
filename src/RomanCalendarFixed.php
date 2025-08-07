<?php
namespace RomanCalendar;
/**
 * RomanCalendar 5.0
 * @author Br. Jayarathina Madharasan SDB
 * @created 2025-08-05
 * @updated 2025-08-05
 * @description This class adds solemnities, feasts and memorials that have fixed dates to the Calendar.
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
		$this->addMemoryToYear();

        return $this->fullYear;
    }

    /**
	 * Adds the given Solemnity to RomanCalendarYear variable
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
				$this->addOtherCelebration($currDate, [$newFeast]);
			}
		}
	}

	/**
	 * Adds the given Memory or optional memory to RomanCalendarYear variable.
	 */
	function addMemoryToYear() {
		$feastList = $this->getCalendar("/^(Mem|OpMem)/");

		$easterDate = new \DateTimeImmutable($this->currentYear . '-03-21 +' . easter_days($this->currentYear) . ' days');
		
		$feastImmaculateHeart = $easterDate->modify('+69 day');
		array_push($feastList, [$feastImmaculateHeart->format('n'), $feastImmaculateHeart->format('j'), 'OW00-ImmaculateHeart', 'தூய கன்னி மரியாவின் மாசற்ற இதயம்', 'Mem-Mary']);

		if($this->currentYear >= 2018) {
			$ordinaryTime2 = $easterDate->modify('+50 days');// Ordinary Time 2 starts on the Monday after Pentecost
			// Mary Mother of the Church - https://www.vatican.va/roman_curia/congregations/ccdds/documents/rc_con_ccdds_doc_20180324_notificazione-mater-ecclesiae_en.html
			array_push($feastList, [$ordinaryTime2->format('n'), $ordinaryTime2->format('j'), 'OW00-MaryMotherofChurch', 'தூய கன்னி மரியா, திரு அவையின் அன்னை', 'Mem-Mary']);
		}

		foreach ($feastList as $feast) {
            $month = $feast[0];
            $date = $feast[1];
            $name = $feast[2];
            $name_ta = $feast[3];
            $type = $feast[4];

			//Pass by reference to avoid copying the array
			$currentDay = &$this->fullYear[$month][$date];
			$newFeast = [
				'code' => $name,
				'rank' => $this->rcr->getRank($type),
				'type' => $type,
				'name_ta' => $name_ta
			];

			$other = [];

			// clash between mem and mem-Mary
			// following the liturgical tradition of pre-eminence amongst persons,
			// the Memorial of the Blessed Virgin Mary is to prevail
			if ((isset($currentDay[1])) && $currentDay[1]['rank'] == 10.2 && $newFeast['rank'] == 10.1) {
				if ($newFeast['code'] === 'OW00-ImmaculateHeart') {
					/*
					 * Exception:
					 * In years when Immaculate Heart memorial coincides with another obligatory memorial,
					 * as happened in 2014 [28 June, Saint Irenaeus] and 2015 [13 June, Saint Anthony of Padua],
					 * both must be considered optional for that year.
					 *
					 * https://www.vatican.va/roman_curia/congregations/ccdds/documents/rc_con_ccdds_doc_20000630_memoria-immaculati-cordis-mariae-virginis_lt.html
					 */
					$currentDay[1]['type'] = 'OpMem';
					$currentDay[1]['rank'] = $this->rcr->getRank('OpMem');

					$newFeast['type'] = 'OpMem';
					$newFeast['rank'] = $this->rcr->getRank('OpMem');
				} else {
					$other[] = $currentDay[1];
					unset($currentDay[1]);
				}
			}

			foreach ($currentDay as $key => $value) {
				if ($key == 0 || $key == 'other')
					continue;
				if ($newFeast['rank'] < $currentDay[$key]['rank']) {
					$other[] = $currentDay[$key];
					unset($currentDay[$key]);
				}
			}

			if ($newFeast['rank'] < $currentDay[0]['rank']) {

				$currentDay[] = $newFeast;
				// The above statement would have added $newFeast to the next index ie., 2 even if index 1 is not present.
				// Therefore, we are looping through the currentDay array and adding the feast.
				// For example, see Year 2020, Mary, Mother of Church Memorial on 1 June.
				$currentDay = array_values($currentDay);

			} elseif (intval($currentDay[0]['rank']) == 9) {
				// Rank 9 => Weekdays of Advent from Dec 17 to 24, Days within the octave of Christmas, Weekdays of Lent
				// Optional memorials that occur in these days will become commomeration

				// GILH 238 When any obligatory memorials falls during Lent, they are treated as optional memorials.

				$newFeast['type'] = 'OpMem-Commemoration';
				$newFeast['rank'] = $this->rcr->getRank('OpMem-Commemoration');

				$currentDay[] = $newFeast;
			} else {
				$other[] = $newFeast;
			}
			$currDate = new \DateTime($this->currentYear . '-' . $month . '-' . $date);
			$this->addOtherCelebration($currDate, $other);
		}
	}

    private function getCalendar($filter='/*/'): array {
        $rows = [];
        rewind($this->fileHandle);
        while (($data = fgetcsv($this->fileHandle, separator: ',', enclosure: '"', escape: "")) !== false) {


			if (preg_match($filter, $data[4]??'') === 1) {

				//Feast added year, previous years do not have this feast
				if(! empty($data[5]) && $data[5] > $this->currentYear){
					continue;
				}
				
				// Feast removed year, current year and future years do not have this feast
				if(! empty($data[6]) && $this->currentYear >= $data[6]){
					continue;
				}
				unset($data[5]); unset($data[6]);

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
		$this->fullYear[$mth][$day] = [0 => $feastDet]; // resets the entire array for the day with a single feast/memoria
		$this->addOtherCelebration($currDate, $temp);
	}

	/**
	 * Adds feasts/memoria that are supressed in this year
	 *
	 * @param DateTime $currDate
	 *        	- Date to be tagged
	 * @param array $feastDet
	 *        	- New feast/memoria to be added
	 */
	function addOtherCelebration($currDate, $feastDet): void {

		if (empty($feastDet))	return;

        //Christmas Week no ferial days because they are all fixed feasts.
		if (str_starts_with($feastDet[0]['code'], 'CW01-')) return;
		
		/**
		 * The Feast to be added to 'other' has 'other' in itself.
		 * Eg: 
		 * $feastDet = [ 
		 * 	0=> ['code' => 'Feast-Other', 'rank' => 8.6, 'type' => 'Feast-Other', 'name_ta' => ''],
		 * 	1=> ['code' => 'Feast-Other-2', 'rank' => 8.6, 'type' => 'Feast-Other-2', 'name_ta' => ''],
		 * 	'other' => [
		 * 		0 => ['code' => 'Feast-Other', 'rank' => 8.6, 'type' => 'Feast-Other', 'name_ta' => ''],
		 * 		1 => ['code' => 'Feast-Other-2', 'rank' => 8.6, 'type' => 'Feast-Other-2', 'name_ta' => ''],
		 * 		2 => ['code' => 'Feast-Other-3', 'rank' => 8.6, 'type' => 'Feast-Other-3', 'name_ta' => '']
		 * ];
		 * The Other has to be added to $feastDet as usual.
		 */
		if(isset($feastDet['other'])){
			// If the arrays contain numeric keys, 
			// the array_merge will not overwrite the original value, 
			// but will be appended.
			$feastDet = array_merge($feastDet, $feastDet['other']);
			unset($feastDet['other']);
		}

		$mth = $currDate->format('n');
		$day = $currDate->format('j');
		$this->fullYear[$mth][$day]['other'] = array_merge($this->fullYear[$mth][$day]['other']??[], $feastDet);
	}

}