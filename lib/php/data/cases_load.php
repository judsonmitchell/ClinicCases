<?php 
session_start();
include '../../../db.php';
include 'time.php';
include 'names.php';

	$user = $_SESSION['login'];
	
	//Get the columns from _CONFIG.php, excluding any hidden fields
	
	foreach ($CC_columns as $val)
	{
		if ($val[2] == "true")
			{@$col_vals_raw .= "cm." . $val[0] . ", ";}		
	}
	
	//trim trailing comma
	$col_vals = substr($col_vals_raw,0,-2);
	
	switch($_SESSION['group'])
		{
		
		case 'prof':
		$query = mysql_query("SELECT $col_vals FROM `cm` WHERE MATCH(`professor`) AGAINST ('$user')");
		break;
		
		case 'admin':
		$query = mysql_query("SELECT $col_vals FROM `cm`");
		break;
		
		case 'student':
		$query = mysql_query("SELECT $col_vals, cm_cases_students.case_id, cm_cases_students.username FROM cm, cm_cases_students WHERE cm.id = cm_cases_students.case_id AND cm_cases_students.username =  '$user' AND cm_cases_students.status =  'active'");
		break;
		
		case 'super':
		$query = mysql_query("SELECT $col_vals FROM `cm`");
		break;			
			
		}
	
	//Create array of column names for json output		
	foreach ($CC_columns as $value)
	{
		if ($value[2] == "true")
			{$cols[] = $value[0];}		
	}
	
		while ($result = mysql_fetch_assoc($query))
			{
				
				$rows = array();
				
			//perform some formatting to make the data more user-friendly
				
				//Convert dates
				if (!empty($result['date_open']));
					{
						$result['date_open'] = sql_date_to_us_date($result['date_open']);
					}
			
				if (!empty($result['date_close']))
					{			
						$result['date_close'] = sql_date_to_us_date($result['date_close']);
					}
			
				//Convert username into last name
				if (!empty($result['professor']))
					{
						$profs = explode(",",$result['professor']);
						array_pop($profs);
						$lnames = array_map('username_to_lastname',$profs);
						$result['professor'] = implode(", ",$lnames);
					}
					
					
			//loop through results, create array, convert to json	
				foreach ($cols as $col)
					{
						
						$rows[] = $result[$col];
					}	
				
				$output['aaData'][] = $rows;

			}

	
	$json = json_encode($output);
	echo $json;
