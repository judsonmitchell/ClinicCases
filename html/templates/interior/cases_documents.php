<?php if (!isset($update)){echo <<<TOOLS

<div class="user_display ui-widget ui-widget-content ui-corner-bottom user_widget" tabindex="1">

</div>

<div class="case_detail_panel_tools">

	<div class="case_detail_panel_tools_left"><img src="html/ico/house.png"> <a href="#" class="doc_trail_home">Home</a>/<span class="path_display" path=""><a href="#" class="doc_trail_item active" path=""></a></span></div>

	<div class="case_detail_panel_tools_right">
TOOLS;

		if ($_SESSION['permissions']['documents_modify'] == '1')
		{
			echo "<button class='doc_new_doc'>New Document</button>";
			
			echo "<button class='doc_new_folder'>New Folder</button>";
		}

		if ($_SESSION['permissions']['documents_upload'])
		{
			echo "<button class='doc_upload'>Upload</button>";
		}

echo <<<TOOLS
	</div>

</div>

<div class = "case_detail_panel_casenotes">


TOOLS;
}
?>

	<div class = "upload_dialog">
			
		<div class = "upload_dialog_file" tabindex="1"></div>

		<div class = "upload_dialog_url" tabindex="2">
			<div class = "upload_url_button qq-upload-button">Web address</div>
			<div class = "upload_url_form">
				<label>URL</label><input type="text" class="url_upload"><br /><br />
				<label>Name</label><input type="text" class="url_upload_name">
				<button class="upload_url_submit">Submit</button>
			</div>

		</div>

	</div>


	<?php

	foreach ($folders as $folder)

		{
			if (strrchr($folder['folder'],'/'))
			{$folder_name = substr(strrchr($folder['folder'],'/'),1);}
			else
			{$folder_name = $folder['folder'];}

			$folder_path = $folder['folder'];

			$user = username_to_fullname($dbh,$folder['username']);

			$date = extract_date_time($folder['date_modified']);

			echo "<div class='doc_item folder' path='$folder_path' data-id='$folder[id]'><img src='html/ico/folder.png'><p>$folder_name</p></div>";
			echo "<div class='doc_properties' tabindex='1'><h3><img src='html/ico/folder.png'>$folder_name</h3>
					<hr />
					<p><label>Type</label>    Folder</p>
					<p><label>Created:</label>     $date</p>
					<p><label>Created By:</label>     $user</p>
					</div>";
		}


	foreach ($documents as $document)

		{
			$icon = get_icon($document['type']);
			$user = username_to_fullname($dbh,$document['username']);
			$date = extract_date_time($document['date_modified']);

			echo "<div id='doc_$document[id]' class='doc_item doc $document[type]' data-id='$document[id]'><img src='$icon'><p>$document[name]</p></div>";
			echo "<div class='doc_properties' tabindex='1'><h3><img src='$icon'>$document[name]</h3>
			<hr />
			<p><label>Type</label>     $document[type]</p>
			<p><label>Uploaded:</label>     $date</p>
			<p><label>Uploaded By:</label>     $user</p>
			</div>";
		}

	?>


<?php if (!isset($update))
		{ echo "</div>";}
?>