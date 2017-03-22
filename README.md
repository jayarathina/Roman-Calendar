# Roman-Calendar
This is a Roman Catholic Liturgical Calendar Generator in PHP. It also has provisions to add local calendars. If you find any bug or suggest any improvement, please feel free to raise a pull request or 

Please note that this is *NOT designed for end users*, but for programmers in mind, so that they can easily embed or extend it to their own website.
This project acts as a backbone for my Tamil-Catholic-Lectionary project.
The code is modular, easy to read and added necessary comments for easy usage. 

For each day the name of the feast (if any) acts as a unique identifier for that feast. For a weekday (with no feast) a code is generated in the following syntax: `<SEASON CODE><WEEK NUMBER>-<DAY NUMBER><DAY NAME>`

* `SEASON CODE` can be one of the following:
  * EW – Easter Week
  * OW – Ordinary Week
  * AW – Advent Week
  * LW – Lent Week
  * CW – Christmas week
* `WEEK NUMBER` is the number of week in that season. (Size is always 2) This may have some special exceptions like the days between Ash Wednesday and first Sunday of lent is counted as week 0 of lent. Similarly days between Dec 17 and Dec 24 is counted as week 5. Similar rule applies to the weeks before and after epiphany.
* `DAY NUMBER` gives the number of the weekday within that week. (Sun – 0, Mon – 1 etc.,) and `DAY NAME` gives a three letter abbreviation of the current day name. It is to be noted that we have both of these rather than having a single segment is that it helps in readability and in sorting.
