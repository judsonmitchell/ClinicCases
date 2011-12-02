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
				$time = "."  . ceil($more_than_minute/CC_TIME_UNIT) * CC_TIME_UNIT;
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

