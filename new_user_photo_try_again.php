<?php
$temp_id = $_GET[temp_id];
$photo2delete = "./images_tmp/" . $temp_id . ".jpg";
unlink($photo2delete);

/* This is for when this script is called from cm_users_view.php */
if (isset($_GET[exists]))
{
header('Location: new_user_photo_form.php?exists=yes&temp_id=' . $temp_id);
}
else
{
header('Location: new_user_photo_form.php?temp_id=' . $temp_id);
}


?>
