<?php
$time = $_GET['time'];


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








?>
