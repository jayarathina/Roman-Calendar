<?php

//DB Connection
const DB_PARAM = [
    'type' => 'mysql',
    'host' => 'localhost',
    'database' => 'liturgy_breviary',
    'username' => 'root',
    'password' => ''
];


// Adding more than two calendars might cause undetermined results as two solemnities might occur on same day.
const CALENDAR = ['generalcalendar', 'generalcalendar__india'];
const CALENDARSUFIX = ['', 'இந்தியாவில்'];

const EPIPHANY_ON_A_SUNDAY = true;
const ASCENSION_ON_A_SUNDAY = true;
const CORPUSCHRISTI_ON_A_SUNDAY = true;
