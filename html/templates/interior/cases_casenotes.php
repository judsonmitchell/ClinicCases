<?php //tools are only called if this is a first request; otherwise we only need the new case notes data
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
try {
	if (!isset($_POST['update'])) {
		echo '
	
				<div class="case_toolbar">
					<div class="form__control">
						<label id="caseNotesSearchLabel-' . $case_notes_data[0]['case_id']  . '" for="caseNotesSearch-' . $case_notes_data[0]['case_id']  . '">Search Case Notes</label>
						<input id="caseNotesSearch-' . $case_notes_data[0]['case_id']  . '" data-label="#caseNotesSearchLabel-' . $case_notes_data[0]['case_id']  . '" name="caseNotesSearch-' . $case_notes_data[0]['case_id']  . '" type="text" />
					</div>
					';

		if ($_SESSION['permissions']['add_case_notes'] == '1') {
			echo "<div><button class = \"button--primary\">+ Add New Note</button>
				<button class = \"secondary-button\">
				<img src='html/ico/timer.svg' alt='Timer Icon' /> <span>&nbsp;Timer</span>
		   	</button>";
		}
		echo '<button class = "button--secondary">
					<img src="html/ico/printer.svg" alt="Print Icon" /> <span>&nbsp;Print</span>

				</div>
			</div>
			<div class="print_content case_detail_panel_casenotes case_';
		echo $case_notes_data[0]['case_id'] . "\">";
	}
	//new note form to be hidden
	$this_thumb = $_SESSION['picture_url'];
	$this_date = date('n/j/Y');
	$this_fname = $_SESSION['first_name'];
	$this_lname = $_SESSION['last_name'];
	$selector = generate_time_selector();
	$this_case_id = $case_id;
	$this_user = $_SESSION['login'];

	echo "
	<div class=''>
				<form>
				<div class=''>
					<div class = ''><img src='$this_thumb'> $this_fname $this_lname</div>
					<div class = ''>
					<label>Date:</label> <input type='hidden' name='csenote_date' class='' value='$this_date'> $selector
					<input type='hidden' name='csenote_user' value='$this_user'>
					<input type='hidden' name='csenote_case_id' value='$this_case_id'>
					<input type='hidden' name='query_type' value='add'>
					<button class='button--primary'>
					Add</button><button class=''>Cancel</button></div>
				</div>
				<textarea name='csenote_description'></textarea>
				</form>
				</div>
";

	//show all case notes
	foreach ($case_notes_data as $case_notes) {

		$time = convert_case_time($case_notes['time']);
		echo "<div class='' id='csenote_" . $case_notes['id'] . "'>
					<div class=''>
					<div class = ''><img class='thumbnail-mask' src='" . thumbify($case_notes['picture_url']) . "'>&nbsp " . username_to_fullname($dbh, $case_notes['username']) . "</div><div class = ''><span class=''>" . extract_date($case_notes['date']) .  "</span> &#183; <span class=''>" . $time[0] . $time[1] . "</span>";

		if ($case_notes['username'] == $_SESSION['login']) {
			echo "&nbsp;&nbsp;&nbsp; <a href='#' class=''>Edit</a>&nbsp;&nbsp;<a href='#' class=''>Delete</a>";
		}
		echo "&nbsp;&nbsp;<a href='#' class=''>Print</a>";
		echo "</div></div><p class=''>"    . nl2br(htmlentities($case_notes['description'])) . "</p></div>";
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
