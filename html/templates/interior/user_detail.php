<?php
include 'lib/php/utilities/convert_times.php';

?>

<div class="modal fade" id="viewUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newCaseLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">

		<div class="modal-content">
			<button data-target="#viewUserModal" class="modal_close close">
				<img src="html/ico/times.png" alt="">
			</button>
			<div class="modal-header">
				<div class="user-detail__info">
					<div class="file_info">

						<img src="<?php echo $user['picture_url'] ?>" alt="Preview of user picture" class="file_preview">
					</div>
					<h2 class="user-detail__name"><?php echo $user['first_name'] . ' ' . $user['last_name'] ?></h2>
				</div>

			</div>
			<div class="modal-body">
				<div class="user-detail__actions">
					<a href="mailto:<?php echo $user['email'] ?>" class="button--secondary">
						<img src="html/ico/mail.svg" alt="Email User">
						<span>&nbsp;&nbsp;Send email </span>
					</a>
					<a href="tel:<?php echo $user['home_phone'] ?>" class="button--secondary">
						<img src="html/ico/call.svg" alt="Call User">
						<span>&nbsp;&nbsp;Call</span>
					</a>
					<a href="mailto:<?php echo $user['email'] ?>" class="button--secondary">
						<img src="html/ico/reset-password.svg" alt="Reset User Password">
						<span>&nbsp;&nbsp;Reset password </span>
					</a>
				</div>
				<hr>
				<div class="user-details__detail">
					<h3>Total Hours:</h3>
					<p><?php echo $total_hours[0] . ' ' . $total_hours[1] ?></p>
					<h3><?php echo count($cases) ?> active cases</h3>
					<div>

						<?php
						foreach ($cases as $key => $value) {
							echo '<p><a href="?i=Cases.php#case/' . $key . '">' . $value . '</a></p>';
						}
						?>
					</div>
					<h3>Email</h3>
					<p><?php echo $user['email'] ?></p>
					<h3>Mobile Phone</h3>
					<p><?php echo $user['mobile_phone'] ?></p>
					<h3>Office Phone</h3>
					<p><?php echo $user['office_phone'] ?></p>
					<h3>Email</h3>
					<p><?php echo $user['email'] ?></p>
					<h3>Group</h3>
					<p><?php echo $user['grp'] ?></p>
					<h3>Username</h3>
					<p><?php echo $user['username'] ?></p>
					<h3>Supervisors</h3>
					<p>
						<?php
						$usernames = explode(",", $user['supervisors']);
						foreach ($usernames as $username) {
							if (!empty($username)) {

								$name = username_to_fullname($dbh, $username); // assuming you have a function named "username_to_name"
								echo "<p>" . $name . "</p>";
							}
						}
						?>
					</p>
					<h3>Status</h3>
					<p><?php echo $user['status'] ?></p>
					<h3>Date Created</h3>
					<p><?php
							echo extract_date_time($user['date_created']); // convert the date string to a Unix timestamp
							?></p>
					<h3>Last login</h3>
					<p>
						<?php echo get_last_login($dbh, $user['username']); ?>

					</p>
					<h3>Last Case Activity</h3>
					<p>
						<?php echo get_last_case_activity($dbh, $user['username']); ?>
					</p>
				</div>

				<div class="modal-footer">
					<button id="editUserModal" data-target="editUserModal" class="user_detail_delete">Delete</button>
					<button type="button" data-target="viewUserModal" class="primary-button user_deatil_edit">Edit</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	document.querySelector('.modal_close').addEventListener('click', () => {
		console.log(window.location.href);
		const searchParams = new URLSearchParams(window.location.search);
		searchParams.delete('user_id');
		console.log(searchParams.toString());
		const newUrl = window.location.origin + window.location.pathname + '?' + searchParams.toString();
		window.history.replaceState(null, null, newUrl);

	})
</script>