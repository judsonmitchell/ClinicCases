<?php
session_start();
include 'db.php';


if ($_POST)
{

$new_client= $_POST[first_name] . " " . $_POST[last_name];
$conflicts_query = mysql_query("SELECT * FROM `cm_adverse_parties` WHERE MATCH (name) AGAINST ('$new_client')");

//Problem here is that the new adverse party must also be checked against the complete client list (all clients in cm).
//?  $split
// SELECT * FROM `cm` WHERE `first_name` LIKE '%$adv_fname%' AND WHERE `%ast_name` LIKE '%$adv_lname%'



if(!empty($_POST[adverse]))
{
$adverse = strtoupper($_POST[adverse]);

$names = explode(',',$adverse);
$case_id = $_POST[clinic_id];
foreach($names as $v)
{
$query = mysql_query("INSERT INTO `cm_adverse_parties` (`id`,`clinic_id`,`name`) VALUES (NULL,'$case_id','$v');");
}


}


$put_in = mysql_query("INSERT INTO `cm` (`id` ,`clinic_id` ,`first_name` ,`last_name` ,`date_open` ,`case_type` ,`professor` , `professor2`, `address1` ,`address2` ,`city` ,`state` ,`zip` ,`phone1` ,`phone2` ,`phone3` ,`ssn` ,`dob` ,`gender` ,`race` ,`judge` ,`pl_or_def` ,`court` ,`section` ,`ct_case_no` ,`case_name`) VALUES (NULL , '$_POST[clinic_id]', '$_POST[first_name]', '$_POST[last_name]', '$_POST[date_open]', '$_POST[case_type]', '$_POST[professor]', '$_POST[professor2]', '$_POST[address1]', '$_POST[address2]', '$_POST[city]', '$_POST[state]', '$_POST[zip]', '$_POST[phone1]', '$_POST[phone2]', '$_POST[phone3]', '$_POST[ssn]', '$_POST[dob]', '$_POST[gender]', '$_POST[race]', '$_POST[judge]', '$_POST[pl_or_def]', '$_POST[court]', '$_POST[section]', '$_POST[ct_case_no]', '$_POST[case_name]')");

/* Notify Professor of Opening */
$subject = "$_POST[first_name] $_POST[last_name] case is opened.";
$rand = rand();

$body = "This is to notify you that the $_POST[first_name] $_POST[last_name] case has been opened in the Clinic Cases system.";

$notify = mysql_query("INSERT INTO `cm_messages` ( `id` ,`thread_id` ,`to` ,`from` ,`subject` ,`body` ,`assoc_case` ,`time_sent` ,`read` ,`archive` ,`temp_id` ) VALUES (NULL,'','$_POST[professor]','system','$subject','$body','',CURRENT_TIMESTAMP,'','','$rand')");

$upd = mysql_query("UPDATE `cm_messages` SET `thread_id` = cm_messages.id WHERE `temp_id` = '$rand' LIMIT 1 ");

$del_upd = mysql_query("UPDATE `cm_messages` SET `temp_id` = '' WHERE `temp_id` = '$rand' LIMIT 1 ");

$get_email = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$_POST[professor]' LIMIT 1");
$res2 = mysql_fetch_array($get_email);

$email_to = $res2[email];

$headers = 'From: <no-reply@ClinicCases.com>' . "\r\n" .
   'Reply-To: <no-reply@ClinicCases.com>' . "\r\n" .
   'X-Mailer: PHP/' . phpversion();

mail($email_to,$subject,$body,$headers);
/* End */

echo <<<RESPONSE
<span id="close"><a href="#" onclick="location.href='cm_admin_cases.php';return false;" alt="Close this Case Window" title="Close this Case Window"><img src="images/cancel_small.png" border="0"></a></span>
Done.
RESPONSE;

if (mysql_num_rows($conflicts_query)>0)
{
echo "<br><br>There may be a potential conflict with this case.<br>  Check the following cases:<br>";
while ($r = mysql_fetch_array($conflicts_query))
{
$case_id = $r['clinic_id'];
$name = $r['name'];

echo "$case_id $name <br>";

}
}


echo <<<AGAIN
<br><br>
<a href="#" onClick="createTargets('window1','window1');sendDataGet('new_case.php');return false;">Add Another</a>
AGAIN;
$get_the_id = mysql_query("SELECT `id` FROM `cm` WHERE `clinic_id` = '$_POST[clinic_id]' LIMIT 1");
$rows = mysql_fetch_array($get_the_id);
$id = $rows[id];
echo <<<AGAIN
<br><br>
<a href="#"  onClick="createTargets('window1','window1');sendDataGet('cm_cases_single.php?id=$id');return false;">View $new_client Case </a><br>
<a href="#" onClick="createTargets('window1','window1');sendDataGet('new_case.php');return false;">Add Another Case</a>
AGAIN;
die;

}




function createCaseNo()
{
/* NOTE: THIS DOES NOT CONTEMPLATE THE SITUATION WHERE PEOPLE ARE SIMULTANEOUSLY ENTERING NEW CASES.  NUMBER IS NOT LOCKED. */
$get_next_number = mysql_query("SELECT MAX(clinic_id) FROM `cm`");
$row = mysql_fetch_array($get_next_number);
$number = $row["MAX(clinic_id)"];
$add_number = $number + 1;
$year = date(Y);
echo $add_number;
}


echo <<<DATA
<span id="close"><a href="#" onclick="Effect.Shrink('window1');document.getElementById('view_chooser').style.display = 'inline';return false;" alt="Close this Case Window" title="Close this Case Window"><img src="images/cancel_small.png" border="0"></a></span>
<FORM id="newCaseForm">
<div id="main">

