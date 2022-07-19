<?php
session_start();
date_default_timezone_set('America/Chicago');
$_POST = json_decode(file_get_contents("php://input"), true);
$this_case_id = $_POST['case_id'];
$this_user = $_SESSION['login'];
$this_date = date('Y-m-d H:i:s');
?>


<div id="timer">
	<div id="timer_inner">
		<div>
			<img src="html/ico/timer.png">
			<span class="timer_case_name">
				<!-- Name Place Holder -->
			</span>

		</div>
		<div>
			<span class="timer_time_elapsed">0</span>
		</div>
		<div id="timer_controls">
			<button data-bs-toggle="modal" data-bs-target="#timerNewCaseNoteModal" class="timer_stop">
				<img src="html/ico/stop.png" alt="">
				STOP
			</button>
		</div>
	</div>
</div>
<div class="modal fade" id="timerNewCaseNoteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="timerNewCaseNoteModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="timerNewCaseNoteModalLabel">Timer Stopped</h5>
			</div>
			<div class="modal-body">
				<div id="caseNotesAddForm-<?php echo $this_case_id ?>">
					<form id="timerForm">
						<div class="">
							<div class="case_note_inputs">

								<div class="form__control">
									<textarea id="case_note_add_description" name="csenote_description" placeholder=" " required></textarea>
									<label for="case_note_add_description">Describe what you did</label>
								</div>
								<input type="datetime-local" hidden name="csenote_date" class="case_note_date" value='<?php echo  $this_date ?>'>
								<input type="hidden" name="csenote_user" value='<?php echo $this_user ?>'>
								<input type="hidden" name="csenote_case_id" value='<?php echo $this_case_id ?>'>
								<input type="hidden" name="query_type" value="add">
							</div>
						</div>
					</form>
					<div class="case_note_add_toolbar">
						<button id="" class="cancel_timer_button">Cancel</button>
						<button id="" class="button--primary save_timer_button">
							Add</button>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>