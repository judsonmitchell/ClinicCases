<?php
//generates selects for time entered.

function generate_time_selector()

{
	if (CC_TIME_UNIT == '5')
	{$minutes = array('0','5','10','15','20','25','30','35','40','45','50','55');}
	else
	{$minutes = array('0','6','12','18','24','30','36','42','48','54');}
	
	$selects = "<label>Hours:</label><select>";
	
	for($i = 0; $i <= 8; $i++)
	{$selects .= "<option value='$i'>" . $i . "</option>";}
	
	$selects .= "</select>";
	
	$selects .= "<label>Minutes: </label><select>";
	
	foreach ($minutes as $val)
	
	{	
		$selects .= "<option value='$val'>$val</option>";	
	} 
	
	$selects .= "</select>";
	
	return $selects;
}
