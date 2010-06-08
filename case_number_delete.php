<?php
session_start();
include 'db.php';

//a script to delete the newly assigned case number when the user cancels inputting a new case.

		$q = mysql_query("DELETE FROM `cm` WHERE `clinic_id` = '$_GET[clinic_id]' LIMIT 1");
		
?>
