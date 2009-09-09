<?php 
session_start();
include 'db.php';
if (!$_SESSION)
{header('Location: index.php?login_error=3');}
else
{
/* check if this is first visit to this page in session. If it is, log it.*/
$check_visit = mysql_query("SELECT * FROM `cm_logs` WHERE `session_id` = '$_COOKIE[PHPSESSID]'");
if (mysql_num_rows($check_visit)<1)
{
$ip = $_SERVER['REMOTE_ADDR'];
if (isset($_COOKIE[PHPSESSID]))
{$sid = $_COOKIE[PHPSESSID];}
else
{$sid = "cookie problem";}

$log_this = mysql_query("INSERT INTO `cm_logs` (`id`,`username`,`timestamp`,`ip`,`session_id`) VALUES (NULL,'$_SESSION[login]',NULL,'$ip','$sid')");

}
}


if (!$_SESSION)
{header('Location: index.php');}


include 'get_client_name.php';
include './classes/format_dates_and_times.class.php';



 ?>
<html>
<head>
<title>At A Glance - ClinicCases.com</title>
<link rel="stylesheet" href="cm.css" type="text/css">
<link rel="stylesheet"  href="cm_tabs.css" type="text/css">
<script src="./javascripts/print.js" type="text/javascript"></script>
<script src="./javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="./javascripts/validations.js" type="text/javascript"></script>
<script src="./javascripts/spread.js" type="text/javascript"></script>
<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>

<script>
function goLookup()
{
new Ajax.Autocompleter("to_full", "autocomplete", "messages_to_lookup.php", {afterUpdateElement: updateFields});
          
function updateFields(element, selectedElement) {
  var oResult = selectedElement.childNodes.item(1);
  $("to").value = oResult.childNodes.item(1).innerHTML;
  }
}

function goLookup2()
{
new Ajax.Autocompleter("cc1_full", "autocomplete2", "messages_to_lookup.php", {afterUpdateElement: updateFields});
          
function updateFields(element, selectedElement) {
  var oResult = selectedElement.childNodes.item(1);
  $("cc1").value = oResult.childNodes.item(1).innerHTML;
  }

}


new Ajax.PeriodicalUpdater('session_info', 'session_keep_alive.php?sid=<?php echo $_COOKIE[PHPSESSID]; ?>', {
    method: 'get',
    frequency: 300
  });

new Ajax.PeriodicalUpdater('msg_notifier', 'updater.php?type=messages', {
    method: 'get',
    frequency: 60
  });

new Ajax.PeriodicalUpdater('message_roll', 'message_roll.php', {
    method: 'get',
    frequency: 60
  });



</script>

</head>
<body>
<div id = "bug" style="display:none;">
</div>
<?php include 'cm_menus.php';?>
<div id = "nav_container">
<div id="header">

  <ul>
    <li id = "current"><a href="cm_home.php"><span id="tab1">At A Glance</span></a></li>
<?php 
if ($_SESSION['pref_case'] == 'on')
{ 
echo "<li><a href=\"cm_cases.php\"><span id=\"tab2\">Cases</span></a></li>";
}

if ($_SESSION['pref_journal'] == 'on')
{ 
echo "<li><a href=\"cm_journals.php\"><span id=\"tab2\">Journals</span></a></li>";
}


if ($_SESSION['class'] == 'prof')
{echo "<li><a href=\"cm_students.php\"><span id=\"tab3\">Students</span></a></li>";}
?>
  <li><a href="cm_utilities.php"><span id="tab5">Utilities</span></a></li>

    <li><a href="cm_preferences.php"><span id="tab6">Preferences</span></a></li>

  </ul>

</div>

