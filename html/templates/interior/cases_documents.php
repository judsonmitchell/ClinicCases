<div class="user_display ui-widget ui-widget-content ui-corner-bottom user_widget">

</div>

<div class="case_detail_panel_tools">

	<div class="case_detail_panel_tools_left"></div>

	<div class="case_detail_panel_tools_right">

		<?php if ($_SESSION['permissions']['documents_modify'] == '1')
		{
			echo "<button class='doc_new_folder'>New Folder</button>";
		}

		if ($_SESSION['permissions']['documents_upload'])
		{
			echo "<button class='doc_upload'>Upload</button>";
		}

		?>
	</div>

</div>

<div class = "case_detail_panel_casenotes">

	<?php

	foreach ($folders as $folder)

		{
			echo "<div class='doc_item'><img src='html/ico/folder.png'><p>$folder[folder]</p></div>";
		}


	foreach ($documents as $document)

		{
			$icon = get_icon($document['type']);
			echo "<div class='doc_itme'><img src='$icon'><p>$document[name]</p></div>";
		}

	?>

</div>