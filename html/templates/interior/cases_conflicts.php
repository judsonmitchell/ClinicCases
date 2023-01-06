<div class="user_display ui-widget ui-corner-bottom user_widget" tabindex="1">

</div>

<div class="case_toolbar">
	<div></div>
	<div>
		<button class="button--secondary print-button" data-print=".case_detail_panel_conflicts[data-id='<?php echo $id; ?>']" data-filename="Conflicts from <?php echo case_id_to_casename($dbh, $id); ?>">
			<img src="html/ico/printer.svg" alt="Print Icon"> <span>&nbsp;Print</span>
		</button>
	</div>

</div>

<div class="case_detail_panel_conflicts" data-id="<?php echo $id; ?>">
	<?php if ($count > 0) {

		if ($count > 0 && $count < 2) //some grammar
		{
			$c = 'conflict';
		} else {
			$c = 'conflicts';
		}

		echo "<h1>" . $count . " potential $c found.</h1>";
	} else {
		echo "<h1>No potential conflicts found.</h1>";
	}
	?>

	<ol class="conflicts">

		<?php if ($count > 0) {
			foreach ($conflicts as $conflict) {
				echo "<li>" . $conflict['text'] . "</li>";
			}
		} ?>

	</ol>

	<p><?php if ($count > 0) {
				echo "Please review these cases to determine if actual conflicts exist.  ";
			} ?>Read the <a href="http://cliniccases.com/conflicts_disclaimer.php" target="_new">disclaimer</a> about conflicts checking with ClinicCases.</p>

</div>