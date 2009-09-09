<?php
session_start();
include 'db.php';
include './classes/format_dates_and_times.class.php';
$case_id = $_GET['case_id'];
$folder = $_GET['folder'];
echo <<<MENU


<div id="docs_menu"><a href="#" onClick="killDroppables();createTargets('case_activity','case_activity');sendDataGet('cm_docs.php?id=$case_id');return false;">Documents</a> > $folder   |<a href="#" onClick="createTargets('uploader','uploader');sendDataGet('upload.php?id=$case_id&folder=$folder&inner=y');Effect.Appear('uploader');return false;">Upload</a><div id="trash" ><a href="#" alt="Drag Item Here to Delete" title="Drag Item Here to Delete"><img src="images/edittrash.png" border = "0" onLoad="
Droppables.add('trash', {onDrop:function(element,dropon){var sure = confirm('Do you want to delete?');
if (sure == true){\$(element.id).style.display='none';createTargets(element.id,element.id);sendDataGet('document_delete.php?id=' + element.id);}else {return false;}}});"></a></div></div>

<div id="uploader" style="display:none;">
</div>
<br>
MENU;
$get_docs= mysql_query("SELECT * FROM `cm_documents` WHERE `case_id` = '$case_id' AND `folder` = '$folder' AND `name` != '' ORDER BY `name` ASC");
while ($line = mysql_fetch_array($get_docs, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_docs,$i);
        $d[$field] = $col_value;
        $i++;

    }
ECHO <<<SINGLEDOCS


<div id="$d[id]" style="width:350px;height:40px;overflow:hide;clear:both;" onMouseOver="this.style.cursor='move';"><img src="images/doc.png" border="0" onMouseOver="Effect.Appear('doc_info_$d[id]');" onMouseOut="Effect.Fade('doc_info_$d[id]');" onLoad="new Draggable('$d[id]');" ondblClick="Effect.Appear('doc_mover_$d[id]')"><a href="$d[url]" target="_new" >$d[name]</a><div id = "doc_mover_$d[id]" style="position:absolute;left:10px;z-index:3005;width:200px;height:200px;background-color:rgb(255, 255, 204);border:1px solid black;font-size:10pt;text-align:center;display:none;"><div style="text-align:right;"><a href="#" onClick="Effect.Fade('doc_mover_$d[id]');"><img src="images/cancel_small.png" border="0"></a></div>Move <b>$d[name]</b> to this folder:<br>
SINGLEDOCS;

ECHO "<SELECT NAME='folder_mover' id='folder_mover' onChange=\"createTargets('folder_mover','folder_mover');sendDataGet('document_move_folder.php?doc_id=$d[id]&chosen_folder=' + this.value);Effect.Fade('doc_mover_$d[id]');document.getElementById('$d[id]').style.display='none';\"><option selected=selected>Please Choose</option><option value = ''>No Folder</option>";
$get_folder_names = mysql_query("SELECT DISTINCT `folder` FROM `cm_documents` WHERE `case_id` = '$case_id' AND `folder` != '$folder'");
while ($result=mysql_fetch_array($get_folder_names))
{
$folder_name = $result['folder'];
echo "<option value=\"$folder_name\">$folder_name</option>";
}




ECHO <<<SINGLEDOCS
</select><br><br><a href="#" onClick="Effect.Fade('doc_mover_$d[id]');">Cancel</a></div><div id="doc_info_$d[id]" style="position:absolute;right:0px;top:30px;width:200px;height:100px;display:none;background-color:rgb(255, 255, 204);border:1px solid black;font-size:10pt;z-index:3002;overflow:hidden;">Name: $d[name]<br>Uploaded: 
SINGLEDOCS;

formatDate($d[date_modified]);
ECHO <<<SINGLEDOCS
<br>By: $d[username]<br>Double-click <img src="images/doc.png" border="0"> for more options</div></div>
SINGLEDOCS;
}
ECHO <<<SINGLEDOCS


SINGLEDOCS;
if (mysql_num_rows($get_docs)<1)
{
echo "No files in this folder.";

}
















?>
