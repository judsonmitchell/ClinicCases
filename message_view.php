<?php
session_start();
include 'db.php';
include './classes/get_names.php';
include 'get_name.php';
include_once './classes/format_dates_and_times.class.php';
$id = $_GET['id'];

$rand =rand();
?>
<div id="bar" onMouseOver="this.style.cursor='pointer';">

</div>

<div id = "msg_info" >
<?php
$msg = mysql_query("SELECT * FROM `cm_messages` WHERE `id` = '$id' LIMIT 1");
	while ($line = mysql_fetch_array($msg, MYSQL_ASSOC)) {
		$i=0;
		foreach ($line as $col_value) {
			$field=mysql_field_name($msg,$i);
			$d[$field] = $col_value;
			$i++;

		}

//This is a variable containing all of the other parties involved in this message, in case recipient presses Reply All
$remove_me = array($_SESSION[login] . ",",$_SESSION[login]);
if (empty($d[ccs]))
	{

		$all = str_replace($remove_me,"",$d[to]) . "," . $d[from] . ",";
	}
	else
	{
		$all = str_replace($remove_me,"",$d[to]) . "," . str_replace($remove_me,"",$d[ccs]) . "," . $d[from] . ",";
	}
	//end

ECHO <<<MESSAGE
<b>To:</b>
MESSAGE;

	$to_array = explode(",",$d[to]);
		foreach ($to_array as $to)
			{
				$fetch = new get_names; $nme = $fetch->get_users_name($to);
				$tolist .= $nme . ", ";
			}

	echo substr($tolist,0,-2);
ECHO <<<MESSAGE
<br><b>From:</b>
MESSAGE;
$fr = new get_names;$from = $fr->get_users_name($d[from]);
echo $from;


	if(!empty($d[ccs]))
		{
			echo "<br><b>CC:</b> ";
			$cc_array = explode(",",$d[ccs]);
			foreach ($cc_array as $cc)
				{
					$fetch_cc = new get_names; $ccnme = $fetch_cc->get_users_name($cc);
					$cclist .=  $ccnme . ", ";

				}
	
	echo substr($cclist,0,-2); 

		}
ECHO <<<MESSAGE
<br><b>Subject:</b>  $d[subject]<br>
<b>Date:</b>
MESSAGE;

formatDate($d[time_sent]);

ECHO <<<MESSAGE
<br><hr>
MESSAGE;

$bodyfix = stripslashes($d[body]);
echo $bodyfix;

if ($d[thread_id] != $d[id])
{
echo <<<THREADER

<br><br>
<a style="font-size:9pt;color:blue;" href="#" onClick="Effect.Appear('thread');return false;">View Previous Messages in this Thread</a>
<div id="thread" style="display:none">

THREADER;

	$get_thread = mysql_query("SELECT * FROM `cm_messages` WHERE `thread_id` = '$d[thread_id]' AND `id` != '$d[id]' ORDER BY `id` DESC");
	while ($line = mysql_fetch_array($get_thread, MYSQL_ASSOC)) {
		$i=0;
		foreach ($line as $col_value) {
			$field=mysql_field_name($get_thread,$i);
			$z[$field] = $col_value;
			$i++;

		}

ECHO <<<THREADER

<DIV STYLE="font-size:11pt;margin-bottom:10px;"><span style="color: #c7c7c7">
THREADER;

$f = new get_names;$from_th = $f->get_users_name($z[from]);
echo $from_th;
ECHO " on ";
formatDate($z[time_sent]);
$body_fix_a = stripslashes($z[body]);

ECHO <<<THREADER
</span><br>
$body_fix_a

</div>
THREADER;

}

echo <<<THREADER

</DIV>
THREADER;

}

ECHO <<<MESSAGE

MESSAGE;

}


?>

</div>
<div id="forward" style="display:none;">
<form id="forwardForm">
<div style="width:70%;margin:auto;">
<div style="text-align:left;">
<label class="msg" for "to_full">Forward To:</label><input type="text"  id="to_full" name="to_full" size="35">
</div>
<input type="hidden" id="to" name="to">
<div id="autocomplete" style="display:none;"></div>
<textarea cols="60" rows="5" name="body">


--- Forwarded Message originally sent by <?php getName($d[from])?> on <?php formatDate($d[time_sent]) ?> ---

<?php echo stripslashes($d[body]) ?>
</textarea>

<?php
/*
if (empty($d[thread_id]))
	{echo "<input type=\"hidden\" name=\"thread_id\" value=\"$d[id]\">";}
	else
	{echo "<input type=\"hidden\" name=\"thread_id\" value=\"$d[thread_id]\">";}


*/

?>


<input type="hidden" name="from" value="<?php echo $_SESSION[login] ?>" >
<input type="hidden" name="subject" value="<?php echo $d[subject] ?>" >
<input type="hidden" name="assoc_case" value="<?php echo $d[assoc_case] ?>" >

<?PHP
if (isset($_GET[re_interior])  || isset($_POST[re_interior]))
	{ echo "<input type='hidden' name='re_interior' value='yes'>";}
