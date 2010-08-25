<?php
session_start();
if (!$_SESSION)
{die('Error: You are not logged in');}
include 'db.php';
include_once './classes/format_dates_and_times.class.php';
include_once './classes/url_handler.php';
$id = $_GET['id'];
$rand = rand();

if ($_GET['newfolder'])
{
$name = $_GET['name'];
$case_id = $_GET['id'];
$rand = rand();
/* This adds a document with no name, just a case id and folder name, so that if the user doesn't add a doc to the folder, the empty folder with the correct name will still appear next time they return */
$put_dummy_doc = mysql_query("INSERT INTO `cm_documents` (`id`,`folder`,`case_id`) VALUES (NULL,'$name','$case_id')");



}

echo <<<UPLOADMENU

<div id="docs_menu"><a href="#" class="singlenav" onClick="createTargets('new_folder','new_folder');sendDataGet('new_folder.php?id=$id');return false;">New Folder</a>  | <a href="#" class="singlenav" id="upload_button">Upload</a> | <a  class="singlenav" href="#" onClick="Effect.BlindDown('url_menu');return false;">Add URL</a><div id="status"></div><div id="trash"><a href="#" alt="Drag Item Here to Delete" title="Drag Item Here to Delete"><img src="images/edittrash.png" border = "0" onLoad="Droppables.add('trash',
{onDrop:function(element,dropon){
var sure = confirm('Do you want to delete?');
if (sure == true)
{\$(element.id).style.display='none';
createTargets(element.id,element.id);
sendDataGet('document_delete.php?id=' + element.id);
new Ajax.Updater('nofolder_docs','documents_list_refresh.php?id=$id');
}
else {return false;}
}
});"></a></div></div>

<div id="url_menu" name="url_menu" style="display:none;">
<form id="url_submit" name="url_submit">
<label for "url">URL:</label><input type="text" id="url" name="url" onBlur="var uu = isUrl(this.value);if (uu == false){alert('URL must be a valid http https or ftp link:  http:\/\/example.com');}">
<label for "title">Title:</label><input type="text" id="title" name="title">
<input type="hidden" name="username" id="username" value="$_SESSION[login]">
<input type="hidden" name="case_id" id="case_id" value="$id">
<input type="hidden" name="folder" id="folder" value="">
<a href="#" title="Submit URL" alt="Submit URL" onClick="var x = isTitle($('title').value);if (x == true){new Ajax.Updater('status','document_url.php',{method:'POST',parameters:Form.serialize('url_submit'),onComplete: function(){new Ajax.Updater('nofolder_docs','documents_list_refresh.php?id=$id');	Effect.Fade('status',{duration:3.0});Effect.BlindUp('url_menu');$('url_submit').reset();}})};return false;"><img src="images/check_yellow_small.png" border="0"></a>
<a href="#" title="Cancel" alt="Cancel" onClick="Effect.BlindUp('url_menu');$('url_submit').reset();return false;"><img src="images/cancel_small.png" border="0"></a>
</form>

</div>

<script>
var button = $('upload_button'),docs = $('nofolder_docs'), interval,statusw = $('status');

