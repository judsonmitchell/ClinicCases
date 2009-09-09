<?php
session_start();
include 'db.php';
$id = $_GET['id'];
$case_id = $_GET['case_id'];
$del_query = mysql_query("DELETE FROM `cm_contacts` WHERE `id` = '$id' LIMIT 1");


echo <<<NOTIFIER
<div id="notifier" style="width:100%;height:20px;color:red;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier');">Contact Deleted</div>
NOTIFIER;
$get_contacts = mysql_query("SELECT * FROM `cm_contacts` WHERE `assoc_case` = '$case_id' $limiter ORDER BY `last_name` ASC");

while ($line = mysql_fetch_array($get_contacts, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_contacts,$i);
        $d[$field] = $col_value;
        $i++;

    }
    
ECHO <<<CONTENTS

<div style="width:99%;height:100px;background:url(images/grade.jpg) repeat-x;">
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
<div style="height:20px;width:100%;text-align:right"><a href="#" alt="Edit this Contact" title="Edit this Contact"><img src="images/user_edit.png" border="0"></a><a href="#" alt="Delete this Contact" title="Delete this Contact" onClick="var check = confirm('Are you sure you want to delete this contact?');if (check == true){createTargets('case_activity','case_activity');sendDataGet('contact_delete.php?id=$d[id]');}else {return false}"><img src="images/user_delete.png" border="0" style="margin-left:10px;"></a></div>
<div style="overflow:auto;height:50%;width:100%">Notes: <i>$d[notes]</i></div>
</td>
<tr>
</table>
</div>
CONTENTS;
}












?>
