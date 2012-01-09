<?php

//This file includes all functions which deal with formatting dates and times.


//extracts date from a mysql datestamp
function extract_date($val)
{
	
	$date = date_parse($val);
	return $date['month'] . "/" . $date['day'] . "/" . $date['year'];
	
}


function sql_date_to_us_date ($date)

	{

		if (!empty($date))
		{	
			$parts = explode('-', $date);
			$us_date = $parts[1] . "/" . $parts[2] . "/" . $parts[0];
			return $us_date;
		}				
		
	}
	
//Converts date to sql datetime
function date_to_sql_datetime($date)

	{
		
		if (!empty($date))
		{
			$parts = explode('/',$date);
			//This is a left over fix from CC6.  Ensures that casenotes entered for the same day appear in the right order
			$time_part = date('H:i:s');

			$datetime = $parts[2] . "-" . $parts[0] . "-" . $parts[1] . " " . $time_part;
			return $datetime; 			
		}
		
	}
