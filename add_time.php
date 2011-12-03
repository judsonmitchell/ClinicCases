<?php
session_start();

include 'session_error.php';
include 'db.php';

if (!$_SESSION)
{header('Location: index.php');}

if ($_POST)
{
// This is to unset the timer on cookie , so you can navigate away from the page without the warning 
$date = $_POST['date'];

if (isset($date))
{
$datebreak = explode("/",$date);
$month = $datebreak[0];
$day = $datebreak[1];
$year = $datebreak[2];
//this "time" is added so that the sort will be correct if you insert time on the same day.
$time_part = date('H:i:s');
$new_date = "'" . "$year" . "-" . "$month" . "-" . "$day" . " " .  $time_part . "'";
}
else
{$new_date = "NOW()";}

$username = $_SESSION['login'];
$time = $_POST['time'];
$description = $_POST['description'];
$case_id = $_POST['id'];
$hours = $_POST['hours'];
$minutes = $_POST['minutes'];
if (isset($hours))
{
$hours_into_seconds = ($hours * 3600);
$minutes_into_seconds = ($minutes * 60);
$time = $hours_into_seconds + $minutes_into_seconds;
}


//If this script is being called from casenote_noncase.php, the prof value is already there
if ($_POST[id] == 'NC')
	
	{$profs = $_POST[professor];}
	
	else
	
		//If not, a query is needed here to deal with multi-professor issue.  The question is: on this case into which the student is entering time, which professors are on this case?  If one, put in one, if two or more, put csv of all professors
		{
			$profs_q = mysql_query("SELECT `id`,`professor` FROM `cm` WHERE `id` = '$case_id' LIMIT 1;");
			$profs_q2 = mysql_fetch_object($profs_q);
			$profs = $profs_q2->professor;
		}

$query = mysql_query("INSERT INTO `cm_case_notes` (id,case_id,date,time,description,username,prof) VALUES (NULL,'$case_id',$new_date,'$time','$description','$username','$profs')");

setcookie("timeron", "timeroff");
$rand = rand();
}

if ($case_id == 'NC')
{
header('Location: display_noncase.php?ieyousuck=' . $rand);
}
else

{header('Location: display_casenotes.php?id=' . $case_id . '&ieyousuck=' . $rand);}


?>
