<?php //tools are only called if this is a first request; otherwise we only need the new case notes data
if (!isset($_POST['update']))
{echo<<<TOOLS

		<div class="user_display ui-widget ui-corner-bottom user_widget" tabindex="1">

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

				<button class = "button3">Print All</button>

			</div>

		</div>

		<div class="print_content case_detail_panel_casenotes case_
TOOLS;
echo $case_notes_data[0]['case_id'] . "\">";
}
//new note form to be hidden

			$this_thumb = thumbify($_SESSION['picture_url']);
			$this_date = date('n/j/Y');
			$this_fname = $_SESSION['first_name'];
			$this_lname = $_SESSION['last_name'];
			$selector = generate_time_selector();
			$this_case_id = $case_id;
			$this_user = $_SESSION['login'];

echo <<<NEWNOTE
<div class='csenote csenote_new'>
			<form>
			<div class='csenote_bar'>
				<div class = 'csenote_bar_left'><img src='$this_thumb'> $this_fname $this_lname</div>
				<div class = 'csenote_bar_right'>
				<label>Date:</label> <input type='hidden' name='csenote_date' class='csenote_date_value' value='$this_date'> $selector
				<input type='hidden' name='csenote_user' value='$this_user'>
				<input type='hidden' name='csenote_case_id' value='$this_case_id'>
				<input type='hidden' name='query_type' value='add'>
				<button class='csenote_action_submit'>Add</button><button class='csenote_action_cancel'>Cancel</button></div>
			</div>
			<textarea name='csenote_description'></textarea>
			</form>
			</div>

NEWNOTE;

			//show all case notes

			foreach($case_notes_data as $case_notes)
			{

				$time = convert_case_time($case_notes['time']);
				echo "<div class='csenote' id='csenote_" . $case_notes['id'] . "'>
				<div class='csenote_bar'>
				<div class = 'csenote_bar_left'><img class='thumbnail-mask' src='" . thumbify($case_notes['picture_url']) . "'>&nbsp " . username_to_fullname($dbh,$case_notes['username']). "</div><div class = 'csenote_bar_right'><span class='csenote_date'>" . extract_date($case_notes['date']) .  "</span> &#183; <span class='csenote_time'>" . $time[0] . $time[1] . "</span>";

				if ($case_notes['username'] == $_SESSION['login']) {
                    echo "&nbsp;&nbsp;&nbsp; <a href='#' class='csenote_edit'>Edit</a>&nbsp;&nbsp;<a href='#' class='csenote_delete'>Delete</a>";
                }
                echo "&nbsp;&nbsp;<a href='#' class='csenote_print'>Print</a>";
				echo "</div></div><p class='csenote_instance'>"    . nl2br(htmlentities($case_notes['description'])) . "</p></div>";

			}

			if (empty($case_notes_data))
				{
					if (isset($search))
						{echo "<p>No case notes found matching <i>$search</i></p>";}
					else
						{echo "<p>No case notes found</p>";die;}
				}

		if (!isset($_POST['update'])){echo "</div>";}

