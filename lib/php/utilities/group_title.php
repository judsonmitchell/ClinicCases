<?php
//function to get the full title of the users group

function get_group_title($v,$dbh)
{
	
	$get_title_query = $dbh->prepare("SELECT * from cm_groups WHERE group_name = ? LIMIT 1");

	$get_title_query->bindParam(1,$v);

	$get_title_query->execute();

	$row = $get_title_query->fetch(PDO::FETCH_ASSOC);
	
	return $row['group_title'];
}

