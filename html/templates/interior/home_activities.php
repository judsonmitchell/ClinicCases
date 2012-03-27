<div class = "activities_stream">

	<?php foreach ($activities as $activity) {
		echo "<p><img src='" . $activity['thumb'] . "'>" . $activity['by'] . $activity['action_text'] . "<a href='" .  $activity['follow_url']."'>" . $activity['casename'] . "</a> on " . $activity['time_formatted'] . "</p><p class = 'grey'>" . $activity['what']."</p><hr>";
	} ?>

</div>