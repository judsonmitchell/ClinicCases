<div class="user_display ui-widget ui-corner-bottom user_widget" tabindex="1">

</div>

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
			<button class="button--secondary docs_new_folder">
				<img src="html/ico/new-event.png" alt="New Event Icon" /> <span>&nbsp;New Event</span>
			</button>
		<?php
		} ?>

		<button class="button--secondary print-button" data-print=".print_content.case_<?php echo $this_case_id ?>" data-filename="<?php echo case_id_to_casename($dbh, $this_case_id) ?> Events">
			<img src="html/ico/printer.svg" alt="Print Icon" /> <span>&nbsp;Print</span>
		</button>



	</div>

</div>

<div class="case_detail_panel_casenotes">

	<div class='csenote csenote_new new_event event'>

		<form>

			<div class='csenote_bar'>

				<div class='csenote_bar_left new_event_left'>

					<h4><span class="event_name_live">New Event</span>
						<h4>

							<h5><span class="contact_type_live"></span></h5>

				</div>

				<div class='csenote_bar_right new_event_right'>

					<button class='event_action_submit'>Add</button><button class='event_action_cancel'>Cancel</button>

				</div>

			</div>

			<div class="new_event_data">

				<p><label>What:</label><input type="text" name="task" class="long"></p>

				<p><label>Where:</label><input type="text" name="where" class="long"></p>

				<p><label>Start:</label><input type="text" name="start"></p>

				<p><label>End:</label><input type="text" name="end"></p>

				<p><label>All Day?</label><input type="checkbox" name="all_day" value="off"></p>

				<p><label>Who's Responsible?</label>

					<select multiple name="responsibles" class="responsibles" data-placeholder="Select Users" style="width:350px;">

						<?php echo users_on_case_select($dbh, $id); ?>

					</select>

				</p>

				<p><label>Notes:</label><textarea name="notes"></textarea></p>

				<input type="hidden" name="case_id" value="<?php echo $id; ?>">


			</div>

		</form>

	</div>


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
		//Geez, I just learned about php extract http://stackoverflow.com/a/8286401/49359  
	?>

		<div class="csenote event" data-id="<?php echo $id; ?>">

			<div class="csenote_bar">

				<div class="csenote_bar_left event_group event_bar_left">

					<?php echo generate_thumbs($resps); ?>

					<span class="event_task_title"><?php echo htmlentities($task); ?></span>

				</div>

				<div class="csenote_bar_right event_bar_right">
					<?php if ($_SESSION['permissions']['edit_events'] === '1') {
						echo "<a href='#'' class='event_edit'>Edit</a>";
					}

					if ($_SESSION['permissions']['delete_events'] === '1') {
						echo " <a href='#'' class='event_delete'>Delete</a>";
					}
					?>
				</div>

			</div>

			<p><label>Start:</label>
				<span class="event_start"><?php echo extract_date_time($start); ?></span>
				<?php if ($all_day === '1') {
					echo " <span class='event_all_day'>(All day)</span>";
				} ?>
			</p>

			<p><label>End:</label>
				<span class="event_end"><?php if (!empty($end)  && $end != '0000-00-00 00:00:00') {
																	echo extract_date_time($end);
																} ?></span>
			</p>

			<p><label>Where:</label>
				<span class="event_location"><?php echo htmlentities($location); ?></span>
			</p>

			<p><label>Notes:</label>
				<span class="event_notes"><?php echo htmlentities($notes); ?></span>
			</p>


		</div>

	<?php }
	if (empty($events)) {
		echo "<p>No events found.</p>";
	} ?>

</div>