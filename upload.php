<?php
session_start();
$id = $_GET['id'];
$folder = $_GET['folder'];
$inner_folder = $_GET['inner'];
$rand = rand();
if (isset($inner_folder))
{
$destination = "cm_folder_inside.php?case_id=$id&folder=$folder";
}
else
{
$destination = "cm_docs.php?id=$id&ieyousuck=$rand";

}

echo <<<FORM
<div style='width:100%;height:12%;background-color:rgb(195, 217, 255);text-align:right;'><a href='#' onClick="killDroppables();createTargets('case_activity','case_activity');sendDataGet('$destination');return false;"><img src='images/cancel_small_blue.png' border='0'></a></div>
<iframe scrolling="no" frameborder="0" id="uploader_frame" src="upload_form.php?id=$id&folder=$folder"></iframe>
FORM;
?>