<DIV ID="new_case">
<P>
<LABEL>New Case No:</label><div style="font-weight:bold;font-size:14pt;">

DATA;
$year = date(Y);

echo "$year" . "-";
createCaseNo();
echo <<<DATA
</div></p>

<input type="hidden" name="clinic_id" value="
DATA;
createCaseNo();
echo <<<DATA

">
<p>
<LABEL FOR "fname">First Name</label><input type="text" name="first_name" id="fname" size="35">
</p>
<p>
<LABEL FOR "lname">Last Name</label><input type="text" name="last_name" id="lname" size="35">
</p>
<p><LABEL FOR "address1">Address</LABEL><INPUT TYPE="text" name = "address1" id="address1" size="35"></p>
<p><LABEL FOR "address2">Address 2</LABEL><INPUT TYPE="text" name = "address2" id="address2" size="35"></p>
<p><LABEL FOR "city">City</LABEL><INPUT TYPE="text" name = "city" id="city" size="35"></p>
<p><LABEL FOR "state">State</LABEL><INPUT TYPE="text" name = "state" id="state" size="35" value=" "></p>
<p><LABEL FOR "zip">Zip Code</LABEL><INPUT TYPE="text" name = "zip" id="zip" size="10"></p>
<p><LABEL FOR "phone1">Phone 1</LABEL><INPUT TYPE="text" name = "phone1" id="phone1" size="35"></p>
<p><LABEL FOR "phone2">Phone 2</LABEL><INPUT TYPE="text" name = "phone2" id="phone2" size="35"></p>
<p><LABEL FOR "phone3">Phone 3</LABEL><INPUT TYPE="text" name = "phone3" id="phone3" size="35"></p>
<p>
<table><tr><td>
<LABEL FOR "DOB">DOB (mm/dd/yyyy)</LABEL><INPUT TYPE="text" name = "dob" id="DOB" size="10"></td><td><LABEL FOR "ssn" style="width:40px;">SSN</label><input type="text" name="ssn" id="ssn" size="11"></td></tr></table></p>
<p>
<table><tr><td>
<LABEL FOR "gender">Gender</label><select name="gender" id="gender"><option value="M">Male</option><option value="F">Female</option></select></td><td><LABEL FOR "race" style="width:40px;">Race</label><select name="race" id="race"><option value="">Select</option><option value="AA">African-American</option><option value="H">Hispanic</option><option value="W">White</option><option value="O">Other</option></select></td></tr></table></p>

</DIV>
</div>
<DIV ID="new_case_right">
<P>
<LABEL FOR "type1">Case Type</label><select name="case_type" id="type1">
<option value="">Choose</option>
DATA;
$get_types = mysql_query("SELECT * FROM `cm_case_types` ORDER BY `type` ASC");
WHILE ($res = mysql_fetch_array($get_types))
{
$thetype = $res['type'];
echo "<option value=\"$thetype\">$thetype</option> ";
}
ECHO <<<DATA
</select>
</P>
<p><LABEL FOR "case_name">Case Title</LABEL><INPUT TYPE="text" name = "case_name" id="case_name" size="35"></p>

<P><LABEL FOR "court">Court</label><select name="court" id="court" style="font-size:12pt"><option value="">Please Select</option>

DATA;
$get_courts = mysql_query("SELECT * FROM `cm_courts` ORDER BY `court` ASC");
WHILE ($result = mysql_fetch_array($get_courts))
{
$court = $result['court'];
echo "<option value=\"$court\">$court</option> ";
}
ECHO <<<DATA

</select></p>


<p>
<table>
<tr><td><label for "ct_case_no">Court Case No.</label><input type="text" name="ct_case_no" id="ct_case_no" size="10"></td><td><label for "section" style="width:50px;">Section</label><input type="text" name="section" id="section" size="5"></td></tr></table></p>
<p><label for "judge">Judge</label><input type="text" name="judge" id="judge" size="35"></p>
<p><label for "pl_or_def">Client is:</label><select name="pl_or_def" id="pl_or_def"><option value="Defendant">Defendant</option><option value="Plaintiff">Plaintiff</option></select>
</p>
<p><label for "adverse">Adverse Parties (seperate names by commas)</label><textarea name="adverse" id="adverse" cols="35" rows="3"></textarea></p>
<p><label for "professor">Professor:</label><select name="professor" id="professor"><option value="">Please Select</option>
DATA;
$get_prof = mysql_query("SELECT * FROM `cm_users` WHERE `class` = 'prof' ORDER BY `last_name` ASC");
WHILE ($result2 = mysql_fetch_array($get_prof))
{
$prof= $result2['last_name'];
$prof_user = $result2['username'];
echo "<option value=\"$prof_user\">$prof</option> ";
}
$date_open = date('Y-m-d');
ECHO <<<DATA

</select></p>

<p><label for "professor2">Professor 2:</label><select name="professor2" id="professor2"><option value="" selected="selected">None</option>
DATA;
$get_prof = mysql_query("SELECT * FROM `cm_users` WHERE `class` = 'prof' ORDER BY `last_name` ASC");
WHILE ($result2 = mysql_fetch_array($get_prof))
{
$prof= $result2['last_name'];
$prof_user = $result2['username'];
echo "<option value=\"$prof_user\">$prof</option> ";
}
$date_open = date('Y-m-d');
ECHO <<<DATA

</select></p>
<input type="hidden" name="date_open" value="$date_open">
<p><center><input type="button" value="Add Case" onClick="createTargets('window1','window1');sendDataPost('new_case.php','newCaseForm');return false;"></center></p>



</DIV></form>
DATA;
?>
