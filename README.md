# Roman-Calendar v 5.0
This generates the Liturgical Calendar of the Roman Rite of the Catholic Church (using PHP). I decided to start a new project because there are no open source libraries available that have dealt with every single caveat of computing the Catholic liturgical year. 

This project acts as a backbone for my [Tamil Lectionary](https://github.com/jayarathina/Tamil-Catholic-Lectionary) and [Tamil Breviary](https://github.com/jayarathina/Tamil-Breviary) projects.

## Features
- Solemnities, Feasts, Memory, Optional Memory etc., for a particular year with **proper liturgical colors** are generated.
- Ability to **add local calendars**.
- Created with programmers in mind, so that programmers can **easily extend and build upon this**.
- Well documented code with **necessary comments for clarity**. 
- Can be easily translated into any language. English translation of the raw data is provided in the HTML representation of the data. See [RomanCalendarRenderHTML.php](src/RomanCalendarRenderHTML.php) and [RomanCalendarRanks.php](src/RomanCalendarRanks.php)
### New in Version 5.0
- No database required. (Removed MySQL dependancy)
- BUGFIX: Added backward compatibility. (That is if a new feast is added to the universal calendar this year. Previous year calendars generated will not have that feast. For example, the commemoration of St. Mary Magdalene raised to a Feast by Pope Francis only in 2016 ([ref](http://en.radiovaticana.va/news/2016/06/10/commemoration_of_st_mary_magdalene_raised_to_a_feast/1236157)), therefore, if you generate a calendar for the year 2008, it will not be marked as a feast in that year)
 
## Requirements
* PHP 8+

## Before Starting
- Specific settings like the Solemnities of Epiphany, Ascension and Corpus Christi occouring on sundays are passed through variables.

## Structure of the csv file
- [calendar.csv](src/calendar.csv) file has the list of feast. You can add local calendars to the end of the file.
- The file does not follow strict csv standards. So, you can use simple text edior to edit it.
- It has the following columns:
  * `feast_month` - Month of the feast
  * `feast_date` - Date of the feast
  * `feast_code` - Name of the feast. (Usually the saints name). It is desired it to be unique, so that it can be used as a primary identifier when that data is used by other packages.
  * `feast_type` - Solemnity, Feast, Mem, OpMem etc., (Refer [RomanCalendarRanks.php](src/RomanCalendarRanks.php) for the complete list)
  * `added_year` - Year in which the feast was added to the General Roman Calendar. (Optional)
  * `removed_year` - Year in which the feast was removed from the General Roman Calendar. Even if the feast was modifed, it is recorded as removed and added as a new entry for backward compatibility. (Optional)

## Structure of Data Generated

It is of a three-dimensional array. Outer most key has the month number and next has the day number. Which is followed by individual feast descriptions. The following is a sample for January 1st.

```PHP
Array (
    [1] => Array (
        [1] => Array (
            [0] => Array (
                [code] => Blessed Virgin Mary, the Mother of God
                [rank] => 3.1
                [type] => Solemnity
                [name] => Blessed Virgin Mary, the Mother of God
                [color] => white
            )
        )
    )
)
```

The following are the data for a particular event:
- `code` - Acts as a unique identifier for that feast. It is usually the name of the feast/saint celebrated on that day. See below for how it is generated for ferial days.
- `rank` - Optional field. It has the rank for that feast which is used to determine its priority. (See [RomanCalendarRanks.php](src/RomanCalendarRanks.php) for more details.)
- ` type ` - Type of celebration. (Solemnity, Feast, memory and other.)
- `color` - Mass vestament colour for that celebration
-  `other` - Optional key that contains feast/memorials that are supressed that year.

Ferial weekday code is generated in the following syntax: `<SEASON CODE><WEEK NUMBER>-<DAY NUMBER><DAY NAME>`. They stand for the following:
* `SEASON CODE` can be one of the following:
  * EW – Easter Week
  * OW – Ordinary Week
  * AW – Advent Week
  * LW – Lent Week
  * CW – Christmas week
* `WEEK NUMBER` is the number of the week in that season (2 chars). This is usually the liturgical week number, but there can be some special cases like the days between Ash Wednesday and the first Sunday of lent is counted as week 0 of lent. Similarly, days between Dec 17 and Dec 24 are counted as week 5 of Advent. A similar rule applies to the weeks before and after epiphany too. This is done for calculation purposes.
* `DAY NUMBER DAY NAME` gives the number (for sorting) and three-letter abbreviation (readability) of the weekday within that week. (0Sun, 1Mon, 2Tue etc.,).

### Examples: 
* `OW04-0Sun` represents Fourth Sunday in Ordinary Time
* `LW03-4Thu` represents Thursday of the 3rd Week of Lent

A sample data is saved as JSON file in the [dat folder](dat/).

## Known Issues
* None

## Suggestions or Comments
If you find any bugs or suggest any improvement, please feel free to contact me.
