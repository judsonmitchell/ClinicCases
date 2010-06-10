<?php

//functions to get the professors class preferences when a new student is added.

function get_prof_case_prefs($prof)
{
	$prf = explode(",",$prof);
	foreach ($prf as $v)
	{
		$find_out = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$v' LIMIT 1");
		$res = mysql_fetch_array($find_out);
		$pref_case_array[] = $res[pref_case];
				
	}
	
	if (in_array("on", $pref_case_array))
	
		{$cases = 'on';}
		else
		{$cases = '';}
		return $cases;
}

function get_prof_journal_prefs($prof)
{
	
	$prf = explode(",",$prof);
	foreach ($prf as $v)
	{
		$find_out = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$v' LIMIT 1");
		$res = mysql_fetch_array($find_out);
		$pref_journal_array[] = $res[pref_journal];
				
	}
	
	if (in_array("on", $pref_journal_array))
	
		{$journals = 'on';}
		else
		{$journals = '';}
		return $journals;

}

?>
