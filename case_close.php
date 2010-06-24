<?php
session_start();
include 'session_error.php';
include 'db.php';
include './classes/format_case_number.php';
include_once './classes/format_dates_and_times.class.php';
include 'get_name.php';


if ($_POST)
{


list($new_date) = dateToSqlAsVar($_POST[date_close]);


$close_it = mysql_query("UPDATE `cm` SET `date_close` = '$new_date',`close_notes` = '$_POST[close_notes]',`dispo` = '$_POST[dispo]' WHERE `id` = '$_POST[id]' LIMIT 1");
/* Then notify admin of closing via message */
$get_admin = mysql_query("SELECT `username` FROM `cm_users` WHERE `class` = 'admin'");
while ($s = mysql_fetch_array($get_admin))
{
list($fname,$lname) = getNameAsVar($_POST[prof]);
list($cs_no) = formatCaseNoAsVar($_POST[id]);
list($date) = formatDateAsVar($_POST[date_close]);
$subject = "Case $cs_no $_POST[first_name] $_POST[last_name] has been closed.";
$rand = rand();
$body = "This is to notify you that $cs_no $_POST[first_name] $_POST[last_name] was closed by $fname $lname on $date.";

$notify = mysql_query("INSERT INTO `cm_messages` ( `id` ,`thread_id` ,`to` ,`from` ,`subject` ,`body` ,`assoc_case` ,`time_sent` ,`read` ,`archive` ,`temp_id` ) VALUES (NULL,'','$s[username]','system','$subject','$body','$_POST[id]',CURRENT_TIMESTAMP,'','','$rand')");

$upd = mysql_query("UPDATE `cm_messages` SET `thread_id` = cm_messages.id WHERE `temp_id` = '$rand' LIMIT 1 ");

$del_upd = mysql_query("UPDATE `cm_messages` SET `temp_id` = '' WHERE `temp_id` = '$rand' LIMIT 1 ");

}


}
/* here to re-open case */
if ($_GET[reopen])
{
$reopen = mysql_query("UPDATE `cm` SET `date_close` = '' WHERE `id` = '$_GET[id]' LIMIT 1");

}

if ($_POST)
{$the_id = $_POST[id];}
else
{$the_id = $_GET[id];}


$result = mysql_query("SELECT * FROM `cm` WHERE `id` = '$the_id' LIMIT 1");

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($result,$i);
        $d[$field] = $col_value;
        $i++;

    }

}



echo "<span id=\"print_title\" style=\"display:none;\"><b>Closing Information</b></span>";

if (empty($d[date_close]))
{
$date = date('m/d/Y');
echo <<<CLOSEFORM
<div id="contacts_menu">Complete form below to close this case. </div>


<div style="margin:5% 20% 5% 20%">
<FORM id = "closer">
<table style="width:200px;border:0px;">
<tr>
<td style="width:40px;"><div style="width:150px"><label for "date_close" class="msg">Date Closed</label></td>
<td><input type = "text" name="date_close" id="date_close" value="$date">

<span style="display:inline"><a id="cal" href="#" onClick="scwShow(date_close,scwID('cal'));return false;" alt="Click to Select Date" title="Click to Select Date"><img src="images/calendar_no_bg.png" border="0"></a></span>

<input type = "hidden" name="id" value="$d[id]"></div>
<br>
</td></tr>

<tr><td><label for "dispo_choose" class="msg">Select Disposition</label></td>
<td><select id="dispo_choose" name="dispo" onChange="var el = document.getElementById('dispo');el.value = this.value;">
<option value= "" selected=selected>Please Select</option>
CLOSEFORM;
$dispos = mysql_query("SELECT `dispo` FROM `cm_dispos` ORDER BY `dispo` ASC");
while ($r = mysql_fetch_array($dispos))
{
echo "<option value='$r[dispo]'>$r[dispo]</option>";

}

ECHO "<OPTION VALUE='Other'>OTHER</OPTION> ";
echo <<<CLOSEFORM
</select>
<br><br>
<INPUT TYPE="hidden" name="dispo" id="dispo">
<INPUT TYPE="hidden" name="prof" id="prof" value="$d[professor]">
<INPUT TYPE="hidden" name="first_name" id="first_name" value="$d[first_name]">
<INPUT TYPE="hidden" name="last_name" id="last_name" value="$d[last_name]">

</td></tr>
<tr><td valign=top>
<label for "close_notes" class="msg">Notes, if any</label></td>
<td>
<textarea name="close_notes" id="close_notes" style="width:400px;height:300px;"></textarea>
</td></tr>
</table>
<center>
<a href="#" title="Close the Case" alt="Close the Case" onClick="createTargets('case_activity','case_activity');sendDataPost('case_close.php','closer');return false;"><img src="images/check.png" border = "0"></a>
</form>
</div>
CLOSEFORM;
}
else
{

echo <<<VIEW

<div style="margin:5% 20% 5% 20%;background-color:#c6c6c6;text-align:left;">
<span style="font-size:14pt;">This case was closed 
VIEW;
if ($_POST)
{$the_id = $_POST[id];}
else
{$the_id = $_GET[id];}
$cls = mysql_query("SELECT * FROM `cm` WHERE `id` = '$the_id'");
$b = mysql_fetch_array($cls);
formatDateHuman($b[date_close]);
echo <<<VIEW
 <br>
 Disposition: $b[dispo]<br>
 Closing Notes: $b[close_notes]<br><br>
 </div><br><center>
 <a href="#" onClick="createTargets('case_activity','case_activity');sendDataGet('case_close.php?reopen=1&id=$the_id');return false;">Re-Open</a>
</center>


VIEW;






}







?>
