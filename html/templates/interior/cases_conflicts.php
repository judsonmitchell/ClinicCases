<div class="user_display ui-widget ui-corner-bottom user_widget" tabindex="1">

</div>

<div class="case_detail_panel_tools">

	<div class="case_detail_panel_tools_left">

		<?php if ($count > 0)
		{

			if ($count > 0 && $count < 2) //some grammar
				{$c = 'conflict';}
			else
				{$c = 'conflicts';}

			echo "<strong>" . $count . " potential $c found.</strong>";
		}
		else
		{
			echo "<strong>No potential conflicts found.</strong>";
		}
		?>

	</div>

	<div class="case_detail_panel_tools_right">


		<button class = "conflicts_print">Print</button>


	</div>

</div>

<div class = "case_detail_panel_casenotes">

	<ol class="conflicts">

	<?php if ($count > 0){foreach ($conflicts as $conflict){
			echo "<li>" . $conflict['text'] . "</li>";
		}
	}?>

	</ol>

	<p><small><?php if ($count > 0){echo "Please review these cases to determine if actual conflicts exist.  ";} ?>Read the <a href="http://cliniccases.com/conflicts_disclaimer.php" target="_new">disclaimer</a> about conflicts checking with ClinicCases.</small></p>

</div>



