<?php
session_start();
if (!$_SESSION)
{header('Location: index.php?login_error=3');}
include 'db.php';


?>


<div id="bar" style="width:100%;height:30px;background-color:rgb(195, 217, 255);"></div>

<span id="close"><a href="#" onclick="Effect.Shrink('messaging_window');return false;" alt="Close this Window" title="Close this Window"><img src="images/cancel_small_blue.png" border="0"></a></span>

<div id = "msg_info" style="text-align:left;padding:5px;height:95%;width:99%;overflow:auto;">

<form id="sendForm">
<label class ="msg" for "to">To</label><input type="text"  id="to_full" name="to_full" size="35"<?php
if(isset($_GET[direct_full]))
{

echo " value='$_GET[direct_full]'";
}
?>


>
<input type="hidden" id="to" name="to" <?php
if(isset($_GET[direct]))
{
echo " value='$_GET[direct]'";
}
?> 
>


<select name="group" id="group" onChange="document.getElementById('to_full').value=this.value;allWarn();">
<option value="">Select Groups</option>
<option value="All Your Students">All Your Students</option>
<option value="All Professors">All Professors</option>
<option value="All Students">All Students</option>
<option value="All Users">All Users</option>
</select>

<div id="autocomplete" style="display: none"></div><br>

<a href="#" style="font-size:9pt;color:blue;" onClick="Effect.Appear('ccer');">Add CC</a>
<br>

<input type="hidden" name="from" value="<?php echo $_SESSION[login];   ?>">

<div id="ccer" style="display:none;">
<label for "cc1_full" class="msg">Cc:</label><input type="text" name="cc1_full" id="cc1_full" size="35">
<input type="hidden" name="cc1" id="cc1">
<div id="autocomplete2" style="display: none"></div>
<br><br>
</div>

<label class ="msg" for "subject">Subject</label><input type="text" name="subject" id="subject" size="35">
<?php
if (isset($_GET[case_id]))
{
$get_client_name = mysql_query("SELECT * FROM `cm` WHERE `id` = '$_GET[case_id]' LIMIT 1");
$u = mysql_fetch_array($get_client_name);

echo <<<CASE
<span id="cs_name" style="font-size:14pt;">$u[first_name] $u[last_name]</span><input type="hidden" name="assoc_case" value="$_GET[case_id]">
CASE;
}
else
{
echo "<select name=\"assoc_case\"><option value=\"\">No Case</option>";

/* Get all cases assigned to student */

if ($_SESSION['class'] == 'student')
{
$get_cases = mysql_query("SELECT * FROM `cm_cases_students` WHERE `username` = '$_SESSION[login]'");
while ($j = mysql_fetch_array($get_cases))
{
$get_case_name = mysql_query("SELECT * FROM `cm` WHERE `id` = '$j[case_id]' AND `date_close` = '' ORDER BY `last_name` ASC");
    while ($k = mysql_fetch_array($get_case_name))
    {echo "<option value=\"$k[id]\">$k[last_name], $k[first_name]</option>";}
}

}
else
/* Get all cases assigned to professor */
{
$get_cases = mysql_query("SELECT * FROM `cm` WHERE `professor` = '$_SESSION[login]' AND `date_close` = '' OR `professor2` = '$_SESSION[login]' AND `date_close` = '' ORDER BY `last_name` ASC"); 
while ($l = mysql_fetch_array($get_cases))
{
echo "<option value=\"$l[id]\">$l[last_name], $l[first_name]</option> ";
}

}








ECHO "</select>";

}
?>

<br><br>
<label class="msg" for "body">Message</label>
<textarea cols="75" rows="15" id="body" name="body"></textarea>

<img src="images/onload_tricker.gif" onLoad="goLookup();goLookup2();">


<?php
/* this variable set header location once send script is excuted.  Yes format response for the case_interior message roll */

if (isset($_GET[case_id]))
{
echo <<<DIRECT
<input type="hidden" name="redirection" value="case">

DIRECT;
/* THIS WILL PUT THE REPSONSE IN CASE ACTIVITY IN cm_cases_single.php */
}
elseif (isset($_GET[direct]))
{
echo <<<DIRECT
<input type="hidden" name="redirection" value="student">

DIRECT;
/* This will put the response in the Student Detail page */
}

?>
<table width="40%" style="margin-left:400px;">
<tr><td><a href="#" onClick="var check = checkTo();if (check==true){Effect.Shrink('messaging_window');createTargets('<?php 
/* Again, the situation where this script is called from cm_cases_single instead of at a glance */


if (isset($_GET[case_id]))
{$update_place = "case_activity";}

elseif (isset($_GET[direct]))
{$update_place = "msg_notify";}

else
{$update_place = "message_roll";}




echo "$update_place";
?>','<?php echo "$update_place";
?>');sendDataPost('message_send.php','sendForm');updater('updater.php?type=messages','msg_notifier');return false;}" alt="Send Message" title="Send Message"><img src="images/check.png" border="0"></a>
<?php
if ($_SESSION['class'] != 'student')
{
echo <<<SMS
<td><label class="msg" for "sms">Also send via SMS</label><input type="checkbox" id="sms" name="sms"></td>
SMS;
}
?></tr></table>
</form>
</div>
