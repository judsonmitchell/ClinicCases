<?php
session_start();
if (!$_SESSION)
{echo "There is a login problem.  Please login again.";die;}
include 'db.php';
include './classes/format_dates_and_times.class.php';

/* Attempt to put in a post */
if ($_POST)
{
	
	foreach ($_POST['professor'] as $pp)
			{
				$assigned_prof .= $pp . ",";
			}
	
$update_query = mysql_query("UPDATE `cm_users` SET `first_name` = '$_POST[first_name]',`last_name` = '$_POST[last_name]',`email` = '$_POST[email]',`mobile_phone` = '$_POST[mobile_phone]',`office_phone` = '$_POST[office_phone]',`home_phone` = '$_POST[home_phone]',`status` = '$_POST[status]',`class` = '$_POST[class]',`assigned_prof` = '$assigned_prof' WHERE `id` = '$_POST[user_id]' LIMIT 1");

$id = $_POST['user_id'];
}

/* End of attempt */
else
{$id = $_GET['id'];}
$get_student_info = mysql_query("SELECT * FROM `cm_users` WHERE `id` = '$id' LIMIT 1");
while ($line = mysql_fetch_array($get_student_info, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_student_info,$i);
        $d[$field] = $col_value;
        $i++;


    }
    }


echo <<<PAGE

<span id="close" style="right:15px;"><a href="#" onclick="Effect.Shrink('window1');document.getElementById('view_chooser').style.visibility='visible';return false;" alt="Close this Case Window" title="Close this Case Window"><img src="images/cancel_small.png" border="0"></a></span>
<div id="user_container">
<div id="straight_left">
<h3>$d[first_name] $d[last_name]</h3>
<table border="0" width="100%">
<tr><td width="50%">
<img src="$d[picture_url]" border="0"><br><a href="#" onClick="document.getElementById('picture_edit').style.display = 'block';return false;">Change Picture</a></td><td width="50%" align="center" valign="center">
<div id="msg_notify" style="display:none;">

</div>
<a href="#" title="Send $d[first_name] $d[last_name] a message" alt="Send $d[first_name] $d[last_name] a message" onClick="Effect.Grow('messaging_window');createTargets('messaging_window','messaging_window');sendDataGet('message_new.php?direct=$d[username]&direct_full=$d[first_name] $d[last_name]');return false;"><img src="images/new_msg_blue.png" border="0"></a></td></tr></table>
<div id = "picture_edit" style="display:none;">
<iframe src="new_user_photo_form.php?temp_id=$d[id]&exists=yes" width="270" height="300" frameborder="0" scrolling="no"></iframe>
</div>

</div>

<div id="straight_right">
<fieldset><legend><b>Personal Data</b></legend>
<table width="80%" border="0" align="center" class="user_detail_table">
<tr><td width="20%">First Name:</td><td id ="fn" class="tdata" width="30%">$d[first_name]</td><td width="20%">Last Name:</td><td id="ln" class="tdata" width="30%">$d[last_name]</td></tr><table>
<table width="80%" border="0" align="center" class="user_detail_table">
<tr><td width="20%">Email</td><td id="email" class="tdata" width="80%"><a href="mailto:$d[email]">$d[email]</a></td></tr></table>

<table width="80%" border="0" align="center" class="user_detail_table">
<tr><td width="20%">Mobile Phone</td><td id="mph" class="tdata"  width="30%">$d[mobile_phone]</td><td width="20%">Office Phone</td><td id="oph" class="tdata">$d[office_phone]</td></tr>
<tr><td width="20%">Home Phone</td><td id="hph" class="tdata"  width="30%">$d[home_phone]</td><td></td><td></td></tr>
</table>

<table width="80%" border="0" align="center" class="user_detail_table">
<tr><td width="20%">Status</td><td class="tdata" width="30%">$d[status]</td><td width="20%">User Type</td><td class="tdata" width="30%">$d[class]</td></tr>
<tr><td width="20%">Username</td><td class="tdata" width="30%">$d[username]</td><td width="20%">Date Added</td><td class="tdata" width="30%">
PAGE;
formatDateHuman($d[date_created]);
echo <<<PAGE

</td></tr>
PAGE;
if ($d["class"] == 'student')
{
	$pr_sub = substr($d[assigned_prof],0,-1);
echo "<tr><td>Assigned Professor(s):</td><td class='tdata'>$pr_sub</td><td></td><td></td></tr>";

}
echo "</table>";
?>
<center>
<input type="button" value="Edit" onClick="createTargets('straight_right','straight_right');sendDataGet('user_edit.php?id=<?php echo $d[id];  ?>');">
</center>
</fieldset>
</div>

