<?php
include 'db.php';
$temp_id = $_GET[temp_id];
$old = "images_tmp/" . $temp_id . ".jpg";
$new = "people/" . $temp_id . ".jpg";
copy($old,$new);
unlink($old);
/* do a check to see if database needs to be updated */
$query = mysql_query("SELECT * FROM `cm_users` WHERE `id` = '$temp_id' LIMIT 1");
while($r = mysql_fetch_array($query))
{
	$r[picture_url] = $target;
if ($target = '/people/no_picture.png' )
{
$fix = mysql_query("UPDATE `cm_users` SET `picture_url` = '$new' WHERE `id` = '$temp_id' LIMIT 1");
}	
	
	
}


echo "This user's photo has been updated. ";
?>