</div>
<div id="content" style="background-color:rgb(255,255,204);">

 <div id="id_strip">
 
 <?php 
 $query = mysql_query("SELECT * from cm_users where username = '$_SESSION[login]' LIMIT 1");
 $ph = mysql_fetch_array($query);
 
 $msg_count = mysql_query("SELECT * FROM `cm_messages`WHERE `to` = '$_SESSION[login]' AND `read` != 'yes' AND `archive` != 'yes' ");
 $n = mysql_num_rows($msg_count);
 
 $last_login = mysql_query("SELECT *  FROM `cm_logs` WHERE `username` LIKE CONVERT(_utf8 '$_SESSION[login]' USING latin1) COLLATE latin1_swedish_ci ORDER BY `timestamp` desc limit 1,1");
 $log = mysql_fetch_array($last_login);
 ?>
 
 <table width="800px"><tr>
 <td width="35px"><img src="<?php echo $ph[picture_url]; ?>" width="32" height="32" border="0"></td><td><span class="name"><?php
 echo $ph[first_name] . ' ' . $ph[last_name]; ?></span> <strong>at a Glance</strong></td><td><span id="msg_notifier"><b><?php echo $n; ?></b></span> new messages</td><td>Last Login: <?php if (mysql_num_rows($last_login) < 1)
	{echo "Never.  Welcome!";}
	else{formatDate($log[timestamp]);} ?>  </td></tr>
 </table>
 
</div>

<div id="messaging">

<div id="messaging_menu"><h5 style="float:left;color:white;">Messages    
</h5> <div style="float:right;margin-right:10px;"><table style="width:300px;margin:0px"><tr><td>
<span id="t1a" style="display:block;">
<a href="#" onclick="spread('messaging','upcoming_events','recent_activity');togg('t1b','t1a');return false;">Maximize</a> 
</span>

<span id="t1b" style="display:none;">
<a href="#" onclick="unspread('messaging','upcoming_events','recent_activity');togg('t1a','t1b');return false;">Minimize</a> 
</span> </td><td><a id="switch3" href="#" onClick="var check = confirm('Do you want to send all messages to the archive?');if (check == true){createTargets('message_roll','message_roll');sendDataGet('message_archive_all.php');updater('updater.php?type=messages','msg_notifier');return false;} else {return false;}">Archive All</a></td>

<td><a id="switch1" href="#" onClick="this.style.display = 'none';var tg = document.getElementById('switch2');var tg3 = document.getElementById('switch3');tg.style.display='block';tg3.style.display='none';createTargets('message_roll','message_roll');sendDataGet('message_roll.php?archive=y');return false;">View Archive</a>
<a href="#" id = "switch2" style='display:none;font-weight:bold;color:red;' onClick="this.style.display='none';var tg2 = document.getElementById('switch1');var tg3 = document.getElementById('switch3');tg2.style.display='block';tg3.style.display='block';createTargets('message_roll','message_roll');sendDataGet('message_roll.php');return false;">Leave Archive</a></span>
</td><td><a href="#" class="singlenav" onClick="Effect.Grow('messaging_window');createTargets('messaging_window','messaging_window');sendDataGet('message_new.php');return false;" alt="New Message" title="New Message"><img src="images/new_msg_blue.png" border="0"></a></td></tr></table></div></div>
<div id="message_roll">
<?php
include 'message_roll.php';
?>

</div>
</div>


<div id="recent_activity">
<div id="recent_activity_menu">
<h5 style="float:left;color:white;">
<?php
if ($_SESSION['class']=='student')
{
echo "Your ";
}
?>
Recent Activity</h5><div style="float:right;margin-right:10px;"><table style="width:240px;margin:0px"><tr><td>

<span id="t2a" style="display:block;">
<a href="#" onclick="spread('recent_activity','messaging','upcoming_events');togg('t2b','t2a');return false;">Maximize</a> 
</span>

<span id="t2b" style="display:none;">
<a href="#" onclick="unspread('recent_activity','messaging','upcoming_events');togg('t2a','t2b');return false;">Minimize</a> 
</span>

</td></tr></table></div></div>
<TABLE WIDTH="99.5%">
<tr><thead style="background-color:gray;font-size:8pt;"><td width="15%">Date</td><td width="15%">Student</td><td width="20%">Case Name</td><td width="10%">Time Spent</td><td width="40%">Activity</td></tr></thead></table>
<?php
if ($_SESSION['class'] == 'student')
{
$show_notes = mysql_query("SELECT  * FROM `cm_case_notes` , `cm_cases_students` WHERE cm_case_notes.username = '$_SESSION[login]' AND cm_cases_students.username = '$_SESSION[login]' AND cm_case_notes.case_id = cm_cases_students.case_id  ORDER BY `date` DESC LIMIT 10");
}
if ($_SESSION['class'] == 'prof')
{
$show_notes = mysql_query("SELECT * FROM `cm_case_notes` WHERE `prof` = '$_SESSION[login]' ORDER BY `date` DESC LIMIT 10");

}


