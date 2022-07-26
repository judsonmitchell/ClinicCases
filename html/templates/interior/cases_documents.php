<?php
if (!isset($update)) {
	$radio_name = rand();
	$grid_id = rand();
	$list_id = rand();

?>

	<div class="user_display ui-widget ui-corner-bottom user_widget" tabindex="1">

	</div>
	<div class="case_documents_toolbar">

		<div class="form__control">
			<input id="caseDocumentsSearch-<?php echo '' ?>" type="text" class="documents_search" placeholder=" ">
			<label for="caseDocumentsSearch-<?php echo '' ?>">Search Titles</label>
		</div>
		<div class="case_documents_toolbar--right">
			<button class="button--secondary">
				<img src="html/ico/new-folder.png" alt="New Folder Icon" /> <span>&nbsp;New Folder</span>
			</button>
			<button class="button--secondary">
				<img src="html/ico/new-document.png" alt="New Document Icon" /> <span>&nbsp;New Document</span>
			</button>
			<button class="button--secondary">
				<img src="html/ico/upload.png" alt="Upload Icon" /> <span>&nbsp;Upload</span>
			</button>
			<?php
			if (isset($list) && $list == "yes") { ?>
				<div class="documents_view_chooser list">
					<div class="documents_view_chooser--grid">
						<img src="html/ico/grid-unselected.png" alt="">
						<p>Grid</p>
					</div>
					<div class="documents_view_chooser--list">
						<img src="html/ico/list-selected.png" alt="">
						<p>List</p>
					</div>
				</div>
			<?php } else { ?>
				<div class="documents_view_chooser grid">

					<div class="documents_view_chooser--grid">
						<img src="html/ico/grid-selected.png" alt="">
						<p>Grid</p>

					</div>
					<div class="documents_view_chooser--list">
						<img src="html/ico/list-unselected.png" alt="">
						<p>List</p>

					</div>
				</div>
		</div>

	<?php } ?>
	</div>


	</div>


	<div class="case_documents_submenu">
		<a href="#" class="doc_trail_home">Home</a>>
		<span class="path_display" path=""><a href="#" class="doc_trail_item active" path=""></a></span>
	</div>
	<div class="case_detail_panel_casenotes">


	<?php
}
	?>

	<?php

	foreach ($folders as $folder) {
		if (strrchr($folder['folder'], '/')) {
			$folder_name = substr(strrchr($folder['folder'], '/'), 1);
		} else {
			$folder_name = $folder['folder'];
		}

		$folder_path = $folder['folder'];

		$user = username_to_fullname($dbh, $folder['username']);

		$date = extract_date_time($folder['date_modified']);
		echo "<div class='doc_item folder' data-caseid='$folder[case_id]' data-path='$folder_path' data-id='$folder[id]'><img src='html/ico/folder.png'><p>" . htmlspecialchars(rawurldecode($folder_name), ENT_QUOTES, 'UTF-8') . "</p></div>";
		echo "<div class='doc_properties' tabindex='1'><h3><img src='html/ico/folder.png'>" . htmlspecialchars(rawurldecode($folder_name), ENT_QUOTES, 'UTF-8') . "</h3>
					<hr />
					<p><label>Type</label>    Folder</p>
					<p><label>Created:</label>     $date</p>
					<p><label>Created By:</label>     $user</p>
					</div>";
	}


	foreach ($documents as $document) {
		$icon = get_icon($document['type']);
		$user = username_to_fullname($dbh, $document['username']);
		$date = extract_date_time($document['date_modified']);


		echo "<div id='doc_$document[id]' data-caseid='$document[case_id]' data-path='$document[folder]' class='doc_item item $document[type]' data-id='$document[id]'><img src='$icon'><p>" . htmlspecialchars(rawurldecode($document['name']), ENT_QUOTES, 'UTF-8') . "</p></div>";
		echo "<div class='doc_properties' tabindex='1'><h3><img src='$icon'>" . htmlspecialchars(rawurldecode($document['name']), ENT_QUOTES, 'UTF-8') . "</h3>
			<hr />
			<p><label>Type</label>     $document[type]</p>
			<p><label>Uploaded:</label>     $date</p>
			<p><label>Uploaded By:</label>     $user</p>
			</div>";
	}

	if (empty($folders) and empty($documents)) {
		echo "<span class='docs_empty'>No documents found.</a>";
	}

	echo "<div class='doc_spacing_fix'></div>";

	?>


	<?php if (!isset($update)) {
		echo "</div>";
	}
	?>