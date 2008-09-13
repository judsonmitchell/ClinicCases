<?php
session_start();
if (!$_SESSION)
{"Sorry.  There was a problem with the timer.";}
include 'db.php';
include './classes/format_dates_and_times.class.php';
setcookie("timeron","timeron");
$id = $_GET['id'];
$pause = $_GET['pause'];
$restart = $_GET['restart'];



function getClient($id)
{
$get_client_name=mysql_query("SELECT * FROM `cm` WHERE `id` = '$id' LIMIT 1");
WHILE ($r = mysql_fetch_object($get_client_name))
{
$fname = $r->first_name;
$lname = $r->last_name;

}
echo "$fname $lname";
}


if ($_GET['finish'])
{
$no_new_time = $_GET['no_new_time'];
$pause_value = $_GET['pause_value'];
if (isset($no_new_time))
{
$new_time = 0;
$time_at_start = 0;
}
else
{
$new_time = time();
$time_at_start= $_GET['time_at_start'];
}
$time_spent = ($new_time - $time_at_start) + $pause_value;


echo <<<TIMESUBMITTER
<div style='width:100%;height:12%;background-color:rgb(195, 217, 255);text-align:right;'><a href='#' onClick="return stopTimer('fade');"><img src='images/cancel_small_blue.png' border='0'></a></div>
<form "add_time.php" method="post" id="case_note" name="case_note">
<input type="hidden" value="$id" name="id">
<input type="hidden" value="$time_spent" name="time">
TIMESUBMITTER;
list($new_time,$the_unit) = formatTime($time_spent);

ECHO <<<TIMESUBMITTER

$new_time $the_unit recorded.<br>
<textarea cols="23" rows="8" name="description" onFocus="this.value='';">What did you do?</textarea><br>
<center><input type="button" value="Add" onClick="Effect.Fade('timer_box');createTargets('the_info','the_info');sendDataPostAndStripeNoStatus2('add_time.php','case_note');updateLeftSide($id);"></center>
</form>
TIMESUBMITTER;
die;


}




if (isset($pause))
{
$pause_value_old =$_GET['pause_value'];
if ($pause_value_old)
{
$new_time = time();
$old_time = $_GET['time_at_start'];
$time_spent = $new_time - $old_time;
$pause_value = $time_spent + $pause_value_old;
}
else
{
$new_time = time();
$old_time = $_GET['old_time'];
$pause_value = $new_time - $old_time;


}


echo <<<PAUSER
<div style='width:100%;height:12%;background-color:rgb(195, 217, 255);text-align:right;'><a href='#' onClick="return stopTimer('fade');"><img src='images/cancel_small_blue.png' border='0'></a></div>
Timing on <br>
PAUSER;
getClient($id);
ECHO <<<PAUSER
<br>Is Paused.
<input type = "hidden" id="pause_value" name="pause_value" value= "$pause_value">
<br><br>
<button style="background-color:rgb(239, 244, 251);color:rgb(77, 122, 179);font-size:9pt;" onClick="createTargets('timer_box','timer_box');sendDataGet('timer.php?id=$id&finish=1&no_new_time=1&pause_value=' + document.getElementById('pause_value').value);return false;"><img src = "images/control_stop_blue.png" border="0"><div>End</div></button>

<button style="background-color:rgb(239, 244, 251);color:rgb(77, 122, 179);font-size:9pt;" onClick="createTargets('timer_box','timer_box');sendDataGet('timer.php?id=$id&restart=1&pause_value=' + document.getElementById('pause_value').value);return false;"><img src="images/control_play_blue.png" border="0"><div>Restart</div></button>

PAUSER;
die;
}

if (isset($restart))
{
$pause_value = $_GET['pause_value'];
$time_at_restart = time();
if ($pause_value)
{
$current_time = time();
$accrued_time = $current_time - $time_at_restart;
$pause_value = $pause_value + $accrued_time;

}


echo <<<RESTART
<div style='width:100%;height:12%;background-color:rgb(195, 217, 255);text-align:right;'><a href='#' onClick="return stopTimer('fade');"><img src='images/cancel_small_blue.png' border='0'></a></div>
You are again timing on <br>
RESTART;
getClient($id);
echo <<<RESTART

<br><br>
<center><img src = "images/timing.gif" border="0"></center>
<br><br>
<input type = "hidden" name = "pause_value" id = "pause_value" value = "$pause_value">
<input type = "hidden" name = "time_at_start" id="time_at_start" value = "$time_at_restart">
<button  style="background-color:rgb(239, 244, 251);color:rgb(77, 122, 179);font-size:9pt;"  onClick= "createTargets('timer_box','timer_box');sendDataGet('timer.php?id=$id&pause=1&pause_value=' +  document.getElementById('pause_value').value + '&time_at_start=' + document.getElementById('time_at_start').value);return false;"><img src = "images/control_pause_blue.png" border="0"><div>Pause</div></button>

<button  style="background-color:rgb(239, 244, 251);color:rgb(77, 122, 179);font-size:9pt;" onClick = "createTargets('timer_box','timer_box');sendDataGet('timer.php?id=$id&finish=1&time_at_start=' + document.getElementById('time_at_start').value + '&pause_value=' + document.getElementById('pause_value').value); return false;"><img src = "images/control_stop_blue.png" border="0"><div>End</div></button>




RESTART;
DIE;
}




ECHO <<<TIMER
<div style='width:100%;height:12%;background-color:rgb(195, 217, 255);text-align:right;'><a href='#' onClick="return stopTimer('fade');"><img src='images/cancel_small_blue.png' border='0'></a></div>
You are timing on<br>
TIMER;
getClient($id);
echo <<<TIMER
<br><br>
<center><img src="images/timing.gif" border="0"></center>

<input type = "hidden" id = "time_at_start" name= "time_at_start" value = "
TIMER;

ECHO time();
ECHO <<<TIMER
"><br>
<button style="background-color:rgb(239, 244, 251);color:rgb(77, 122, 179);font-size:9pt;" onClick="createTargets('timer_box','timer_box');sendDataGet('timer.php?id=$id&finish=1&time_at_start=' + document.getElementById('time_at_start').value); return false;"><img src = "images/control_stop_blue.png" border="0"><div>End</div></button>
<button style="background-color:rgb(239, 244, 251);color:rgb(77, 122, 179);font-size:9pt;" onClick="createTargets('timer_box','timer_box');sendDataGet('timer.php?id=$id&pause=1&old_time=' + document.getElementById('time_at_start').value);return false;"><img src = "images/control_pause_blue.png" border="0"><div>Pause</div></button>

TIMER;
?>
