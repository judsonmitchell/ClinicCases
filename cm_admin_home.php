<?php
session_start();
include 'db.php';

if (!$_SESSION)
{header('Location: index.php');}
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
include './classes/format_dates_and_times.class.php';
 ?>
<html>
<head>
<title>At A Glance - ClinicCases.com</title>
<link rel="stylesheet" href="cm.css" type="text/css">
<link rel="stylesheet"  href="cm_tabs.css" type="text/css">
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
<script src="./javascripts/validations.js" type="text/javascript"></script>
<script src="./javascripts/print.js" type="text/javascript"></script>
<script src="./javascripts/spread.js" type="text/javascript"></script>
<script src="javascripts/ajax_scripts.js" type="text/javascript"></script>
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
</script>
<script>

new Ajax.PeriodicalUpdater('session_info', 'session_keep_alive.php', {
    method: 'post',parameters:{sid:'<?php echo $_COOKIE[PHPSESSID]; ?>'},
    frequency: 300
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


</script>
</head>
<body>
<div id="notifications"></div>
<div id = "bug" style="display:none;">
</div>

<div id = "nav_container">
<div id="header">

  <ul>
    <li id = "current"><a href="cm_admin_home.php"><span id="tab1">At a Glance</span></a></li>

    <li><a href="cm_admin_cases.php"><span id="tab2">Cases</span></a></li>
    <li><a href="cm_admin_students.php"><span id="tab3">Students</span></a></li>
        <li><a href="cm_admin_users.php"><span id="tab4">Users</span></a></li>
	   	      <li><a href="cm_board.php"><span id="tab5">Board</span></a></li>

   <li><a href="cm_admin_preferences.php"><span id="tab5">Prefs</span></a></li>


  </ul>

</div>
<?php include 'cm_menus.php';?>
</div>
<div id="content" style="background-color:rgb(255,255,204);" >
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
 <td width="35px"><img src="<?php echo $ph[picture_url]; ?>" width="32" height="32" border="0"></td><td><span class="name"><?php echo $ph[first_name] . ' ' . $ph[last_name]; ?></span> <strong>at a Glance</strong></td><td><span id="msg_notifier"><b><?php echo $n; ?></b></span>  new messages</td><td>Last Login:  <?php if (mysql_num_rows($last_login) < 1)
	{echo "Never.  Welcome!";}
	else{formatDate($log[timestamp]);} ?>   </td></tr>
 </table>

</div>









<div id="messaging"  style="height:50%">

<div id="messaging_menu"><h5 style="float:left;color:white;">Messages
</h5> <div style="float:right;margin-right:10px;">
<table style="width:400px;margin:0px"><tr><td>
<span id="t1a" style="display:block;">
<a href="#" onclick="spread2('messaging','new_users');togg('t1b','t1a');return false;">Maximize</a>
</span>

<span id="t1b" style="display:none;">
<a href="#" onclick="unspread2('messaging','new_users');togg('t1a','t1b');return false;">Minimize</a>
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
</td>

<td><a href="#" class="singlenav" onClick="new Ajax.Updater('messaging_window','message_new.php',{method:'get',onComplete:function(){Effect.Grow('messaging_window');(function(){new Draggable('messaging_window',{handle:'bar'})}).defer();}});return false;" alt="New Message" title="New Message"><img src="images/new_msg_blue.png" border="0"></a></td></tr></table></div></div>
<div id="messages_container" style="width:100%;height:86%;overflow-y:auto;overflow-x:hidden">
<?php
include 'message_roll.php';
?>

</div>

<div id="new_users"  style="overflow-x:hidden;overflow-y:auto;height:39%"><div id="messaging_menu"><h5 style="float:left;color:white;">New Users  </h5><div style="float:right;margin-right:10px;"><table style="width:300px;margin:0px"><tr><td><span id="t2a" style="display:block;">
<a href="#" onclick="spread2('new_users','messaging');togg('t2b','t2a');return false;">Maximize</a></span> <span id="t2b" style="display:none;">
<a href="#" onclick="unspread2('new_users','messaging');togg('t2a','t2b');return false;">Minimize</a>
</span> </td></tr></table></div>

</div>
<?php

$new_user = mysql_query("SELECT * FROM `cm_users` WHERE `new` = 'yes'");
if (mysql_num_rows($new_user)<1)
{echo "There are no new users to be activited.";}
while ($line = mysql_fetch_array($new_user, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($new_user,$i);
        $d[$field] = $col_value;
        $i++;

    }

echo <<<LIST
<div style="width:100%;height:25px;background:url(images/grade_small.jpg) repeat-x;">
<TABLE WIDTH="100%">
<TR><TD width="40%"><B>$d[first_name] $d[last_name]<B></TD><TD width="20%"><LABEL>Professor:</LABEL> $d[assigned_prof]</TD><TD width="25%">  <LABEL>Date Applied:</LABEL>
LIST;
formatDate($d[date_created]);
ECHO <<<LIST
</TD><TD width="15%"><div id="status_$d[id]"><a href="#" onClick="createTargets('status_$d[id]','status_$d[id]');sendDataGet('activate_user.php?id=$d[id]');return false;" alt="Activate User" title="Activate User"><img src="images/accept.png" border="0"></a>

<a href="#" onClick="var check = confirm('Delete this Application?');if (check == true){createTargets('status_$d[id]','status_$d[id]');sendDataGet('activate_user_delete.php?id=$d[id]');}else return false;" alt="Delete this Application" title="Delete this Application"><img src="images/delete.png" border="0" style="margin-left:10px;"></a>

</div></TD></TR></TABLE></div>
LIST;

}


?>

</div>


<div id="messaging_window" style="display:none;"></div>
</div>

</body>
</html>
