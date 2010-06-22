<?php
include 'db.php';
include './classes/thumbnail_generator.php';

$temp_id = $_GET[temp_id];
$old = "images_tmp/" . $temp_id . ".jpg";
$new = "people/" . $temp_id . ".jpg";
copy($old,$new);
unlink($old);
$new_pic_tn = "people/tn_" . $target .".jpg";

createthumb($new_pic,'/people/tn_' .  $new_pic_tn,32,32);

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
