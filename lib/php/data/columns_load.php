<?php
//A script to load the column definitions into DataTables.  Returns json.
//session_start();
include '../../../db.php';

	foreach ($CC_columns as $col)
	
		{
			if ($col[2] == "true")
			{
			$output['aoColumns'][]['bVisible'] = $col[4];
			}
		}
		
		$columns = json_encode($output);
		
		echo $columns;
		
