<?php
session_start();


if (!$_SESSION)
{header('Location: index.php?login_error=3');}
else
{
	
include 'db.php';
include 'classes/get_faces.php';

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
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

<script src="./javascripts/print.js" type="text/javascript"></script>
<script src="./javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="./javascripts/validations.js" type="text/javascript"></script>
<script src="./javascripts/spread.js" type="text/javascript"></script>
<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
<script src="./javascripts/FormProtector.js" type="text/javascript"></script>


<script>
function goLookup()
{
new Ajax.Autocompleter("to_full", "autocomplete", "messages_to_lookup.php", {tokens: ',',afterUpdateElement: updateFields});

function updateFields(element, selectedElement) {
  var oResult = selectedElement.childNodes.item(1);
  $("to").value = $("to").value + oResult.childNodes.item(1).innerHTML + ',';
  }
}

function goLookup2()
{
new Ajax.Autocompleter("cc1_full", "autocomplete2", "messages_to_lookup.php", {tokens: ',',afterUpdateElement: updateFields});

function updateFields(element, selectedElement) {
  var oResult = selectedElement.childNodes.item(1);
  $("cc1").value = $("cc1").value + oResult.childNodes.item(1).innerHTML + ',';
  }

}


new Ajax.PeriodicalUpdater('session_info', 'session_keep_alive.php', {
    method: 'post',parameters:{sid:'<?php echo $_COOKIE[PHPSESSID]; ?>'},
    frequency: 60
  });

new Ajax.PeriodicalUpdater('msg_notifier', 'updater.php?type=messages', {
    method: 'get',
    frequency: 60
  });

inboxUpdater = new Ajax.PeriodicalUpdater('messages_container', 'message_roll.php', {evalScripts:true,
    method: 'get',
    frequency: 60
  });


Event.observe(window, 'load', function() {

$$("a.nobubble").invoke("observe", "click", function(e) {

	Event.stop(e);
})})


Event.observe(window, 'load', function() {

$$("tr").invoke("observe", "click", function(e) {

	Event.stop(e);
})})



</script>

</head>
<body>
<div id="notifications"></div>
<div id = "bug" style="display:none;">
</div>

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
   <li><a href="cm_board.php"><span id="tab4">Board</span></a></li>

  <li><a href="cm_utilities.php"><span id="tab5">Utilities</span></a></li>

    <li><a href="cm_preferences.php"><span id="tab6">Prefs</span></a></li>

  </ul>

</div>
<?php include 'cm_menus.php';?>
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

 <table style="width:100%"><tr>
 <?php $thumb_target = get_thumb($_SESSION['login']); ?>
 <td width="35px"><img src="<?php echo $thumb_target; ?>"  border="0"></td><td><span class="name"><?php
 echo $ph[first_name] . ' ' . $ph[last_name]; ?></span> <strong>at a Glance</strong></td><td><span id="msg_notifier"><b><?php echo $n; ?></b></span> new messages</td><td>Last Login: <?php if (mysql_num_rows($last_login) < 1)
	{echo "Never.  Welcome!";}
	else{formatDate($log[timestamp]);} ?>  </td></tr>
 </table>

</div>

<div id="messaging">

<div id="messaging_menu"><h5 style="float:left;color:white;">Messages
</h5> <div id="notifier" style="position:absolute;top:0;left:43%;width:120px;color:red;"></div><div style="float:right;margin-right:10px;"><table style="width:400px;margin:0px"><tr><td>
<span id="t1a" style="display:block;">
<a href="#" onclick="spread('messaging','upcoming_events','recent_activity');togg('t1b','t1a');return false;">Maximize</a>
</span>

<span id="t1b" style="display:none;">
<a href="#" onclick="unspread('messaging','upcoming_events','recent_activity');togg('t1a','t1b');return false;">Minimize</a>
</span> </td><td><a id="switch3" href="#" onClick="

var check = confirm('Do you want to send all messages to the archive?');

if (check == true){new Ajax.Updater('messages_container','message_roll.php',{evalScripts:true,method:'post',parameters:{archive_all:'yes'}}); updater('updater.php?type=messages','msg_notifier');return false;}

else {return false;}">Archive All</a></td>
<td>View:</td>
<td><select name="msg_rd" id="msg_rd" style="font-size:12pt;display:inline;" onChange="
var stopStart;
if ($('msg_rd').value == '')
{stopStart = inboxUpdater.start();}
else
{stopStart = inboxUpdater.stop();}

new Ajax.Updater('messages_container','message_roll.php',{evalScripts:true,method:'post',postBody:$('msg_rd').value,onLoading:$('messages_container').innerHTML = '<img src=images/wait.gif border=0>',onSuccess:function(){stopStart;}});">
<option value="" selected="selected">Inbox</option>
<option value = "starred=y">Starred</option>
<option value="archive=y">Archive</option>
<option value="sent=y">Sent</option>
</select>
</td><td><a href="#" class="singlenav" onClick="new Ajax.Updater('messaging_window','message_new.php',{method:'get',onComplete:function(){Effect.Grow('messaging_window');(function(){new Draggable('messaging_window',{handle:'bar'})}).defer();}});return false;" alt="New Message" title="New Message"><img src="images/new_msg_blue.png" border="0"></a></td></tr></table></div></div>
<div id="messages_container" style="width:100%;height:78%;overflow-y:auto;overflow-x:hidden">
<?php
include 'message_roll.php';
?>


</div>


<div id="recent_activity" style="overflow-x:hidden;overflow-y:hidden;">
<div id="recent_activity_menu">


<h5 style="float:left;color:white;">
<?php
if ($_SESSION['class']=='student')
{
echo "Your ";
}
?>
Recent Activity</h5><div style="float:right;margin-right:10px;"><table style="width:240px;margin:0px"><tr><td width=50%>

<span id="t2a" style="display:block;">
<a href="#" onclick="spread('recent_activity','messaging','upcoming_events');togg('t2b','t2a');return false;">Maximize</a>
</span>

<span id="t2b" style="display:none;">
<a href="#" onclick="unspread('recent_activity','messaging','upcoming_events');togg('t2a','t2b');return false;">Minimize</a>
</span>

</td><td align=right><a alt="Get RSS feed of latest case activity" title="Get RSS feed of latest case activity" onMouseOver="this.style.cursor='hand'" onClick="location.href='<?php echo $CC_base_url; ?>rss_create.php?pkey=<?php echo $ph[private_key]; ?>'"><img src="images/feed-icon-14x14.png" border="0"></a></td></tr></table></div></div>
<TABLE WIDTH="100%">
<tr><thead style="background-color:gray;font-size:8pt;"><td width="15%">Date</td><td width="15%">Student</td><td width="20%">Case Name</td><td width="10%">Time Spent</td><td width="40%">Activity</td></tr></thead></table>

<div id="activity_roll" style="width:100%;height:70%;overflow-y:auto;overflow-x:hidden">
<?php
if ($_SESSION['class'] == 'student')
{
$show_notes = mysql_query("SELECT  * FROM `cm_case_notes` , `cm_cases_students` WHERE cm_case_notes.username = '$_SESSION[login]' AND cm_cases_students.username = '$_SESSION[login]' AND cm_case_notes.case_id = cm_cases_students.case_id  ORDER BY `date` DESC LIMIT 20");
}
if ($_SESSION['class'] == 'prof')
{
$show_notes = mysql_query("SELECT * FROM `cm_case_notes` WHERE `prof` LIKE '%$_SESSION[login]%' ORDER BY `date` DESC LIMIT 20");

}


while ($line = mysql_fetch_array($show_notes, MYSQL_BOTH)) {


echo <<<ACTIVITY

<div style="width:100%;height:40px;background:url(images/grade_small.jpg) repeat-x;">
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
</td><td WIDTH="38%"  valign="top"><div style="height:35px;overflow:auto;">$line[description]</div></td></tr></table>
</div>

ACTIVITY;

}
if (mysql_num_rows($show_notes)<1)
{echo "There has been no activity.";}

?>
</div>
</div>

<div id="upcoming_events" style="overflow-x:hidden;overflow-y:hidden;">
<div id="upcoming_events_menu">
<h5 style="background-color:rgb(195, 217, 255);color:white;float:left; ">Upcoming Events</h5><div style="float:right;margin-right:10px;"><table style="width:240px;margin:0px;"><tr><td width=50%>
<span id="t3a" style="display:block;">
<a href="#" onclick="spread('upcoming_events','messaging','recent_activity');togg('t3b','t3a');return false;">Maximize</a>
</span>

<span id="t3b" style="display:none;">
<a href="#" onclick="unspread('upcoming_events','messaging','recent_activity');togg('t3a','t3b');return false;">Minimize</a>
</span>
</td><td align="right"><a  alt="Your Ical Events Feed" title="Your Ical Events Feed" onMouseOver="this.style.cursor='hand'"onClick = "location.href='<?php echo $CC_base_url; ?>feeds_ical_generate.php?sid=<?php echo $ph[private_key]; ?>'"><img src="images/ical.gif" border="0"></a></td></tr></table></div></div>

<TABLE WIDTH="100%">
<tr><thead style="background-color:gray;font-size:8pt;"><td width="10%">Date Due</td><td width="20%">Case Name</td><td width="10%">Status</td><td width="50%">To Be Done</td><td width="10%"></td></tr></thead></table>
<div id="events_roll" style="width:100%;height:70%;overflow-y:auto;overflow-x:hidden">
<?php include 'events_roll.php' ?>
</div>
</div>




<div id="messaging_window" style="display:none;">

</div>
<script>
</script>

</div>


</body>
</html>
