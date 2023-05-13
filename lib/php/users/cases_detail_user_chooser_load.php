<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
$case_id = $_POST['case_id'];

function load_user_list($dbh)
{
	$user_list_query = $dbh->prepare("SELECT * from cm_users where status='active' ORDER BY last_name asc");

	$user_list_query->execute();

	$user_list_data = $user_list_query->fetchAll();

	$users = NULL;

	foreach ($user_list_data as $user) {

		$users .= "<option value='" . $user['username'] . "'>" . $user['first_name'] . " " . $user['last_name'] . "</option>";
	}

	return $users;
}
//Generate a list of all active users and all groups.  Used in messages.
function all_active_users_and_groups($dbh, $case_num, $you)
{

	try {

		$options = null;

		//If case, add ability to send to all on the case
		if ($case_num) {
			$q = $dbh->prepare("SELECT * FROM cm_case_assignees WHERE `case_id` = '$case_num' AND `status` = 'active'");

			$q->execute();

			$count = $q->rowCount();

			$options .= "<option value='_all_on_case_'>All Users on this Case ($count)</option>";
		}

		//Determine total number of active users
		$q = $dbh->prepare("SELECT * FROM `cm_users` WHERE `status` = 'active'");

		$q->execute();

		$count = $q->rowCount();

		$options .= "<option value='_all_users_'>All Users ($count)</option>";

		//First get all groups defined in cm_groups config
		$q = $dbh->prepare("SELECT group_name, group_title FROM cm_groups ORDER BY group_title ASC");

		$q->execute();

		$groups = $q->fetchAll(PDO::FETCH_ASSOC);

		foreach ($groups as $group) {
			$options .= "<option value='_grp_" . $group['group_name'] . "'>Group: All " . $group['group_title'] . "s</option>";
		}

		//Then get every supervisor
		$q = $dbh->prepare("SELECT cm_groups.group_name, cm_groups.supervises, cm_users.grp, cm_users.username
		FROM cm_groups, cm_users
		WHERE cm_groups.supervises =  '1'
		AND cm_users.grp = cm_groups.group_name
		AND cm_users.status =  'active'
		ORDER BY cm_users.username ASC");

		$q->execute();

		$groups = $q->fetchAll(PDO::FETCH_ASSOC);

		foreach ($groups as $group) {
			$options .= "<option value = '_spv_" . $group['username'] . "'>Group: " . username_to_fullname($dbh, $group['username']) . "'s group</option>";
		}

		//Then just get individual users
		$q = $dbh->prepare("SELECT * FROM cm_users WHERE status = 'active' ORDER BY last_name ASC");

		$q->execute();

		$users = $q->fetchAll(PDO::FETCH_ASSOC);

		foreach ($users as $user) {

			if ($you) {

				if ($user['username'] == $_SESSION['login']) {
					$options .= "<option selected=selected value='" . $user['username'] . "'>You</option>";
				} else {
					$options .= "<option value = '" . $user['username']  . "'>" . $user['first_name'] . " " . $user['last_name'] . "</option>";
				}
			} else {

				$options .= "<option value = '" . $user['username']  . "'>" . $user['first_name'] . " " . $user['last_name'] . "</option>";
			}
		}

		return $options;
	} catch (Exception $e) {
		echo "Error: " . $e->getMessage();
	}
}
$user_list = load_user_list($dbh);

include '../../../html/templates/interior/cases_detail_user_chooser.php';
