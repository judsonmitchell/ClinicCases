<?php //tools are only called if this is a first request; otherwise we only need the new case notes data
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
try {
	//new note form to be hidden
	date_default_timezone_set('America/Chicago');
	$this_thumb = $_SESSION['picture_url'];
	$this_date = date('Y-m-d H:i:s');
	$this_fname = $_SESSION['first_name'];
	$this_lname = $_SESSION['last_name'];
	$selector = generate_time_selector(null, null);
	$this_case_id = $case_id;
	$this_user = $_SESSION['login'];
	if (!isset($_POST['update'])) {
		include('../../../html/templates/interior/case_note_add.php');
	}

	//show all case notes
	foreach ($case_notes_data as $case_notes) {

		$time = convert_case_time($case_notes['time']);
		// TODO implement infinite scroll
		include('../../../html/templates/interior/case_note.php');
	}

	if (empty($case_notes_data)) {
		if (isset($search)) {
			echo "<p>No case notes found matching <i>$search</i></p>";
		} else {
			echo "<p>No case notes found</p>";
			die;
		}
	}

	if (!isset($_REQUEST['update'])) {
		echo "</div>";
	}
} catch (Exception $e) {
	var_dump($e);
	echo $e->getMessage();
}
