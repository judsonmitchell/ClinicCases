<?php
session_start();
include 'db.php';
include_once './classes/format_dates_and_times.class.php';
$id = $_GET['id'];
$rand = rand();

echo <<<UPLOADMENU

<div id="docs_menu"><a href="#" class="singlenav" onClick="createTargets('new_folder','new_folder');sendDataGet('new_folder.php?id=$id');return false;">New Folder</a>  | <a href="#" onClick="createTargets('uploader','uploader');sendDataGet('upload.php?id=$id');Effect.Appear('uploader');return false;">Upload</a><div id="trash"><a href="#" alt="Drag Item Here to Delete" title="Drag Item Here to Delete"><img src="images/edittrash.png" border = "0" onLoad="Droppables.add('trash', 
{onDrop:function(element,dropon){
var sure = confirm('Do you want to delete?');
if (sure == true)
{\$(element.id).style.display='none';
createTargets(element.id,element.id);
sendDataGet('document_delete.php?id=' + element.id);}
else {return false;}
}
});"></a></div></div>

UPLOADMENU;

ECHO <<<UPLOAD
<div id="uploader" style="display:none;">
</div>

<div id = "folders">



UPLOAD;
$get_folders = mysql_query("SELECT DISTINCT `folder` FROM `cm_documents` WHERE `case_id` = '$id' AND `folder` != '' ");
while ($r = mysql_fetch_array($get_folders))
{
$folder_name = $r['folder'];
echo <<<FOLDERLOOP
<div class = "folder_x" id= "$folder_name"><a href="#" alt="
Click to Open Folder" title="Click to Open Folder" onClick="killDroppables();createTargets('case_activity','case_activity');sendDataGet('cm_folder_inside.php?case_id=$id&folder=$folder_name');return false;"><img src="images/folder_small.png" border="0" onLoad="Droppables.add('$folder_name', {onDrop:function(element,dropon){\$(element.id).style.display='none';createTargets(element.id,element.id);sendDataGet('put_in_folder.php?id=' + element.id + '&folder=' + dropon.id);}});" ></a><br><a class="folder" href="#" onClick="Effect.Appear('folder_options_$folder_name');" alt="Click for Folder Options" title="Click for Folder Options">$folder_name</a>

<div id = "folder_options_$folder_name" style="position:absolute;left:10px;z-index:3100;width:200px;height:220px;background-color:rgb(255, 255, 204);border:1px solid black;font-size:10pt;text-align:center;display:none;">
<div style="text-align:right;"><a href="#" onClick="Effect.Fade('folder_options_$folder_name');"><img src="images/cancel_small.png" border="0"></a></div>

<ul>
<li><a href="#" onClick="var ask = confirm('This will delete the folder and all files in it.  Proceed?');if (ask == true){killDroppables();createTargets('folder_options_$folder_name','folder_options_$folder_name');sendDataGet('document_delete_folder_and_contents.php?folder_name=$folder_name&case_id=$id');
}else{return false;}">Delete Folder and Contents</a></li>
<li><a href="#" onClick="var ask = confirm('This will delete the folder but keep the files in the main directory.  Proceed?');if (ask == true){killDroppables();createTargets('folder_options_$folder_name','folder_options_$folder_name');sendDataGet('document_delete_folder_keep_contents.php?folder_name=$folder_name&case_id=$id');
}else{return false;}">Delete Folder But Keep Contents</a></li>
<li>Rename Folder</li>
<ul>
<input type="text" id="new_folder_name_$folder_name">
<input type="button" value="Go" onClick="killDroppables();createTargets('folder_options_$folder_name','folder_options_$folder_name');sendDataGet('document_rename_folder.php?case_id=$id&folder_name=$folder_name&new_folder_name=' + document.getElementById('new_folder_name_$folder_name').value);return false;">

</div>
</div>
FOLDERLOOP;


}


ECHO <<<UPLOAD
<div class="folder_x" id="new_folder" style="display:none;"></div>
</div>

<div id="nofolder_docs">
UPLOAD;
$get_docs= mysql_query("SELECT * FROM `cm_documents` WHERE `case_id` = '$id' AND `folder` = '' AND `name` != ''");
while ($line = mysql_fetch_array($get_docs, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_docs,$i);
        $d[$field] = $col_value;
        $i++;


    }
ECHO <<<SINGLEDOCS
<div id="$d[id]" style="width:350px;height:40px;overflow:hide;clear:both;"><img src="images/doc.png" border="0" onLoad="new Draggable('$d[id]');this.style.cursor='move';" onMouseOver="Effect.Appear('doc_info_$d[id]');" onMouseOut="Effect.Fade('doc_info_$d[id]');"><a href="$d[url]" target="_new">$d[name]</a>

<div id="doc_info_$d[id]" style="position:absolute;right:0px;top:30px;width:200px;height:70px;display:none;background-color:rgb(255, 255, 204);border:1px solid black;font-size:10pt;z-index:3002;overflow:hidden;">Name: $d[name]<br>Uploaded: 
SINGLEDOCS;


formatDate($d[date_modified]);
ECHO <<<SINGLEDOCS
<br>By: $d[username]</div> </div>
SINGLEDOCS;

}
ECHO <<<UPLOAD

</div>


UPLOAD;
?>
