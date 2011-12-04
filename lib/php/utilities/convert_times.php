<?php

//extracts date from a mysql datestamp
function extract_date($val)
{
	
	$date = date_parse($val);
	return $date['month'] . "/" . $date['day'] . "/" . $date['year'];
	
}


