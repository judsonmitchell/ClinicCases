<?php
session_start();
include 'db.php';
include 'get_name.php';
include_once './classes/format_dates_and_times.class.php';

/* menu if called from cm_cases_single */
if (isset($_POST[draw_menu]))
{

echo<<<MENU
<div id="contacts_menu"><a href="#" class="singlenav" onClick="new Ajax.Updater('messaging_window','message_new.php',{method:'get',parameters:{case_id:'$_POST[case_id]'},onComplete:function(){Effect.Grow('messaging_window');(function(){new Draggable('messaging_window',{handle:'bar'})}).defer();}});return false;" title="New Message" alt="New Message"><img src="images/new_msg.png" border="0"></a> <div id="notifier" style="position:absolute;top:0;left:43%;width:120px;color:red;"></div></div>
<div id="messages_container" style="height:94%;width:100%;overflow-y:scroll;overflow-x:auto;">

MENU;
}



if(isset($_POST[doarchive]))
{
	$get_current_archive = mysql_query("SELECT `id`,`archive` FROM `cm_messages` WHERE `id` = '$_POST[id]' LIMIT 1");
	$a = mysql_fetch_object($get_current_archive);
	$current_archive = $a->archive;
	$new_archive = $a->archive . "$_SESSION[login],";
	$set_new_archive = mysql_query("UPDATE `cm_messages` SET `archive` = '$new_archive' WHERE `id` = '$_POST[id]'");
	}

if (isset($_POST[unarchive]))
{
	$get_current_archive = mysql_query("SELECT `id`,`archive` FROM `cm_messages` WHERE `id` = '$_POST[id]' LIMIT 1");
	$a = mysql_fetch_object($get_current_archive);
	$current_archive = $a->archive;
	$new_archive = str_replace($_SESSION[login] . ",","",$current_archive);
	$set_new_archive = mysql_query("UPDATE `cm_messages` SET `archive` = '$new_archive' WHERE `id` = '$_POST[id]'");
	}

if (isset($_POST[archive_all]))
{
	$get_current_unarchived = mysql_query("SELECT * FROM `cm_messages` WHERE `to` LIKE '%$_SESSION[login]%' OR `ccs` LIKE '%$_SESSION[login]%'");
	while ($a = mysql_fetch_object($get_current_unarchived))
	{
		$current_archive = $a->archive;
		$new_archive = $a->archive . "$_SESSION[login],";
		$set_new_archive = mysql_query("UPDATE `cm_messages` SET `archive` = '$new_archive' WHERE `id` = '$a->id'");

	}
	
	}


if (isset($_POST[mark_read]))
{
	$get_current_mark = mysql_query("SELECT `id`,`read` FROM `cm_messages` WHERE `id` = '$_POST[id]' LIMIT 1");
	$b = mysql_fetch_object($get_current_mark);
	$current_mark = $b->read;
	$new_mark = $b->read . "$_SESSION[login],";
	$set_new_mark_read = mysql_query("UPDATE `cm_messages` SET `read` = '$new_mark' WHERE `id` = '$_POST[id]' LIMIT 1");}

if (isset($_GET[mark_read]))
{
	$get_current_mark = mysql_query("SELECT `id`,`read` FROM `cm_messages` WHERE `id` = '$_GET[id]' LIMIT 1");
	$b = mysql_fetch_object($get_current_mark);
	$current_mark = $b->read;
	$new_mark = $b->read . "$_SESSION[login],";
	$set_new_mark_read = mysql_query("UPDATE `cm_messages` SET `read` = '$new_mark' WHERE `id` = '$_GET[id]' LIMIT 1");}




if (isset($_POST[re_interior]))
{
$get_messages = mysql_query("SELECT * FROM `cm_messages` WHERE `assoc_case` = '$_POST[case_id]' ORDER BY `time_sent` DESC");

if (mysql_num_rows($get_messages)<1)
{echo "There are no messages in this case. ";}
}

elseif (isset($_GET[re_interior]))
{
$get_messages = mysql_query("SELECT * FROM `cm_messages` WHERE `assoc_case` = '$_GET[case_id]' ORDER BY `time_sent` DESC");

if (mysql_num_rows($get_messages)<1)
{echo "There are no messages in this case. ";}
}

elseif (isset($_POST[starred]))
{
$get_messages = mysql_query("SELECT * FROM `cm_messages` WHERE  `starred` LIKE '%$_SESSION[login]%' ORDER BY `time_sent` DESC");


}

elseif (isset($_POST[archive]))
{
$get_messages = mysql_query("SELECT * FROM `cm_messages` WHERE  `archive` LIKE '%$_SESSION[login]%' ORDER BY `time_sent` DESC");


}