new AjaxUpload('upload_button', {action: 'document_upload.php', name:'docfile',data :{'case_id' : '$id', 'folder' : '$folder'},autoSubmit: true,onSubmit : function(file, ext){

			// change button text, when user selects file

			statusw.style.display = 'inline';
			statusw.update('Uploading ' + file);

			// If you want to allow uploading only 1 file at time,
			// you can disable upload button
			//this.disable();

			// Animating upload button
			// Uploding -> Uploading. -> Uploading...
			interval = window.setInterval(function(){
				var text = statusw.innerHTML;

				if (text.length < 100){
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
				//Effect.Fade(statusw,{duration:6.0})
				return false;

			}
			else
			{

			window.clearInterval(interval);

			// enable upload button
			//this.enable();

			statusw.innerHTML = 'Upload successful.';
			// add file to the list


				new Ajax.Updater('nofolder_docs','documents_list_refresh.php?id=$id');
				Effect.Fade(statusw,{duration:3.0});
		}}

});
</script>



UPLOADMENU;

ECHO <<<UPLOAD

<div id = "folders" style="width:100%">



UPLOAD;
$get_folders = mysql_query("SELECT DISTINCT `folder` FROM `cm_documents` WHERE `case_id` = '$id' AND `folder` != '' ");
while ($r = mysql_fetch_array($get_folders))
{
$folder_name = $r['folder'];

echo <<<FOLDERLOOP
<div class = "folder_x" id= "$folder_name"><a href="#" alt="
Click to Open Folder" title="Click to Open Folder" onClick="killDroppables();new Ajax.Updater('case_activity','cm_folder_inside.php?case_id=$id&folder=$folder_name',{evalScripts:true,method:'get'});return false;"><img src="images/folder_small.png" border="0" onLoad="Droppables.add('$folder_name', {onDrop:function(element,dropon){\$(element.id).style.display='none';createTargets(element.id,element.id);sendDataGet('put_in_folder.php?id=' + element.id + '&folder=' + dropon.id);new Ajax.Updater('nofolder_docs','documents_list_refresh.php?id=$id');}});" ></a><br><a class="folder" href="#" onClick="Effect.Appear('folder_options_$folder_name');" alt="Click for Folder Options" title="Click for Folder Options">$folder_name</a>

<div id = "folder_options_$folder_name" style="position:absolute;left:10px;z-index:3100;width:300px;height:220px;background-color:rgb(255, 255, 204);border:1px solid black;font-size:10pt;text-align:center;display:none;">
<div style="text-align:right;"><a href="#" onClick="Effect.Fade('folder_options_$folder_name');"><img src="images/cancel_small.png" border="0"></a></div>

<ul>
<li><a href="#" onClick="var ask = confirm('This will delete the folder and all files in it.  Proceed?');if (ask == true){killDroppables();createTargets('folder_options_$folder_name','folder_options_$folder_name');sendDataGet('document_delete_folder_and_contents.php?folder_name=$folder_name&case_id=$id');
}else{return false;}">Delete Folder and Contents</a></li>
<li><a href="#" onClick="var ask = confirm('This will delete the folder but keep the files in the main directory.  Proceed?');if (ask == true){killDroppables();createTargets('folder_options_$folder_name','folder_options_$folder_name');sendDataGet('document_delete_folder_keep_contents.php?folder_name=$folder_name&case_id=$id');
}else{return false;}">Delete Folder But Keep Contents</a></li>
<li>Rename Folder</li>
<ul>
<input type="text" id="new_folder_name_$folder_name" >
<input type="button" value="Go" onClick="killDroppables();createTargets('folder_options_$folder_name','folder_options_$folder_name');sendDataGet('document_rename_folder.php?case_id=$id&folder_name=$folder_name&new_folder_name=' + document.getElementById('new_folder_name_$folder_name').value);return false;">

</div>
</div>
FOLDERLOOP;


}


ECHO <<<UPLOAD
<div class="folder_x" id="new_folder" style="display:none;"></div>
</div>

<div id="nofolder_docs" >
UPLOAD;
$get_docs= mysql_query("SELECT * FROM `cm_documents` WHERE `case_id` = '$id' AND `folder` = '' AND `name` != '' ORDER BY `date_modified` DESC");
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
<div id="$d[id]" style="position:relative;width:350px;height:40px;overflow:hide;clear:both;"><img src="$icon" border="0" onLoad="new Draggable('$d[id]');this.style.cursor='move';" onMouseOver="$('doc_info_$d[id]').show();" onMouseOut="$('doc_info_$d[id]').hide();"><a href="$clean_url" target="_new">$d[name]</a>

<div id="doc_info_$d[id]" style="position:absolute;right:0px;top:30px;width:250px;height:100px;display:none;background-color:rgb(255, 255, 204);border:1px solid black;font-size:10pt;z-index:10000;overflow:hidden;">Name: <b>$d[name]</b><br>Uploaded: <b>
SINGLEDOCS;


formatDate($d[date_modified]);
ECHO <<<SINGLEDOCS
</b><br>By: <b>$d[username]</b></div> </div>
SINGLEDOCS;

}
ECHO <<<UPLOAD

</div>


UPLOAD;
?>
