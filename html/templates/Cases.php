<!-- CSS specific to this page -->
<link rel="stylesheet" href="lib/DataTables-1.8.2/media/css/data_table_jui.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.8.2/extras/TableTools/media/css/TableTools.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.8.2/extras/ColVis/media/css/ColVis.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.8.2/extras/ColReorder/media/css/ColReorder.css" type="text/css">

<link type="text/css" href="html/css/fff.icon.core.css" rel="stylesheet"/>

<link type="text/css" href="html/css/fff.icon.icons.css" rel="stylesheet"/>

<link type="text/css" href="lib/javascripts/chosen/chosen.css" rel="stylesheet"/>


<!-- Jquery Calls Specific to this page -->
	<script src="lib/DataTables-1.8.2/media/js/jquery.dataTables.js" type="text/javascript"></script>
	
	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/TableTools/media/js/TableTools.min.js"></script>

	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/ColReorder/media/js/ColReorder.min.js"></script>
	
	<script type="text/javascript" src="lib/DataTables-1.8.2/extras/ColVis/media/js/ColVis.js"></script> 

	<script type="text/javascript" src="lib/javascripts/chosen/chosen.jquery.min.js"></script>

	<script  src="html/js/Cases.js" type="text/javascript"></script>
	
	<script  src="html/js/casesCaseDetail.js" type="text/javascript"></script>

	<script  src="html/js/notifyUser.js" type="text/javascript"></script>

	
	

</head>
<body>
	
	<div id="notifications"><p></p></div>
	
	<div id="idletimeout">
		You will be logged off in <span><!-- countdown place holder --></span>&nbsp;seconds due to inactivity. 
		<a id="idletimeout-resume" href="#">Click here to continue using ClinicCases</a>.
	</div>

	<div id = "nav_container">

		<?php $t = tabs($dbh,$_GET['i']); echo $t; 	?>
		
		<div id="menus">
			
			<?php include 'html/templates/Menus.php'; ?>

		</div>

	</div>

	<div id="content">

		<div id="processing">Loading....</div>
		
			<?php if ($_SESSION['permissions']['add_cases'] == "1")
				{echo "<div id=\"new_case_icon\" class=\"ui-widget-header ui-corner-all\"><a class=\"DTTT_button ui-button DTTT_button_icon ui-state-default \">Add New Case <span class=\"cc_add_icon\"><img src='html/images/add.png'></span></a></div>";} ?>
		
			<table id="table_cases" class="display">
			
			<thead>
				
				<tr>					
					<?php $CC_columns = columns_array($dbh);
					foreach($CC_columns as $key=>$col){
						if ($col['include_in_case_table'] == "true")
						{echo "<th>" . $col['display_name'] . "</th>";}
						}
					?>
				</tr>
				
				<tr class="advanced">
					
					<?php 
					foreach($CC_columns as $key=>$col){
						
						//Check for date fields. They get special treatment.
						$date_check = substr($col['db_name'],0,4);
						
						if ($col['include_in_case_table'] == "true"  && $col['input_type'] == "input" && $date_check !== "date"):
							echo "<th><input type=\"text\" name = \"" . $col['db_name'] . "\" class = \"search_init\"></th>";
						
						elseif ($col['include_in_case_table'] == "true" && $col['input_type'] == "select"):
							echo "<th class=\"addSelects\" name =\"" . $col['display_name'] . "\"></th>"; 
							
						elseif ($col['db_name'] == "date_open" || $col['db_name'] == "date_close"):
							//Create id variable
							$date_type = substr($col['db_name'],5);
							
							echo "
							<th class=\"complex\">
					
								<select id=\"" . $date_type . "_range\" title=\"" . $date_type . " date is less, greater, or equal to...\">
									<option value=\"equals\" selected=selected>=</option>
									<option value=\"greater\">&gt;</option>
									<option value=\"less\">&lt;</option>
								</select>
					
								<input type=\"text\" name = \"" . $col['db_name'] . "\" id=\"" . $col['db_name'] . "\" class=\"search_init\" title=\"Select a Date\" column = \"" . $col['db_name'] . "\"><br />
								
								<a href=\"#\" id=\"add" . $date_type . "Row\" class=\"smallgray\">Add Condition</a>
								
							</th>";
						
						endif;
						
						}
						?>
					</tr>
					<tr class="advanced_2">
					<?php 
						foreach($CC_columns as $key=>$col){
						
						//Check for date fields. They get special treatment.
						$date_check = substr($col['db_name'],0,4);
						
						if ($col['include_in_case_table'] == "true"  && $date_check !== "date"):
						 echo "<th></th>";
						 
						elseif ($col['db_name'] == "date_open" || $col['db_name']== "date_close"):
							//Create id variable
							$date_type = substr($col['db_name'],5);
							
							echo "
							<th class=\"complex\" id=\"second_" . $date_type . "_cell\">
					
								<select id=\"" . $date_type . "_range_2\" title=\"" . $date_type . " date is less, greater, or equal to...\">
									<option value=\"equals\" selected=selected>=</option>
									<option value=\"greater\">&gt;</option>
									<option value=\"less\">&lt;</option>
								</select>
					
								<input type=\"text\" name = \"" . $col['db_name'] . "_2" .  "\" id=\"" . $col['db_name'] .  "_2" . "\" class=\"search_init\" title=\"Select a Date\"><br />
								
								
							</th>";
														
							endif;
						}
							?>		
					</tr>
				</thead>
			<tbody>
				
		</table>

	</div>





