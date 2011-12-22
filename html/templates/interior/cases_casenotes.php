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
		
		<div class="case_detail_panel_casenotes">
		
			<?php
		
			foreach($case_notes_data as $case_notes)
			{
				
				echo "<p>" . $case_notes['description'] . "</p><br>";
				
			}
			
			?>
		
			
		
		
		</div>

