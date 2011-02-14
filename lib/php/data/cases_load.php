<?php 
session_start();
include '../../../db.php';
include 'time.php';

	$user = $_SESSION['login'];
	
	switch($_SESSION['group'])
		{
		
		case 'prof':
		$query = mysql_query("SELECT `id`, `first_name`,`last_name`,`date_open`,`date_close`,`case_type`,`professor`,`dispo` FROM `cm` WHERE `professor` LIKE '%$user%'");
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
				//for ( $i=0 ; $i<count($cols) ; $i++ )
				
				foreach ($cols as $col)
					{
						$rows[] = $result[$col];
					}	
				
				$output['aaData'][] = $rows;

			}
	
	$json = json_encode($output);
	echo $json;
