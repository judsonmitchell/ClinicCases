<?php extract($user); ?>

<div class = "pref pref_profile">

	<p class="pref_error" id = "profile_error"></p>

	<form id = "profile_form">

		<p><label>First Name</label><input type="text" name="first_name" value="<?php echo $first_name;?>"></p>

		<p><label>Last Name</label><input type="text" name="last_name" value="<?php echo $last_name;?>"></p>

		<p><label>Mobile Phone</label><input type="text" name="mobile_phone" value="<?php echo $mobile_phone?>"></p>

		<p><label>Home Phone</label><input type="text" name="home_phone" value="<?php echo $home_phone; ?>"></p>

		<p><label>Office Phone</label><input type="text" name="office_phone" value="<?php echo $office_phone ?>"></p>

		<p><label>Email</label><input type="text" name="email" value="<?php echo $email; ?>"></p>

		<input type="hidden" name="id" value="<?php echo $id; ?>">

		<p class="pref_submit"><button class = "profile_form_submit">Save Changes</button></p>

	</form>


</div>

<div class = "pref pref_change_pword">

	<p class="pref_error" id = "pword_error"></p>

	<form id = "change_pword">

		<p><label>Current Password</label><input type="password" name="current_pword"></p>

		<p><label>New Password</label><input type="password" name="new_pword"></p>

		<p><label>Confirm New Password</label><input type="password" name="new_pword_confirm"></p>

		<input type="hidden" name="id" value="<?php echo $id; ?>">

		<p class="pref_submit"><button class = "change_pword_form_submit">Change Password</button></p>

	</form>


</div>

<div class = "pref pref_change_picture">

	<p>This feature is not yet implemented.  Please <a href="mailto:<?php echo CC_ADMIN_EMAIL;?>" target="_new" title = "Email your administrator">ask your administrator</a> to change your picture.</p>

</div>

<div class = "pref pref_private_key">

	<form id = "change_private_key">

		<p>This is your private key which is used for web-based services (Google Calendar, etc) to access your account information, e.g. calendar and RSS feeds. Do not share it with anyone.  If you suspect that your key has been compromised, please reset it.</p>

		<p>Your private key: <?php echo $private_key; ?></p>

		<p>Your ICal feed of calendar events: <a href = "<?php echo CC_BASE_URL . 'ical_feed.php?key=' . $private_key;?>" target="_new"><?php echo CC_BASE_URL . 'ical_feed.php?key=' . $private_key;?></a></p>

		<p>Your RSS feed of ClinicCases Activity: <a href = "<?php echo CC_BASE_URL . 'activity_feed.php?key=' . $private_key;?>" target="_new"><?php echo CC_BASE_URL . 'activity_feed.php?key=' . $private_key;?></a></p>

		<p class="pref_submit"><button class = "change_private_key_form_submit">Reset Key</button></p>


	</form>


</div>




