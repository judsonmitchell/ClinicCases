<?php

//Gets the name of the case for inclusion on the case detail tab. Returns json.

require_once('../../../db.php');

$id = $_GET['id'];

$query = $dbh->prepare("SELECT id,first_name,last_name,organization FROM cm WHERE id = ? LIMIT 1");
		
		$query->bindParam(1,$id);
		
		$query->execute();

		$r = $query->fetch(PDO::FETCH_ASSOC);
		
		$tab_data = json_encode($r);
		
		echo $tab_data;
