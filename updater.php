<?php
session_start();
if (!$_SESSION)
	{echo "Session Error."; die;}
include 'db.php';
include './classes/format_dates_and_times.class.php';

$type = $_GET[type];
$id = $_GET[id];


switch($type){
case "messages":
	$sql = "SELECT * FROM `cm_messages`WHERE `to` LIKE '%$_SESSION[login]%' AND `read` NOT LIKE '%$_SESSION[login]%' AND `archive` NOT LIKE '%$_SESSION[login]%' OR `ccs` LIKE '%$_SESSION[login]%' AND `read` NOT LIKE '%$_SESSION[login]%' AND `archive` NOT LIKE '%$_SESSION[login]%' ";
	$query = mysql_query($sql);
	
		while ($r = mysql_fetch_array($query))
		{
			$var = mysql_num_rows($query);
			$res = $var;
			
			
			}
			
		if (mysql_num_rows($query) < 1)
				{echo "<img src=\"images/onload_tricker.gif\" border=\"0\" onLoad=\"Effect.Pulsate('msg_notifier',{pulses:2});\"><b>0</b> ";}
			else echo "<img src=\"images/onload_tricker.gif\" border=\"0\" onLoad=\"Effect.Pulsate('msg_notifier',{pulses:2});\"><b>$res</b> ";
	
			break;
case "activity":
	$sql = "SELECT * FROM `cm_case_notes` WHERE `case_id` = '$id' ORDER BY `date` DESC LIMIT 1";
	$query = mysql_query($sql);
	
		while ($r = mysql_fetch_array($query))
		{
			$qq = $r[description];
			
			
			}
		if (strlen($qq > 130))
			{
			$result = substr($qq,0,130) . "...";}
			else
			{$result = $qq;}
			echo "<img src=\"images/onload_tricker.gif\" border=\"0\" onLoad=\"Effect.Pulsate('activity_notifier',{pulses:2});\">$result ";
			
		break;
		
	case "time":
	
			$sql = "SELECT `case_id`, SUM(time) AS `total` FROM `cm_case_notes` WHERE `case_id` = '$id' GROUP BY `case_id`";
			$query = mysql_query($sql);
	
		while ($r = mysql_fetch_array($query))
		{
			$time= $r[total];
			
			
			}
			$result = formatTime($time);
		
			echo "<img src=\"images/onload_tricker.gif\" border=\"0\" onLoad=\"Effect.Pulsate('time_notifier',{pulses:2});\">$result[0] $result[1] ";

	break;
case "event":
	$sql = "SELECT * FROM `cm_events` WHERE `case_id` = '$id' and `status` = 'Pending' ORDER BY `date_due` ASC LIMIT 1";
	$query = mysql_query($sql);
	while ($r = mysql_fetch_array($query))
		{
			$event = $r[task];
			$due = $r[date_due];
			
			}
			$date = sqlToDateAsVar($due);
		if (mysql_num_rows($query)<1)
			{echo  "<img src=\"images/onload_tricker.gif\" border=\"0\" onLoad=\"Effect.Pulsate('event_notifier',{pulses:2});\">There are no upcoming events.";}
		else	
		{echo "<img src=\"images/onload_tricker.gif\" border=\"0\" onLoad=\"Effect.Pulsate('event_notifier',{pulses:2});\">$date -  $event ";}

	break;
	
case "journal":
	$query = mysql_query("SELECT * FROM `cm_journals` WHERE `deleted` != 'yes'  AND `read` != 'yes' AND `professor` = '$_SESSION[login]'");
	$jnum = mysql_num_rows($query);
	echo $jnum;
	break;

case "journal_student":
	$query = mysql_query("SELECT * FROM `cm_journals` WHERE `deleted` != 'yes'  AND `username` = '$_SESSION[login]'");
	$jnum = mysql_num_rows($query);
	echo $jnum;
	break;
	
	

}


