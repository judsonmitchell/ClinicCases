<?php
session_start();
include 'db.php';
$id = $_GET['id'];

function genSelect($target,$select_name){
echo "<select name=\"$select_name\" id=\"$select_name\">";
$get_options = mysql_query("SELECT `type` FROM `cm_contacts_types`");
while ($r = mysql_fetch_array($get_options))
{
$option = $r['type'];
if ($option == $target)
{echo "<option value = \"$option\" selected=\"selected\">$option</option>";}
else {echo "<option value=\"$option\">$option</option>";}
}
echo "</select>";
}


$get_contact_data = mysql_query("SELECT * FROM `cm_contacts` WHERE `id` = '$id' LIMIT 1");
while ($line = mysql_fetch_array($get_contact_data, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_contact_data,$i);
        $d[$field] = $col_value;
        $i++;
    }
  }
echo <<<EDITCONTACTS

<form id="contactsForm" name="contactsForm">
<table><tr><td>
<label for "first_name">First Name</label></td><td><label for "last_name">Last Name</label></td><td><label for "type">Type</label></td></tr>

<tr><td><input  type="text"  id="first_name" name="first_name" value="$d[first_name]"></td><td>
<input  type="text" name="last_name" id="last_name" value="$d[last_name]"></td><td>

EDITCONTACTS;
genSelect($d[type],'type');
ECHO <<<EDITCONTACTS

</td></tr></table>
<table>
<tr><td><label for "address">Address</label></td></tr>

<tr><td><input  type="text"  name="address" id="address" size="45" value="$d[address]"></td></tr></table>
<table>

<tr><td><label for "city">City</label></td><td><label for "state">State</label></td><td><label for "zip">Zip</label></td></tr>

<tr><td><input  type="text" name="city" id="city" value="$d[city]"></td><td><input  type="text" name="state" id="state" value="$d[state]"></td><td><input  type="text" name="zip" id="zip" value="$d[zip]"></td></tr>

<tr><td><label for "phone1">Phone 1</label></td><Td><label for "phone2">Phone 2</label></td><td><label for "fax">Fax</label></td></tr>
<tr><td><input  type="text" name="phone1" id="phone1" value="$d[phone1]"></td><td>
<input  type="text" name="phone2" id="phone2" value="$d[phone2]"></td><td>
<input type="text" id="fax" name="fax"  name="fax" value="$d[fax]"></td></tr>
</table>
<table>
<tr><td><label for "email">email</label></td><td><label for "notes">Notes</label></td><td></td><td></td></tr>

<tr><td valign="top">
<input  type="text" id="email" name="email" value="$d[email]"></td><td>
<textarea name="notes" id="Notes" cols="40" rows="4">$d[notes]</textarea></td><td valign="center" style="padding-left:10px;"><a href="#" onClick="createTargets('case_activity','case_activity');sendDataPost('cm_case_contacts.php?id=$id','contactsForm');return false;"><img src="images/check_yellow.png" border="0"></a></td><td valign="center" style="padding-left:10px;"><a href="#" onClick="Effect.BlindUp('add_contact');return false;"><img src="images/cancel_small.png" border="0"></a>  </td></tr></table>
<input type="hidden" value="$d[assoc_case]" name="assoc_case">
<input type="hidden" value="$d[id]" name="id">
<input type="hidden" name="edit" value="yes">

</form>




EDITCONTACTS;



?>
