<div class="case_detail_panel_tools">

	<div class="case_detail_panel_tools_left"></div>

	<div class="case_detail_panel_tools_right"></div>

</div>


<?php foreach($events as $event) {extract($event);  //Geez, I just learned about php extract http://stackoverflow.com/a/8286401/49359  ?>

<div class = "csenote" data-id = "<?php echo $id; ?>">

	<div class = "csenote_bar">

		<div class = "csenote_bar_left"><?php echo $task; ?></div>

		<div class = "csenote_bar_right"></div>

	</div>

</div>





<?php } ?>
