<?php

include_once 'db.php';
include_once 'classes/iCalcreator.class.php';
include_once 'classes/get_names.php';

//Get the username from the private key
$get_user = mysql_query("SELECT `username`,`private_key`,`class` FROM `cm_users` WHERE `private_key` = '$_GET[sid]' LIMIT 1");
$g = mysql_fetch_object($get_user);
$user = $g->username;

$v = new vcalendar();
                          // initiate new CALENDAR
$v->setConfig( '$_GET[sid]'
             , 'ClinicCases.com' );             // config with site domain
$v->setProperty( 'X-WR-CALNAME'
               , 'ClinicCases Upcoming Events' );          // set some X-properties, name, content.. .
$v->setProperty( 'X-WR-CALDESC'
               , 'Your Upcoming Events on ClinicCases. For more information, log on to your account at ' . $CC_base_url );
$v->setProperty( 'method', 'PUBLISH' ); 
$v->setProperty( 'X-WR-TIMEZONE'
               , 'America/Chicago' );

	if ($g->class == 'student')

			{
			$get_events = mysql_query("SELECT * FROM `cm_events_responsibles` WHERE `username` = '$user'");
			while ($h = mysql_fetch_array($get_events))
			{
			$show_events = mysql_query("SELECT * FROM `cm_events`  WHERE `id` = '$h[event_id]' AND `archived` = 'n'  ORDER BY `date_due` desc");
			while ($line = mysql_fetch_array($show_events, MYSQL_ASSOC)) {
				$i=0;
				foreach ($line as $col_value) {
					$field=mysql_field_name($show_events,$i);
					$f[$field] = $col_value;
					$i++;
				}
				//Put date in Ical format
				$date_strip = str_replace('-','',$f[date_due]);
				
				//Get clients name
				$name = new get_names;
				$client_name = $name->get_clients_name($f[case_id]);
				
				$nme = new get_names;
				$get_responsibles = mysql_query("SELECT * FROM `cm_events_responsibles` WHERE `event_id` = '$h[event_id]'");
				while($r = mysql_fetch_array($get_responsibles))
					{
						
						$r_name = $nme->get_users_name($r[username]);
						$name_list .= $r_name . ",";
					}
				
				
				$description = "$client_name case\nTask: $f[task]\nStatus: $f[status]\nResponsible Parties: $name_list";
				
    
					$e = new vevent();                             // initiate EVENT

					$e->setProperty( 'dtstart', $date_strip, array('VALUE' => 'DATE'));
					
					$e->setProperty( 'summary', $client_name . ": " . $f[task] );    // describe the event
					
					$e->setProperty( 'description', $description ); 
					
					$v->setComponent( $e );
						$name_list = "";
					
						

			}
			
			
		}
	}


else
			{
	
	
			$show_events = mysql_query("SELECT * FROM `cm_events`  WHERE `prof` = '$user' AND `archived` = 'n' ORDER BY `date_due` DESC");
			while ($line = mysql_fetch_array($show_events, MYSQL_ASSOC)) {
				$i=0;
				foreach ($line as $col_value) {
					$field=mysql_field_name($show_events,$i);
					$f[$field] = $col_value;
					$i++;
				}
				
				//Put date in Ical format
				$date_strip = str_replace('-','',$f[date_due]);
				
				//Get clients name
				$name = new get_names;
				$client_name = $name->get_clients_name($f[case_id]);
				
				//Get all responsible parties
				$get_responsibles = mysql_query("SELECT * FROM `cm_events_responsibles` WHERE `event_id` = '$f[id]'");
				$nme = new get_names;
				while($r = mysql_fetch_array($get_responsibles))
					{
						
						
						$r_name = $nme->get_users_name($r[username]);
						$name_list .= $r_name . ",";
					}
				
				$description = "$client_name case\nTask: $f[task]\nStatus: $f[status]\nResponsible Parties: $name_list";
				
    
					$e = new vevent();                             // initiate EVENT

					$e->setProperty( 'dtstart', $date_strip, array('VALUE' => 'DATE'));
					
					$e->setProperty( 'summary', $client_name . ": " . $f[task] );    // describe the event
					
					$e->setProperty( 'description', $description ); 
					
					
					$v->setComponent( $e );
					$name_list = "";
					
				}
			}
	


$str = $v->createCalendar();
echo $str;


	

?>