?>

<input type="hidden" name="mark_read" value="<?php echo $id; ?>">

<table align="center">
<tr><td><input type="button" value="Send" onClick="Effect.Shrink('messaging_window');
new Ajax.Updater('messages_container','message_send.php',{method:'POST',parameters:Form.serialize('forwardForm'),onSuccess:function(){new Ajax.Updater('messages_container', 'message_roll.php', {evalScripts:true,method:'post',postBody:'mark_read=y&amp;id=<?php echo $_GET[id]; ?>'});$('notifications').update('Message Forwarded');$('notifications').style.display='block';Effect.Fade('notifications',{duration:4});}});updater('updater.php?type=messages','msg_notifier');return false;"></td><td><input type="button" value="Cancel" onClick="Effect.Fade('forward');Effect.Appear('msg_options');"></td>

<?php
if ($_SESSION['class'] == 'prof')
	{echo "<td><label class=\"msg\" for \"sms\">Also send via SMS</label><input type=\"checkbox\" id=\"sms\" name=\"sms\"></td>";}
?>

</tr></table>
</form>
</div>
</div>

<div id="reply_all" style="display:none;">
<form id="replyAllForm">
<div style="width:70%;margin:auto;">
<div style="text-align:left;">
<b>To:</b>
<?php

$reply_all_array = explode(",",$all);
foreach ($reply_all_array as $u)
	{
		
		$fetch = new get_names; $nme = $fetch->get_users_name($u);
				$tolist2 .= $nme . ", ";
		//getName($u);
		//echo "  ";
	}

	echo substr($tolist2,0,-2);
	//echo $tolist;

?>  <br>
<b>Subject:</b> <?php echo $d[subject]; ?>
</div>
<textarea name="body" style="width:100%;" rows="5"> </textarea>
</div>

<?php
if (empty($d[thread_id]))
	{echo "<input type=\"hidden\" name=\"thread_id\" value=\"$d[id]\">";}
		else
	{echo "<input type=\"hidden\" name=\"thread_id\" value=\"$d[thread_id]\">";}
?>

<input type="hidden" name="to" value="<?php echo $all; ?>" >
<input type="hidden" name="from" value="<?php echo $_SESSION[login]; ?>" >
<input type="hidden" name="subject" value="<?php echo $d[subject]; ?>" >
<input type="hidden" name="assoc_case" value="<?php echo $d[assoc_case]; ?>" >
<input type="hidden" name="mark_read" value="<?php echo $id; ?>">

<?php
/* if called from cm_cases_single, alerts message_send to redirect to a different url */
	if (isset($_GET[re_interior]) || isset($_POST[re_interior]))
		{
			echo "<input type='hidden' name='re_interior' value='yes'";
		}

?>
<table align="center">
<tr><td><input type="button" value="Send" onClick="Effect.Shrink('messaging_window');
new Ajax.Updater('messages_container','message_send.php',{method:'POST',parameters:Form.serialize('replyAllForm'),onSuccess:function(){new Ajax.Updater('messages_container', 'message_roll.php', {evalScripts:true,method:'post',postBody:'mark_read=y&amp;id=<?php echo $_GET[id]; ?>'});$('notifications').update('Reply Sent');$('notifications').style.display='block';Effect.Fade('notifications',{duration:4});}});updater('updater.php?type=messages','msg_notifier');return false;"></td><td><input type="button" value="Cancel" onClick="Effect.Fade('reply_all');Effect.Appear('msg_options');"></td>
<?php
	if ($_SESSION['class'] == 'prof')
		{echo "<td><label class=\"msg\" for \"sms\">Also send via SMS</label><input type=\"checkbox\" id=\"sms\" name=\"sms\"></td>";}


?>
</tr></table>
</form>
</div>


<div id="reply" style="display:none;">
<form id="replyForm">
<div style="width:70%;margin:auto;">
<div style="text-align:left;">
<b>To:</b>
<?php
	getName($d[from]);

?>  <br>
<b>Subject:</b> <?php echo $d[subject]; ?>
</div>
<textarea name="body" style="width:100%;" rows="5"> </textarea>
</div>

<?php
if (empty($d[thread_id]))
	{echo "<input type=\"hidden\" name=\"thread_id\" value=\"$d[id]\">";}
		else
	{echo "<input type=\"hidden\" name=\"thread_id\" value=\"$d[thread_id]\">";}
?>

<input type="hidden" name="to" value="<?php echo $d[from] . "," ?>" >
<input type="hidden" name="from" value="<?php echo $_SESSION[login] ?>" >
<input type="hidden" name="subject" value="<?php echo $d[subject] ?>" >
<input type="hidden" name="assoc_case" value="<?php echo $d[assoc_case] ?>" >
<input type="hidden" name="mark_read" value="<?php echo $id; ?>">

<?php
/* if called from cm_cases_single, alerts message_send to redirect to a different url */
	if (isset($_GET[re_interior]) || isset($_POST[re_interior]))
		{
			echo "<input type='hidden' name='re_interior' value='yes'";
		}

