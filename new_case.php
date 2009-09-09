<?php
session_start();
include 'db.php';
include './classes/case_opening.php';


if ($_POST)
{
	//Now, run the conflicts check
	$new_client= $_POST[first_name] . " " . $_POST[last_name];
	$checker = new openCase;
	if (!empty($_POST[adverse]))
	{$adverse_array = explode("\n",$_POST[adverse]);}
	$conflict_string = $checker->checkConflicts($new_client, $adverse_array);

	//Insert Adverse Party Data
	if(!empty($_POST[adverse]))
	{
	$adverse = strtoupper($_POST[adverse]);
	$names = explode("\n",$adverse);
	$case_id = $_POST[clinic_id];

		foreach($names as $v)

		{
		$query = mysql_query("INSERT INTO `cm_adverse_parties` (`id`,`clinic_id`,`name`) VALUES (NULL,'$case_id','$v');");
		}

	}

	//Insert New Client Data
	$put_in = mysql_query("

UPDATE `cm` SET `clinic_id` = '$_POST[clinic_id]',
`first_name` = '$_POST[first_name]',
`m_initial` = '$_POST[m_initial]',
`last_name` = '$_POST[last_name]',
`date_open` = '$_POST[date_open]',
`case_type` = '$_$_POST[case_type]',
`professor` = '$_POST[professor]',
`professor2` = '$_POST[professor2]',
`address1` = '$_POST[address1]',
`address2` = '$_POST[address2]',
`city` = '$_POST[city]',
`state` = '$_POST[state]',
`zip` = '$_POST[zip]',
`phone1` = '$_POST[phone1]',
`phone2` = '$_POST[phone2]',
`email` = '$_POST[email]',
`ssn` = '$_POST[ssn]',
`dob` = '$_POST[dob]',
`gender` = '$_POST[gender]',
`race` = '$_POST[race]',
`judge` = '$_POST[judge]',
`pl_or_def` = '$_POST[pl_or_def]',
`court` = '$_POST[court]',
`section` = '$_POST[section]',
`ct_case_no` = '$_POST[ct_case_no]',
`case_name` = '$_POST[case_name]',
`notes` = '$_POST[notes]',
`referral` = '$_POST[referral]' WHERE `id` = '$_POST[id]' LIMIT 1 ;

	");



	//Notify Professor of Opening and Of Potential Conflicts
	//Email Strings
	$subject = "$_POST[first_name] $_POST[last_name] case is opened.";
	$rand = rand();

	$body = "This is to notify you that the $_POST[first_name] $_POST[last_name] case has been opened in the Clinic Cases system. " .  nl2br($conflict_string);
	$body_for_email = "This is to notify you that the $_POST[first_name] $_POST[last_name] case has been opened in the Clinic Cases system. $conflict_string";


	$notify = mysql_query("INSERT INTO `cm_messages` ( `id` ,`thread_id` ,`to` ,`from` ,`subject` ,`body` ,`assoc_case` ,`time_sent` ,`read` ,`archive` ,`temp_id` ) VALUES (NULL,'','$_POST[professor]','system','$subject','$body','',CURRENT_TIMESTAMP,'','','$rand')");
	$upd = mysql_query("UPDATE `cm_messages` SET `thread_id` = cm_messages.id WHERE `temp_id` = '$rand' LIMIT 1 ");
	$del_upd = mysql_query("UPDATE `cm_messages` SET `temp_id` = '' WHERE `temp_id` = '$rand' LIMIT 1 ");

	$get_email = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$_POST[professor]' LIMIT 1");
	$res2 = mysql_fetch_array($get_email);
	$email_to = $res2[email];

	$headers = 'From: ' . $CC_default_email . "\r\n" .
   'Reply-To: ' . $CC_default_email . "\r\n" .
   'X-Mailer: PHP/' . phpversion();

	mail($email_to,$subject,$body_for_email,$headers);
	//End

echo <<<RESPONSE
<span id="close"><a href="#" onclick="location.href='cm_admin_cases.php';return false;" alt="Close this Case Window" title="Close this Case Window"><img src="images/cancel_small.png" border="0"></a></span>

RESPONSE;

//Notify of case opener of opening and conflict

echo "<br><br>";
echo "<span class='required'>Case opened</span>. <br><br>";

echo nl2br($conflict_string);
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
//End of Post




//The case opening form
echo <<<DATA
<span id="close"><a href="#" onclick="Effect.Shrink('window1');document.getElementById('view_chooser').style.display = 'inline';return false;" alt="Close this Case Window" title="Close this Case Window"><img src="images/cancel_small.png" border="0"></a></span>
<FORM id="newCaseForm">
<div id="main">

<DIV ID="new_case">
<P>
<LABEL>New Case No:</label><div style="font-weight:bold;font-size:14pt;">

DATA;

	//create new case number
	$new_no = new openCase();
	$x = $new_no->createCaseNo();
	$year = date(Y);
	echo "$year" . "-" . $x[0];

echo <<<DATA
</div></p>

<input type="hidden" name="clinic_id" value="$x[0]">
<input type="hidden" name="id" value="$x[1]">
<p>
<LABEL FOR "fname">First Name</label><input type="text" name="first_name" id="fname" size="35">
 </p>
<p><LABEL FOR "m_initial">Middle Initial</label><input type="text" name="m_initial" id="m_initial" size="3">
</p>

<p>
<LABEL FOR "lname">Last Name</label><input type="text" name="last_name" id="lname" size="35">
</p>
<p><LABEL FOR "address1">Address</LABEL><INPUT TYPE="text" name = "address1" id="address1" size="35"></p>
<p><LABEL FOR "address2">Address 2</LABEL><INPUT TYPE="text" name = "address2" id="address2" size="35"></p>
<p><LABEL FOR "city">City</LABEL><INPUT TYPE="text" name = "city" id="city" size="35"></p>
<p><LABEL FOR "state">State</LABEL><SELECT name = "state" id="state">
<option value =  "" selected="selected">--</option>
<option value = "AK">Alaska</option>
<option value = "AL">Alabama</option>
<option value = "AR">Arkansas</option>
<option value = "AZ">Arizona</option>
<option value = "CA">California</option>
<option value = "CO">Colorado</option>
<option value = "CT">Connecticut</option>
<option value = "DC">District of Columbia</option>
<option value = "DE">Delaware</option>
<option value = "FL">Florida</option>

<option value = "GA">Georgia</option>
<option value = "HI">Hawaii</option>
<option value = "IA">Iowa</option>
<option value = "ID">Idaho</option>
<option value = "IL">Illinois</option>
<option value = "IN">Indiana</option>
<option value = "KS">Kansas</option>
<option value = "KY">Kentucky</option>
<option value = "LA">Louisiana</option>

<option value = "MA">Massachusetts</option>
<option value = "MD">Maryland</option>
<option value = "ME">Maine</option>
<option value = "MI">Michigan</option>
<option value = "MN">Minnesota</option>
<option value = "MO">Missouri</option>
<option value = "MS">Mississippi</option>
<option value = "MT">Montana</option>
<option value = "NC">North Carolina</option>

<option value = "ND">North Dakota</option>
<option value = "NE">Nebraska</option>
<option value = "NH">New Hampshire</option>
<option value = "NJ">New Jersey</option>
<option value = "NM">New Mexico</option>
<option value = "NV">Nevada</option>
<option value = "NY">New York</option>
<option value = "OH">Ohio</option>
<option value = "OK">Oklahoma</option>

<option value = "OR">Oregon</option>
<option value = "PA">Pennsylvania</option>
<option value = "RI">Rhode Island</option>
<option value = "SC">South Carolina</option>
<option value = "SD">South Dakota</option>
<option value = "TN">Tennessee</option>
<option value = "TX">Texas</option>
<option value = "UT">Utah</option>
<option value = "VA">Virginia</option>

<option value = "VT">Vermont</option>
<option value = "WA">Washington</option>
<option value = "WI">Wisconsin</option>
<option value = "WV">West Virginia</option>
<option value = "WY">Wyoming</option>
</SELECT>
</p>
<p><LABEL FOR "zip">Zip Code</LABEL><INPUT TYPE="text" name = "zip" id="zip" size="10"></p>
<p><LABEL FOR "phone1">Phone 1</LABEL><INPUT TYPE="text" name = "phone1" id="phone1" size="35"></p>
<p><LABEL FOR "phone2">Phone 2</LABEL><INPUT TYPE="text" name = "phone2" id="phone2" size="35"></p>
<p><LABEL FOR "email">Email</LABEL><INPUT TYPE="text" name = "email" id="email" size="35"></p>

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

	//get the types of cases the clinic handles from the db
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

<P><LABEL FOR "court">Court</label><select name="court" id="court" ><option value="">Please Select</option>

DATA;

	//get the courts list from the db
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
<p><label for "pl_or_def">Client is:</label><select name="pl_or_def" id="pl_or_def"><option value="Defendant">Defendant</option><option value="Plaintiff">Plaintiff</option><option value="Other">Other</option></select>
</p>
<p><label for "adverse">Adverse Parties (place each name on a new line)</label><textarea name="adverse" id="adverse" cols="41" rows="5"></textarea></p>
<p><label for "professor">Professor:</label><select name="professor" id="professor"><option value="">Please Select</option>
DATA;

	//get the list of professors
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

	//Get the professor list again in case two professors are handling case
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
<p><label for "referral">Referral</label><select name="referral" id="referral"><option value="None" selected="selected">None</option>
DATA;

//get the referral list from the db
	$get_referrals = mysql_query("SELECT * FROM `cm_referral` ORDER BY `referral` ASC");
	WHILE ($result3 = mysql_fetch_array($get_referrals))
	{
	$referral = $result3['referral'];
	echo "<option value=\"$referral\">$referral</option> ";
	}


ECHO <<<DATA
</select></p>
<p><label for "notes">Notes</label><textarea name="notes" id="notes" cols="41" rows="5"></textarea></p>
<input type="hidden" name="date_open" value="$date_open">
<p><center><input type="button" value="Add Case" onClick="var ncval = newCaseVal();if (ncval == true){createTargets('window1','window1');sendDataPost('new_case.php','newCaseForm');return false;}"></center></p>

</DIV></form>
DATA;
?>
