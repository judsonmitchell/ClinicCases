<?php
session_start();
include 'db.php';
include 'get_name.php';
include_once './classes/format_dates_and_times.class.php';

/* menu if called from cm_cases_single */
if (isset($_GET[case_id]))
{

echo<<<MENU
<div id="contacts_menu"><a href="#" class="singlenav" onClick="Effect.Grow('messaging_window');createTargets('messaging_window','messaging_window');sendDataGet('message_new.php?case_id=$_GET[case_id]');return false;" title="New Message" alt="New Message"><img src="images/new_msg.png" border="0"></a> </div>


MENU;
}
if ($_GET[notify] == '1')
{
echo <<<NOTIFY
 <div id="notifier2" style="width:100%;height:20px;color:blue;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier2');">Message Sent.</div>
NOTIFY;
}

if ($_GET[notify] == '2')
{
echo <<<NOTIFY
 <div id="notifier2" style="width:100%;height:20px;color:blue;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier2');">Message Archived.</div>
NOTIFY;
}

if ($_GET[notify] == '3')
{
echo <<<NOTIFY
 <div id="notifier2" style="width:100%;height:20px;color:blue;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier2');">Message Returned to Inbox.</div>
NOTIFY;
}

if ($_GET[notify] == '4')
{
echo <<<NOTIFY
 <div id="notifier2" style="width:100%;height:20px;color:blue;font-weight:bold;text-align:center;"><img src="images/onload_tricker.gif" border="0" onLoad="Effect.Fade('notifier2');">All Messages Archived.</div>
NOTIFY;
}
/* here is the situation when this script is called from inside cm_cases_single */




if (isset($_GET[case_id]))
{
$get_messages = mysql_query("SELECT * FROM `cm_messages` WHERE `assoc_case` = '$_GET[case_id]' ORDER BY `time_sent` DESC");
if (mysql_num_rows($get_messages)<1)
{echo "There are no messages in this case. ";}  

}
elseif (isset($_GET[archive]))
{
$get_messages = mysql_query("SELECT * FROM `cm_messages` WHERE `to` = '$_SESSION[login]' AND `archive` = 'yes' ORDER BY `time_sent` DESC");


}
/* Script is being called from At A Glance */
else
{
$get_messages = mysql_query("SELECT * FROM `cm_messages` WHERE `to` = '$_SESSION[login]' AND `archive` = '' ORDER BY `time_sent` DESC");
if (mysql_num_rows($get_messages)<1)
{echo "<br>You have no messages.";} 
}   
while ($line = mysql_fetch_array($get_messages, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_messages,$i);
        $d[$field] = $col_value;
        $i++;

    }
$bodyfix = stripslashes($d[body]);
$snip = substr($bodyfix,0,35);
$snippet = str_replace("<br />","",$snip);

if ($d[read] == 'yes')
{
ECHO <<<READ
<div id ="loc_$d[id]" style="width:100%;height:40px;background:url(images/grade_gray_small.jpg) repeat-x;">
READ;
}
else
{
echo <<<NOTREAD
<div id ="loc_$d[id]" style="width:100%;height:40px;background:url(images/grade_small.jpg) repeat-x;">
NOTREAD;
}


echo <<<MESSAGE
<table width="99%" id="table_$d[id]">
<tr onmouseover="this.style.color='red';this.style.cursor='pointer';" onmouseout="this.style.color='black';" onclick="Effect.Grow('messaging_window');createTargets('messaging_window','messaging_window');sendDataGet('message_view.php?id=$d[id]
MESSAGE;
/* When called from cm_cases_single: */
if (isset($_GET[case_id]))
{
echo "&interior=y&case_id=$_GET[case_id]";
}
echo <<<MESSAGE
');var row = document.getElementById('loc_$d[id]');var newBg = 'url(images/grade_gray_small.jpg)';row.style.backgroundImage = newBg;"><td width="20%"
MESSAGE;
if ($d[read] == 'yes')
{echo "style = 'color:#c7c7c7;'>";}
else
{echo ">";}
getName($d[from]);
ECHO <<<MESSAGE
</td><td
MESSAGE;
if (isset($_GET[case_id]))
{echo " width = \"30%\"";}
else
{echo " width=\"20%\" ";}



if ($d[read] == 'yes')
{echo " style = 'color:#c7c7c7;'>";}
else
{echo ">";}
formatDate($d[time_sent]);
ECHO <<<MESSAGE
</td><td 
MESSAGE;
if (isset($_GET[case_id]))
{echo " width = \"50%\"";}
else
{echo "width=\"60%\"";}
if ($d[read] == 'yes')
{echo "style = 'color:#c7c7c7;'>";}
else
{echo ">";}
ECHO <<<MESSAGE
$d[subject] - <span style="color:#c7c7c7;">$snippet ...</span> </td><td>
MESSAGE;
if ($d[archive] != 'yes' & !isset($_GET[case_id]))
echo "<a href=\"#\" onClick=\"createTargets('message_roll','message_roll');sendDataGet('message_archive.php?id=$d[id]');\">Archive</a>";
echo <<<MESSAGE

<td></tr>
</table>
<span id="print_title" style="display:none;"><b>Message List</b></span>

</div>
MESSAGE;

}
?>
