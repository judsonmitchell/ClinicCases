<div class = "case_detail_bar">

	<div class='case_title'>

		<h2>
		<?php

		if ($case_data->organization)
			{echo htmlspecialchars($case_data->organization, ENT_QUOTES, 'UTF-8');}
		else
			{echo htmlspecialchars($case_data->first_name, ENT_QUOTES, 'UTF-8'). " " . htmlspecialchars($case_data->last_name, ENT_QUOTES, 'UTF-8');}

		?>
		</h2>

	</div>

	<div class="assigned_people">

		<ul>

			<li class="slide closed"><a title="Click here to see history (all users ever on this case)">Assigned:</a></li>


		<?php if ($assigned_users_data){ foreach ($assigned_users_data as $user)
		{
			$thumbnail = thumbify($user['picture_url']);

			if ($user['user_case_status'] == "active")
			{
			echo "<li class = 'active'><span><img class='thumbnail-mask' tabindex='1' id='imgid_" . $user['case_id'] . "_" . $user['username']  . "' src='$thumbnail' title='" . htmlspecialchars($user['first_name'], ENT_QUOTES, 'UTF-8') . " " . htmlspecialchars($user['last_name'], ENT_QUOTES,'UTF-8') . "'></span></li>";
			}

			else

			{
			echo "<li class = 'inactive'><span><img  class='thumbnail-mask' tabindex='1' id='imgid_" . $user['case_id'] . "_" . $user['username']  . "' src='$thumbnail' title='" . htmlspecialchars($user['first_name'], ENT_QUOTES, 'UTF-8') . " " . htmlspecialchars($user['last_name'], ENT_QUOTES,'UTF-8')  . "'></span></li>";
			}


		}}

		if ($_SESSION['permissions']['assign_cases'] == "1")
		{ echo "<li><span></span><img class='thumbnail-mask user_add_button' id='add_button_" . $id . "' src='people/tn_add_user.png'></span></li>";}
		?>

		</ul>

	</div>

</div>

<div class = "case_detail_nav">

	<ul class = "case_detail_nav_list">

		<li id="item1" class="selected">Case Notes</li>

		<li id="item2">Case Data</li>

		<li id="item3">Documents</li>

		<li id="item4">Events</li>

		<li id="item5">Messages</li>

		<li id="item6">Contacts</li>

		<li id="item7">Conflicts <span class="conflicts_number"></span></li>

	</ul>

</div>

<div class = "case_detail_panel">




</div>
