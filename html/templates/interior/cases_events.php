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

	<?php foreach($events as $event) {$resps = get_responsibles($dbh,$event['id']);extract($event);
	 //Geez, I just learned about php extract http://stackoverflow.com/a/8286401/49359  ?>

	<div class = "csenote event" data-id = "<?php echo $id; ?>">

		<div class = "csenote_bar">

			<div class = "csenote_bar_left event_group"><?php  echo generate_thumbs($resps);echo extract_date_time($start); ?></div>

			<div class = "csenote_bar_right">
				<?php if ($_SESSION['permissions']['edit_events'] === '1')
					{echo "<a href='#'' class='event_edit'>Edit</a>"; }

					if ($_SESSION['permissions']['delete_events'] === '1')
						{echo " <a href='#'' class='event_delete'>Delete</a>";}
					?>
			</div>

		</div>

		<p><label>What:</label><?php echo $task; ?></p>

		<p><label>Where</label><?php echo $where; ?></p>

		<p><label>Start:</label><?php echo extract_date_time($start);if ($all_day === '1'){echo " (All day)";} ?></p>

		<p><label>End:</label><?php if (!empty($end)){echo extract_date_time($end);} ?></p>

		<p><label>Notes:</label><?php echo $notes; ?></p>


	</div>

	<?php } ?>

</div>



