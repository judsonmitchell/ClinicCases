<?php
session_start();
if (!$_SESSION)
{header('Location: index.php');die;}
include 'db.php';


$rand = rand();




echo <<<DATA
<span id="close"><a href="#" onclick="Effect.Shrink('window1');document.getElementById('view_chooser').style.display = 'inline';return false;" alt="Close this Case Window" title="Close this Case Window"><img src="images/cancel_small.png" border="0"></a></span>
<FORM id="newUserForm" name="newUserForm">
<div id="main">

<DIV ID="new_case">
<P><B>Add New User</b>.  <span class="required">Pink Fields</span> are required.</p>
<p>
<LABEL FOR "fname">First Name</label><input type="text" name="first_name" id="first_name" size="35" class="required">
</p>
<p>
<LABEL FOR "lname">Last Name</label><input type="text" name="last_name" id="last_name" size="35" class="required">
</p>
<p><LABEL FOR "email">Email</LABEL><INPUT TYPE="text" name = "email" id="email" size="35" class="required"></p>
<p><LABEL FOR "mobile_phone">Mobile Phone</LABEL><INPUT TYPE="text" name = "mobile_phone" id="mobile_phone" size="35"></p>
<p><LABEL FOR "office_phone">Office Phone</LABEL><INPUT TYPE="text" name = "office_phone" id="office_phone" size="35"></p>
<p><LABEL FOR "home_phone">Home Phone</LABEL><INPUT TYPE="text" name = "home_phone" id="home_phone" size="35" value=""></p>

<p>

<LABEL FOR "class">User Type</label><select name="class" id="class" onChange="profCheck();"  class="required"><option value="" selected=selected>Please Select</option><option value="student">Student</option><option value="prof">Professor</option><option value="admin">Adminstrator</option></select>

</p>


<div id = "popout" style="display:none;">
<p>
<LABEL FOR "assigned_prof">Professor</label>
<select name="assigned_prof" id="assigned_prof"><option value="">Please Select</option>


DATA;
$get_prof = mysql_query("SELECT * FROM `cm_users` WHERE `class` = 'prof' ORDER BY `last_name` ASC");
WHILE ($result2 = mysql_fetch_array($get_prof))
{
$prof= $result2['last_name'];
$prof_user = $result2['username'];
echo "<option value=\"$prof_user\">$prof</option> ";
}

ECHO <<<DATA

</select></p>

</div>
<p>
<LABEL FOR "status">Status</label><select name="status" id="status"><option value="active" selected=selected>Active</option><option value="inactive">Inactive</option></select></p>

<p>
<LABEL FOR "timezone">Timezone</label><select id="timezone" name="timezone">
<option value = "1" selected = "selected">U.S. Central</option>
<option value = "2">U.S. Eastern</option>
<option value = "0">U.S. Mountain</option>
<option value = "-1">U.S. Pacific</option>
</select></p>



</div>


</div>
<DIV ID="new_case_right">
<input type="hidden" value="$rand" name="temp_id">
<iframe src="new_user_photo_form.php?temp_id=$rand" width="270" height="300" frameborder="0" scrolling="no"></iframe>
<br><br><br>
<input type="button" value="Submit" onClick="var check = newUserValidate();if (check == true){createTargets('window1','window1');sendDataPost('new_user_process.php','newUserForm'); return false;}">


</DIV></form>
DATA;
?>
