<?php

/**
 * RomanCalendar 4.0
 * @author Br. Jayarathina Madharasan SDR
 */

use Medoo\Medoo;

require_once 'lib/config.php';
require_once 'lib/Medoo.php';
require_once 'RomanCalendarMovable.php';
require_once 'RomanCalendarRanks.php';
class RomanCalendarFixed extends RomanCalendarMovable {

	function __construct($currentYear) {
		parent::__construct($currentYear);

		$database = new Medoo(DB_PARAM);

		$clmList = ['feast_month', 'feast_date', 'feast_code', 'feast_type'];
		$odr = ['feast_month' => 'ASC', 'feast_date' => 'ASC'];

		foreach (CALENDAR as $key => $cal) {
			$feastList = $database->select($cal, $clmList, ['feast_type[~]' => 'Solemnity%', 'ORDER' => $odr]);
			$this->addSolemnityToYear($feastList);

			$feastList = $database->select($cal, $clmList, ['feast_type[~]' => 'Feast%', 'ORDER' => $odr]);
			$this->addFeastToYear($feastList);

			$feastList = $database->select($cal, $clmList, ['feast_type[~]' => ['Mem%', 'OpMem%'], 'ORDER' => $odr]);

			if ($key == 0) {
				$feastImmaculateHeart = $this->eastertideStarts->modify('+69 day');
				array_push($feastList, ['feast_month' => $feastImmaculateHeart->format('n'), 'feast_date' => $feastImmaculateHeart->format('j'), 'feast_code' => 'OW00-ImmaculateHeart', 'feast_type' => 'Mem-Mary']);

				// Mary Mother of the Church - https://www.vatican.va/roman_curia/congregations/ccdds/documents/rc_con_ccdds_doc_20180324_notificazione-mater-ecclesiae_en.html
				array_push($feastList, ['feast_month' => $this->ordinaryTime2Starts->format('n'), 'feast_date' => $this->ordinaryTime2Starts->format('j'), 'feast_code' => 'OW00-MaryMotherofChurch', 'feast_type' => 'Mem-Mary']);
			}
			$this->addMemoryToYear($feastList);
		}

		// GILH 240. On Saturdays in Ordinary Time, when optional memorials are permitted,
		// an optional memorial of the Blessed Virgin Mary may be celebrated in the same way as other memorials,
		// with its own proper reading.

		$OS_limits = [[$this->ordinaryTime1Starts, $this->lentStart], [$this->ordinaryTime2Starts, $this->adventStart]];
		$feastList = [];
		$rk = RomanCalendarRanks::getRank('OpMem');

		for ($i = 0; $i < sizeof($OS_limits); $i++) {
			$tempDate = DateTime::createFromImmutable($OS_limits[$i][0]);
			do {
				$tempDate = $tempDate->modify('next saturday');
				$mth = $tempDate->format('n');
				$day = $tempDate->format('j');

				if ($this->fullYear[$mth][$day][0]['rank'] < $rk)
					continue;
				if (isset($this->fullYear[$mth][$day][1]['rank']) && $this->fullYear[$mth][$day][1]['rank'] <= $rk)
					continue;

				$feastList[] = ['feast_month' => $mth, 'feast_date' => $day, 'feast_code' => 'Mem-Mary-Sat', 'feast_type' => 'OpMem'];
			} while ($tempDate < $OS_limits[$i][1]);
		}
		$this->addMemoryToYear($feastList);
	}

