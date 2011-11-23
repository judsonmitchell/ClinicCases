<?php $id = $_GET['id'];require('../../../lib/php/data/case_detail_load_data.php'); ?>

<div class = "case_detail_bar">
	<h3><?php echo $case_data->first_name . " " . $case_data->last_name; ?></h3>
	
	<div class = "assigned_people"></div>
	
</div>

<div class = "case_detail_nav">

	<ul class = "case_detail_nav_list">
	
		<li id="item1" class="selected">Case Notes</li>
		
		<li id="item2">Documents</li>
		
		<li id="item3">Events</li>
		
		<li id="item4">Client Data</li>
		
		<li id="item5">Memos</li>

		<li id="item6">Conflict</li>

	</ul>




</div>

<div class = "case_detail_panel">panel</div>
