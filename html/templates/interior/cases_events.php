<div class="case_detail_panel_tools">

	<div class="case_detail_panel_tools_left"></div>

	<div class="case_detail_panel_tools_right">

		<input type="text" class="events_search" value="Search Events">

		<input type="button" class="casenotes_search_clear">

		<?php
			if ($_SESSION['permissions']['add_events'] == '1')
				{echo "<button class='new_event'>New Event</button>";}

		?>

		<button class = "event_print">Print</button>


	</div>

</div>

<div class = "case_detail_panel_casenotes">

	<div class='csenote csenote_new new_event event'>

		<form>

			<div class='csenote_bar'>

				<div class = 'csenote_bar_left new_event_left'>

					<h4><span class="event_name_live">New Event</span><h4>

					<h5><span class="contact_type_live"></span></h5>

				</div>

				<div class = 'csenote_bar_right new_event_right'>

					<button class='event_action_submit'>Add</button><button class='event_action_cancel'>Cancel</button>

				</div>

			</div>

			<div class="new_event_data">

				<p><label>What:</label><input type="text" name="task" class="long"></p>

				<p><label>Where:</label><input type="text" name="where" class="long"></p>

				<p><label>Start:</label><input type="text" name="start"></p>

				<p><label>End:</label><input type="text" name = "end"></p>

				<p><label>All Day?</label><input type="checkbox" name= "all_day" value="off"></p>

				<p><label>Who's Responsible?</label>

					<select multiple name="responsibles" class="responsibles">

						<?php echo users_on_case_select($dbh,$id); ?>

					</select>

				</p>

				<p><label>Notes:</label><textarea name="notes"></textarea></p>

				<input type="hidden" name="case_id" value="<?php echo $id  ;?>">


			</div>

		</form>

	</div>


	<?php foreach($events as $event) {$resps = get_responsibles($dbh,$event['id']);extract($event);
	 //Geez, I just learned about php extract http://stackoverflow.com/a/8286401/49359  ?>

	<div class = "csenote event" data-id = "<?php echo $id; ?>">

		<div class = "csenote_bar">

			<div class = "csenote_bar_left event_group">

				<?php  echo generate_thumbs($resps);echo $task; ?>

			</div>

			<div class = "csenote_bar_right">
				<?php if ($_SESSION['permissions']['edit_events'] === '1')
					{echo "<a href='#'' class='event_edit'>Edit</a>"; }

					if ($_SESSION['permissions']['delete_events'] === '1')
						{echo " <a href='#'' class='event_delete'>Delete</a>";}
					?>
			</div>

		</div>

		<p><label>Start:</label><?php echo extract_date_time($start);if ($all_day === '1'){echo " (All day)";} ?></p>

		<p><label>End:</label><?php if (!empty($end)){echo extract_date_time($end);} ?></p>

		<p><label>Where</label><?php echo $where; ?></p>

		<p><label>Notes:</label><?php echo $notes; ?></p>


	</div>

	<?php } ?>

</div>



