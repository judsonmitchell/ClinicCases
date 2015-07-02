<?php

//Script to add users to a case
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('user_data.php');
require('../utilities/names.php');
require('update_case_with_users.php');


$case_id = $_GET['case_id'];
$user_array = $_GET['users_add'];
$username = $_SESSION['login'];

function array_searchRecursive( $needle, $haystack, $strict=false, $path=array() )
{
    if( !is_array($haystack) ) {
        return false;
    }

    foreach( $haystack as $key => $val ) {
        if( is_array($val) && $subPath = array_searchRecursive($needle, $val, $strict, $path) ) {
            $path = array_merge($path, array($key), $subPath);
            return $path;
        } elseif( (!$strict && $val == $needle) || ($strict && $val === $needle) ) {
            $path[] = $key;
            return $path;
        }
    }
    return false;
}

//return an array of everybody currently assigned to the case
$check_previous_assignment = $dbh->prepare("SELECT case_id,username from cm_case_assignees WHERE case_id = :case_id");

$check_previous_assignment->bindParam(':case_id',$case_id);

$check_previous_assignment->execute();

$check_previous_assignment_data = $check_previous_assignment->fetchAll(PDO::FETCH_ASSOC);


foreach ($user_array as $user)

	{
		//find out if this user has already been assigned to this case
		$already_assigned = array_searchRecursive($user,$check_previous_assignment_data);

		//if already assigned, just set their status to active
		if ($already_assigned)
		{

			$update_status = $dbh->prepare("UPDATE cm_case_assignees SET status='active' where case_id = :case_id AND username = :user");

			$update_status->bindParam(':user',$user);

			$update_status->bindParam(':case_id',$case_id);

			$update_status->execute();

		}

		else

		{
		//add user to case
		$user_add_query = $dbh->prepare("INSERT INTO  cm_case_assignees (`id` ,`username` ,`case_id` ,`status` ,`date_assigned` ,`date_removed`)VALUES (NULL ,  :user,  :case_id,  'active', CURRENT_TIMESTAMP ,  '0000-00-00 00:00:00');");

		$user_add_query->bindParam(':user',$user);

		$user_add_query->bindParam(':case_id',$case_id);

		$user_add_query->execute();

		}

		//Send email to user
		$email = user_email($dbh,$user);
		$subject = "ClinicCases: You have been assigned to a case";
		$body = "You have been assigned to the " . case_id_to_casename($dbh,$case_id) . " case.\n\n" . CC_EMAIL_FOOTER;
		mail($email,$subject,$body,CC_EMAIL_HEADERS,"-f ". CC_EMAIL_FROM);

		//Send CC message to user
		$q = $dbh->prepare("INSERT INTO `cm_messages` (`id`, `thread_id`, `to`, `from`, `ccs`, `subject`, `body`, `assoc_case`, `time_sent`, `read`, `archive`, `starred`) VALUES (NULL, '', :tos, :sender, '', :subject, :body, :assoc_case, CURRENT_TIMESTAMP, :sender_has_read, '', '');");

		$sender_has_read = $username .',';

		$data = array('tos' => $user,'sender' => $username, 'subject' => $subject,'body' => $body,'assoc_case' => $case_id,'sender_has_read' => $sender_has_read);

		$q->execute($data);

		$error = $q->errorInfo();

		if (!$error[1])
		{
				//Add thread id to message; if thread_id the same as id,
				//we know message was not a reply.

				$last_id = $dbh->lastInsertId();

				$insert_thread = $dbh->prepare("UPDATE cm_messages SET `thread_id` = '$last_id' WHERE `id` = '$last_id'");

				$insert_thread->execute();

		}
	}

//Add current users to cm table
update_case_with_users($dbh,$case_id);


//Handle mysql errors
if (isset($user_add_query))
	{
		$error = $user_add_query->errorInfo();
	}

if (isset($update_status))
	{
		$error = $update_status->errorInfo();
	}

if($error[1])
	{echo "Error: There was an error adding users";}
	else
	{

		echo "User(s) added to case";

	}
