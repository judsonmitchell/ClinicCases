<?php //tools are only called if this is a first request; otherwise we only need the new case notes data
if (!isset($_POST['update']))
{echo<<<TOOLS
		
		<div class="user_display ui-widget ui-widget-content ui-corner-bottom user_widget">
						
		</div>
	
		<div class="case_detail_panel_tools">
		
			<div class="case_detail_panel_tools_left"></div>
			
			<div class="case_detail_panel_tools_right">
			
				<button id = "button1">Add</button>
				
				<button id = "button2">Timer</button>

				<button id = "button 3">Print</button>

			</div>

		</div>
		
		<div class="case_detail_panel_casenotes" id = "case_
TOOLS;
echo $case_notes_data[0]['case_id'] . "\">";

}		
			
		
			foreach($case_notes_data as $case_notes)
			{
				$time = convert_case_time($case_notes['time']);
				echo "<div class='csenote'>";
				echo "<p class = 'csenote_instance'><img src='" . thumbify($case_notes['picture_url']) . "'> " . username_to_fullname($dbh,$case_notes['username']). " " . extract_date($case_notes['datestamp']) .  " " . $time[0] . $time[1]    . "<br><p>"    . $case_notes['description'] . "</p></p></div><br>";
				
			}
			
			if (empty($case_notes_data))
				{echo "<p>No case notes found.</p>";}
			
		
		
			
		
		
		if (!isset($_POST['update'])){echo "</div>";}

