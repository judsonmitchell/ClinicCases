<p><a>Assigned To:</a></label></p>
<ul>
	<?php

	foreach ($refresh_users_data as $user) {
		$thumbnail = thumbify($user->picture_url);
		if ($user->user_case_status == "active") {
			echo "<li class = 'active'><span><img class='thumbnail-mask' tabindex='0' id='imgid_" . $user->case_id . "_" . $user->username  . "' src='$thumbnail' data-bs-toggle='tooltip' data-placement='top' title='$user->first_name $user->last_name'></span></li>";
		} else {
			echo "<li class = 'inactive'><span><img class='thumbnail-mask' tabindex='0' id='imgid_" . $user->case_id . "_" . $user->username  . "' src='$thumbnail' data-bs-toggle='tooltip' data-placement='top' title='$user->first_name $user->last_name'></span></li>";
		}

		$case_id = $user->case_id;
	}


	?>
</ul>
<?php

if ($_SESSION['permissions']['assign_cases'] = "1") {
	echo "<div><span></span><img  class='user_add_button' id='add_button_" . $case_id . "' src='html/ico/add-item.svg'></span></div>";
}
?>