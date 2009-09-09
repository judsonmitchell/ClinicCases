<?php
session_start();
include 'db.php';
include './classes/format_dates_and_times.class.php';
include_once './classes/url_handler.php';

$case_id = $_GET['case_id'];
$folder = $_GET['folder'];


echo <<<MENU


<div id="docs_menu"><a href="#" id="docu" onClick="killDroppables();new Ajax.Updater('case_activity','cm_docs.php?id=$case_id',{evalScripts:true,method:'get'});return false;">Documents</a> > <img src="images/folder_super_small.png"> $folder |  <a href="#" class="singlenav" id="upload_button">Upload</a> | <a href="#" onClick="Effect.BlindDown('url_menu');return false;">Add URL</a><div id="status"></div><div id="trash" ><a href="#" alt="Drag Item Here to Delete" title="Drag Item Here to Delete"><img src="images/edittrash.png" border = "0" onLoad="
Droppables.add('trash', {onDrop:function(element,dropon){var sure = confirm('Do you want to delete?');
if (sure == true){\$(element.id).style.display='none';createTargets(element.id,element.id);sendDataGet('document_delete.php?id=' + element.id);new Ajax.Updater('nofolder_docs','documents_list_refresh.php?',{method:'get',parameters:({id:'$case_id',folder:'$folder'})});
}else {return false;}}});"></a></div></div>

<div id="url_menu" style="display:none;">
<form id="url_submit">
<label for "url">URL:</label><input type="text" id="url" name="url" onBlur="isUrl(this.value);">
<label for "title">Title:</label><input type="text" id="title" name="title">
<input type="hidden" name="username" id="username" value="$_SESSION[login]">
<input type="hidden" name="case_id" id="case_id" value="$case_id">
<input type="hidden" name="folder" id="folder" value="$folder">
<a href="#" title="Submit URL" alt="Submit URL" onClick="new Ajax.Updater('status','document_url.php',{method:'POST',parameters:Form.serialize('url_submit'),onComplete: function(){new Ajax.Updater('nofolder_docs','documents_list_refresh.php',{evalScripts:true,method:'get',parameters:({id:'$case_id',folder:'$folder'})});	Effect.Fade('status',{duration:3.0});Effect.BlindUp('url_menu');}})"><img src="images/check_yellow_small.png" border="0"></a>
<a href="#" title="Cancel" alt="Cancel" onClick="Effect.BlindUp('url_menu');return false;"><img src="images/cancel_small.png" border="0"></a>
</form>

</div>
<script>
var button = $('upload_button'),docs = $('nofolder_docs'), interval,statusw = $('status');

new AjaxUpload('upload_button', {action: 'document_upload.php', name:'docfile',data :{'case_id' : '$case_id', 'folder' : '$folder'},autoSubmit: true,onSubmit : function(file, ext){

			// change button text, when user selects file			
			
			statusw.update('Uploading ' + file);
			
			// If you want to allow uploading only 1 file at time,
			// you can disable upload button
			//this.disable();
			
			// Animating upload button
			// Uploding -> Uploading. -> Uploading...
			interval = window.setInterval(function(){
				var text = statusw.innerHTML;
				
				if (text.length < 53){
					statusw.update(text + '.');					
				} else {
					statusw.update('Uploading ' + file);				
				}
			}, 200);		
			
},
onComplete: function(file, response){
			//console.log(response);
			if (response !== 'Document Uploaded')
			{
				statusw.innerHTML = response;
				
				window.clearInterval(interval);
				//this.enable();
				return false;
				
			}
			else
			{
			//button.update('Upload Another');
			window.clearInterval(interval);
			
			// enable upload button
			//this.enable();
			
			statusw.innerHTML = 'Done.';
			// add file to the list
						
				new Ajax.Updater('nofolder_docs_inside','documents_list_refresh.php?id=$case_id&folder=$folder');	
				Effect.Fade(statusw,{duration:3.0});
		}}

});
</script>
</div></div>


<br>
<div id="nofolder_docs_inside">
MENU;
$get_docs= mysql_query("SELECT * FROM `cm_documents` WHERE `case_id` = '$case_id' AND `folder` = '$folder' AND `name` != '' ORDER BY `name` ASC");
while ($line = mysql_fetch_array($get_docs, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_docs,$i);
        $d[$field] = $col_value;
        $i++;

    }
      $prefix = substr($d[url], 0, 4);
    if ( $prefix == 'http')
    {
    	$icon = "images/url.png";
	}
		else
		{$icon = "images/doc.png";}
			$clean_url = safe_url($d[url]);
	
ECHO <<<SINGLEDOCS


<div id="$d[id]" style="width:350px;height:40px;overflow:hide;clear:both;" onMouseOver="this.style.cursor='move';"><img src="$icon" border="0" onMouseOver="$('doc_info_$d[id]').show();" onMouseOut="$('doc_info_$d[id]').hide();" onLoad="new Draggable('$d[id]');" ondblClick="Effect.Appear('doc_mover_$d[id]')"><a href="$clean_url" target="_new" >$d[name]</a>

<div id = "doc_mover_$d[id]" style="position:absolute;left:10px;z-index:3005;width:200px;height:200px;background-color:rgb(255, 255, 204);border:1px solid black;font-size:10pt;text-align:center;display:none;">Move <b>$d[name]</b> to this folder:<br>
SINGLEDOCS;

ECHO "<SELECT NAME='folder_mover' id='folder_mover' onChange=\"createTargets('folder_mover','folder_mover');sendDataGet('document_move_folder.php?doc_id=$d[id]&chosen_folder=' + this.value);Effect.Fade('doc_mover_$d[id]');document.getElementById('$d[id]').style.display='none';\"><option selected=selected>Please Choose</option><option value = ''>No Folder</option>";
$get_folder_names = mysql_query("SELECT DISTINCT `folder` FROM `cm_documents` WHERE `case_id` = '$case_id' AND `folder` != '$folder'");
while ($result=mysql_fetch_array($get_folder_names))
{
$folder_name = $result['folder'];
/*
$folder_name = stripslashes($folder_name);
*/
echo "<option value=\"$folder_name\">$folder_name</option>";
}




ECHO <<<SINGLEDOCS
</select><br><br><a href="#" onClick="Effect.Fade('doc_mover_$d[id]');return false;">Cancel</a></div>

<div id="doc_info_$d[id]" style="position:absolute;right:0px;top:30px;width:250px;height:100px;display:none;background-color:rgb(255, 255, 204);border:1px solid black;font-size:10pt;z-index:3002;overflow:hidden;"><b>Name:</b> $d[name]<br><b>Uploaded:</b> 
SINGLEDOCS;

formatDate($d[date_modified]);
ECHO <<<SINGLEDOCS
<br><b>By:</b> $d[username]<br>Double-click <img src="$icon" border="0"> for more options</div></div>
SINGLEDOCS;
}
if (mysql_num_rows($get_docs)<1)
{
echo "No files in this folder.";

}

ECHO <<<SINGLEDOCS
</div>

SINGLEDOCS;
















?>