elseif (isset($_POST[sent]))
{
$get_messages = mysql_query("SELECT * FROM `cm_messages` WHERE  `FROM` = '$_SESSION[login]' ORDER BY `time_sent` DESC");


}

/* Script is being called from At A Glance */
else
{
$get_messages = mysql_query("SELECT * FROM `cm_messages` WHERE `to` LIKE '%$_SESSION[login]%' AND `archive` NOT LIKE '%$_SESSION[login]%' OR `ccs` LIKE '%$_SESSION[login]%' AND `archive` NOT LIKE '%$_SESSION[login]%'
ORDER BY `time_sent` DESC");


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

if (stristr($d[read], $_SESSION[login]))
{$read = 'yes';}
else
{$read = 'no';}

if (stristr($d[archive], $_SESSION[login]))
{$archive = 'yes';}
else
{$archive = 'no';};




if ($read == 'yes')
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
<tr onmouseover="this.style.color='red';this.style.cursor='pointer';" onmouseout="this.style.color='black';" onclick="
new Ajax.Updater('messaging_window','message_view.php',{method:'get',parameters:{id:'$d[id]'
MESSAGE;
/* When called from cm_cases_single: */

if (isset($_POST[re_interior]) || isset($_GET[re_interior]))
{
	echo ",re_interior:'y',case_id:'$_POST[case_id]'";
}
echo <<<MESSAGE
},onComplete:function(){Effect.Grow('messaging_window');(function(){new Draggable('messaging_window',{handle:'bar'})}).defer();var row = document.getElementById('loc_$d[id]');var newBg = 'url(images/grade_gray_small.jpg)';row.style.backgroundImage = newBg;;}});return false;"><td id = "m_$d[id]"><a  title="Click to star/unstar message" alt="Click to star/unstar message" href="#" class="nobubble" onClick="new Ajax.Updater('star_status_$d[id]','message_star.php',{evalScripts:true,postBody:'id=$d[id]'});"><span id="star_status_$d[id]">
MESSAGE;

if (stristr($d[starred], $_SESSION[login]))
{echo "<img src='images/starred.png' ";}
else
{echo "<img src='images/not_starred.png' ";} 

ECHO <<<MESSAGE

border="0"></span></a></td><td width="20%"
MESSAGE;
if ($read == 'yes')
{echo "style = 'color:#c7c7c7;'>";}
else
{echo ">";}
getName($d[from]);
ECHO <<<MESSAGE
</td><td
MESSAGE;
if (isset($_POST[case_id]))
{echo " width = \"30%\"";}
else
{echo " width=\"20%\" ";}



if ($read == 'yes')
{echo " style = 'color:#c7c7c7;'>";}
else
{echo ">";}
formatDate($d[time_sent]);
ECHO <<<MESSAGE
</td><td
MESSAGE;
if (isset($_POST[case_id]))
{echo " width = \"50%\"";}
else
{echo " width=\"60%\"";}
if ($read == 'yes')
{echo "style = 'color:#c7c7c7;'>";}
else
{echo ">";}
ECHO <<<MESSAGE
$d[subject] - <span style="color:#c7c7c7;">$snippet ...</span> </td><td>
MESSAGE;

if (isset($_POST[re_interior])  || isset($_GET[re_interior]))
//we don't do archiving in Cases
{}
else
{
if ($archive !== 'no' )
	{
	echo "<a title='Archive this message' alt='Archive this message' class=\"nobubble\" href=\"#\" onClick=\"new Ajax.Updater('messages_container', 'message_roll.php', {evalScripts:true,method:'post',postBody:'id=$d[id]&amp;unarchive=yes&amp;archive=yes',onSuccess:function(){\$('notifications').update('Message Returned to Inbox');$('notifications').style.display='block';Effect.Fade('notifications',{duration:4});}})\">Unarchive</a>";
	}

		else
			{echo "<a title='Unarchive this message' alt='Unarchive this message' class=\"nobubble\" href=\"#\" onClick=\"new Ajax.Updater('messages_container', 'message_roll.php', {evalScripts:true,method:'post',postBody:'id=$d[id]&amp;doarchive=yes&amp;notify=2',onSuccess:function(){\$('notifications').update('Message Archived');$('notifications').style.display='block';Effect.Fade('notifications',{duration:4});}})\">Archive</a>";
			}


}
echo <<<MESSAGE

<td></tr>
</table>
<span id="print_title" style="display:none;"><b>Message List</b></span>

</div>
MESSAGE;

}
echo <<<dingo
</div>
<script>


$$("a.nobubble").invoke("observe", "click", function(e) {
	Event.stop(e);
})

$$("tr").invoke("observe", "click", function(e) {

	Event.stop(e);
})
</script>
dingo;
?>
