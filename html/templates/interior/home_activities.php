<div class="activities_stream">

	<div class="activities_feed">

		<a href="feeds/rss.php?type=activities&amp;key=<?php echo $_SESSION['private_key']; ?>" target="_new"><img src="html/ico/rss.png" title="RSS Feed of Activity" border="0"></a>

	</div>

	<?php if (empty($activities)) {
		echo "<p class='end'>There has been no activity in the last sixty days.  If you have just installed ClinicCases 8, it may take a while for this to start filling up.</p>";
		die;
	}

	foreach ($activities as $activity) {
		$activity['thumb'] = 'html/ico/new-case.png'; // default is case
		if (str_contains($activity['activity_type'], 'event')) {
			$activity['thumb'] = 'html/ico/new-event.png';
		}
		if (str_contains($activity['activity_type'], 'board')) {
			$activity['thumb'] = 'html/ico/new-board.png';
		}



		echo "<div class='activity_card'><img src='" . $activity['thumb'] . "'><div><p class='activity_text'>" .
			htmlspecialchars($activity['by'], ENT_QUOTES, 'UTF-8') . htmlspecialchars($activity['action_text'], ENT_QUOTES, 'UTF-8') .
			"<a href='" .  $activity['follow_url'] . "'>" . $activity['casename'] .
			"</a>
			</p>
			<p>
			" . $activity['time_formatted'] . "</p></div>
			<p class='activity_what'>" . $activity['what'] . "</p></div>";
	}
	?>

	<p class="end">End of activities from the last sixty days</p>
</div>