while ($line = mysql_fetch_array($show_notes, MYSQL_BOTH)) {
    
  
echo <<<ACTIVITY

<div style="width:99.7%;height:40px;background:url(images/grade_small.jpg) repeat-x;">
<TABLE WIDTH="100%">
<TR title="Click to View Case" alt="Click to View Case" onmouseover="this.style.color='red';this.style.cursor='pointer'" onmouseout="this.style.color='black';" onClick="location.href='cm_cases.php?direct=$line[case_id]'";><TD WIDTH="15%" STYLE="PADDING-RIGHT:3.5%;" valign="top">
ACTIVITY;

formatDateHuman($line[date]);

echo "</td><td WIDTH='15%'  valign='top'>";

getName($line["username"]);

echo <<<ACTIVITY
</td><td width="20%" valign="top">

ACTIVITY;
if ($line["case_id"] == 'NC')
{echo "Non-Case";}
else
{
getClient($line["case_id"]);
}

ECHO <<<ACTIVITY
</td><td width="10%" valign="top">
ACTIVITY;
list($new_time,$the_unit) = formatTime($line["time"]);
echo "$new_time" . " " . "$the_unit";

ECHO <<<ACTIVITY
</td><td WIDTH="40%"  valign="top"><div style="height:35px;overflow:auto;">$line[description]</div></td></tr></table>
</div>

ACTIVITY;

}
if (mysql_num_rows($show_notes)<1)
{echo "There has been no activity.";}

?>
</div>

<div id="upcoming_events">
<div id="upcoming_events_menu">
<h5 style="background-color:rgb(195, 217, 255);color:white;float:left; ">Upcoming Events</h5><div style="float:right;margin-right:10px;"><table style="width:240px;margin:0px"><tr><td>
<span id="t3a" style="display:block;">
<a href="#" onclick="spread('upcoming_events','messaging','recent_activity');togg('t3b','t3a');return false;">Maximize</a> 
</span>

<span id="t3b" style="display:none;">
<a href="#" onclick="unspread('upcoming_events','messaging','recent_activity');togg('t3a','t3b');return false;">Minimize</a> 
</span>
</td></tr></table></div></div>
<TABLE WIDTH="99.5%">
<tr><thead style="background-color:gray;font-size:8pt;"><td width="10%">Date Due</td><td width="20%">Case Name</td><td width="10%">Status</td><td width="50%">To Be Done</td><td width="10%"></td></tr></thead></table>
<?php
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

<div style="height:35px;overflow:auto;">$f[task]</div></td><td width="10%" align="right"><div id="event_archive_$f[id]">
EVENT;

if ($f[archived] = 'n')
{echo "<a alt=\"Click to Archive\" title=\"Click to Archive\"  href=\"#\" onClick=\"createTargets('event_archive_$f[id]','event_archive_$f[id]');sendDataGet('event_archive.php?id=$f[id]');\">Archive</a>";
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
$show_events = mysql_query("SELECT * FROM `cm_events`  WHERE `prof` = '$_SESSION[login]' AND `archived` = 'n' ORDER BY `date_due` DESC LIMIT 20");
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

<div style="height:35px;overflow:auto;">$f[task]</div></td><td width="10%" align="right"> <div id="event_archive_$f[id]">
EVENT;

if ($f[archived] = 'n')
{echo "<a alt=\"Click to Archive\" title=\"Click to Archive\" href=\"#\" onClick=\"createTargets('event_archive_$f[id]','event_archive_$f[id]');sendDataGet('event_archive.php?id=$f[id]');return false;\">Archive</a>";
}
	ECHO <<<EVENT
</div></td></tr></table></div>
EVENT;
}
if (mysql_num_rows($show_events)<1)
{echo "No events have been entered.";}
}
?>
</div>




<div id="messaging_window" style="display:none;">

</div>


</div>


</body>
</html>
