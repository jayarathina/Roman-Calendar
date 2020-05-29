<?php
/**
 * Table of Liturgical Days According To Their Order of Precedence
 *
 * I
 *
 * 1. Easter triduum of the Lord's passion and resurrection
 *
 * 2.1 Christmas, Epiphany, Ascension, and Pentecost, Sundays of Advent, Lent, and the season of Easter
 * 2.2 Ash Wednesday
 * 2.3 Weekdays of the Holy Week, Monday to Thursday inclusive
 * 2.4 Days within the octave of Easter
 *
 * 3.1 Solemnities of the Lord, the Blessed Virgin Mary, and saints listed in the general calendar
 * 3.2 All Souls' Day
 *
 * 4. Proper solemnities, namely:
 * 4.1 Solemnity of the principal patron of the place, city, or state
 * 4.2 Solemnity of the dedication and anniversary of the dedication of a particular church
 * 4.3 Solemnity of the titular saint of a particular church
 * 4.4 Solemnity of the titular saint, founder, or principal patron of an order or congregation
 *
 * II
 *
 * 5. Feasts of the Lord in the general calendar
 *
 * 6. Sundays of the Christmas season and Sundays in ordinary time
 *
 * 7. Feasts of the Blessed Virgin Mary and of the saints in the general calendar
 *
 * 8. Proper feasts, namely:
 * 8.1 Feast of the principal patron of the diocese
 * 8.2 Feast of the anniversary of the dedication of the cathedral
 * 8.3 Feast of the principal patron of the territory, province, country, or more extensive territory
 * 8.4 Feast of the titular saint, founder, or principal patron of an order or congregation and religious province, observing the directives in no. 4
 * 8.5 Other feasts proper to an individual church
 * 8.6 Other feasts listed in the calendar of the diocese, order, or congregation
 *
 * 9.1 Weekdays of Advent from December 17 to December 24 inclusive
 * 9.2 Days within the octave of Christmas
 * 9.3 Weekdays of Lent
 *
 * III
 *
 * 10. Obligatory memorials
 * 10.1 Obligatory memorials of Blessed Virgin Mary in the general calendar (following the liturgical tradition of pre-eminence amongst persons, if there is a clash, the Memorial of BVM is to prevail)
 * 10.2 Other Obligatory memorials in the general calendar
 *
 * 11. Proper obligatory memorials, namely:
 * 11.1 Memorial of a secondary patron of the place, diocese, region or province, country, or more extensive territory; or of an order, congregation, or religious province
 * 11.2 Obligatory memorials proper to an individual church
 * 11.3 Obligatory memorials listed in the calendar of a diocese, order, or congregation
 *
 * 12. Optional memorials
 *
 * 13.1 Weekdays of Advent up to December 16 inclusive
 * 13.2 Weekdays of the Christmas season from January 2 until the Saturday after Epiphany
 * 13.3 Weekdays of the Easter season from Monday after the octave of Easter until the Saturday before Pentecost inclusive
 * 13.4 Weekdays in ordinary time
 *
 *
 * Concurrent Celebrations
 *
 * If several celebrations fall on the same day, the one that holds the higher rank according to the above table is observed.
 * A solemnity, however, which is impeded by a liturgical day that takes precedence over it should be transferred to the
 * closest day which is not a day listed in nos. 1-8 in the table of precedence, the rule of no. 5 remaining in effect.
 * Other celebrations are omitted that year.
 *
 * Optional memorials (may be observed even on the days in no. 9)
 * In the same manner obligatory memorials may be celebrated as optional memorials if they happen to fall on the Lenten weekdays.
 */
class RomanCalendarRanks {
	
