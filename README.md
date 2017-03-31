# Roman-Calendar
This is a Roman Catholic Liturgical Calendar Generator in PHP. It generates the Catholic Liturgical Calendar. This project acts as a backbone for my [Tamil-Catholic-Lectionary project](https://github.com/jayarathina/Tamil-Catholic-Lectionary). (I decided to separate these both because verse subdivisions (Not the actual readings) in lectionary differ based on the translation used. Hence it would be better if a separate project was started for Tamil Language)

## Features of this library include:
- Solemnities, Feasts, Memory, Optional Memory etc., for a particular year with proper color codes are generated.
- Ability to add local calendars (Using JSON).
- Created with programmers in mind, so that programmers can easily embed or extend it in their own website.
- Designed to modular, easy to read with necessary comments for clarity. 
- Can be easily translated into any language. (English Translation of the raw data is provided in the HTML representation of the data.)

## Points to Note
For each day the name of the feast (if any) acts as a unique identifier for that feast. For a weekday (with no feast) a code is generated in the following syntax: `<SEASON CODE><WEEK NUMBER>-<DAY NUMBER><DAY NAME>`. They stand for the following:
* `SEASON CODE` can be one of the following:
  * EW – Easter Week
  * OW – Ordinary Week
  * AW – Advent Week
  * LW – Lent Week
  * CW – Christmas week
* `WEEK NUMBER` is the number of week in that season. (Length is 2 chars). This may have some special cases like the days between Ash Wednesday and first Sunday of lent is counted as week 0 of lent. Similarly days between Dec 17 and Dec 24 is counted as week 5 of Advent. Similar rule applies to the weeks before and after epiphany too. This is done for easier calculation purposes, since for these days week number does not appear in the title of the day.
* `DAY NUMBER DAY NAME` gives the number and three letter abbreviation of the weekday within that week. (0Sun, 1Mon, 2Tue etc.,). It is to be noted that we have both of these rather than only one because the former helps in sorting, latter in readability. 

### For example: 
* `OW04-0Sun` represents Ordinary Week Four Sunday
* `LW03-4Thu` represents Lent Week Three Thursday

## Known Issues
* Only one local calendar (Probably your country) can be added as of now. For more than one local calendar (Probably a religious Society and a country - more than one solemnity clashes with each other) the result is undetermined.
* Not backward compatible. (That is if a new feast is added to the calendar this year. Previous year calendars generated will have that feast too. For example commemoration of St. Mary Magdalene raised to a Feast by Pope Francis in 2016 ([ref](http://en.radiovaticana.va/news/2016/06/10/commemoration_of_st_mary_magdalene_raised_to_a_feast/1236157), if you generate calendar for the year 2008, it will be representaed as a feast then too))

## Suggestions or Comments
If you find any bug or suggest any improvement, please feel free to raise a pull request or contact me.
