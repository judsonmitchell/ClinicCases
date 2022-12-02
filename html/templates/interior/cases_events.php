<div class="user_display ui-widget ui-corner-bottom user_widget" tabindex="1">

</div>

<?php function return_user_name($user)
{
	return $user['username'];
} ?>
<div class="case_toolbar">

	<div>
		<div class="form__control">
			<input class="case_events_search" data-caseid="<?php echo $this_case_id ?>" id="caseEventsSearch-<?php echo $this_case_id ?>" data-label="#caseEventsSearchLabel-<?php echo $this_case_id ?>" name="caseEventsSearch-<?php echo $this_case_id ?>" type="text" placeholder="search" />
			<label id="caseEventsSearchLabel-<?php echo $this_case_id ?>" for="caseEventsSearch-<?php echo $this_case_id ?>">Search Case Events</label>

		</div>
		<button class="case_events_search_clear" data-caseid="<?php echo $this_case_id ?>">&times;</button>
	</div>


	<div class="">

		<?php
		if ($_SESSION['permissions']['add_events'] == '1') {
		?>
			<button class="button--secondary events_new" data-caseid="<?php echo $this_case_id ?>">
				<img src="html/ico/new-event.png" alt="New Event Icon" /> <span>&nbsp;New Event</span>
			</button>
		<?php
		} ?>

		<button class="button--secondary print-button" data-print=".print_content.case_<?php echo $this_case_id ?>" data-filename="<?php echo case_id_to_casename($dbh, $this_case_id) ?> Events">
			<img src="html/ico/printer.svg" alt="Print Icon" /> <span>&nbsp;Print</span>
		</button>



	</div>

</div>

<div class="case_detail_panel_caseevents">

	<?php if (empty($events)) {
		if (isset($q)) {
			echo "<p>No events found matching <i>$q</i></p>";
			die;
		} else {
			echo "<p>No events in this case</p>";
			die;
		}
	}

	foreach ($events as $event) {
		$resps = get_responsibles($dbh, $event['id']);
		extract($event);


		$respFormValue = array_map("return_user_name", $resps);;
		//Geez, I just learned about php extract http://stackoverflow.com/a/8286401/49359  
	?>
		<div class="case-event" data-id="<?php echo $id; ?>" data-caseid="<?php echo $this_case_id; ?>">
			<div class="case-event__title">
				<div>
					<?php echo generate_thumbs($resps); ?>
				</div>
				<h2 class="event_task_title"><?php echo htmlentities($task); ?></h2>

			</div>
			<div class="case-event__details">
				<div class="event-task-time">
					<div>
						<p><label><strong>Start:</strong></label>
							<span class="event_start"><?php echo extract_date_time($start); ?></span>
						</p>
						<p><label><strong>End:</strong></label>
							<span class="event_end"><?php if (!empty($end)  && $end != '0000-00-00 00:00:00') {
																				echo extract_date_time($end);
																			} ?></span>
						</p>
					</div>

					<?php if ($all_day == '1') {
						echo " <span class='event_all_day'>all day</span>";
					} ?>
				</div>



				<p class="event-location"><label><img src="html/ico/location.svg" alt=""></label>
					<span><?php echo htmlentities($location); ?></span>
				</p>
				<p class="event-location"><label><img src="html/ico/guests.svg" alt=""></label>
					<span><?php echo count($resps) . ' guests' ?></span>
				</p>

				<p><label><strong>Notes:</strong></label>
					<span class="event_notes"><?php echo htmlentities($notes); ?></span>
				</p>



			</div>
			<div class="case-event__bar">
				<?php if ($_SESSION['permissions']['edit_events'] == '1') {
					echo "<a href='#' data-target='#editEventModal-$id' class='event_edit'>Edit</a>";
				}

				if ($_SESSION['permissions']['delete_events'] == '1') {
					echo " <a href='#'' class='event_delete'>Delete</a>";
				}
				?>
			</div>
			<div class="modal fade" role="dialog" id="editEventModal-<?php echo $id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editEventLabel-<?php echo $id ?>" aria-hidden="true">
				<div class="modal-dialog modal-lg modal-dialog-centered">
					<form>
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="editEventLabel-<?php echo $id ?>">Edit Event</h5>
							</div>
							<div class="modal-body">
								<div class="form__control">
									<input id="task" required type="text" name="task" placeholder=" " value="<?php echo $task ?>">
									<label for="task">What is the name of this event?</label>
								</div>
								<div class="form__control">
									<input id="where" required type="text" name="where" placeholder=" " value="<?php echo $location ?>">
									<label for="where">Where is this event?</label>
								</div>
								<div class="form-control__two">
									<div class="form__control">
										<input required type="datetime-local" name="start" placeholder=" " value="<?php echo $start ?>">
										<label for="start">When does this event start?</label>
									</div>
									<div class="form__control">
										<input required type="datetime-local" name="end" placeholder=" " value="<?php echo $end ?>">
										<label for="end">When does this event end?</label>
									</div>
								</div>

								<div class="form__control--checkbox">
									<input name="all_day" type="checkbox" <?php if($all_day == '1') { echo 'checked'; } ?>>
									<label for="all_day">All Day?</label>
								</div>

								<div class="form__control form__control--select">
									<select name="responsibles" multiple class="edit_event_slim_select" tabindex="2" data-value="<?php echo implode(',', $respFormValue) ?>">
									</select>
									<label for="responsibles">Who's Responsible?</label>
								</div>

								<div class="form__control">
									<textarea id="notes" required name="notes" placeholder=" "><?php echo $notes ?></textarea>
									<label for="notes">Description</label>
								</div>
								<input type="text" hidden name="case_id" value="<?php echo $this_case_id ?>">
								<input type="text" hidden name="event_id" value="<?php echo $id ?>">
					</form>
					<div class="modal-footer">
						<button id="editCaseEventEventCancel-<?php echo $id ?>" data-target="editEventModal-<?php echo $id ?>" class="case_event_edit_cancel">Cancel</button>
						<button type="button" data-target="editEventModal-<?php echo $id ?>" class="primary-button edit_event_submit">Submit</button>
					</div>
				</div>

			</div>
		</div>
</div>
</div>


<?php }
	if (empty($events)) {
		echo "<p>No events found.</p>";
	} ?>

</div>