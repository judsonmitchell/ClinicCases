<?php 
session_start();
include '../../../db.php';
include 'time.php';
include 'names.php';

	$user = $_SESSION['login'];
	
	switch($_SESSION['group'])
		{
		
		case 'prof':
		$query = mysql_query("SELECT `id`, `first_name`,`last_name`,`date_open`,`date_close`,`case_type`,`professor`,`dispo` FROM `cm` WHERE MATCH(`professor`) AGAINST ('$user')");
		break;
		
		case 'admin':
		$query = mysql_query("SELECT `id`, `first_name`,`last_name`,`date_open`,`date_close`,`case_type`,`professor`,`dispo` FROM `cm`");
		break;
		
		case 'student':
		$query = mysql_query("SELECT cm.id, cm.first_name, cm.last_name, cm.date_open, cm.date_close, cm.case_type, cm.professor, cm.dispo, cm_cases_students.case_id, cm_cases_students.username FROM cm, cm_cases_students WHERE cm.id = cm_cases_students.case_id AND cm_cases_students.username =  '$user' AND cm_cases_students.status =  'active'");
		break;
		
		case 'super':
		$query = mysql_query("SELECT `id`, `first_name`,`last_name`,`date_open`,`date_close`,`case_type`,`professor`,`dispo` FROM `cm`");
		break;			
			
		}
			

	$cols = array('id','first_name','last_name','date_open','date_close','case_type','professor','dispo');
	
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
						array_walk($profs,'username_to_lastname');
						$result['professor'] = implode(", ",$profs);
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
