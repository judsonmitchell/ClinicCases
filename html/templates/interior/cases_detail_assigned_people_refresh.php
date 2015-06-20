
			<li class="slide closed"><a>Assigned:</a></label></li>
	<?php

	foreach ($refresh_users_data as $user)

		{
			$thumbnail = thumbify($user->picture_url);

			if ($user->user_case_status == "active")
			{
			echo "<li class = 'active'><span><img class='thumbnail-mask' tabindex='0' id='imgid_" . $user->case_id . "_" . $user->username  . "' src='$thumbnail' title='$user->first_name $user->last_name'></span></li>";
			}

			else

			{
			echo "<li class = 'inactive'><span><img class='thumbnail-mask' tabindex='0' id='imgid_" . $user->case_id . "_" . $user->username  . "' src='$thumbnail' title='$user->first_name $user->last_name'></span></li>";
			}

			$case_id = $user->case_id;

		}

		if ($_SESSION['permissions']['assign_cases'] = "1")
		{ echo "<li><span></span><img  class='thumbnail-mask user_add_button' id='add_button_" . $case_id . "' src='people/tn_add_user.png'></span></li>";}
		?>
