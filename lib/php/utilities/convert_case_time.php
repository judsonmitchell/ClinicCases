<?php
//function to take case time in the db - recorded in seconds - and return human readable time
//default time unit is set in _CONFIG.php

function convert_case_time($time)

	{
		switch (TRUE){

			case ($time >= 3599):
				$hours = floor($time / 3600);
				$minutes = $time - ($hours * 3600);
				$minutes2 = $minutes / 60;
				$min_format = round($minutes2/CC_TIME_UNIT,0) * CC_TIME_UNIT;
				$time = "$hours" . ".$min_format";
				$unit = " hours";
				break;

			case ($time > 59):
				$more_than_minute = $time / 60;
				$time = ceil($more_than_minute/CC_TIME_UNIT) * CC_TIME_UNIT;
				$unit = " minutes";
				break;

			case ($time <= 59):
				$time = "1";
				$unit = " minute";
				break;

		}

	$info = array($time,$unit);
	return $info;

	}

//Convert to hours only
function convert_to_hours($time)
{
	$hours = floor($time / 3600);
	$minutes = $time - ($hours * 3600);
	$minutes2 = $minutes / 60;
	$min_format = (round($minutes2/CC_TIME_UNIT) * CC_TIME_UNIT) / 100;
	$min_val = explode('.', number_format($min_format,2));
	$time = "$hours" . ".$min_val[1]";
	return $time;
}

//Convert user-entered time into seconds
function convert_to_seconds($hours,$minutes)

	{

		$hours_into_seconds = ($hours * 3600);
		$minutes_into_seconds = ($minutes * 60);
		$time = $hours_into_seconds + $minutes_into_seconds;
		return $time;

	}

