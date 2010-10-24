<?php

include 'db.php';
include './classes/format_dates_and_times.class.php';
include './classes/get_names.php';



function createRSS($type)
{
	$pkey = $_GET['pkey'];
	$get_user = mysql_query("SELECT `private_key`,`username`,`class` from `cm_users` where `private_key` = '$pkey' LIMIT 1");
	
	if (mysql_num_rows($get_user)<1)
	{die("No feed available.  Please check the URL for this feed by going to the Prefs tabs and clicking 'Private Key.'");}
	
	$rr = mysql_fetch_object($get_user);
	$usr = $rr->username;
	
	//If we're looking to create a journal feed.
	if ($type == 'journals')
	
		{
		
			$get_journals = mysql_query("SELECT * FROM `cm_journals` WHERE `professor` = '$usr' ORDER BY `date_added` DESC");

			$body="<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
			<rss version=\"2.0\"> 
			<channel> 
				<title>Student Journals - ClinicCases</title> 
				<link>" . $CC_base_url . "</link> 
				<description>Notification of new student journal entries on ClinicCases</description> 
				<copyright>(c) 2007-2010, threepipeproblem.com, All rights reserved.</copyright> 
			";
			
					while($r = mysql_fetch_array($get_journals)) { 
						$namer = new get_names;
						$name = $namer->get_users_name($r[username]);
						$date = formatDateAsVar($r[date_added]);

			$body .="
						<item>
							<title>$name - $date[0]</title>
							
					<link>" . $CC_base_url . "/cm_journals.php</link> 
							<description> <![CDATA[ $name submitted a journal on $date[0]. ]]> </description>				
							
							<pubDate>$r[date_added]</pubDate>
						</item>";
				}


				$body .="
				</channel>
				</rss>";
		}
		
		
		else
		//we're looking for a feed of case notes
		{
				$body="<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
		<rss version=\"2.0\"> 
		<channel> 
			<title>Latest Activity - ClinicCases</title> 
			<link>" . $CC_base_url . "</link> 
			<description>Notification of new case activity on ClinicCases</description> 
			<copyright>(c) 2007-2010, threepipeproblem.com, All rights reserved.</copyright> 
		";
		
			if ($rr->class == 'prof')
			
				{ $cq = mysql_query("SELECT * FROM `cm_case_notes` WHERE `prof` LIKE '%$usr%' ORDER BY `date` DESC LIMIT 0,30");
					
					while ($ar = mysql_fetch_array($cq))
						{  
							$client = new get_names;
							$client_name = $client->get_clients_name($ar['case_id']);
							
							$student = new get_names;
							$student_name = $student->get_users_name($ar['username']);
							
							$date = formatDateAsVar($ar['date']);

							$body.= "<item>
								<title>$client_name Case - Activity by $student_name </title>
								<link>" . $CC_base_url . "/cm_cases.php?direct=" . $ar['case_id'] . "</link> 
								<description> <![CDATA[ $ar[description] ]]> </description>				
								<pubDate>$ar[date]</pubDate>
								</item>";
					}
					
						$body .="
						</channel>
						</rss>";
					}
				
				else
				//we are a student
				{
					//find out which cases student is on
					$sq = mysql_query("SELECT `username`,`case_id` FROM `cm_cases_students` WHERE `username` = '$usr'");
					
						while ($ssq = mysql_fetch_array($sq))
							
							{
								
								
								//make an array of all possible case id s
								$arr[] = $ssq['case_id'];				
								
								}
								
								//turn the array into a rather lengthy WHERE clause
								
								foreach ($arr as $p)
								
									{
										$where .= "case_id ='" . $p . "'" . " OR ";
										
									}
									
									//then chop the last four characters
									$where_clause = substr($where,0,-4);
								
										//then run the damn query
										//echo $where_clause;die;
										$stu_q = mysql_query("SELECT * FROM `cm_case_notes` WHERE `case_id` = $where_clause ORDER BY `date` DESC LIMIT 1, 30");
										
										
											while ($stu_qr = mysql_fetch_array($stu_q)) 
												{
													$client = new get_names;
													$client_name = $client->get_clients_name($stu_qr['case_id']);
													$student = new get_names;
													$student_name = $student->get_users_name($stu_qr['username']);
				
													
													$body.= "<item>
													<title>$client_name Case - Activity by $student_name </title>
													<link>" . $CC_base_url . "/cm_cases.php?direct=" . $stu_qr['case_id'] . "</link> 
													<description> <![CDATA[ $stu_qr[description] ]]> </description>				
													<pubDate>$stu_qr[date]</pubDate>
													</item>";

												}
												
											$body .="
											</channel>
											</rss>";
				
				}
			}
			
			echo $body;

		}
		
	
/* End the RSS Feed */



createRSS($_GET['type']);
























?>
