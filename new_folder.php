<?php
session_start();
if (!$_SESSION)
{die('Error: You are not logged in');}
$case_id = $_GET['id'];
$new_folder = "new_folder" . rand();

echo <<<MAKEFOLDER
<span id="putNewName"><div class = "folder_x" id= "$new_folder" >


<a href="#" onClick="createTargets('case_activity','case_activity');sendDataGet('cm_folder_inside.php?case_id=$id&folder=' + escape($('newNamer').value));return false;"><img src="images/folder_small.png" border="0"></a>

<br>

<input id="newNamer" type="text" name="newfoldername" value="Name Your Folder" style="background-color:yellow;border:0px;" onFocus="this.style.backgroundColor='white';this.style.border='1px solid black';this.value=''" onBlur="isAlphaNum(this.value)">
<input type="button" value="Go" onClick="killDroppables();$('$new_folder').id = $('newNamer').value;
new Ajax.Updater('case_activity','cm_docs.php',{evalScripts:true,method:'get',parameters:({id:'$case_id',name:$('newNamer').value,newfolder:'true'})  })">

</div></span>
MAKEFOLDER;







?>
