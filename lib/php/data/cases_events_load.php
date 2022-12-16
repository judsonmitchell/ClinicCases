<?php
try {
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
	@session_start();
	require_once dirname(__FILE__) . '/../../../db.php';

	// require_once(CC_PATH . '/lib/php/auth/session_check.php');
	// require_once(CC_PATH . '/lib/php/utilities/thumbnails.php');
	// require_once(CC_PATH . '/lib/php/utilities/names.php');
	// require_once(CC_PATH . '/lib/php/utilities/convert_times.php');
	// require_once(CC_PATH . '/lib/php/html/gen_select.php');
	require_once('../auth/session_check.php');
	require_once('../utilities/thumbnails.php');
	require_once('../utilities/names.php');
	require_once('../utilities/convert_times.php');
	require_once('../html/gen_select.php');

	$_POST = json_decode(file_get_contents("php://input"), true);
	$_REQUEST = json_decode(file_get_contents("php://input"), true);

	$user = $_SESSION['login'];

	if (isset($_GET['id'])) {
		$id = $_GET['id'];
	} else {
		$id = $_POST['case_id'];
	}

	if (isset($_REQUEST['q'])) {
		$q = $_REQUEST['q'];
	} else {
		$q = null;
	}


	function get_responsibles($dbh, $event_id)
	{ //get names of all users on event

		$q = $dbh->prepare("SELECT * FROM cm_events_responsibles
		WHERE event_id = '$event_id'");
		$q->execute();
		$users = $q->fetchAll(PDO::FETCH_ASSOC);
		$responsibles = array();

		foreach ($users as $user) {
			$lastname = username_to_lastname($dbh, $user['username']);
			$fullname = username_to_fullname($dbh, $user['username']);
			$user_id = username_to_userid($dbh, $user['username']);
			$thumb = return_thumbnail($dbh, $user['username']);
			$responsibles[] = array(
				'user_id' => $user_id, 'last_name' => $lastname,
				'full_name' => $fullname, 'thumb' => $thumb, 'username' => $user['username']
			);
		}

		return $responsibles;
	}

	function generate_thumbs($responsibles, $count = null)
	{ //create thumbnail row for assigned users

		$thumb_row = null;
		if ($count) {
			foreach (array_slice($responsibles, 0, 3) as $resp) {;
				$thumb_row .= "<span class='user_identifier' data='" . $resp['username'] .
					"'><img class='thumbnail-mask' src = '" . $resp['thumb']  . "' border = '0' title='" . $resp['full_name']  . "'></span>";
			}
			if(count($responsibles) > 3) {
				$thumb_row .= "<span class='thumb-ellipsis'>...</span>";
			}
		} else {

			foreach ($responsibles as $resp) {;
				$thumb_row .= "<span class='user_identifier' data='" . $resp['username'] .
					"'><img class='thumbnail-mask' src = '" . $resp['thumb']  . "' border = '0' title='" . $resp['full_name']  . "'></span>";
			}
		}

		return $thumb_row;
	}


	if (isset($q)) {  //searching events
		$sql = "SELECT * from cm_events WHERE case_id = :id and (task LIKE :q OR location
    LIKE :q OR notes LIKE :q OR start_text LIKE :q OR end_text LIKE :q)";
		$search_term = '%' . $q . '%';
	} else { //listing all events on a case
		$sql = "SELECT * from cm_events WHERE case_id = :id ORDER BY start DESC";
	}

	//Load events
	$get_events = $dbh->prepare($sql);

	if (isset($q)) {  //searching events
		$data = array('id' => $id, 'q' => $search_term);
	} else {
		$data = array('id' => $id);
	}

	$get_events->execute($data);

	$events = $get_events->fetchAll(PDO::FETCH_ASSOC);
	$this_case_id = $id;
	include('../../../html/templates/interior/cases_events.php');
} catch (Exception $e) {
	// echo 'here';
	// var_dump($e);
	// echo $e->getMessage();
}
