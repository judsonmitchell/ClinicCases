<?php
//A script to load the column definitions into DataTables.  Returns json.
require('../../../db.php');

	$get_columns = $dbh->prepare('SELECT * from cm_columns');
	$get_columns->execute();
	$result = $get_columns->fetchAll();

	foreach ($result as $col)

		{
		//check to see if this column is supposed to be included in the case table
			if ($col[3] == "true")
			{
			//set the default visibility
				//Cast value into boolean
				if ($col[6] == "true")
					{$vis = true;}
					else
					{$vis = false;}

				$output['aoColumns'][]['bVisible'] = $vis;
			}
		}

		$columns = json_encode($output);

		echo $columns;

