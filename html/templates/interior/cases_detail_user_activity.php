<div tabindex="2" class="user_display_detail <?php if ($data['case_status'] == "inactive"){echo "inactive_user";} ?>" id="user_box_<?php echo $data['id'] . "_" .$data['username']; ?>">

	<h3 style="text-align:center"  >

		<?php echo $data['first_name'] . " " . $data['last_name']; ?>

	</h3>

	<p><label>Total Time on this Case:</label></p>

	<p>

	<?php

		if (isset($data['totaltime']))

			{
				$total = convert_case_time($data['totaltime']);

				echo $total[0] . $total[1];
			}

				else

					{
						echo "0 minutes";
					}
	?>

	</p>

	<p><label>Group:</label></p>

	<p><?php $grp = get_group_title($data['grp'],$dbh); echo $grp ?> </p>

	<p><label>Date Assigned:</label></p>

	<p><?php $date_assigned = extract_date($data['date_assigned']); echo $date_assigned; ?></p>

	<p><label>Last Activity:</label></p>

	<p>

	<?php

		if (isset($data['description']))

			{
				$date_clip = extract_date($data['date']);

				echo $date_clip . ": " . snippet('20',$data['description']);

			}

				else

					{
						echo "No activity";
					}

					?>
	</p>

	<?php

	if ($_SESSION['permissions']['assign_cases'] == '1')

		{
				if ($data['case_status'] == "inactive")
				{$txt = "will be reassigned to the case.";}
				else
				{$txt = "will no longer be able to see or to work on this case.";}


				echo "<div class='dialog-user-remove' title='Change " . $data['first_name'] . "&#39;s status?'>" . $data['first_name'] . " " . $txt .  " Are you sure?</div>";

				echo "<form id='form_" . $data['id'] . "'>";

				echo "<input type='hidden' class='RemoveImgId' value='imgid_" . $data['case_id'] . "_" . $data['username'] . "'>";

				echo "<input type='hidden' class='RemoveId' value='" . $data['assign_id'] ."'>";

				echo "</form>";

				echo "<button id='button_" . $data['assign_id'] . "' class='user-action-button'></button>";

		}

		?>

</div>