?>
<table align="center">
<tr><td><input type="button" value="Send" onClick="Effect.Shrink('messaging_window');
new Ajax.Updater('messages_container','message_send.php',{evalScripts:true,method:'POST',parameters:Form.serialize('replyForm'),onSuccess:function(){new Ajax.Updater('messages_container', 'message_roll.php', {evalScripts:true,method:'post',postBody:'mark_read=y&amp;id=<?php echo $_GET[id]; ?>'});$('notifications').update('Reply Sent');$('notifications').style.display='block';Effect.Fade('notifications',{duration:4});}});updater('updater.php?type=messages','msg_notifier');return false;"></td><td><input type="button" value="Cancel" onClick="Effect.Fade('reply');Effect.Appear('msg_options');"></td>
<?php
	if ($_SESSION['class'] == 'prof')
		{echo "<td><label class=\"msg\" for \"sms\">Also send via SMS</label><input type=\"checkbox\" id=\"sms\" name=\"sms\"></td>";}


?>
</tr></table>
</form>
</div>

<div id="msg_options"><a href="#" onClick="Effect.Fade('msg_options');Effect.Appear('reply');return false;">Reply</a>     <a href="#" onClick="Effect.Fade('msg_options');Effect.Appear('reply_all');return false;">Reply All</a>     <a href="#" onClick="Effect.Fade('msg_options');Effect.Appear('forward');return false;">Forward</a>     <a href="#" onClick="printDiv('messaging_window');return false;">Print</a>     
<?php
//Test to find out if this message is in the archive
 if(stristr($d[archive], $_SESSION[login]))
 	{$archive_status = "yes";} 
 	
 	
 	//Test to find out if this message is starred
 if(stristr($d[starred], $_SESSION[login]))
 	{$starred_status = "yes";} 

if (empty($archive_status) & !isset($_GET[interior])){

echo <<<ARCHOPT
<a href="#" onClick="Effect.Shrink('messaging_window');
new Ajax.Updater('messages_container','message_roll.php',{evalScripts:true,method:'POST',postBody:'doarchive=y&amp;mark_read=y&amp;id=$_GET[id]',onSuccess:function(){\$('notifications').update('Message Archived');$('notifications').style.display='block';Effect.Fade('notifications',{duration:4});}});updater('updater.php?type=messages','msg_notifier');return false;">Archive</a>
ARCHOPT;
}

else
{
if (isset($_GET[interior]))
{
echo <<<ARCHOPT
.
ARCHOPT;
}
else
{
echo <<<ARCHOPT
<a href="#" onClick="Effect.Shrink('messaging_window');
new Ajax.Updater('messages_container','message_roll.php',{evalScripts:true,method:'POST',postBody:'unarchive=y&amp;mark_read=y&amp;id=$_GET[id]',onSuccess:function(){\$('notifications').update('Message Unarchived');$('notifications').style.display='block';Effect.Fade('notifications',{duration:4});}});updater('updater.php?type=messages','msg_notifier');return false;">Unarchive</a>
ARCHOPT;
}
}
?>
</div>
<span id="close"><a href="#" onClick="

<?php
//Message is being selected from the case file; here, the message_roll refresh must show messages from one case only
if (isset($_POST[re_interior]) || isset($_GET[re_interior])):
echo "new Ajax.Updater('messages_container', 'message_roll.php', {evalScripts:true,method:'post',postBody:'re_interior=y&amp;case_id=$d[assoc_case]&amp;mark_read=y&amp;id=$_GET[id]'})";

//We selected this message while in the archive.  We now need to return to the archive
elseif ($archive_status):
echo "new Ajax.Updater('messages_container', 'message_roll.php', {evalScripts:true,method:'post',postBody:'archive=yes&amp;mark_read=y&amp;id=$_GET[id]'})";

//We selected this message while in the starred box.  We now need to return to the starred box.
elseif ($starred_status):
echo "new Ajax.Updater('messages_container', 'message_roll.php', {evalScripts:true,method:'post',postBody:'starred=yes&amp;mark_read=y&amp;id=$_GET[id]'})";

//We selected this message while in the sent messages box.  We need to return to sent messages.
elseif ($d[from] == $_SESSION[login]):
echo "new Ajax.Updater('messages_container', 'message_roll.php', {evalScripts:true,method:'post',postBody:'sent=yes&amp;mark_read=y&amp;id=$_GET[id]'})";




//We selected this message from in the main inbox.  We need to return to inbox
else:
echo "new Ajax.Updater('messages_container', 'message_roll.php', {evalScripts:true,method:'post',postBody:'mark_read=y&amp;id=$_GET[id]'}) ";
endif;
?>

;Effect.Shrink('messaging_window');updater('updater.php?type=messages','msg_notifier');return false;" alt="Close this Window" title="Close this Window"><img src="images/cancel_small_blue.png" border="0"></a></span>
<img src="images/onload_tricker.gif" onLoad="goLookup();">
