<?php //tools are only called if this is a first request; otherwise we only need the new case notes data
if (!isset($_POST['update']))
{echo<<<TOOLS

		<div class="user_display ui-widget ui-widget-content ui-corner-bottom user_widget">
						
		</div>
		
		<div class="case_detail_panel_tools">
		
			<div class="case_detail_panel_tools_left"></div>
			
			<div class="case_detail_panel_tools_right">
			
				<input type="text" class="casenotes_search" id="casesearch_
TOOLS;
echo $case_notes_data[0]['case_id'];
echo <<<TOOLS
" value="Search Case Notes">

				<input type="button" class="casenotes_search_clear">
TOOLS;

			if ($_SESSION['permissions']['add_case_notes'] == '1')
			{echo "<button class = \"button1\">Add</button>	
			<button class = \"button2\">Timer</button>";
			}
				
echo <<<TOOLS

				<button class = "button3">Print</button>

			</div>

		</div>
		
		<div class="case_detail_panel_casenotes case_
TOOLS;
echo $case_notes_data[0]['case_id'] . "\">";

}		
			//new note to be hidden
		
			$this_thumb = thumbify($_SESSION['picture_url']);
			$this_date = date('n/j/Y');
			$this_fname = $_SESSION['first_name'];
			$this_lname = $_SESSION['last_name'];

			echo "<div class='csenote csenote_new'>
			<div class='csenote_bar'><p class = 'csenote_instance'><img src='$this_thumb'> $this_fname $this_lname  <input class='cse_note_date' value='$this_date'></p></div><textarea></textarea></div>";
		
			foreach($case_notes_data as $case_notes)
			{
				$time = convert_case_time($case_notes['time']);
				echo "<div class='csenote'>";
				echo "<div class='csenote_bar'><p class = 'csenote_instance'><img src='" . thumbify($case_notes['picture_url']) . "'> " . username_to_fullname($dbh,$case_notes['username']). " " . extract_date($case_notes['datestamp']) .  " " . $time[0] . $time[1]    . "</div><p>"    . $case_notes['description'] . "</p></p></div>";
				
			}
			
			if (empty($case_notes_data))
				{echo "<p>No case notes found.</p>";}
			
		
		
			
		
		
		if (!isset($_POST['update'])){echo "</div>";}

