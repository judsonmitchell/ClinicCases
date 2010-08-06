<?php
include '../db.php';
include '../classes/thumbnail_generator.php';


//This script is to facilitate an upgrade to cc6.  It assumes you are running ClinicCases 5

//get the assigned professor names from professor and professor  2

echo "Making db changes...<br />";

$add_field = mysql_query("ALTER TABLE  `cm_board` ADD  `is_form` VARCHAR( 10 ) NOT NULL AFTER  `locked`");
$add_income = mysql_query("ALTER TABLE  `cm` ADD  `income` INT( 50 ) NOT NULL AFTER  `race` ,
ADD  `per` VARCHAR( 15 ) NOT NULL AFTER  `income`");

echo "Updating the professors field...<br>";

$get_profs = mysql_query("SELECT `id`,`professor`,`professor2` from `cm`") or die("Query failed with error: ".mysql_error());

	

//loop through the array, putting all values in professor in a comma seperated list.

while ($r = mysql_fetch_array($get_profs))
	{
		//note that the last comma is needed b/c scripts assume a trailing comma:
		if (!empty($r['professor2']))
			{$data = $r['professor'] . ',' . $r['professor2'] . ',';}
			else
			{$data = $r['professor'] . ',';}
			
		$update = mysql_query("UPDATE `cm` SET `professor` = '$data' WHERE `id` = '$r[id]' LIMIT 1");	
		
	}

	




//delete column professor2

$delete_col = mysql_query("ALTER TABLE `cm` DROP COLUMN `professor2`");
	
	
echo "Assigned Professors updated....<br>Creating thumbnails<br>";


//add opened_by field

$add = mysql_query("ALTER TABLE  `cm` ADD  `opened_by` VARCHAR( 50 ) NOT NULL");


$pics=directory('../people/','jpg,JPG,JPEG,jpeg');
$pics=ditchtn($pics,'tn_');
if ($pics[0]!='')
{
	foreach ($pics as $p)
	{
		//echo '../people/' . $p . ' AND ' . '../people/tn_' . $p . '</br>';
		createthumb('../people/'.$p,'../people/tn_'.$p,32,32);
	}
}


echo "Thumbnails created.<br>";

//Update users for multiple assigned profs

$mp = mysql_query("SELECT `assigned_prof`,`id`,`class` FROM `cm_users` WHERE `class` = 'student'");
	while ($mp2 = mysql_fetch_array($mp))
	{
		$replace = $mp2['assigned_prof'] . ',';
		$upd = mysql_query("UPDATE `cm_users` SET `assigned_prof` = '$replace' WHERE `id` = '$mp2[id]' LIMIT 1");
	}
	
	echo "User db updated.<br>";
	
	//error
	if (mysql_errno($connection)) { 
	  $error = "Sorry, there was an error completing the upgrade.  Maybe this will help: MySQL error ".mysql_errno($connection).": ". mysql_error($connection); 
	  die;
		}
		ELSE
		 {echo "Upgrade complete.";}
	
?>
