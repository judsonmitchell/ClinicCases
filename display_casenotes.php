<?php
session_start();
if(!$_SESSION)
{
    die("Error: Your session has timed out. Please log in again.");
}
include_once './classes/format_dates_and_times.class.php';
include 'db.php';

if (isset($_GET['id']))
    {
    $id = $_GET['id'];

    }

$show_notes = mysql_query("SELECT * FROM `cm_case_notes` WHERE `case_id` = '$id' ORDER BY `date` DESC");

if (isset($_GET[notifydelete]))
    {

echo <<<NOTIFIER
<div id="notifier" style="width:100%;height:20px;color:red;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier');updateLeftSide($id);">Case Note Deleted</div>
NOTIFIER;
    }

if (isset($_GET[notifyedit]))
    {

echo <<<NOTIFIER
<div id="notifier" style="width:100%;height:20px;color:red;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier');updateLeftSide($id);">Case Note Edited</div>
NOTIFIER;
    }


echo <<<TOP
<table id="display_time" width="100%">
<thead><tr><td>Date</td><td>Time</td><td>Description</td><td>By:</td><td style="width:23px;"></td><td style="width:23px;"></td><td></td></tr></thead><tbody>
TOP;

while ($line = mysql_fetch_array($show_notes, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($show_notes,$i);
        $d[$field] = $col_value;
        $i++;
    }

echo <<<NOTES
<tr id="$d[id]"><td width="10%">
NOTES;
    
    formatDateHuman($d[date]);

echo <<<NOTES
</td><td width="15%">
NOTES;

    list($new_time,$the_unit) = formatTime($d[time]);
    echo "$new_time" . " " . "$the_unit";
    $rand = rand();
    
echo <<<NOTES
</td><td width="65%"><div class="casenotedesc">$d[description]</div></td><td width="10%">$d[username]</td><td><a href="#" title="Edit this Case Note" alt="Edit this Case Note" onClick="createTargets('time_form','time_form');sendDataGet('casenote_edit.php?id=$d[id]&ieyousuck=$rand');Effect.BlindDown('time_form');return false;"><img src="images/report_edit.png" border="0"></a></td><td id="deleter_$d[id]"><a href="#" title="Delete this Case Note" alt="Delete this Case Note" onClick="deleteCaseNote('$d[id]','the_info','$d[case_id]');return false;"><img src="images/report_delete.png" border="0"></a></td><td><a href="#" title="Print this casenote" alt="Print this casenote" onClick="printCaseNotes($d[id]);return false;"><img src='images/print_extra_small.png' border='0'> </a></td></tr>
NOTES;
}

    IF (mysql_num_rows($show_notes) < 1)
    {echo "There has been no activity in this case.";}

    echo
"</tbody></table><script>stripe('display_time','#fff','#e0e0e0');</script>";
?>