	/**
	 * Get rank of a given dayCode
	 *
	 * @param string $dayCode
	 * @return number
	 */
	public static function getRank($dayCode) {
		$RomanCalendarRanks = array (
				// Easter Tridum
				'EW01-0Sun' => 1, // Easter
				'LW06-6Sat' => 1.1,
				'LW06-5Fri' => 1.1,
				'LW06-4Thu' => 1.1,
				
				'Nativity of the Lord' => 2, // Christmas Code generated in movable feast is CW01-Dec25; will be superceeded later
				'CW03-Epiphany' => 2, // Epiphany
				'EW07-Ascension' => 2, // Assension
				'EW08-Pentecost' => 2, // Pentecost
				
				'AW\d{2}-0Sun' => 2.1, // Sundays of Advent
				'LW\d{2}-0Sun' => 2.1, // Sundays of Lent
				'EW\d{2}-0Sun' => 2.1, // Sundays of Easter
				'LW00-3Wed' => 2.2, // Ash Wednesday
				'LW06' => 2.3, // Weekdays of the Holy Week, Monday to Thursday inclusive; Palm sunday, Good friday and Holy Saturday ranked above
				'EW01' => 2.4, // Easter Octave EW02-0Sun is ranked above
				               
				// Solemnities of the Lord, the Blessed Virgin Mary, and saints listed in the general calendar and All Souls Day
				'Solemnity' => 3,
				'OW00-Trinity' => 3, // Trinity Sunday
				'OW00-CorpusChristi' => 3, // Corpus Christi
				'OW00-SacredHeart' => 3, // Sacred Heart
				'OW34-0Sun' => 3, // Christ the King
				'All Souls' => 3.1,
				
				// Solemities of Particular Calendars
				'Solemnity-PrincipalPartron-Place' => 4.1, // Principal Patron of a town, city or a similar place
				'Solemnity-ChurchDedication' => 4.2, // The dedication and Anniversay of the Dedication of one's own church
				'Solemnity-OwnChurch' => 4.3, // The titular of one's own church
				'Solemnity-Religious' => 4.3, // The titular or Holy Founder or Principal Patron of a Religious Order or Congregation
				                              
				// Feasts of the Lord in the general calendar: (Baptism of the Lord, Presentation of the Lord, Transfiguration, Triumph of the Cross, Holy Family)
				'Feast-Lord' => 5,
				'CW04-Baptism' => 5,
				'CW01-HolyFamily' => 5,
				
				// Sundays of the Christmas season and Sundays in ordinary time
				'OW\d{2}-0Sun' => 6,
				'CW\d{2}-Sun' => 6,
				
				'Feast' => 7, // Feasts of the Blessed Virgin Mary and of the saints in the general calendar
				              
				// Feasts of Particular Calendar
				'Feast-PrincipalPartron-Diocese' => 8.1, // The principal patron of a diocese
				'Feast-CathedralDedication' => 8.2, // The anniversary of the dedication of the cathedral
				'Feast-PrincipalPartron-Place' => 8.3, // The principal patron of the territory, province, country, or more extensive territory
				'Feast-Religious' => 8.4, // The titular saint, founder, or principal patron of an order or congregation and religious province, observing the directives
				'Feast-OwnChurch' => 8.5, // Other feasts proper to one's own church
				'Feast-Other' => 8.6, // Other feasts listed in the calendar of the diocese, order, or congregation
				
				'AW05' => 9.1, // Weekdays of Advent from December 17 to December 24 inclusive
				'CW01' => 9.2, // Days within the octave of Christmas - Jan 1 is ranked as solemnity
				'LW' => 9.3, // Weekdays of Lent
				
				'Mem-Mary' => 10.1, // Obligatory memorials of BVM in the general calendar
				'Mem' => 10.2, // Obligatory memorials in the general calendar
				               
				// Obligatory memorials of Particular Calendar
				'Mem-SecondaryPatron' => 11.1, // Memorial of a secondary patron of the place, diocese, region or province, country, or more extensive territory; or of an order, congregation, or religious province
				'Mem-OwnChurch' => 11.2, // Obligatory memorials proper to one's own church
				'Mem-Other' => 11.3, // Obligatory memorials listed in the calendar of a diocese, order, or congregation
				
				'OpMem' => 12, // Optional memorials, may be observed even on the days in no. 9.
				
				'AW' => 13.1, // Weekdays of Advent up to December 16 inclusive
				'CW' => 13.2, // Weekdays of the Christmas season from January 2 until the Saturday after Epiphany
				'EW' => 13.3, // Weekdays of the Easter season from Monday after the octave of Easter until the Saturday before Pentecost inclusive
				'OW' => 13.4, // Weekdays in ordinary time
				
				'Commomeration' => 14 
		);
		
		$keys = array_keys ( $RomanCalendarRanks );
		foreach ( $keys as $str ) {
			if (preg_match ( "/^$str/", $dayCode ) === 1) {
				return $RomanCalendarRanks [$str];
			}
		}
		die ( 'ERROR: Invalid Feast Code : ' . $dayCode ); // This should never happen
	}
}

?>