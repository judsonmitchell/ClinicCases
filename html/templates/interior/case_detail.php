<?php $id = $_GET['id'];require('../../../lib/php/data/case_detail_load_data.php');  ?>

<div class = "case_detail_bar">
	<h3><?php echo $case_data->first_name . " " . $case_data->last_name; ?></h3>
	
	<div class="assigned_people">
		
		<ul>
		
		<?php foreach ($assigned_users_data as $user)
		{
			$thumbnail = thumbify($user->picture_url);
			echo "<li><span><img id='imgid_" . $case_data->id . "_" . $user->id  . "' src='$thumbnail'></span></li>";
		}
		
		if ($_SESSION['permissions']['assign_cases'] = "1")
		{ echo "<li><span><button></button></span></li>";}
		?>
		
		</ul>
		
	</div>
	
</div>

<div class = "case_detail_nav">

	<ul class = "case_detail_nav_list">
	
		<li id="item1" class="selected">Case Notes</li>
		
		<li id="item2">Documents</li>
		
		<li id="item3">Events</li>
		
		<li id="item4">Client Data</li>
		
		<li id="item5">Memos</li>

		<li id="item6">Conflict</li>

	</ul>




</div>

<div class = "case_detail_panel">
		
		<div class="ui-overlay user_widget">
						
			<div id="user_display_border" class="ui-widget-shadow ui-corner-all"></div>
		
		</div>
				
		<div id="user_display" class="ui-widget ui-widget-content ui-corner-all user_widget">
			
			<?php foreach ($assigned_users_data as $user)
			{
				echo "<div class='user_display_detail' id='user_box_" . $case_data->id . "_" .$user->id . "'>";
				echo "<h3>" . $user->first_name . " " . $user->last_name ."</h3>";
				echo "<div class='user_display_closer'></div>";
				echo "<img src='" . $user->picture_url . "'>"; 
				echo "<p>Total Time on this Case: ";
				$time_check = array_searchRecursive($user->username,$case_time_data);
					if ($time_check)
					{foreach ($case_time_data as $ttime)
						{
							if ($ttime['username'] == $user->username)
							{	
								$total = convert_case_time($ttime['totaltime']);
							
								echo $total[0] . $total[1];
													
							}
							
						}}
					else
					{	
						echo "0 minutes";
					}	
				echo "</p>";
				$grp = get_group_title($user->group,$dbh);
				echo "<p>Group: "  . $grp  . "</p>";
				echo "<p>Last Activity:";
				$activity_check = array_searchRecursive($user->username,$last_activity_data);
					if ($activity_check)
					{foreach ($last_activity_data as $activity)
						{
								if($activity['username'] == $user->username)
								{echo $activity['date'] . ": " . $activity['description'];}
							
						}
					}
					else
					{
						echo "No activity";					
					}
				echo "</p>";
				
				if ($_SESSION['permissions']['assign_cases'] == '1')
				{
				 echo "<div id='dialog-confirm' title='Remove $user->first_name from this case?'><p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>$user->first_name will no longer be able to work on this case.  Are you sure?</p></div>";
					echo "<input type='hidden' class='RemoveImgId' value='imgid_$case_data->id". "_" .  $user->id . "'>";
					echo "<input type='hidden' class='RemoveId' value='$user->assign_id'>";
					echo "<button>Remove</button>";
				
				
				
				}
				echo "</div>";
				
			}
			?>
			
		</div>
	panel
	
</div>
