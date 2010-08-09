<?php
session_start();
include 'db.php';
include './classes/format_case_number.php';
include './classes/gen_select.php';

if ($_POST)
{
$update_adverse = mysql_query("UPDATE `cm_adverse_parties` SET `name` = '$_POST[adverse]' WHERE `id` = '$_POST[adverse_id]' LIMIT 1");

//This is to explode the professor array
	
		foreach ($_POST['professor'] as $pp)
		{
			$prof_list .= $pp . ",";
		}


$update = mysql_query("UPDATE `cm` SET `first_name` = '$_POST[first_name]', `m_initial` = '$_POST[m_initial]',`last_name` = '$_POST[last_name]', `case_type` = '$_POST[case_type]', `professor` = '$prof_list', `address1` = '$_POST[address1]', `address2` = '$_POST[address2]', `city` = '$_POST[city]', `state` = '$_POST[state]', `zip` = '$_POST[zip]', `phone1` = '$_POST[phone1]', `phone2` = '$_POST[phone2]', `email` = '$_POST[email]', `dob`= '$_POST[dob]', `ssn` = '$_POST[ssn]', `gender` = '$_POST[gender]', `race` = '$_POST[race]', `income` = '$_POST[income]',`per` = '$_POST[per]',`judge` = '$_POST[judge]', `pl_or_def` = '$_POST[pl_or_def]', `court`= '$_POST[court]', `section` = '$_POST[section]', `ct_case_no` = '$_POST[ct_case_no]',`case_name` = '$_POST[case_name]', `notes` = '$_POST[notes]' WHERE `id` = '$_POST[id]' LIMIT 1");




die;

}
//End Post

$get_case_d = mysql_query("SELECT * FROM `cm` WHERE `id` = '$_GET[id]' LIMIT 1");
	while ($line = mysql_fetch_array($get_case_d, MYSQL_ASSOC)) {
    		$i=0;
			foreach ($line as $col_value) {
				$field=mysql_field_name($get_case_d,$i);
				$d[$field] = $col_value;
				$i++;

			}
    	}
?>

<span id="close"><a href="#" onclick="Effect.Shrink('window1');document.getElementById('view_chooser').style.display = 'inline';return false;" alt="Close this Case Window" title="Close this Case Window"><img src="images/cancel_small.png" border="0"></a></span>

<FORM id="newCaseEditForm">
<div id="main">

<DIV ID="new_case">
<p><LABEL>Case No:</label><div style="font-weight:bold;font-size:14pt;">

<?php
	list($cs_no) = formatCaseNo($d[id]);
	echo $cs_no;
?>

</div></p>

<p>
<LABEL FOR "fname">First Name</label><input type="text" name="first_name" id="fname" size="30" value="<?php echo $d[first_name]; ?>">
</p>
<p><LABEL FOR "m_inital">Middle Initial</label><input type="text" name="m_initial" id="m_initial" size="3" value="<?php echo $d[m_initial]; ?>">
</p>
<p>
<LABEL FOR "lname">Last Name</label><input type="text" name="last_name" id="lname" size="30" value="<?php echo $d[last_name]; ?>">
</p>
<p><LABEL FOR "address1">Address</LABEL><INPUT TYPE="text" name = "address1" id="address1" size="30" value="<?php echo $d[address1]; ?>"></p>
<p><LABEL FOR "address2">Address 2</LABEL><INPUT TYPE="text" name = "address2" id="address2" size="30" value="<?php echo $d[address2]; ?>"></p>
<p><LABEL FOR "city">City</LABEL><INPUT TYPE="text" name = "city" id="city" size="30" value="<?php echo $d[city]; ?>"></p>
<p><LABEL FOR "state">State</LABEL>
<?php
genStateSelect($d[state],'state');
?>

</p>





<p><LABEL FOR "zip">Zip Code</LABEL><INPUT TYPE="text" name = "zip" id="zip" size="10" value="<?php echo $d[zip]; ?>"></p>
<p><LABEL FOR "phone1">Phone 1</LABEL><INPUT TYPE="text" name = "phone1" id="phone1" size="30" value="<?php echo $d[phone1]; ?>"></p>
<p><LABEL FOR "phone2">Phone 2</LABEL><INPUT TYPE="text" name = "phone2" id="phone2" size="30" value="<?php echo $d[phone2]; ?>"></p>
<p><LABEL FOR "email">Email</LABEL><INPUT TYPE="text" name = "email" id="email" size="30" value="<?php echo $d[email]; ?>"></p>
<p>
<table><tr><td>
<LABEL FOR "DOB">DOB (mm/dd/yyyy)</LABEL><INPUT TYPE="text" name = "dob" id="DOB" size="10" value="<?php echo $d[dob] ?>"></td><td><LABEL FOR "ssn" style="width:40px;">SSN</label><input type="text" name="ssn" id="ssn" size="11" value="<?php echo $d[ssn] ?>"></td></tr></table></p>
<p>
<table><tr><td>
<LABEL FOR "gender">Gender</label>
	
<?php
	genSelect($d[gender],'gender','gender');
?>

