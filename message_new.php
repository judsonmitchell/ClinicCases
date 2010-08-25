<?php
session_start();
if (!$_SESSION)
{header('Location: index.php?login_error=3');}
include 'db.php';

?>


<div id="bar" style="width:100%;height:30px;background-color:rgb(195, 217, 255);" onMouseOver="this.style.cursor='pointer';"></div>


<div id="close"><a href="#" onclick="fp.resetAlrt();Effect.Shrink('messaging_window');$(notifications).update('');return false;" alt="Close this Window" title="Close this Window"><img src="images/cancel_small_blue.png" border="0"></a></div>

<div id = "msg_info" style="height:95%">
<form id="sendForm">

<label class ="msg" for "to">To</label><input type="text"  id="to_full" name="to_full" size="35"

<?php
if(isset($_GET[direct_full]))
{

echo " value=\"$_GET[direct_full]\"";
}
?>
>
<input type="hidden" id="to" name="to"
<?php
if(isset($_GET[direct]))
{
echo " value=\"$_GET[direct]\"";
}

echo ">";
if(!isset($_GET[direct]))

{
echo <<<DIRECT
<select name="group" id="group" onChange="document.getElementById('to_full').value=this.value;allWarn();if(this.value == 'All on a Case'){\$(notifications).style.display='block';\$(notifications).update('Please Select a Case');\$(assoc_case).setStyle({backgroundColor:'red'});}">
<option value="">Select Groups</option>
DIRECT;

	if (isset($_GET[case_id]))
		{
			echo "<option value=\"All on this Case\">All on this Case</option>";
		}
		
			else
			
			{
				echo "<option value=\"All on a Case\">All on a Case</option>";
			}

	if ($_SESSION['class'] == 'prof')
		{
			echo "<option value=\"All Your Students\">All Your Students</option>";
		}
		
ECHO <<<DIRECT
<option value="All Professors">All Professors</option>
<option value="All Students">All Students</option>
<option value="All Users">All Users</option>
</select>
DIRECT;
}
?>

<div id="autocomplete" style="display: none"></div><br>

<a href="#" style="font-size:9pt;color:blue;" onClick="Effect.Appear('ccer');return false;">Add CC</a>
<br>

<input type="hidden" name="from" value="<?php echo $_SESSION[login];   ?>">

<div id="ccer" style="display:none;">
<label for "cc1_full" class="msg">Cc:</label><input type="text" name="cc1_full" id="cc1_full" size="35">
<input type="hidden" name="cc1" id="cc1">
<div id="autocomplete2" style="display: none"></div>
<br><br>
</div>

<label class ="msg" for "subject">Subject</label><input type="text" name="subject" id="subject" size="35">
<?php
if (isset($_GET[case_id]))
{
$get_client_name = mysql_query("SELECT * FROM `cm` WHERE `id` = '$_GET[case_id]' LIMIT 1");
$u = mysql_fetch_array($get_client_name);

echo <<<CASE
<span id="cs_name" style="font-size:14pt;">$u[first_name] $u[last_name]</span><input type="hidden" name="assoc_case" value="$_GET[case_id]">
CASE;
}
else
{
echo "<select name=\"assoc_case\" onChange=\"Effect.Fade($('notifications'),{duration:1.0});$(assoc_case).setStyle({backgroundColor:'green'});\"><option value=\"\">No Case</option>";

/* Get all cases assigned to student */

if ($_SESSION['class'] == 'student')
	{
	$get_cases = mysql_query("SELECT * FROM `cm_cases_students` WHERE `username` = '$_SESSION[login]' AND `status` = 'active'");
	while ($j = mysql_fetch_array($get_cases))
			{
			$get_case_name = mysql_query("SELECT * FROM `cm` WHERE `id` = '$j[case_id]' AND `date_close` = '' ORDER BY `last_name` ASC");
			    while ($k = mysql_fetch_array($get_case_name))
			    {echo "<option value=\"$k[id]\">$k[last_name], $k[first_name]</option>";}
			}

	}
		else
/* Get all cases assigned to professor */
			{
			$get_cases = mysql_query("SELECT * FROM `cm` WHERE `professor` LIKE '%$_SESSION[login]%' AND `date_close` = '' ORDER BY `last_name` ASC");
			while ($l = mysql_fetch_array($get_cases))
				{
					echo "<option value=\"$l[id]\">$l[last_name], $l[first_name]</option> ";
				}

			}

ECHO "</select>";

}
?>

<br><br>
<label class="msg" for "body">Message</label>
<textarea cols="75" rows="15" id="body" name="body"></textarea>



<?php
/* this variable set header location once send script is excuted.  Yes format response for the case_interior message roll */

if (isset($_GET[case_id]))
{
echo <<<DIRECT
<input type="hidden" name="re_interior" value="y">

DIRECT;
/* THIS WILL PUT THE REPSONSE IN CASE ACTIVITY IN cm_cases_single.php */
}
elseif (isset($_GET[direct]))
{
echo <<<DIRECT
<input type="hidden" name="redirection" value="student">

DIRECT;
/* This will put the response in the Student Detail page */
}
if (isset($_GET[case_id]))
{$target = "messages_container";}

elseif (isset($_GET[direct]))
{$target = "dummy";}

else
{$target = "message_roll";}


echo <<<you
<table width="40%" style="margin-left:400px;"><tr><td><a href="#" onClick="var check = checkTo();if (check==true){Effect.Shrink('messaging_window');
new Ajax.Updater('$target','message_send.php',{method:'POST',parameters:Form.serialize('sendForm'),onSuccess:function(){\$('notifications').update('Message Sent');$('notifications').style.display='block';Effect.Fade('notifications',{duration:4});}});updater('updater.php?type=messages','msg_notifier');fp.resetAlrt();return false;} else {return false;}" alt="Send Message" title="Send Message"><img src="images/check.png" border="0"></a></td>
you;

if ($_SESSION['class'] != 'student')
{
echo <<<SMS
<td><label class="msg" for "sms">Also send via SMS</label><input type="checkbox" id="sms" name="sms"></td>
SMS;
}
?>
</tr></table>



</form>
</div>

<img src="images/onload_tricker.gif" onLoad="goLookup();goLookup2();fp = new FormProtector('sendForm');fp.setMessage('If you do not send, your message will be lost.');document.getElementById('to_full').focus();">
