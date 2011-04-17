<?php

//This file includes all functions which deal with formatting dates and times.

function sql_date_to_us_date ($date)

	{

		if (!empty($date))
		{	
			$parts = explode('-', $date);
			$us_date = $parts[1] . "/" . $parts[2] . "/" . $parts[0];
			return $us_date;
		}				
		
	}

