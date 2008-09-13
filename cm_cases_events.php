<?php
session_start();
if (!$_SESSION)
{header('Location: index.php?login_error=3');}
include 'db.php';
include_once './classes/format_dates_and_times.class.php';
include 'get_client_name.php';
if ($_POST)
{$id = $_POST[case_id];}
else
{$id = $_GET['id'];}

/* ajax fix hack */
$date = time();


function who($the_id)
{

$get_responsibles = mysql_query("SELECT * FROM `cm_events_responsibles` WHERE `event_id` = '$the_id'");
while ($line2 = mysql_fetch_array($get_responsibles, MYSQL_ASSOC)) {
    $i2=0;
    foreach ($line2 as $col_value2) {
        $field2=mysql_field_name($get_responsibles,$i2);
        $r[$field2] = $col_value2;
        $i2++;

    }
  $get_full_name = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$r[username]'");
  while ($x = mysql_fetch_array($get_full_name))
  {echo "$x[first_name] $x[last_name]<br>";}

}

}


if ($_POST)
{

if ($_POST['edit'])
{
$date_parts = explode('/',$_POST[date_due]);
$new_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
$update = mysql_query("UPDATE `cm_events` SET `task` = '$_POST[task]', `date_due` = '$new_date', `status` = 'Pending' WHERE `id` = '$_POST[event_id]' LIMIT 1");

$delete_resp = mysql_query("DELETE FROM `cm_events_responsibles` WHERE `event_id` = '$_POST[event_id]'");
$fix_resp = rtrim($_POST[collect],",");
$resp = explode(',',$fix_resp);
foreach($resp as $v)
{
$do_responsibles = mysql_query("INSERT INTO `cm_events_responsibles` (`id`,`event_id`,`username`) VALUES (NULL,'$_POST[event_id]','$v')");
}



}

else
{
$date_set = date('Y-m-d');
$date_parts = explode('/',$_POST[date_due]);
$new_date = $date_parts[2] . "-" . $date_parts[0] . "-" . $date_parts[1];
$setter = $_SESSION['login'];
$temp_id = rand(1000000,100000000);
$do_event = mysql_query("INSERT INTO `cm_events`  (`id` ,`case_id` ,`set_by` ,`task` ,`date_set`, `date_due` ,`status`,`prof`,`temp_id`) VALUES (NULL , '$_POST[case_id]', '$setter', '$_POST[task]','$date_set', '$new_date', 'Pending','$_POST[prof]','$temp_id')");

$get_event_id = mysql_query("SELECT `id` FROM `cm_events` WHERE `temp_id` = '$temp_id' LIMIT 1");
while ($r = mysql_fetch_array($get_event_id))
{
$event_id = $r['id'];
}
$fix_resp = rtrim($_POST[collect],",");
$resp = explode(',',$fix_resp);
foreach($resp as $v)
{
$do_responsibles = mysql_query("INSERT INTO `cm_events_responsibles` (`id`,`event_id`,`username`) VALUES (NULL,'$event_id','$v')");

/* Here professors notify students of the event */
$subject = "Case Event Assigned To You";
$rand = rand();
list($fname,$lname) = getClientAsVar($_POST[case_id]);
$body = "This is to notify you that an event has been assigned to you in the ClincCases system for the $fname $lname case.\n\nEvent: $_POST[task]\n\nDue: $_POST[date_due]";

$notify = mysql_query("INSERT INTO `cm_messages` ( `id` ,`thread_id` ,`to` ,`from` ,`subject` ,`body` ,`assoc_case` ,`time_sent` ,`read` ,`archive` ,`temp_id` ) VALUES (NULL,'','$v','system','$subject','$body','$_POST[id]',CURRENT_TIMESTAMP,'','','$rand')");

$upd = mysql_query("UPDATE `cm_messages` SET `thread_id` = cm_messages.id WHERE `temp_id` = '$rand' LIMIT 1 ");

$del_upd = mysql_query("UPDATE `cm_messages` SET `temp_id` = '' WHERE `temp_id` = '$rand' LIMIT 1 ");

$get_email = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$v' LIMIT 1");
$res2 = mysql_fetch_array($get_email);

$email_to = $res2[email];

$headers = "From: no-reply@" . $_SERVER['HTTP_HOST'] . "\r\n" .
   "Reply-To: no-reply@" . $_SERVER[HTTP_HOST] . "\r\n" .
   "X-Mailer: PHP/" . phpversion();

mail($email_to,$subject,$body,$headers);



/* End */
}
}
}
if ($_POST)
{
if ($_POST['edit'])
{
echo <<<NOTIFY
 <div id="notifier" style="width:100%;height:20px;color:red;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier');">Event Edited</div>
NOTIFY;
}
else
echo <<<NOTIFY
 <div id="notifier" style="width:100%;height:20px;color:red;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier');">Event Added</div>
NOTIFY;
}

