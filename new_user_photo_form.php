<?php
$temp_id = $_GET[temp_id];
$exists = $_GET[exists];

?>
<html>
<head>
<body style="background:rgb(255, 255, 207);">
<table>
<Tr><td><img src="images/no_picture.png" border="0"></td><td valign="bottom"><a href="#" onClick=
"document.getElementById('loader').style.display = 'block';">Add Picture</a></td></tr></table>
<div id = "loader" style="display:none;">
<form id = "form1" name="form1" action="img_process.php" method="post" enctype="multipart/form-data">
<label for 'photofile'>Upload Picture</label><input type="file" name="photofile" id='photofile'>
<label for "auto">Automatically Resize and Crop</label><input type="checkbox" checked id="auto" name="auto"><br>
<input type="hidden" name="temp_id" value="<?php echo $temp_id ?>">
<?php
/* This is for when this script is called from cm_users_view.php */
if ($exists == 'yes')
{echo "<input type='hidden' name='exists' value='yes'>";}

?>
<input type="submit" name="Submit" value="Upload Picture"> 
</form>
</div>

