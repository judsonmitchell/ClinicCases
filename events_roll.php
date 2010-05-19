<?php
session_start();

if (!$_SESSION)
{die('Error: You are not logged in.');}

include 'db.php';
include_once './classes/format_dates_and_times.class.php';
include_once 'get_client_name.php';


if($_POST[archive])
{
$archive = mysql_query("UPDATE `cm_events` SET `archived` = 'y' WHERE `id` = '$_POST[archive]'");
}



/* Students get following query: */
if ($_SESSION['class'] == 'student')
{
$get_events = mysql_query("SELECT * FROM `cm_events_responsibles` WHERE `username` = '$_SESSION[login]'");
while ($h = mysql_fetch_array($get_events))
{
$show_events = mysql_query("SELECT * FROM `cm_events`  WHERE `id` = '$h[event_id]' AND `archived` = 'n' ORDER BY `date_due` desc LIMIT 20");
while ($line = mysql_fetch_array($show_events, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($show_events,$i);
        $f[$field] = $col_value;
        $i++;
    }
if ($f[status] == 'Done')
{
ECHO <<<DONE

<div style="width:99.5%;height:40px;background:url(images/grade_gray_small.jpg) repeat-x;">
DONE;

}
ELSE
{
ECHO <<<NOTDONE
<div style="width:99%;height:40px;background:url(images/grade_small.jpg) repeat-x;">
NOTDONE;

}
echo <<<EVENT

<TABLE WIDTH="100%">
<TR title="Double Click to View Case" alt="Double Click to View Case" onmouseover="this.style.color='red';this.style.cursor='pointer'" onmouseout="this.style.color='black';" onDblClick="location.href='cm_cases.php?direct=$f[case_id]';"><TD WIDTH="10%"  valign='top'
EVENT;

if ($f[status] == 'Done')
{echo " style = 'color:#c7c7c7;'>";}
else
{echo ">";};

formatDateHuman($f[date_due]);
echo <<<EVENT
</td><td width="20%" valign="top"
EVENT;
if ($f[status] == 'Done')
{echo " style = 'color:#c7c7c7;'>";}
else
{echo ">";};
getClient($f[case_id]);
ECHO <<<EVENT
</td><td width="10%" valign='top'
EVENT;

if ($f[status] == 'Done')
{echo " style = 'color:#c7c7c7;'>";}
else
{echo ">";};
ECHO <<<EVENT

$f[status]</td><td width="50%" valign='top'

EVENT;
if ($f[status] == 'Done')
{echo " style = 'color:#c7c7c7;'>";}
else
{echo ">";};
ECHO <<<EVENT

<div style="height:35px;overflow:auto;">$f[task]</div></td><td width="7%" align="right"><div id="event_archive_$f[id]">
EVENT;

if ($f[archived] = 'n')
{echo "<a alt=\"Click to Archive\" title=\"Click to Archive\"  href=\"#\" onClick=\"new Ajax.Updater('events_roll','events_roll.php',{method:'post',postbody:'archive=$f[id]',onSuccess:function(){\$(notifications).update('Event Archived');\$('notifications').style.display='block';Effect.Fade('notifications',{duration:4});}});\">Archive</a>";
}
ECHO <<<EVENT
</td></tr></table></div>
EVENT;
}

}

if (mysql_num_rows($get_events)<1)
{echo "No events have been entered.";}

}
else
{
$show_events = mysql_query("SELECT * FROM `cm_events`  WHERE `prof` LIKE '%$_SESSION[login]%' AND `archived` = 'n' ORDER BY `date_due` DESC LIMIT 20");
while ($line = mysql_fetch_array($show_events, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($show_events,$i);
        $f[$field] = $col_value;
        $i++;
    }
if ($f[status] == 'Done')
{
ECHO <<<DONE

<div style="width:99.5%;height:40px;background:url(images/grade_gray_small.jpg) repeat-x;">
DONE;

}
ELSE
{
ECHO <<<NOTDONE
<div style="width:99%;height:40px;background:url(images/grade_small.jpg) repeat-x;">
NOTDONE;

}
echo <<<EVENT

<TABLE WIDTH="100%">
<TR title="Double Click to View Case" alt="Double Click to View Case" onmouseover="this.style.color='red';this.style.cursor='pointer'" onmouseout="this.style.color='black';" onDblClick="location.href='cm_cases.php?direct=$f[case_id]';"><TD WIDTH="10%"  valign='top'
EVENT;

if ($f[status] == 'Done')
{echo " style = 'color:#c7c7c7;'>";}
else
{echo ">";};

formatDateHuman($f[date_due]);
echo <<<EVENT
</td><td width="20%" valign="top"
EVENT;
if ($f[status] == 'Done')
{echo " style = 'color:#c7c7c7;'>";}
else
{echo ">";};
getClient($f[case_id]);
ECHO <<<EVENT
</td><td width="10%" valign='top'
EVENT;

if ($f[status] == 'Done')
{echo " style = 'color:#c7c7c7;'>";}
else
{echo ">";};
ECHO <<<EVENT

$f[status]</td><td width="50%" valign='top'

EVENT;
if ($f[status] == 'Done')
{echo " style = 'color:#c7c7c7;'>";}
else
{echo ">";};
ECHO <<<EVENT

<div style="height:35px;overflow:auto;">$f[task]</div></td><td width="7%" align="right"> <div id="event_archive_$f[id]">
EVENT;

if ($f[archived] = 'n')
{echo "<a alt=\"Click to Archive\" title=\"Click to Archive\" href=\"#\" onClick=\"new Ajax.Updater('events_roll','events_roll.php',{method:'post',postBody:'archive=$f[id]',onSuccess:function(){\$(notifications).update('Event Archived');\$('notifications').style.display='block';Effect.Fade('notifications',{duration:4});}});\">Archive</a>";
}
	ECHO <<<EVENT
</div></td></tr></table></div>
EVENT;
}
if (mysql_num_rows($show_events)<1)
{echo "No events have been entered.";}
}
?>