if ($_GET[alerter] == 'done')
{
echo <<<NOTIFY
 <div id="notifier" style="width:100%;height:20px;color:red;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier');updateLeftSide('$id','event');">Event Marked Done</div>
NOTIFY;

}

if ($_GET[alerter] == 'delete')
{
echo <<<NOTIFY
 <div id="notifier" style="width:100%;height:20px;color:red;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier');updateLeftSide('$id','event');">Event Deleted</div>
NOTIFY;

}

?>

<div id="contacts_menu"><a href="#" class="singlenav" onClick="Effect.BlindDown('add_event');return false;">Add Event</a> </div>

<div id="add_event" style="display:none;">

<form id="eventForm" name="eventForm">
<table width="99%"><tr><td></td><td><label for "date_due">When does<br>it happen?</label></td><td><label for "responsible">Who is<br>responsible?</label></td><td><label for "task">What is it?</label></td><td></td><td></td></tr>

<tr>
<td valign="center" align="right">
<input type="text" id="datedue<?php echo $date; ?>" name="date_due" size="10" style="border:0px;background-color:rgb(255, 255, 204);"></td><td align="center"  valign="center">

<a id="cal2" href="#" onClick="scwShow(datedue<?php echo $date; ?>,scwID('cal2'));return false;" alt="Click to Select Date" title="Click to Select Date"><img src="images/calendar.png" border="0"></a>

</td><td  valign="center">
<?php
$get_responsibles = mysql_query("SELECT * FROM `cm_cases_students` WHERE `case_id` = '$id' AND `status` = 'active'");
while ($line = mysql_fetch_array($get_responsibles, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_responsibles,$i);
        $d[$field] = $col_value;
        $i++;

    }

echo <<<LIST
<input type="checkbox" name="$d[username]"  id = "$d[username]" onChange="modRes('$d[username]')">$d[first_name] $d[last_name] <br>
LIST;
}

$get_professor = mysql_query("SELECT `professor`,`professor2` FROM `cm` WHERE `id` = '$id' LIMIT 1");
while ($r = mysql_fetch_array($get_professor))
{
$prof = $r['professor'];
$prof2 = $r['professor2'];
$get_prof_name = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$prof' OR `username` = '$prof2' LIMIT 1");
while ($r2 = mysql_fetch_array($get_prof_name))
{

echo "<input type='hidden' name='prof' id='prof' value='$prof'><input type=\"checkbox\" id = \"$r2[username]\" name=\"$r2[username]\"  onChange=\"modRes('$r2[username]');\">$r2[first_name] $r2[last_name]";

}

}


?>



</td><td>
<input type="hidden" id="collect" name="collect">
<textarea name="task" id="tasker" cols="35" rows="2"></textarea></td><td  valign="center">
<a href="#" onClick="var check = checkEvent('datedue<?php echo $date; ?>');if (check==true){Effect.BlindUp('add_event');createTargets('case_activity','case_activity');sendDataPost('cm_cases_events.php?id=<?php echo $id ?>','eventForm');updateLeftSide('<?php echo $id ?>','event');return false}" alt="Add This Event" title="Add This Event"><img src="images/check_yellow.png" border="0"></a></td><td  valign="center"><a title="Cancel " alt="Cancel" href="#" onClick="Effect.BlindUp('add_event');return false;"><img src="images/cancel_small.png" border="0"></a></td></tr></table>
<input type="hidden" value="<?php echo $id ?>" name="case_id">

