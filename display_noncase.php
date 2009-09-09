<?php
session_start();
include 'db.php';
include './classes/format_dates_and_times.class.php';

if (isset($_GET['id']))
{
$id = $_GET['id'];

include 'db.php';
}
$show_notes = mysql_query("SELECT * FROM `cm_case_notes` WHERE `case_id` = 'NC' AND `username` = '$_SESSION[login]' ORDER BY `date` DESC");

if (isset($_GET[notifydelete]))
{

echo <<<NOTIFIER
<div id="notifier" style="width:100%;height:20px;color:red;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier');">Time Deleted</div>
NOTIFIER;
}

if (isset($_GET[notifyedit]))
{

echo <<<NOTIFIER
<div id="notifier" style="width:100%;height:20px;color:red;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier');">Time Edited</div>
NOTIFIER;
}


echo <<<TOP
<table id="display_time" width="99%" style="background-color:white;">
<thead><tr><td>Date</td><td>Time</td><td>Description</td><td>By:</td><td style="width:23px;"></td><td style="width:23px;"></td></tr></thead><tbody>
TOP;
while ($line = mysql_fetch_array($show_notes, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($show_notes,$i);
        $d[$field] = $col_value;
        $i++;
    }
echo <<<NOTES

<tr><td width="10%">
NOTES;
formatDateHuman($d[date]);
echo <<<NOTES
</td><td width="15%">
NOTES;
list($new_time,$the_unit) = formatTime($d[time]);
echo "$new_time" . " " . "$the_unit";
$rand = rand();
echo <<<NOTES
</td><td width="65%"><div style="height:40px;overflow:auto;">$d[description]</div></td><td width="10%">$d[username]</td><td><a href="#" title="Edit" alt="Edit " onClick="createTargets('time_form','time_form');sendDataGet('casenote_edit.php?id=$d[id]&ieyousuck=$rand');Effect.BlindDown('time_form');return false;"><img src="images/report_edit.png" border="0"></a></td><td id="deleter_$d[id]"><a href="#" title="Delete" alt="Delete" onClick="deleteCaseNote('$d[id]','the_info','$d[case_id]');"><img src="images/report_delete.png" border="0"></a></td></tr>
NOTES;
}
IF (mysql_num_rows($show_notes) < 1)
{echo "You have not reported any non-case time.";}
echo
"</tbody></table><script>stripe('display_time','#fff','#e0e0e0');</script>

";
?>

