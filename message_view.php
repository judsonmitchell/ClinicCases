<?php
session_start();
include 'db.php';
include 'get_name.php';
include_once './classes/format_dates_and_times.class.php';
$id = $_GET['id'];

$rand =rand();
?>
<div id="bar" style="width:100%;height:30px;background-color:rgb(195, 217, 255);">

</div>

<div id = "msg_info" style="text-align:left;padding:5px;height:50%;overflow:auto;">
<?php
$msg = mysql_query("SELECT * FROM `cm_messages` WHERE `id` = '$id' LIMIT 1");
while ($line = mysql_fetch_array($msg, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($msg,$i);
        $d[$field] = $col_value;
        $i++;

    }
ECHO <<<MESSAGE
To:
MESSAGE;
getName($d[to]);
ECHO <<<MESSAGE
<br>From:
MESSAGE;
getName($d[from]);
ECHO <<<MESSAGE
<br>Subject:  $d[subject]<br>
Date:
MESSAGE;
formatDate($d[time_sent]);
ECHO <<<MESSAGE
<br><hr>
MESSAGE;
$bodyfix = stripslashes($d[body]);
echo $bodyfix;
MESSAGE;
if ($d[thread_id] != $d[id])
{
echo <<<THREADER

<br><br>
<a style="font-size:9pt;color:blue;" href="#" onClick="Effect.Appear('thread');return false;">View Previous Messages in this Thread</a>
<div id="thread" style="font-size:10pt;width:96%;height:35%;overflow:auto;display:none;padding-left:20px;background-color:#e5e5e5">

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
getName($z[from]);
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
<label class="msg" for "to_full">Forward To:</label><input type="text"  id="to_full" name="to_full" size="35">
<input type="hidden" id="to" name="to">
<div id="autocomplete" style="display:none;"></div>
<textarea cols="60" rows="5" name="body">


--- Forwarded Message originally sent by <?php getName($d[from])?> on <?php formatDate($d[time_sent]) ?> ---

<?php echo stripslashes($d[body]) ?>
</textarea>
<?php
if (empty($d[thread_id]))
{echo "<input type=\"hidden\" name=\"thread_id\" value=\"$d[id]\">";}
else
{echo "<input type=\"hidden\" name=\"thread_id\" value=\"$d[thread_id]\">";}



?>


<input type="hidden" name="from" value="<?php echo $d[to] ?>" >
<input type="hidden" name="subject" value="<?php echo $d[subject] ?>" >
<input type="hidden" name="assoc_case" value="<?php echo $d[assoc_case] ?>" >
<input type="hidden" name="re_interior" value="yes">
<table align="center">
<tr><td><input type="button" value="Send" onClick="sendDataGetDoNothing('message_read.php?id=<?php echo $d[id] ?>');Effect.Fade('messaging_window');createTargets('<?php
/* Again, the situation where this script is called from cm_cases_single instead of at a glance */
if (isset($_GET[interior]))
{$update_place = "case_activity";}
else
{$update_place = "message_roll";}
echo "$update_place";
?>','<?php echo "$update_place";
?>');sendDataPost('message_send.php','forwardForm');updater('updater.php?type=messages','msg_notifier');"></td><td><input type="button" value="Cancel" onClick="Effect.Fade('forward');Effect.Appear('msg_options');"></td>
<?php
if ($_SESSION['class'] == 'prof')
{echo "<td><label class=\"msg\" for \"sms\">Also send via SMS</label><input type=\"checkbox\" id=\"sms\" name=\"sms\"></td>";}


?>
</tr></table>
</form>
</div>

<div id="reply" style="display:none;">
<form id="replyForm">
<textarea cols="60" rows="5" name="body"> </textarea>
<?php
if (empty($d[thread_id]))
{echo "<input type=\"hidden\" name=\"thread_id\" value=\"$d[id]\">";}
else
{echo "<input type=\"hidden\" name=\"thread_id\" value=\"$d[thread_id]\">";}



?>

<input type="hidden" name="to" value="<?php echo $d[from] ?>" >
<input type="hidden" name="from" value="<?php echo $d[to] ?>" >
<input type="hidden" name="subject" value="<?php echo $d[subject] ?>" >
<input type="hidden" name="assoc_case" value="<?php echo $d[assoc_case] ?>" >
<?php
/* if called from cm_cases_single, alerts message_send to redirect to a different url */
if (isset($_GET[interior]))
{
echo "<input type='hidden' name='re_interior' value='yes'";
}

?>
<table align="center">
<tr><td><input type="button" value="Send" onClick="sendDataGetDoNothing('message_read.php?id=<?php echo $d[id] ?>');Effect.Fade('messaging_window');createTargets('<?php
/* Again, the situation where this script is called from cm_cases_single instead of at a glance */
if (isset($_GET[interior]))
{$update_place = "case_activity";}
else
{$update_place = "message_roll";}
echo "$update_place";
?>','<?php echo "$update_place";
?>
');sendDataPost('message_send.php','replyForm');updater('updater.php?type=messages','msg_notifier');"></td><td><input type="button" value="Cancel" onClick="Effect.Fade('reply');Effect.Appear('msg_options');"></td>
<?php
if ($_SESSION['class'] == 'prof')
{echo "<td><label class=\"msg\" for \"sms\">Also send via SMS</label><input type=\"checkbox\" id=\"sms\" name=\"sms\"></td>";}


?>
</tr></table>
</form>
</div>
<div id="msg_options" style="position:absolute;bottom:5px;left:5px;width:100%;height:30px;"><a href="#" onClick="Effect.Fade('msg_options');Effect.Appear('reply');return false;">Reply</a>     <a href="#" onClick="Effect.Fade('msg_options');Effect.Appear('forward');">Forward</a>     <a href="#" onClick="printDiv('messaging_window');return false;">Print</a>     <?php


if ($d[archive] !='yes' & !isset($_GET[interior])){

echo <<<ARCHOPT
<a href="#" onClick="sendDataGetDoNothing('message_read.php?id=$d[id]');createTargets('message_roll','message_roll');sendDataGet('message_archive.php?id=$d[id]');Effect.Shrink('messaging_window');return false;">Archive</a>
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
<a href="#" onClick="createTargets('message_roll','message_roll');sendDataGet('message_unarchive.php?id=$d[id]');Effect.Shrink('messaging_window');return false;">Unarchive</a>
ARCHOPT;
}
}
?>
</div>
<span id="close"><a href="#" onclick="sendDataGetDoNothing('message_read.php?id=<?php echo $id ?>&ieyoursuck=<?php echo $rand ?>');<?php
if (isset($_GET[interior]))
{echo "createTargets('case_activity','case_activity');sendDataGet('message_roll.php?case_id=$_GET[case_id]')";}
elseif ($d[archive] == 'yes')
{echo "createTargets('message_roll','message_roll');sendDataGet('message_roll.php?ieyousuck=$rand&archive=y')";}
else
{echo "createTargets('message_roll','message_roll');sendDataGet('message_roll.php?ieyousuck=$rand')";}?>;Effect.Shrink('messaging_window');updater('updater.php?type=messages','msg_notifier');return false;" alt="Close this Window" title="Close this Window"><img src="images/cancel_small_blue.png" border="0"></a></span>
<img src="images/onload_tricker.gif" onLoad="goLookup();">
