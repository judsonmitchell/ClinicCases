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
$time_part = date('G:i:s');
$new_date = "'" . "$year" . "-" . "$month" . "-" . "$day" . " " .  $time_part . "'";
}
else
{$new_date = "NOW()";}

$username = $_SESSION['login'];
$time = $_POST['time'];
$description = $_POST['description'];
$prof = $_SESSION['assigned_prof'];
$case_id = $_POST['id'];
$hours = $_POST['hours'];
$minutes = $_POST['minutes'];
if (isset($hours))
{
$hours_into_seconds = ($hours * 3600);
$minutes_into_seconds = ($minutes * 60);
$time = $hours_into_seconds + $minutes_into_seconds;
}
$query = mysql_query("INSERT INTO `cm_case_notes` (id,case_id,date,time,description,username,prof) VALUES (NULL,'$case_id',$new_date,'$time','$description','$username','$prof')");

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
