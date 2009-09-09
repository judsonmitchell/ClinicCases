<?php
session_start();

include 'session_error.php';
include 'db.php';
include './classes/format_dates_and_times.class.php';
function genSelect($target,$chosen_array,$select_name){

echo "<select name=\"$select_name\" id=\"$select_name\">";
/* arrays of the possible choices in all selects */
$hours = array(0,1,2,3,4,5,6,7,8);
$minutes =  array('0','1','5','10','15','20','25','30','35','40','45','50','55');

switch($chosen_array){
case "hours": 
$array = $hours;break;
case "minutes":
$array = $minutes;break;

}

foreach ($array as $v)
{
if ($v == $target)
{echo "<option value = \"$v\" selected=\"selected\">$v</option>";}
else {echo "<option value=\"$v\">$v</option>";}
}
echo "</select>";
}

if ($_POST)
{
$datebreak = explode("/",$_POST[date]);
$month = $datebreak[0];
$day = $datebreak[1];
$year = $datebreak[2];
$new_date = "$year" . "-" . "$month" . "-" . "$day" . " " . "00:00:00";
$hours_into_seconds = ($_POST[hours] * 3600);
$minutes_into_seconds = ($_POST[minutes] * 60);
$time = $hours_into_seconds + $minutes_into_seconds;
$update = mysql_query("UPDATE `cm_case_notes` SET `date` = '$new_date', `time` = '$time', `description` = '$_POST[description]' WHERE `id` = '$_POST[id]'");

$rand = rand();

if ($_POST["case_id"] == 'NC')
{
header('Location: display_noncase.php?notifyedit=1&ieyousuck=' . $rand);die;
}
else
{
header('Location: display_casenotes.php?notifyedit=1&id=' . $_POST[case_id] . '&ieyousuck=' . $rand);die;
}






}

/* This is a hack to fix an ajax problem with the calendar coming back after you navigate away from this page.  The date input is given a unix timestamp to make it unique.  Solves DOM problem with javascript */
$date = time();





$id = $_GET['id'];
$get_data = mysql_query("SELECT * FROM `cm_case_notes` WHERE `id` = '$id' LIMIT 1");
while ($line = mysql_fetch_array($get_data, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_data,$i);
        $d[$field] = $col_value;
        $i++;
    }


}


list($new_time,$the_unit) = formatTime($d[time]);
$break = explode(".",$new_time);
$the_hour = $break[0];
$the_minute = $break[1];
 
 
  



echo <<<FORM

<form id="caseNotes">
<table id="time_form_table" border="0">
<tr>
<td></td><td><label for "date">Select Date</label></td><td><label for "hours">Hours</label></td><td><label for "minutes">Minutes</label><td align="center"><label for "description">Describe What You Did</label></td><td></td><td></td></tr>

<tr><td>
<input type="text" id="date$date" name="date" size="10" value= "
FORM;

formatDateHuman($d[date]);

ECHO <<<FORM
" style="border:0px;background-color:rgb(255, 255, 204);"></td><td align="center">


<a id="cal" href="#" onClick = scwShow(date$date,scwID('cal'));return false;" alt="Click to Select Date" title="Click to Select Date"><img src="images/calendar.png" border="0"></a></td><td>
FORM;
genSelect($the_hour,'hours','hours');


echo "</td><td>";
genSelect($the_minute,'minutes','minutes');

ECHO <<<FORM
<input type="hidden" name="id" value="$id">
<input type="hidden" name="case_id" value="$d[case_id]">
</td><td><textarea cols="20" rows="2" id="description" name="description">$d[description]</textarea></td><td>
<a href="#" onClick="createTargets('status','the_info');sendDataPostAndStripeNoStatus2('casenote_edit.php','time_form');clearFormAll($date);Effect.BlindUp('time_form');updateLeftSide($case_id);return false;" alt="Edit This Case Note" title="Edit This Case Note"><img src="images/check_yellow.png" border="0"></a></td><td><a title="Cancel" alt="Cancel" href="#" onClick="Effect.BlindUp('time_form');clearFormAll();return false;"><img src="images/cancel_small.png" border="0"></a></td></tr></table></form></div>

FORM;







?>