</form>

</div>
<span id="print_title" style="display:none;"><b>Case Events</b></span>

<div id="events">
<?php
$get_events = mysql_query("SELECT * FROM `cm_events` WHERE `case_id` = '$id' ORDER BY `date_due` desc");
if (mysql_num_rows($get_events)<1)
{echo "No events scheduled in this case.";}
while ($line = mysql_fetch_array($get_events, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_events,$i);
        $e[$field] = $col_value;
        $i++;

    }


if ($e[status] == 'Done')
{
ECHO <<<DONE

<div style="width:99%;height:100px;background:url(images/grade_gray.jpg) repeat-x;">
DONE;

}
ELSE
{   
ECHO <<<NOTDONE
<div style="width:99%;height:100px;background:url(images/grade.jpg) repeat-x;">
NOTDONE;

}
ECHO <<<CONTENTS

<table width="100%">
<tr>
<td valign="top" width="15%"
CONTENTS;
if ($e[status] == 'Done')
{ECHO "style=\"color:gray;\"";}

ECHO <<<CONTENTS
>
<label>Due Date:</label><br>
CONTENTS;

formatDateHuman($e[date_due]);

ECHO <<<CONTENTS
</td>
<td valign="top" width="30%"
CONTENTS;
if ($e[status] == 'Done')
{ECHO "style=\"color:gray;\"";}


ECHO <<<CONTENTS
>
<label>To Be Done</label><br>
<div style="height:60px;overflow:auto;">$e[task]</div>
</td>
<td valign="top" width="30%"
CONTENTS;
if ($e[status] == 'Done')
{ECHO "style=\"color:gray;\"";}
$rand = rand();


ECHO <<<CONTENTS
>
<label>Who's Repsonsible?</label><br>
CONTENTS;
who($e[id]);
echo <<<CONTENTS
</td>
<td valign="top" width="10%"
CONTENTS;
if ($e[status] == 'Done')
{ECHO "style=\"color:gray;\"";}

ECHO <<<CONTENTS
>
<label>Status</label><br>
$e[status]
</td>
<td valign="top" width="15%"><input type="hidden" id="set_by" value="$e[set_by]">
<div style="height:20px;width:100%;text-align:right"><a href="#" alt="Edit this Event" title="Edit this Event" onClick="var checkit = checkAuth('$_SESSION[login]');if (checkit == true){createTargets('add_event','add_event');sendDataGet('event_edit.php?event_id=$e[id]&case_id=$e[case_id]&ieyousuck=$rand');Effect.BlindDown('add_event');return false;} else {return false;};"><img src="images/calendar_edit.png" border="0"></a><a href="#" alt="Delete this Event" title="Delete this Event" onClick="var checkit = checkAuth('$_SESSION[login]','$_SESSION[class]');if (checkit == true){var check = confirm('Are you sure you want to delete all records of this event? If you just want to mark this event as done, click cancel and then click the cocktail glass on the right.');if (check == true){createTargets('case_activity','case_activity');sendDataGet('event_delete.php?event_id=$e[id]&case_id=$e[case_id]');}else {return false}} else {return false;};"><img src="images/calendar_delete.png" border="0" style="margin-left:10px;"></a>
CONTENTS;
if ($e[status] == 'Pending')
{
ECHO <<<SUBCONTENTS
<a href="#" alt="Mark As Done" title="Mark As Done" onClick="var check = confirm('Mark this event as done?');if (check == true){createTargets('case_activity','case_activity');sendDataGet('event_done.php?event_id=$e[id]&case_id=$id');return false;}else {return false}"><img src="images/drink.png" border="0" style="margin-left:10px;"></a>
SUBCONTENTS;
}
ECHO <<<CONTENTS
</div>

</td>
<tr>
</table>
</div>
CONTENTS;
}









?>
</div>
