<?php
session_start();
include 'db.php';
if($_POST)
{$id = $_POST[assoc_case];}
else
{$id = $_GET['id'];}

$filter = $_GET['filter'];


switch ($filter)
{
case $filter == "All":
$limiter = '';
break;
case $filter !== "All":
$limiter = "AND `type` = '$filter'";
break;
case !isset($filter):
$limiter = '';
break;
}

function genTypes()
{
$filter = $_GET['filter'];
$get_types = mysql_query("SELECT * FROM `cm_contacts_types`");
while ($r = mysql_fetch_array($get_types))
{
$type = $r['type'];
if ($type == $filter)
{echo "<option value=\"$type\" selected=\"selected\">$type</option>"; }
else
{echo "<option value=\"$type\">$type</option>";}
}
}




if ($_POST)
{
if ($_POST[edit])
{
$update = mysql_query("UPDATE `cm_contacts` SET `first_name` = '$_POST[first_name]',`last_name` = '$_POST[last_name]', `type` = '$_POST[type]', `address` = '$_POST[address]', `city` = '$_POST[city]', `state` = '$_POST[state]', `zip` = '$_POST[zip]', `phone1` = '$_POST[phone1]',`phone2` = '$_POST[phone2]', `fax` = '$_POST[fax]', `email` = '$_POST[email]', `notes` = '$_POST[notes]'   WHERE `id` = '$_POST[id]' LIMIT 1");


}
else
{
	
	
$query = mysql_query("INSERT INTO `cm_contacts` (id,first_name,last_name,type,address,city,state,zip,phone1,phone2,fax,email,notes,assoc_case) VALUES (NULL,'$_POST[first_name]','$_POST[last_name]','$_POST[type]','$_POST[address]','$_POST[city]','$_POST[state]','$_POST[zip]','$_POST[phone1]','$_POST[phone2]','$_POST[fax]','$_POST[email]','$_POST[notes]','$_POST[assoc_case]')");


}

}


if ($_POST[edit])
{   $get_contacts = mysql_query("SELECT * FROM `cm_contacts` WHERE `assoc_case` = '$_POST[assoc_case]' ORDER BY `last_name` ASC");  


 }

else
{$get_contacts = mysql_query("SELECT * FROM `cm_contacts` WHERE `assoc_case` = '$id' $limiter ORDER BY `last_name` ASC");}



echo <<<MENU

<div id="contacts_menu"><a href="#" class="singlenav" onClick="Effect.BlindDown('add_contact');$('type_picker').style.display='none';return false;">Add Contact</a> 

<div id="type_picker" style="position:absolute;right:10px;top:0px;width:300px;height:30px;">Show:
<select name="typer" id="typer" onChange="createTargets('case_activity','case_activity');sendDataGet('cm_case_contacts.php?id=$id&filter=' + document.getElementById('typer').value);">
<option value="All" selected=selected>All</option>
MENU;
genTypes();
ECHO <<<MENU

</select>
</div>
<div id="add_contact" style="position:absolute;z-index:4000;display:none;width:100%;height:350px;top:35px;left:0px;background-color:rgb(255, 255, 204);border-bottom: 3px ridge rgb(195, 217, 255);">


<form id="contactsForm" name="contactsForm">
<table><tr><td>
<label for "first_name">First Name</label></td><td><label for "last_name">Last Name</label></td><td><label for "type">Type</label></td></tr>

<tr><td><input  type="text"  id="first_name" name="first_name"></td><td>
<input  type="text" name="last_name" id="last_name"></td><td>
<select id="type" name="type">
<option value="">Please Select</option>
MENU;
genTypes();
ECHO <<<MENU
</select>
</td></tr></table>
<table>
<tr><td><label for "address">Address</label></td></tr>

<tr><td><input  type="text"  name="address" id="address" size="45"></td></tr></table>
<table>

<tr><td><label for "city">City</label></td><td><label for "state">State</label></td><td><label for "zip">Zip</label></td></tr>

<tr><td><input  type="text" name="city" id="city"></td><td><input  type="text" name="state" id="state"></td><td><input  type="text" name="zip" id="zip"></td></tr>

<tr><td><label for "phone1">Phone 1</label></td><Td><label for "phone2">Phone 2</label></td><td><label for "fax">Fax</label></td></tr>
<tr><td><input  type="text" name="phone1" id="phone1"></td><td>
<input  type="text" name="phone2" id="phone2"></td><td>
<input type="text" id="fax" name="fax"  name="fax"></td></tr>
</table>
<table>
<tr><td><label for "email">email</label></td><td><label for "notes">Notes</label></td><td></td><td></td></tr>

<tr><td valign="top">
<input  type="text" id="email" name="email"></td><td>
<textarea name="notes" id="Notes" cols="40" rows="4"></textarea>
<input type="hidden" value="$id" name="assoc_case">


</td><td valign="center" style="padding-left:10px;"><a href="#" onClick="
createTargets('case_activity','case_activity');sendDataPost('cm_case_contacts.php','contactsForm');return false;"><img src="images/check_yellow.png" border="0"></a></td><td valign="center" style="padding-left:10px;"><a href="#" onClick="Effect.BlindUp('add_contact');$('typer').value='';$('type_picker').style.display='block';return false;"><img src="images/cancel_small.png" border="0"></a>  </form></td></tr></table>

</div>
</div>

<span id="print_title" style="display:none;"><b>Case Contacts</b></span>

<div id = "contact_list" >
MENU;
if ($_POST[edit])
{
echo <<<NOTIFIER
<div id="notifier" style="width:100%;height:20px;color:red;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier');">Contact Edited</div>
NOTIFIER;



}



while ($line = mysql_fetch_array($get_contacts, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_contacts,$i);
        $d[$field] = $col_value;
        $i++;

    }
$rand = rand();
ECHO <<<CONTENTS
<div style="width:100%;height:100px;background:url(images/grade.jpg) repeat-x;">
<table width="100%">
<tr>
<td valign="top" width="33.3%">
<b>$d[first_name] $d[last_name]</b> <br>
<i>$d[type]</i><br>
$d[address]<br>
$d[city] $d[state] $d[zip]
</td>
<td valign="top" width="33.3%">
Phone 1: $d[phone1]<br>
Phone 2: $d[phone2]<br>
Fax: $d[fax]<br>
Email : <a href="mailto:$d[email]">$d[email]</a><br>
</td>
<td valign="top" width="33.3%">
<div style="height:20px;width:100%;text-align:right"><a href="#" alt="Edit this Contact" title="Edit this Contact" onClick="createTargets('add_contact','add_contact');sendDataGet('contact_edit.php?id=$d[id]&ieyousuck=$rand');Effect.BlindDown('add_contact');"><img src="images/user_edit.png" border="0"></a><a href="#" alt="Delete this Contact" title="Delete this Contact" onClick="var check = confirm('Are you sure you want to delete this contact?');if (check == true){createTargets('contact_list','contact_list');sendDataGet('contact_delete.php?id=$d[id]&case_id=$id');}else {return false}"><img src="images/user_delete.png" border="0" style="margin-left:10px;"></a></div>
<div style="overflow:auto;height:50%;width:100%">Notes: <i>$d[notes]</i></div>
</td>
<tr>
</table>
</div>
CONTENTS;
}
if (mysql_num_rows($get_contacts)<1)
{echo "No contacts associated with this case.";
}
echo "</div>";

?>
