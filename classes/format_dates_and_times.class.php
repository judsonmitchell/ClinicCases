<?php
session_start();
if (isset($_GET))
{
$time = $_GET[time];
}
/* formats mysql date and  the time, with time zone correction, prints result */
function formatDate($time)
{
$get_date = explode(' ',$time);
$date_raw = $get_date[0];
$split_raw_date = explode('-',$date_raw);
$year = $split_raw_date[0];
$month = $split_raw_date[1];
$day = $split_raw_date[2];
/* This section deals with the time */
$time = $get_date[1];
$time_parts = explode(':',$time);
$hour = $time_parts[0];
$user = $_SESSION['login'];
$get_offset = mysql_query("SELECT `timezone_offset` FROM `cm_users` WHERE `username` = '$user' LIMIT 1");
while ($r = mysql_fetch_array($get_offset))
{
$the_offset = $r['timezone_offset'];
}

$corrected_hour = $hour + $the_offset;
if ($corrected_hour == 12):
$corrected_hour_non24 = $corrected_hour;
$ampm = " p.m.";

elseif ($corrected_hour > 12):

$corrected_hour_non24 = $corrected_hour - 12;
$ampm = " p.m.";

else:

$corrected_hour_non24 = $corrected_hour;
$ampm = " a.m.";
endif;

$corrected_time = "$corrected_hour_non24" . ":" . "$time_parts[1]" . "$ampm";


echo "$month" . "/" . "$day" . "/" . "$year" . " " . "$corrected_time";


}

/* formats the date and the time, with time zone correction, returns string */

function formatDateAsVar($time)
{
$get_date = explode(' ',$time);
$date_raw = $get_date[0];
$split_raw_date = explode('-',$date_raw);
$year = $split_raw_date[0];
$month = $split_raw_date[1];
$day = $split_raw_date[2];
/* This section deals with the time */
$time = $get_date[1];
$time_parts = explode(':',$time);
$hour = $time_parts[0];
$user = $_SESSION['login'];
$get_offset = mysql_query("SELECT `timezone_offset` FROM `cm_users` WHERE `username` = '$user' LIMIT 1");
while ($r = mysql_fetch_array($get_offset))
{
$the_offset = $r['timezone_offset'];
}

$corrected_hour = $hour + $the_offset;
if ($corrected_hour > 12)
{
$corrected_hour_non24 = $corrected_hour - 12;
$ampm = " p.m.";
}
else
{
$corrected_hour_non24 = $corrected_hour;
$ampm = " a.m.";
}
$corrected_time = "$corrected_hour_non24" . ":" . "$time_parts[1]" . "$ampm";


$date_correct =  "$month" . "/" . "$day" . "/" . "$year" . " " . "$corrected_time";
$info = array($date_correct);
return $info;

}

/* formats the date, chops time, with time zone correction(?), prints result */
function formatDateNoTime($time)
{
$get_date = explode(' ',$time);
$date_raw = $get_date[0];
$split_raw_date = explode('-',$date_raw);
$year = $split_raw_date[0];
$month = $split_raw_date[1];
$day = $split_raw_date[2];
/* This section deals with the time */
$time = $get_date[1];
$time_parts = explode(':',$time);
$hour = $time_parts[0];
$user = $_SESSION['login'];
$get_offset = mysql_query("SELECT `timezone_offset` FROM `cm_users` WHERE `username` = '$user' LIMIT 1");
while ($r = mysql_fetch_array($get_offset))
{
$the_offset = $r['timezone_offset'];
}

$corrected_hour = $hour + $the_offset;
if ($corrected_hour > 12)
{
$corrected_hour_non24 = $corrected_hour - 12;
$ampm = " p.m.";
}
else
{
$corrected_hour_non24 = $corrected_hour;
$ampm = " a.m.";
}
$corrected_time = "$corrected_hour_non24" . ":" . "$time_parts[1]" . "$ampm";


echo "$month" . "/" . "$day" . "/" . "$year";
}

/* formats a mysql date string, chops time, prints result */
function formatDateHuman($time)
{
$get_date = explode(' ',$time);
$date_raw = $get_date[0];
$split_raw_date = explode('-',$date_raw);
$year = $split_raw_date[0];
$month = $split_raw_date[1];
$day = $split_raw_date[2];
echo "$month" . "/" . "$day" . "/" . "$year";
}

/* formats a mysql date string, chops time, returns string */

function formatDate2($time)
{
$get_date = explode(' ',$time);
$date_raw = $get_date[0];
$split_raw_date = explode('-',$date_raw);
$year = $split_raw_date[0];
$month = $split_raw_date[1];
$day = $split_raw_date[2];
$result =  "$month" . "/" . "$day" . "/" . "$year";
return $result;

}


/* does the same as one before, needs to be deleted */

function formatDateAsVarHuman($time)
{
$get_date = explode(' ',$time);
$date_raw = $get_date[0];
$split_raw_date = explode('-',$date_raw);
$year = $split_raw_date[0];
$month = $split_raw_date[1];
$day = $split_raw_date[2];
$date = "$month" . "/" . "$day" . "/" . "$year";
$go = array($date);
return $go;

}

/* formats time so that the seconds on the database are human readable */
function formatTime($time)
{
switch (TRUE){
case ($time >= 3599):

$hours = floor($time / 3600);
$minutes = $time - ($hours * 3600);

$minutes2 = $minutes / 60;
$min_format = round($minutes2/5,0) * 5;
$time = "$hours" . ".$min_format";
$unit = " hours";
break;

case ($time > 59):
$more_than_minute = $time / 60;
$time = "."  . ceil($more_than_minute/5) * 5;
$unit = " minutes";
break;
case ($time <= 59):
$time = ".1";
$unit = " minute";
break;
}
$info = array($time,$unit);
return $info;
}


/* convert english date string to sql readable date */

function dateToSqlAsVar($date)
{

$split_raw_date = explode('/',$date);
$year = $split_raw_date[2];
$month = $split_raw_date[0];
$day = $split_raw_date[1];
$new_date = "$year" . "-" . "$month" . "-" . "$day";
$go = array($new_date);
return $go;

}

function sqlToDateAsVar($date)
{

$split_raw_date = explode('-',$date);
$year = $split_raw_date[0];
$month = $split_raw_date[1];
$day = $split_raw_date[2];
$new_date = "$month" . "/" . "$day" . "/" . "$year";
$go = $new_date;
return $go;

}



/* used to change the mysql time on cm_logs into human readable. */

function formatServerTime($date)
{
$time = strtotime($date);
echo date('M d y, g:i a',$time);
	
}





?>
