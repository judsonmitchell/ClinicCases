<?php
/* This needs a trigger for auto; if auto is called, resizing, cropping is done */

if($_POST)
{
$temp_id = $_POST[temp_id];
$goo = $_FILES['photofile']['tmp_name'];
if (!empty($goo))
{
copy ($_FILES['photofile']['tmp_name'], "images_tmp/" . $_FILES['photofile']['name']) 
    or die ("Could not copy"); 

$the_photo = 'images_tmp/' . $_FILES['photofile']['name'];


if ($_POST[auto] == 'on')
{

//Set our crop dimensions.
//width = 128;
//$height = 128;
// Get dimensions of existing image
//$dimensions = getimagesize($the_photo);
// Prepare canvas
//$canvas = imagecreatetruecolor($width,$height);
//$piece = imagecreatefromjpeg($the_photo);
// Prepare image resizing and crop -- Center crop location
//$newwidth = $dimensions[0] / 2;
//$newheight = $dimensions[1] / 2;
//$cropLeft = ($newwidth/2) - ($width/2);
//$cropHeight = ($newheight/2.9) - ($height/2);
// Generate the cropped image
//imagecopyresized($canvas, $piece, 0,0, $cropLeft, $cropHeight, $width, $height, $newwidth, $newheight);
// Write image or fail
//if (imagejpeg($canvas,'./images_tmp/' . $temp_id . '.jpg',90)) {
//
// This is for when this script is called from cm_users_view.php *

function  fiximg($image_filename, $thumb_location, $image_thumb_size){
//@$image_filename - the filename of the image you want
//to get a thumbnail for (relative to location of this
//function).
//@$thumb_location - the url (relative to location of this
//function) to save the thumbnail.
//@$image_thumb_size - the x-y dimension of your thumb
//in pixels.

   list($ow, $oh) = getimagesize($image_filename);
   $image_original = imagecreatefromjpeg($image_filename);
   $image_thumb = imagecreatetruecolor($image_thumb_size,$image_thumb_size);
if ($ow > $oh) {
   $off_w = ($ow-$oh)/2;
   $off_h = 0;
   $ow = $oh;
} elseif ($oh > $ow) {
   $off_w = 0;
   $off_h = ($oh-$ow)/2;
   $oh = $ow;
} else {
   $off_w = 0;
   $off_h = 0;
}
imagecopyresampled($image_thumb, $image_original, 0, 0, $off_w, $off_h, 128, 128, $ow, $oh);

imagejpeg($image_thumb, $thumb_location);
}//end function


fiximg($the_photo,'./images_tmp/' . $temp_id . '.jpg','128-128');
	
	
  	if (isset($_POST[exists]))
   	{echo "Not Good? <a href=\"new_user_photo_try_again.php?exists=yes&temp_id=$temp_id\">Try Again</a>";}
	else

	{echo "Not Good? <a href=\"new_user_photo_try_again.php?temp_id=$temp_id\">Try Again</a>";}
} 
else {
echo 'Image crop failed';
}
// Clean-up

//imagedestroy($piece);
unlink($the_photo);
}
else
{
$new_name = "images_tmp/" . $temp_id . ".jpg";
copy($the_photo,$new_name);

	/* This is for when this script is called from cm_users_view.php */
	if (isset($_POST[exists]))
   	{echo "Not Good? <a href=\"new_user_photo_try_again.php?exists=yes&temp_id=$temp_id\">Try Again</a>";}
	else

	{echo "Not Good? <a href=\"new_user_photo_try_again.php?temp_id=$temp_id\">Try Again</a>";}
unlink($the_photo);
}

echo "<img src='images_tmp/" . $temp_id . ".jpg'>";
}

if (isset($_POST[exists]))
{
echo <<<FORM
<br><br>
<a href="user_change_photo.php?temp_id=$temp_id">Make Change</a>

FORM;
} 


?> 

