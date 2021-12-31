<?php

//This file includes all functions which deal with formatting dates and times.
//TODO deal with timezones

//extracts date from a mysql datestamp
function extract_date($val)
{

	$date = date_parse($val);
	return $date['month'] . "/" . $date['day'] . "/" . $date['year'];

}

//extracts date and time from a mysql timestamp
function extract_date_time($val)
{
	$date = date_create($val);
	return date_format($date,'F j, Y g:i a');
}

//extracts date and time from a mysql timestamp, sortable
function extract_date_time_sortable($val)
{
	$date = date_create($val);
	return date_format($date,'m/d/Y g:i a');
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
