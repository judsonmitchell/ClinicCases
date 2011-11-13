<?php

//Function to query the db and return the correct columns for the cases table html.

function columns_array($dbh)
	
	{
		
		$get_columns = $dbh->prepare('SELECT * from cm_columns');
		$get_columns->execute();
		$result = $get_columns->fetchAll(PDO::FETCH_ASSOC);
		
		return $result;
		
	}	

