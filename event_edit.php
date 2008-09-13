<?php
session_start();
include 'db.php';
include './classes/format_dates_and_times.class.php';
$event_id = $_GET['event_id'];
$case_id = $_GET['case_id'];

$ajax_hack = time();


function who($event_id,$case_id)
{
$get_all_responsibles = mysql_query("SELECT cm.professor,cm_cases_students.username FROM `cm`,`cm_cases_students` WHERE cm.id = '$case_id' AND cm_cases_students.case_id = '$case_id' AND cm_cases_students.status = 'active'");
while ($line = mysql_fetch_array($get_all_responsibles))
{

$all_student_array[] = $line['username'];
$professor_array[] = $line['professor'];
$prof = array_unique($professor_array);


}
$all_responsibles_array = array_merge($all_student_array,$prof);





$get_current_resp = mysql_query("SELECT `username` FROM `cm_events_responsibles` WHERE `event_id` = '$event_id'");  
while ($r = mysql_fetch_assoc($get_current_resp))
{
$username = $r['username'];
$current_array[] = $r['username'];

}



$not_resp = array_diff($all_responsibles_array,$current_array);



foreach ($current_array as $goo)
{
$get_name = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$goo'");
$namer = mysql_fetch_array($get_name);
echo "<input type=\"checkbox\" checked=checked name=\"$goo\"  id=\"$goo\" onChange=\"modRes('$goo');\">$namer[first_name] $namer[last_name]<br>";

}

foreach ($not_resp as $v)
{
$get_name = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$v'");
$namer = mysql_fetch_array($get_name);

echo "<input type=\"checkbox\" name=\"$v\" id=\"$v\" onChange=\"modRes('$v');\">$namer[first_name] $namer[last_name]</br>";

}

echo <<<CONT
<input type="hidden" name="collect" id="collect" value="
CONT;
foreach ($current_array as $goo)
{echo "$goo,";}
ECHO <<<CONT
">
CONT;

}


$get_event_data = mysql_query("SELECT * FROM `cm_events` WHERE `id` = '$event_id'");
while ($res = mysql_fetch_array($get_event_data))
{
$task = $res['task'];
$date_due = $res['date_due'];

}




?>

<form id="eventForm" name="eventForm">
<table width="99%"><tr><td></td><td><label for "date_due">When does<br>it happen?</label></td><td><label for "responsible">Who is<br>responsible?</label></td><td><label for "task">What is it?</label></td><td></td><td></td></tr>

<tr>
<td valign="center" align="right">
<input type="text" id="datedue<?php echo $ajax_hack; ?>" name="date_due" size="10" style="border:0px;background-color:rgb(255, 255, 204);" value="
<?php formatDateHuman($date_due); ?>"></td><td align="center"  valign="center">

<a id="cal2" href="#" onClick="scwShow(datedue<?php echo $ajax_hack; ?>,scwID('cal2'));return false;" alt="Click to Select Date" title="Click to Select Date"><img src="images/calendar.png" border="0"></a>
</td><td  valign="center">
<?php
who($event_id,$case_id);
?>



</td><td>

<textarea name="task" id="tasker" cols="35" rows="2"><?php echo $task ?></textarea></td><td  valign="center">
<input type="hidden" value="<?php echo $event_id ?>" name="event_id">
<input type="hidden" value="<?php echo $case_id ?>" name="case_id">
<input type="hidden" name="edit" value="yes">
<a href="#" onClick="var check = checkEvent('datedue<?php echo $ajax_hack; ?>');if (check==true){Effect.BlindUp('add_event');createTargets('case_activity','case_activity');sendDataPost('cm_cases_events.php','eventForm');updateLeftSide('<?php echo $case_id ?>','event');return false;}" alt="Edit This Event" title="Edit This Event"><img src="images/check_yellow.png" border="0"></a></td><td  valign="center"><a title="Cancel Editing" alt="Cancel Editing" href="#" onClick="Effect.BlindUp('add_event');return false;"><img src="images/cancel_small.png" border="0"></a></td></tr></table>

</form>





