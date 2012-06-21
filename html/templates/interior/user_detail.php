<?php extract($user); if ($view === 'display'){?>

<div class = "user_detail_control">

	<p class="top_row">

		<img src="<?php echo return_thumbnail($dbh,$username) . '?' . rand(); ?>">

		<span class="name_display"><?php echo $first_name . " " . $last_name; ?></span>

	</p>

	<button>Close</button>

</div>

<span class="user_data_display_area" data-id = "<?php echo $id; ?>">

<div class = "user_detail_left">

	<p><label>First Name</label><?php echo $first_name; ?></p>

	<p><label>Last Name</label><?php echo $last_name; ?></p>

	<p><label>Email</label><a href="mailto:<?php echo $email; ?>" target = "_new"><?php echo $email; ?> </a> </p>

	<p><label>Mobile Phone</label><?php echo $mobile_phone; ?></p>

	<p><label>Office Phone</label><?php echo $office_phone; ?></p>

	<p><label>Home Phone</label><?php echo $home_phone; ?></p>

	<p><label>Group</label><?php echo array_search($grp, $group_name_data); ?></p>

	<p><label>Username</label><?php echo $username; ?></p>

	<p><label>Supervisors</label>
		<?php if ($supervisors){$sups = explode(',', $supervisors); $sup_names = array();foreach($sups as $sup){$sup_names[] = array_search($sup, $supervisor_name_data);} echo rtrim(implode(', ', $sup_names),', ');} ?>
	</p>

	<p><label>Status</label><?php echo $status; ?></p>

	<p><label>Date Created</label><?php echo extract_date_time($date_created); ?></p>

	<p><label>Last Login</label><?php echo extract_date_time(get_last_login($dbh,$username)) ?></p>

	<p><label>Last Case Activity</label><?php echo get_last_case_activity($dbh,$username);?></p>

</div>

<div class = "user_detail_right">

	<p><label>Active Cases</label>
			<?php $active_cases = get_active_cases($dbh,$username);echo count($active_cases); ?>
	</p>

	<p class ="active_case_list">

		<div class="active_case_list">

		<?php
		if (!empty($active_cases))
			{
				$ac = null;

				foreach ($active_cases as $key => $value) {
				  $ac .= "<a href='index.php?i=Cases.php#cases/$key' target='_new'>$value</a>" . "<br />";
				}

				echo $ac;
			}
			else
				{echo "This user is not currently assigned to any open cases.";}
		?>

		</div>


	</p>

	<p><label>Total Hours</label>

		<?php $t = get_total_hours($dbh,$username); echo implode(' ', $t); ?>
	<p>


	<div class="user_detail_actions">

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

		<img src="<?php echo return_thumbnail($dbh,$username)  . '?' . rand(); ?>">

		<span class="name_display"><?php echo $first_name . " " . $last_name; ?></span>

	</p>

	<button>Close</button>

</div>

<span class="user_data_display_area" data-id = "<?php echo $id; ?>">

<div class = "user_detail_left">

	<form>

	<p><label>First Name</label><input name = "first_name" type="text" value="<?php echo $first_name; ?>"></p>

	<p><label>Last Name</label><input name = "last_name" type="text" value="<?php echo $last_name; ?>"></p>

	<p><label>Email</label><input name = "email" type="text" value="<?php echo $email; ?>"></p>

	<p><label>Mobile Phone</label><input name = "mobile_phone" type="text" value="<?php echo $mobile_phone; ?>"></p>

	<p><label>Office Phone</label><input name = "office_phone"  type="text" value="<?php echo $office_phone; ?>"></p>

	<p><label>Home Phone</label><input name = "home_phone" type="text" value="<?php echo $home_phone; ?>"></p>

	<p><label>Group</label>

		<select name="grp" class="group_chooser" data-placeholder="None">

			<?php echo group_select($dbh,$grp) ?>

		</select>
	</p>

	<p><label>Supervisors</label>

		<select multiple name="supervisors" class = "supervisor_chooser" data-placeholder="None">

			<?php echo supervisors_select($supervisors,$supervisor_name_data);  ?>

		</select>
	</p>


	<p><label>Status</label>

		<select name="status" class="status_chooser">

			<?php echo status_select($status); ?>

		</select>


	</p>

	<input type="hidden" name="id" value="<?php echo $id; ?>">

	<input type="hidden" name="action" value="update">


	</form>

</div>

<div class = "user_detail_right">

	<div class="user_picture"><img src = "<?php echo $picture_url  . '?' . rand();?>"></div>

	<div class="user_change_picture">Change picture</div>


	<div class="user_detail_edit_actions">

		<button>Cancel</button>

		<button>Submit</button>

	</div>

</div>

</span>

<?php } ?>