</td><td><LABEL FOR "race" style="width:40px;">Race</label>

<?php
genSelect($d[race],'race','race');
?>

</td></tr></table></p>

<p>
<table><tr><td><LABEL FOR "income">Income</label><input type="text" name="income" id="income" size="10" value="<?php echo $d[income]; ?>"></td><td><LABEL FOR "per" style="width:40px;">per:</label>
<?php
	genSelect($d[per],'per','per');
?>

</td></tr></table></p>

</DIV>
</div>
<DIV ID="new_case_right">
<P>
<LABEL FOR "type1">Case Type</label><select name="case_type" id="type1">

<?php
$get_types = mysql_query("SELECT * FROM `cm_case_types` ORDER BY `type` ASC");
	while ($res = mysql_fetch_array($get_types))
		{
			$thetype = $res['type'];
			$get_this_type = mysql_query("SELECT `case_type` FROM `cm` WHERE `id` = '$_GET[id]' LIMIT 1");
			$z = mysql_fetch_array($get_this_type);
				if ($thetype == $z[case_type])
				{
				echo "<option value=\"$thetype\" selected=\"selected\">$thetype</option>";

				}
					else
						{
						echo "<option value=\"$thetype\">$thetype</option>";
						}
		}

?>

</select>
</P>
<p><LABEL FOR "case_name">Case Title</LABEL><INPUT TYPE="text" name = "case_name" id="case_name" size="30" value="<?php echo $d[case_name]; ?>"></p>

<P><LABEL FOR "court">Court</label><select name="court" id="court" style="font-size:12pt">
<option value="">Please Select</option>
<?php
$get_courts = mysql_query("SELECT * FROM `cm_courts` ORDER BY `court` ASC");
WHILE ($result = mysql_fetch_array($get_courts))
{
$court = $result['court'];
$get_this_court = mysql_query("SELECT `court` FROM `cm` WHERE `id` = '$_GET[id]' LIMIT 1");
$y = mysql_fetch_array($get_this_court);
if ($court == $y[court])
{echo "<option value=\"$court\" selected=\"selected\">$court</option>";}
else
{echo "<option value=\"$court\">$court</option> ";}
}


?>
</select>
</p>


<p>
<table>
<tr><td><label for "ct_case_no">Court Case No.</label><input type="text" name="ct_case_no" id="ct_case_no" size="10" value="<?php echo $d[ct_case_no];   ?>"></td><td><label for "section" style="width:50px;">Section</label><input type="text" name="section" id="section" size="5" value = "<?php echo $d[section];   ?>"></td></tr></table></p>
<p><label for "judge">Judge</label><input type="text" name="judge" id="judge" size="30" value="<?php echo $d[judge];  ?>"</p>

<p><label for "pl_or_def">Client is:</label>
<?php
genSelect($d[pl_or_def],'pl_or_def','pl_or_def');


?>
</p>

<p><label for "adverse">Adverse Parties (seperate names by new lines)</label><textarea name="adverse" id="adverse" cols="35" rows="3">
<?php
$get_adverse = mysql_query("SELECT * FROM `cm_adverse_parties` WHERE `clinic_id` = '$d[clinic_id]'");
while ($w = mysql_fetch_array($get_adverse))
{echo $w[name] . "\n";
$adverse_id = $w[id];
}

?>


</textarea></p>
<input type="hidden" name="adverse_id" value="<?php echo $adverse_id;   ?>">
<input type="hidden" name="id" value="<?php echo $_GET[id] ?>">
<p><label for "professor">Professor:</label><select multiple="multiple" name="professor[]" id="professor">
<?php

//this gets the list of profs on this case
$case_profs = mysql_query("SELECT `professor` FROM `cm` WHERE `id` = '$_GET[id]' LIMIT 1");
$result2 = mysql_fetch_array($case_profs);
$arr = explode(",",$result2['professor']);

//this gets the list of all profs
$all_profs = mysql_query("SELECT * FROM `cm_users` WHERE `class` = 'prof' ORDER BY `last_name` ASC");
	while ($result  = mysql_fetch_array($all_profs))
		{
			if (in_array($result[username],$arr))
				{echo "<option value=\"$result[username]\" selected=selected>$result[last_name]</option>";
			
			
				}
				else
				{echo "<option value=\"$result[username]\">$result[last_name]</option>";
				}


	
	
}

?>
</select></p>

<p><label for "notes">Notes</label><textarea name="notes" id="notes" cols="41" rows="5"><?php echo $d[notes]?></textarea></p>

<p><center><input type="button" value="Save Changes" onClick="var ncval = newCaseVal();if (ncval == true){
new Ajax.Request('new_case_edit_tab.php',{parameters:Form.serialize('newCaseEditForm'),onComplete:function(){
	new Ajax.Updater('window1','client_data.php',{method:'get',parameters:{id:'<?php echo $_GET[id] ?> '}});
	$(notifications).update('Case Updated');$('notifications').style.display='block';Effect.Fade('notifications',{duration:4});}});return false;} else {return false};">
<input type="button" value="Cancel" onClick="Effect.Shrink('window1');return false;"></center></p>


</DIV></form>
