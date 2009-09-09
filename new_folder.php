<?php
session_start();
$case_id = $_GET['id'];
$new_folder = "new_folder" . rand();

echo <<<MAKEFOLDER
<span id="putNewName"><div class = "folder_x" id= "$new_folder" >


<a href="#" onClick="createTargets('case_activity','case_activity');sendDataGet('cm_folder_inside.php?case_id=$id&folder=' + document.getElementById('newNamer').value);return false;"><img src="images/folder_small.png" border="0"></a>

<br>

<input id="newNamer" type="text" name="newfoldername" value="Name Your Folder" style="background-color:yellow;border:0px;" onFocus="this.style.backgroundColor='white';this.style.border='1px solid black';this.value=''">
<input type="button" value="Go" onClick="killDroppables();document.getElementById('$new_folder').id = document.getElementById('newNamer').value;createTargets('case_activity','case_activity');sendDataGet('new_folder_name.php?case_id=$case_id' + '&name=' + document.getElementById('newNamer').value);">

</div></span>
MAKEFOLDER;







?>
