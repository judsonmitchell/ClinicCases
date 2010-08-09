<?php
session_start();
if (!$_SESSION)
{echo "You must be logged in to access this.";die;}

include 'db.php';
include './classes/format_dates_and_times.class.php';

function genSelect($target,$chosen_array,$select_name){

echo "<select name=\"$select_name\" id=\"$select_name\">";
/* arrays of the possible choices in all selects */
$status = array('active','inactive');
$class = array('prof','admin','student');
$profs = array();
$i = 0;
$get_profs = mysql_query("SELECT `username` FROM `cm_users` WHERE `class` = 'prof'");

while ($r = mysql_fetch_array($get_profs))
{

$profs[$i++] = $r[username];

}




switch($chosen_array){
case "status": 
$array = $status;break;
case "class":
$array = $class;break;

}

foreach ($array as $v)
{
if ($v == $target)
{echo "<option value = \"$v\" selected=\"selected\">$v</option>";}
else {echo "<option value=\"$v\">$v</option>";}
}
echo "</select>";
}


$id = $_GET['id'];
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

<fieldset><legend>Personal Data</legend>
<form id="user_data_edit" name="user_data_edit">
<table width="80%" border="0" align="center" class="user_detail_table">
<tr><td>First Name:</td><td id ="fn" class="tdata"><input type="text" value="$d[first_name]" id="first_name" name="first_name"></td><td>Last Name:</td><td id="ln" class="tdata"><input type="text" value="$d[last_name]" id="last_name" name="last_name"></td></tr>
<tr><td>Email</td><td id="email" class="tdata"><input type="text" value="$d[email]" id="email" name="email"></td><td></td><td></td></tr>
<tr><td>Mobile Phone</td><td id="mph" class="tdata"><input type="text" value="$d[mobile_phone]" id="mobile_phone" name="mobile_phone"></td><td>Office Phone</td><td id="oph" class="tdata"><input type="text" value="$d[office_phone]" id="office_phone" name="office_phone"></td></tr>
<tr><td>Home Phone</td><td id="hph" class="tdata"><input type="text" value="$d[home_phone]" id="home_phone" name="home_phone"></td><td></td><td></td></tr>
</table>

<table width="80%" border="0" align="center" class="user_detail_table">
<tr><td>Status</td><td class="tdata">
PAGE;
genSelect($d[status],'status','status');
ECHO <<<PAGE
</td><td>User Type</td><td class="tdata">
PAGE;
genSelect($d["class"],'class','class');
ECHO <<<PAGE
</td></tr>

PAGE;
if ($d["class"] == 'student')
{
echo "<tr><td>Assigned Professor(s):</td><td class='tdata'><select multiple=\"multiple\" name=\"professor[]\" id=\"professor\" style=\"height:75px;\">";

//this gets the list of profs this student is assigned to
$student_profs = mysql_query("SELECT `assigned_prof`,`username` FROM `cm_users` WHERE `username` = '$d[username]' LIMIT 1");
$result = mysql_fetch_array($student_profs);
$arr = explode(",",$result['assigned_prof']);

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

echo "</select></td><td></td><td></td></tr>";

}
echo "</table>";
?>
<center>
<input type="hidden" id="user_id" name="user_id" value = "<?php echo $d[id]; ?>">
<input type="button" value="Cancel" onClick="createTargets('window1','window1');sendDataGet('cm_users_view.php?id=<?php echo $d[id]; ?>');return false;">  <input type="button" value="Apply Changes" onClick="createTargets('window1','window1');sendDataPost('cm_users_view.php','user_data_edit');return false;">
</center></form>
</fieldset>
