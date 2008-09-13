<?php
/* This function takes the value of timestamp mysql field and corrects it to the local timezone.  mysql default zone is MT, so offset for CT would be -1.  Don't know what to do about daylight savings yet. */


function timezone_corrected($fulltime, $timezone_offset)
{
$break_timestamp = explode(' ', $fulltime);
/* Deal with the date part */
$date_part = $break_timestamp[0];
$date_part2 = explode('-',$date_part);
$year = $date_part2[0];
$month = $date_part2[1];
$day = $date_part2[2];
/* End */

/* Deal with the Time Part */

$time_part = $break_timestamp[1];
$time_part2 = explode(':',$time_part);
$hour = $time_part2[0];
$minute = $time_part2[1];
$second = $time_part2[2];

$correct_hour = $hour + $timezone_offset;

echo $year . "-" . $month . "-" . $day . " " . "$correct_hour" . ":" . "$minute" . ":" . "$second";
}

timezone_corrected('2007-07-03 19:52:16','-1')

?>
