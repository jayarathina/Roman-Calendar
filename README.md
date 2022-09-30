# Roman-Calendar
This is a Roman Catholic Liturgical Calendar Generator in PHP. I decided to start a new project because there are no open source libraries available that have dealt with every single caveat of computing the Catholic liturgical year.

This project acts as a backbone for my [Tamil Lectionary](https://github.com/jayarathina/Tamil-Catholic-Lectionary) and [Tamil Breviary](https://github.com/jayarathina/Tamil-Breviary) projects.

## Features
- Solemnities, Feasts, Memory, Optional Memory etc., for a particular year with **proper liturgical colors** are generated.
- Ability to **add local calendars** (Using a separate table in the database).
- Created with programmers in mind, so that programmers can **easily extend and build upon this**.
- Well documented code with **necessary comments for clarity**. 
- Can be easily translated into any language. English translation of the raw data is provided in the HTML representation of the data. See [RomanCalendarRenderHTML.php](lib/RomanCalendar/RomanCalendar.php)
 
## Requirements
* PHP 8+ is a must.
* SQL database, preferably MySQL (but works with many other SQL databases too)
* The [Medoo library](http://medoo.in) (included in the package)

## Flow

Specific settings like the Solemnities of Epiphany, Ascension and Corpus Christi can be set in [config.php](lib/config.php).

The project generates a JSON file for each year. Once generated, this JSON file can be used without the database. (The [Medoo library](http://medoo.in) is required for DataBase access. It comes with the package.)

Database user name and password are to be set in [config.php](lib/config.php).

Sample DB structure is found at [liturgy_lectionary.sql](mysql/liturgy_lectionary.sql). It uses two tables: 
  - `generalcalendar` - General Roman Calendar
  - `generalcalendar__india` - Local Calendar for India.

You can add separate tables for each of your local calendars and place the table names in [config.php](lib/config.php).

The tables have a column with Tamil data used for translation. You can use it as a sample to translate into any other language.

## Structure of JSON Generated
The content of the JSON file that is generated is of two-dimensional array. Outer most key has the month number and next has the day number. Which is followed by individual feast descriptions. The following is a sample for January 3rd.
```JSON
{
    "1": {
        "3": [
            {
                "code": "CW03-Day1",
                "rank": 13.2,
                "color": "white"
            }
        ],
    }
}
```

The feast description contains three values for ferial days. Others may have fourth value `rank` for example the value for January 01 will be:
```JSON
{
    "1": {
        "1": [
            {
                "code": "Blessed Virgin Mary, the Mother of God",
                "rank": 3.1,
                "type": "Solemnity",
                "color": "white"
            }
        ],
.....
```
There can also be a special `other` key that contains feast/memorials that are supressed that year.

The following are the data keys:
- `code` - Acts as a unique identifier for that feast. It is usually the name of the feast/saint celebrated on that day. See below for how it is generated for ferial days.
- `rank` - Computed rank for that feast. Used to determine priority. (See [RomanCalendarRanks.php](lib/RomanCalendar/RomanCalendarRanks.php) for more details.)
- ` type ` - Type of celebration. Solemnity, Feast, memory and other.
- `color` - Mass vestament colour for that celebration

For a ferial weekday a code is generated in the following syntax: `<SEASON CODE><WEEK NUMBER>-<DAY NUMBER><DAY NAME>`. They stand for the following:
* `SEASON CODE` can be one of the following:
  * EW – Easter Week
  * OW – Ordinary Week
  * AW – Advent Week
  * LW – Lent Week
  * CW – Christmas week
* `WEEK NUMBER` is the number of the week in that season (2 chars). This is usually the liturgical week number, but there can be some special cases like the days between Ash Wednesday and the first Sunday of lent is counted as week 0 of lent. Similarly, days between Dec 17 and Dec 24 are counted as week 5 of Advent. A similar rule applies to the weeks before and after epiphany too. This is done for calculation purposes.
* `DAY NUMBER DAY NAME` gives the number (for sorting) and three-letter abbreviation (readability) of the weekday within that week. (0Sun, 1Mon, 2Tue etc.,).

### Examples: 
* `OW04-0Sun` represents Ordinary Week Four Sunday
* `LW03-4Thu` represents Lent Week Three Thursday

## Known Issues
* If in more than one local calendar (eg. A religious Society and A Diocese) then there is a possibility of more than one solemnity of the same rank clashing with each other. (See [RomanCalendarRanks.php](lib/RomanCalendar/RomanCalendarRanks.php) for how ranks are calculated). In such a case, the result is undetermined. In these cases, those solemnities are to be manually added to their proper dates.
* This library is Not backwards compatible. (That is if a new feast is added to the universal calendar this year. Previous year calendars generated will have that feast too. For example, the commemoration of St. Mary Magdalene raised to a Feast by Pope Francis only in 2016 ([ref](http://en.radiovaticana.va/news/2016/06/10/commemoration_of_st_mary_magdalene_raised_to_a_feast/1236157)), but if you generate a calendar for the year 2008, it will be marked as a feast then too)

## Suggestions or Comments
If you find any bugs or suggest any improvement, please feel free to raise a pull request or contact me.