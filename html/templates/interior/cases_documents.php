<?php if (!isset($update)){echo <<<TOOLS

<div class="user_display ui-widget ui-corner-bottom user_widget" tabindex="1">

</div>

<div class="case_detail_panel_tools">

	<div class="case_detail_panel_tools_left">
        <div class="documents_view_chooser">
TOOLS;
            if ($_COOKIE['cc_doc_view'] == 'grid'){ ?>
                <input type="radio" id="radio_grid" class="radio_toggle_grid" name="radio" checked="checked"><label for="radio_grid">Grid</label>
                <input type="radio" id="radio_list" class="radio_toggle_list" name="radio"><label for="radio_list">List</label>
            <?php } else { ?>
                <input type="radio" id="radio_grid" class="radio_toggle_grid" name="radio" ><label for="radio_grid">Grid</label>
                <input type="radio" id="radio_list" class="radio_toggle_list" name="radio"checked="checked"><label for="radio_list">List</label>
            <?php } 
        echo <<<TOOLS
        </div>
    </div>

	<div class="case_detail_panel_tools_right">
		<input type="text" class="documents_search" value="Search Titles">

		<input type="button" class="documents_search_clear">
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

<div class="case_documents_submenu">
    <img src="html/ico/house.png"> <a href="#" class="doc_trail_home">Home</a>/
    <span class="path_display" path=""><a href="#" class="doc_trail_item active" path=""></a></span>
</div>
<div class = "case_detail_panel_casenotes">


TOOLS;
}
?>



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

			echo "<div class='doc_item folder' path='$folder_path' data-id='$folder[id]'><img src='html/ico/folder.png'><p>" . htmlspecialchars(rawurldecode($folder_name) ,ENT_QUOTES,'UTF-8'). "</p></div>";
			echo "<div class='doc_properties' tabindex='1'><h3><img src='html/ico/folder.png'>" . htmlspecialchars(rawurldecode($folder_name) ,ENT_QUOTES,'UTF-8'). "</h3>
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

			echo "<div id='doc_$document[id]' class='doc_item item $document[type]' data-id='$document[id]'><img src='$icon'><p>" . htmlspecialchars(rawurldecode($document[name]), ENT_QUOTES,'UTF-8') . "</p></div>";
			echo "<div class='doc_properties' tabindex='1'><h3><img src='$icon'>" . htmlspecialchars(rawurldecode($document[name]), ENT_QUOTES,'UTF-8') . "</h3>
			<hr />
			<p><label>Type</label>     $document[type]</p>
			<p><label>Uploaded:</label>     $date</p>
			<p><label>Uploaded By:</label>     $user</p>
			</div>";
		}

	if (empty($folders) AND empty($documents))
		{echo "<span class='docs_empty'>No documents found.</a>";}

	echo "<div class='doc_spacing_fix'></div>";

	?>


<?php if (!isset($update))
		{ echo "</div>";}
?>
