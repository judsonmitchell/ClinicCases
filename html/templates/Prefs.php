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

<div class = "pref pref_private_key">

	<p class="pref_error" id = "private_key_error"></p>

	<form id = "change_private_key">

		<p>This is your private key which is used for web-based services to access your account information. Do not share it with anyone.  If you suspect that your key has been compromised, please reset it.</p>

		<p>Your private key: <?php echo $private_key; ?></p>

		<p style="word-wrap:break-word">Your ICal feed of calendar events: <a href = "<?php echo CC_BASE_URL . 'feeds/ical.php?key=' . $private_key;?>" target="_new"><?php echo CC_BASE_URL . 'feeds/ical.php?key=' . $private_key;?></a></p>

		<p style="word-wrap:break-word">Your RSS feed of ClinicCases Activity: <a href = "<?php echo CC_BASE_URL . 'feeds/rss.php?type=activities&key=' . $private_key;?>" target="_new"><?php echo CC_BASE_URL . 'feeds/activity_feed.php?type=activities&key=' . $private_key;?></a></p>

		<p class="pref_submit"><button class = "change_private_key_form_submit" data-id="<?php echo $id; ?>">Reset Key</button></p>


	</form>


</div>




