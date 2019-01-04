# Roman-Calendar
This is a Roman Catholic Liturgical Calendar Generator in PHP. I decided to start a new project because there are no open source libraries available that has dealt with every single caveat of computing a liturgical year. This project acts as a backbone for my [Tamil-Catholic-Lectionary](https://github.com/jayarathina/Tamil-Catholic-Lectionary) project.

## Features of this library include:
- Solemnities, Feasts, Memory, Optional Memory etc., for a particular year with **proper liturgical colors** are generated.
- Ability to **add local calendars** (Using JSON or database).
- Created with programmers in mind, so that programmers can **easily extend and build upon this**.
- Easy to read with **necessary comments for clarity**. 
- Can be easily translated into any language. (English Translation of the raw data is provided in the HTML representation of the data. See: [RomanCalendarRenderHTML.php](lib/RomanCalendarRenderHTML.php)

## Dependencies
This is a PHP poject. The project uses JSON flat file as data source. But _If_ you want to use a database (See getDataFromDB() in [RomanCalendar.php](lib/RomanCalendar.php)), the [Medoo library](http://medoo.in) is required. Sample DB structure is found at [liturgy_lectionary.sql](mysql/liturgy_lectionary.sql) which have Tamil data used for translation. You can build upon it to tanslate other languages.

## Points to Note
For each day the name of the feast (if any) acts as a unique identifier for that feast. For a ferial weekday (with no feast) a code is generated in the following syntax: `<SEASON CODE><WEEK NUMBER>-<DAY NUMBER><DAY NAME>`. They stand for the following:
* `SEASON CODE` can be one of the following:
  * EW – Easter Week
  * OW – Ordinary Week
  * AW – Advent Week
  * LW – Lent Week
  * CW – Christmas week
* `WEEK NUMBER` is the number of week in that season. (Length is 2 chars). This may have some special cases like the days between Ash Wednesday and first Sunday of lent is counted as week 0 of lent. Similarly days between Dec 17 and Dec 24 is counted as week 5 of Advent. Similar rule applies to the weeks before and after epiphany too. This is done for easier calculation purposes, since for these days week number does not appear in the title of the day.
* `DAY NUMBER DAY NAME` gives the number and three letter abbreviation of the weekday within that week. (0Sun, 1Mon, 2Tue etc.,). This is to help in sorting and readability.

### Examples: 
* `OW04-0Sun` represents Ordinary Week Four Sunday
* `LW03-4Thu` represents Lent Week Three Thursday

## Known Issues
* Only one local calendar can be added as of now without clashes. If more than one local calendar (A religious Society and a Country) then there is a posibility of more than one solemnity clashes with each other. In that case, the result is undetermined. This will be fixed in the future if need arises.
* This library is Not backward compatible. (That is if a new feast is added to the calendar this year. Previous year calendars generated will have that feast too. For example commemoration of St. Mary Magdalene raised to a Feast by Pope Francis only in 2016 ([ref](http://en.radiovaticana.va/news/2016/06/10/commemoration_of_st_mary_magdalene_raised_to_a_feast/1236157)), but if you generate calendar for the year 2008, it will be marked as a feast then too)

## Suggestions or Comments
If you find any bug or suggest any improvement, please feel free to raise a pull request or contact me.
