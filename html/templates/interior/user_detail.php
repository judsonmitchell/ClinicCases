<?php extract($user); if ($view === 'display'){?>

<div class = "user_detail_control">

	<p class="top_row">

		<img class="thumbnail-mask"  src="<?php echo return_thumbnail($dbh,$username) . '?' . rand(); ?>">

		<span class="name_display"><?php echo htmlspecialchars($first_name ,ENT_QUOTES,'UTF-8'). " " . htmlspecialchars($last_name,ENT_QUOTES,'UTF-8'); ?></span>

	</p>

	<button>Close</button>

</div>

<span class="user_data_display_area" data-id = "<?php echo $id; ?>">

<div class = "user_detail_left">

	<p><label>First Name</label><?php echo htmlspecialchars($first_name,ENT_QUOTES,'UTF-8'); ?></p>

	<p><label>Last Name</label><?php echo htmlspecialchars($last_name,ENT_QUOTES,'UTF-8'); ?></p>

	<p><label>Email</label><a href="mailto:<?php echo htmlspecialchars($email,ENT_QUOTES,'UTF-8'); ?>" target = "_new"><?php echo htmlspecialchars($email,ENT_QUOTES,'UTF-8'); ?> </a> </p>

	<p><label>Mobile Phone</label><?php echo htmlspecialchars($mobile_phone,ENT_QUOTES,'UTF-8'); ?></p>

	<p><label>Office Phone</label><?php echo htmlspecialchars($office_phone,ENT_QUOTES,'UTF-8'); ?></p>

	<p><label>Home Phone</label><?php echo htmlspecialchars($home_phone,ENT_QUOTES,'UTF-8'); ?></p>

	<p><label>Group</label><?php echo array_search($grp, $group_name_data); ?></p>

	<p><label>Username</label><?php echo $username; ?></p>

	<p><label>Supervisors</label><?php if ($supervisors){$sups = explode(',', $supervisors); $sup_names = array();foreach($sups as $sup){$sup_names[] = array_search($sup, $supervisor_name_data);} echo rtrim(implode(', ', $sup_names),', ');} ?></p>

	<p><label>Status</label><?php echo htmlspecialchars($status,ENT_QUOTES,'UTF-8'); ?></p>

	<p><label>Date Created</label><?php echo extract_date_time($date_created); ?></p>

	<p><label>Last Login</label><?php
			$last_login = get_last_login($dbh,$username);
			if ($last_login){echo extract_date_time($last_login);}else{echo "Never";} ?></p>

	<p><label>Last Case Activity</label><?php echo get_last_case_activity($dbh,$username);?></p>


</div>

<div class = "user_detail_right">

	<p><label>Photo</label>
		<p><img src="<?php echo $picture_url?>" border="0"></p>

	</p>

	<p><label>Active Cases</label>
			<?php $active_cases = get_active_cases($dbh,$username);echo count($active_cases); ?>
	</p>


	<div class="active_case_list">

	<?php
	if (!empty($active_cases))
		{
			$ac = null;

			foreach ($active_cases as $key => $value) {
			  $ac .= "<a href='index.php?i=Cases.php#cases/$key' target='_new'>" . htmlspecialchars($value ,ENT_QUOTES,'UTF-8'). "</a>" . ", ";
			}

			echo rtrim($ac,', ');
		}
		else
			{echo "This user is not currently assigned to any open cases.";}
	?>

	</div>

	<p><label>Total Hours</label>

		<?php $t = get_total_hours($dbh,$username); echo implode(' ', $t); ?>
	<p>

	<?php if ($_SESSION['permissions']['supervises'] == '1'){ ?>

	<p><label>Evaluations</label>

		<div class="eval_display">
			<?php echo nl2br(htmlentities($evals)); ?>
		</div>

	<?php } ?>


	<div class="user_detail_actions">

		<?php if ($_SESSION['permissions']['activate_users']  == '1'){ ?>
		<button class="reset_password">Reset Password</button>
        <?php } ?>

		<?php if ($_SESSION['permissions']['delete_users']  == '1'){ ?>

		<button class = "user_delete">Delete</button>

		<?php } ?>

		<?php if ($_SESSION['permissions']['edit_users']  == '1'){ ?>

		<button class = "user_edit">Edit</button>

		<?php } ?>

	</div>

</div>

</span>
<?php } else { //this is an edit ?>

<div class = "user_detail_control">

	<p class="top_row">

		<img class="thumbnail-mask" src="<?php echo return_thumbnail($dbh,$username)  . '?' . rand(); ?>">

		<span class="name_display"><?php echo htmlspecialchars($first_name ,ENT_QUOTES,'UTF-8'). " " . htmlspecialchars($last_name,ENT_QUOTES,'UTF-8'); ?></span>

	</p>

	<button>Close</button>

</div>

<span class="user_data_display_area" data-id = "<?php echo $id; ?>">

<form name="user_edit_form">

<div class = "user_detail_left">


	<p><label>First Name</label><input name = "first_name" type="text" value="<?php echo htmlspecialchars($first_name,ENT_QUOTES,'UTF-8'); ?>"></p>

	<p><label>Last Name</label><input name = "last_name" type="text" value="<?php echo htmlspecialchars($last_name,ENT_QUOTES,'UTF-8'); ?>"></p>

	<p><label>Email</label><input name = "email" type="text" value="<?php echo htmlspecialchars($email,ENT_QUOTES,'UTF-8'); ?>"></p>

	<p><label>Mobile Phone</label><input name = "mobile_phone" type="text" value="<?php echo htmlspecialchars($mobile_phone,ENT_QUOTES,'UTF-8'); ?>"></p>

	<p><label>Office Phone</label><input name = "office_phone"  type="text" value="<?php echo htmlspecialchars($office_phone,ENT_QUOTES,'UTF-8'); ?>"></p>

	<p><label>Home Phone</label><input name = "home_phone" type="text" value="<?php echo htmlspecialchars($home_phone,ENT_QUOTES,'UTF-8'); ?>"></p>

	<p><label>Group</label>

		<select name="grp" class="group_chooser" data-placeholder="Please Select">

			<option selected=selected></option>

			<?php echo group_select($dbh,$grp) ?>

		</select>
	</p>

	<p><label>Supervisors</label>

		<select multiple name="supervisors" class = "supervisor_chooser" data-placeholder="None">

			<?php echo supervisors_select($supervisors,$supervisor_name_data);  ?>

		</select>
	</p>


	<p><label>Status</label>

		<select name="status" class="status_chooser" data-placeholder = "Please Select">

			<?php echo status_select($status); ?>

		</select>


	</p>

	<input type="hidden" name="id" value="<?php echo $id; ?>">

	<!--  Value is set to yes only when a new user is being created from the new_account page.
		  Yes triggers the alert to admins to check the user.  Here, the admin is creating
		  account, so it's not necessary.
	-->
	<input type="hidden" name="new" value="<?php echo $new ;?>">

	<input type="hidden" name="action" value="<?php echo $view; ?>">


</div>

<div class = "user_detail_right">

	<div class="user_picture"><img src = "<?php echo $picture_url  . '?' . rand();?>"></div>

	<div class="user_change_picture">Change picture</div>

	<?php if ($_SESSION['permissions']['supervises'] == '1'){

		$supervisees = all_users_by_supvsr($dbh,$_SESSION['login']);

		if (in_array($username, $supervisees)){ ?>

			<div class="user_eval">

				<label>Evaluations</label> <br />

				<textarea name="evals" class="eval_block"><?php echo htmlspecialchars($evals,ENT_QUOTES,'UTF-8'); ?></textarea>

			</div>

	<?php }} ?>


	<div class="user_detail_edit_actions">

		<button>Cancel</button>

		<button>Submit</button>

	</div>

</div>

</form>


</span>

<?php } ?>