	/**
	 * Adds the given Solemnity to RomanCalendarYear variable
	 *
	 * @param array $FeastList
	 */
	function addSolemnityToYear($FeastList) {
		foreach ($FeastList as $feastDet) {

			if ($feastDet['feast_month'] == 11 && $feastDet['feast_date'] == 2) {
				$feastDet['feast_type'] = 'All Souls';
			}

			$feastRank = RomanCalendarRanks::getRank($feastDet['feast_type']);

			$currentDay = $this->fullYear[$feastDet['feast_month']][$feastDet['feast_date']];
			$currentDayRank = (!empty($currentDay)) ? $currentDay[0]['rank'] : 50;

			$tempDate = new DateTime($this->currentYear . '-' . $feastDet['feast_month'] . '-' . $feastDet['feast_date']);

			if (!($feastRank < $currentDayRank)) {
				// Conflict is between ranks 1 to 4; See RomanCalendarRanks.php for ranks

				// If a fixed date Solemnity occurs on a Sunday of Lent or Advent, the Solemnity is transferred to the following Monday.
				// This affects Joseph, Husband of Mary (Mar 19), Annunciation (Mar 25), and Immaculate Conception (Dec 8).
				if ($feastDet['feast_date'] == 19 && preg_match("/^LW06/", $currentDay[0]['code']) === 1) {
					// If Joseph, Husband of Mary (Mar 19) falls on Palm Sunday or during Holy Week, it is moved to the Saturday preceding Palm Sunday.
					// This Solemnity can never occur on Holy Sat
					$tempDate->modify('last saturday');
				} elseif ($feastDet['feast_date'] == 25 && preg_match("/^LW06-0Sun/", $currentDay[0]['code']) === 1) {
					// If the Annunciation (Mar 25) falls on Palm Sunday, it is celebrated on the Saturday preceding.
					$tempDate->modify('last saturday');
				} elseif ($feastDet['feast_date'] == 25 && (preg_match("/^LW06|EW01/", $currentDay[0]['code']) === 1)) {
					// If Annunciation falls during Holy Week or within the Octave of Easter, it is transferred to the Monday of the Second Week of Easter.
					// 2008, 2013, 2016
					$tempDate = $this->eastertideStarts->modify('+ 8 days');
				} elseif ($feastDet['feast_code'] == 'Birth of Saint John the Baptist' && $currentDay[0]['code'] == 'OW00-SacredHeart' && $this->currentYear == 2022) {
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
					// - with each other [General Calendar: 24 June 2022, Birth of St. John the Baptist and Sacred Heart](This case is addressed in previous 'else' section)
					// - Particular Calendar (Rank 4) and General Calendar
					// These should be moved to the nearest ferial day

					do {
						// Go to next day and check for its rank
						$tempDate->modify('+ 1 days');
						$currentDay_ = $this->fullYear[$tempDate->format('n')][$tempDate->format('j')];
					} while ($feastRank > $currentDay_[0]['rank']);
				}
			}

			$newFeast = ['code' => $feastDet['feast_code'], 'rank' => $feastRank, 'type' => $feastDet['feast_type']];
			$this->pushDayCode($tempDate, $newFeast);
		}
	}

	/**
	 * Adds the given Feast to RomanCalendarYear variable.
	 * Feast type (feast_type) could be either feast or Feasts of the Lord
	 *
	 * @param array $FeastList
	 */
	function addFeastToYear($FeastList) {
		foreach ($FeastList as $feastDet) {
			$currDate = new DateTime($this->currentYear . '-' . $feastDet['feast_month'] . '-' . $feastDet['feast_date']);
			$currentDayRank = $this->fullYear[$feastDet['feast_month']][$feastDet['feast_date']][0]['rank'];

			$newFeast = ['code' => $feastDet['feast_code'], 'rank' => RomanCalendarRanks::getRank($feastDet['feast_type']), 'type' => $feastDet['feast_type']];

			if ($newFeast['rank'] < $currentDayRank) {
				$this->pushDayCode($currDate, $newFeast);
			} else {
				$this->addOtherFeast($currDate, [$newFeast]);
			}
		}
	}

	/**
	 * Adds the given Memory or optional memory to RomanCalendarYear variable.
	 *
	 * @param array $FeastList
	 */
	function addMemoryToYear($memoryList) {
		foreach ($memoryList as $feastDet) {

			$currentDay = &$this->fullYear[$feastDet['feast_month']][$feastDet['feast_date']];

			$newFeast = ['code' => $feastDet['feast_code'], 'rank' => RomanCalendarRanks::getRank($feastDet['feast_type']), 'type' => $feastDet['feast_type']];

			if (preg_match("/^[LW|AW05]/", $currentDay[0]['code']) === 1) {
				// Optional memorials that occour between Dec17-Dec24, Dec-25-Jan1 or during Lent will become commomeration
				// If a fixed date Memorial or Optional Memorial falls within the Lenten season, it is reduced in rank to a Commemoration.
				$newFeast['type'] = 'OpMem-Commomeration';
			}


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
					$currentDay[1]['rank'] = RomanCalendarRanks::getRank('OpMem');

					$newFeast['type'] = 'OpMem';
					$newFeast['rank'] = RomanCalendarRanks::getRank('OpMem');
				} else {
					$other[] = $currentDay[1];
					unset($currentDay[1]);
				}
			}

			// clash between mem and opMem
			// eg. May 05, 2015 May Mother of God clashes with multiple opMem
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
			} elseif (intval($currentDay[0]['rank']) == 9) {
				//GILH 238 When any [obligatory memorials] happen to fall during Lent in a given year, they are treated as optional memorials.
				$newFeast['type'] = 'OpMem-Commomeration';
				$newFeast['rank'] = RomanCalendarRanks::getRank('OpMem');

				$currentDay[] = $newFeast;
			} else {
				$other[] = $newFeast;
			}

			$currDate = new DateTime($this->currentYear . '-' . $feastDet['feast_month'] . '-' . $feastDet['feast_date']);
			$this->addOtherFeast($currDate, $other);
		}
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
