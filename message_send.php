<?php
session_start();
if (!$_SESSION)
{die("You must be logged in to view this page.");}

include 'db.php';
$body_mod_a = addslashes($_POST[body]);
$body_mod = nl2br($body_mod_a);

//If option to message all users on a particular case is requested, this function determines their usernames.
function get_all_on_case($case_id)

	{
		$get_profs = mysql_query("SELECT * FROM `cm` WHERE `id` = '$_POST[assoc_case]' LIMIT 1");
		$get_students = mysql_query("SELECT * FROM `cm_cases_students` WHERE `case_id` = '$_POST[assoc_case]' AND `status` = 'active'");
		
		while ($a = mysql_fetch_object($get_profs))
			{$targets = $a->professor;}
		
		while ($b = mysql_fetch_object($get_students))
			{$targets .= $b->username . ",";}
			
		return $targets;	
	
	}
	
if (!empty($_POST[group]))
{

	if ($_POST[group] == 'All on this Case' || $_POST[group] == 'All on a Case')
	
	{$tos_list = get_all_on_case($_POST[assoc_case]);}

		else
		{
			if ($_POST[group] == 'All Your Students')
			{$group_query = "SELECT * FROM `cm_users` WHERE `assigned_prof` LIKE '%$_SESSION[login]%' AND `status` = 'active'";}

			if ($_POST[group] == 'All Professors')
			{$group_query = "SELECT * FROM `cm_users` WHERE `class` = 'prof' AND `status` = 'active'";}

			if ($_POST[group] == 'All Users')
			{$group_query = "SELECT * FROM `cm_users` WHERE `status` = 'active'";}

			if ($_POST[group] == 'All Students')
			{$group_query = "SELECT * FROM `cm_users` WHERE `class` = 'student' AND `status` = 'active'";}



			$send_to_group = mysql_query("$group_query");
				while ($result = mysql_fetch_array($send_to_group))
					{

						$tos_list .=  $result[username] . ",";
					}
			}

$tos = substr($tos_list,0,-1);

}

//This is if no group is selected
else
{
	$tos = substr($_POST[to],0,-1);
}

	$ccs = substr($_POST[cc1],0,-1);



		$send_msg = mysql_query("INSERT INTO `cm_messages` (`id`,`thread_id`,`to`,`from`,`ccs`,`subject`,`body`,`assoc_case`) VALUES (NULL,'$_POST[thread_id]','$tos','$_POST[from]','$ccs','$_POST[subject]','$body_mod','$_POST[assoc_case]')");

			if (empty($_POST[thread_id]))
				{
					$lst_id = mysql_insert_id();
					$set_thread = mysql_query("UPDATE `cm_messages` SET `thread_id` = '$lst_id' WHERE `id` = '$lst_id' LIMIT 1");


				}




//Now, we shove the to parties and cc parties into one array for the sending of notifications.

if (empty($ccs))//There are no cc's
	{$all = $tos;}
	else
	{$all = $tos . ',' . $ccs;}

$all_array = explode(",", $all);

foreach ($all_array as $recips)
	{
		$get_data = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$recips' LIMIT 1");
		while ($line = mysql_fetch_array($get_data, MYSQL_ASSOC)) {
			$i=0;
			foreach ($line as $col_value) {
				$field=mysql_field_name($get_data,$i);
				$d[$field] = $col_value;
				$i++;

			}

	$get_sender_name = mysql_query("SELECT * FROM `cm_users` WHERE `username` = '$_POST[from]' LIMIT 1");
	$x = mysql_fetch_array($get_sender_name);
	$sender_name = "$x[first_name] $x[last_name]";



	$sms_message = "Preview:  " . substr($_POST[body],0,50) . "...";
	$email_message = "You have a new message re: $_POST[subject] from $sender_name on the ClinicCases system." . "\r\n" . $sms_message . "\r\n" . "Please log on to " . $CC_base_url . " to view or " . $CC_base_url . "mobile from a mobile browser." ;
	$subject = "ClinicCases Message from $sender_name";

	$email_to = "$d[email]";
	$headers = 'From: ' . $CC_default_email . "\r\n" .
	   'Reply-To: ' . $CC_default_email . "\r\n" .
	   'X-Mailer: PHP/' . phpversion();

	mail($email_to,$subject,$email_message,$headers);

	if ($_POST[sms] == 'on')
			{

			$domains[] = "$d[mobile_phone]@txt.att.net,$d[mobile_phone]@message.alltel.com,$d[mobile_phone]@tmomail.net,$d[mobile_phone]@vtext.com,$d[mobile_phone]@messaging.nextel.com,$d[mobile_phone]@messaging.sprintpcs.com";

			foreach ($domains as $sms_to)
			{

			mail($sms_to,$subject,$sms_message,$headers);
			}

			}
	}
}



if (isset($_POST[re_interior]))
{
header("Location:message_roll.php?re_interior=y&case_id=$_POST[assoc_case]&mark_read=$_POST[mark_read]");
}
elseif ($_POST[redirection]=='student')
{die;}
else
{
header("Location:message_roll.php?mark_read=$_POST[mark_read]");
}

?>
