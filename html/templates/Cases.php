<?php include '../../db.php'; ?>

<!-- CSS specific to this page -->
<link rel="stylesheet" href="lib/DataTables-1.7.5/media/css/data_table_jui.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.7.5/extras/TableTools-2.0.0/media/css/TableTools.css" type="text/css">

<link rel="stylesheet" href="lib/DataTables-1.7.5/extras/ColVis/media/css/ColVis.css" type="text/css">

<!-- Jquery Calls Specific to this page -->
	<script src="lib/DataTables-1.7.5/media/js/jquery.dataTables.js" type="text/javascript"></script>

	<script type="text/javascript" src="lib/DataTables-1.7.5/extras/TableTools-2.0.0/media/js/TableTools.min.js"></script>

	<script type="text/javascript" src="lib/DataTables-1.7.5/extras/ColReorder/media/js/ColReorder.min.js"></script>
	
	<script type="text/javascript" src="lib/DataTables-1.7.5/extras/ColVis/media/js/ColVis.js"></script> 

	<script  src="html/js/Cases.js" type="text/javascript"></script>
	
	

</head>
<body>

	<div id="notifications"></div>
	
	<div id="idletimeout">
		You will be logged off in <span><!-- countdown place holder --></span>&nbsp;seconds due to inactivity. 
		<a id="idletimeout-resume" href="#">Click here to continue using ClinicCases</a>.
	</div>

	<div id = "nav_container">

		<?php $t = tabs($_GET['i']); echo $t; ?>
		
		<div id="menus">
			
			<?php include 'html/templates/Menus.php'; ?>

		</div>

	</div>

	<div id="content">

		<div id="processing">Loading....</div>
		
			<table id="table_cases" class="display">
			
			<thead>
				
				<tr>					
					<?php foreach($CC_columns as $col){
						if ($col[2] == "true")
						{echo "<th>" . $col[1] . "</th>";}
						}
					?>
				</tr>
				
				<tr class="advanced">
					
					<?php 
					foreach($CC_columns as $key=>$col){
						
						//Check for date fields. They get special treatment.
						$date_check = substr($col[0],0,4);
						
						if ($col[2] == "true"  && $col[3] == "input" && $date_check !== "date"):
							echo "<th><input type=\"text\" name = \"$col[0]\" class = \"search_init\"></th>";
						
						elseif ($col[2] == "true" && $col[3] == "select"):
							echo "<th class=\"addSelects\" name =\"$col[1]\"></th>"; 
							
						elseif ($col[0] == "date_open" || $col[0] == "date_close"):
							//Create id variable
							$date_type = substr($col[0],5);
							
							echo "
							<th class=\"complex\">
					
								<select id=\"" . $date_type . "_range\" title=\"" . $date_type . " date is less, greater, or equal to...\">
									<option value=\"equals\" selected=selected>=</option>
									<option value=\"greater\">&gt;</option>
									<option value=\"less\">&lt;</option>
								</select>
					
								<input type=\"text\" name = \"$col[0]\" id=\"$col[0]\" class=\"search_init\" title=\"Select a Date\" column = \"$col[0]\"><br />
								
								<a href=\"#\" id=\"addCloseRow\" class=\"smallgray\">Add Condition</a>
								
							</th>";
						
						endif;
						
						}
						?>
					</tr>
					<tr class="advanced_2">
					<?php 
						foreach($CC_columns as $key=>$col){
						
						//Check for date fields. They get special treatment.
						$date_check = substr($col[0],0,4);
						
						if ($col[2] == "true"  && $date_check !== "date"):
						 echo "<th></th>";
						 
						elseif ($col[0] == "date_open" || $col[0] == "date_close"):
							//Create id variable
							$date_type = substr($col[0],5);
							
							echo "
							<th class=\"complex\" id=\"second_" . $date_type . "_cell\">
					
								<select id=\"" . $date_type . "_range_2\" title=" . $date_type . " date is less, greater, or equal to...\">
									<option value=\"equals\" selected=selected>=</option>
									<option value=\"greater\">&gt;</option>
									<option value=\"less\">&lt;</option>
								</select>
					
								<input type=\"text\" name = \"$col[0]" . "_2" .  "\" id=\"$col[0]" . "_2" . "\" class=\"search_init\" title=\"Select a Date\"><br />
								
								<a href=\"#\" id=\"addCloseRow\" class=\"smallgray\">Add Condition</a>
								
							</th>";
														
							endif;
						}
							?>		
					</tr>
				</thead>
			<tbody>
				
		</table>

	</div>





