<?php

if (isset($search)){
    echo "Search Results for $search";
}


?>

<?php if (!isset($update)){echo <<<TOOLS

<div class="user_display ui-widget ui-corner-bottom user_widget" tabindex="1">

</div>

<div class="case_detail_panel_tools">

	<div class="case_detail_panel_tools_left">
        <div class="documents_view_chooser">
            <input type="radio" id="radio_grid" class="radio_toggle_grid" name="radio" checked="checked"><label for="radio_grid">Grid</label>
            <input type="radio" id="radio_list" class="radio_toggle_list" name="radio"><label for="radio_list">List</label>
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

<table id="doc_list">
    <thead>
    <tr><td></td><td>Name</td><td>Folder</td><td>Date</td><td>By</td></tr>
    </thead>
    <tbody>
<?php


    foreach ($folders as $folder){
        $user = username_to_fullname($dbh,$folder['username']);
        $date = extract_date_time($folder['date_modified']);
        if (strrchr($folder['folder'],'/')) {
            $folder_name = substr(strrchr($folder['folder'],'/'),1);
        } else {
            $folder_name = $folder['folder'];
        }
        $folder_path = $folder['folder'];
?>

        <tr class="doc_item folder" path="<?php echo $folder_path; ?>" data-id="<?php echo $folder['id']; ?>">
        <td width="10%"><img src="html/ico/folder.png"></td>
        <td><?php echo htmlspecialchars(rawurldecode($folder_name), ENT_QUOTES,'UTF-8') ?> </td>
        <td><?php echo $folder['folder']; ?></td>
        <td><?php echo $date; ?></td>
        <td><?php echo $user; ?></td></tr>

<?php
    }
    foreach ($documents as $document){
        $icon = get_icon($document['type']);
        $user = username_to_fullname($dbh,$document['username']);
        $date = extract_date_time($document['date_modified']);
?>
        <tr id="doc_<?php echo $document['id']; ?>" class="doc_item item <?php echo $document['type']; ?>" data-id="<?php echo $document['id']; ?>">
        <td><img src="<?php echo $icon; ?>"></td>
        <td><?php echo htmlspecialchars(rawurldecode($document[name]), ENT_QUOTES,'UTF-8') ?> </td>
        <td><?php echo $document['folder']; ?></td>
        <td><?php echo $date; ?></td>
        <td><?php echo $user; ?></td></tr>

<?php

    }
?>

    </tbody>
</table>
</div>
