<?php
include 'lib/php/utilities/convert_times.php';

?>

<div class="modal fade" id="viewUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newCaseLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">

		<div class="modal-content">
			<button data-target="#viewUserModal" class="modal_close close">
				<img src="html/ico/times.png" alt="">
			</button>
			<div id="viewUser">

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
						<a data-id="<?php echo $user['id'] ?>" class="button--secondary reset_password">
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
						<div>
							<?php
							$usernames = explode(",", $user['supervisors']);
							foreach ($usernames as $username) {
								if (!empty($username)) {
									$name = username_to_fullname($dbh, $username);
									if (!empty($name)) {
										echo "<p>" . $name . "</p>";
									}
								}
							}
							?>
						</div>
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
						<button data-target="#viewUserModal" data-id="<?php echo $user['id'] ?>" class="user_detail_delete">Delete</button>
						<button type="button" data-target="viewUserModal" class="primary-button user_detail_edit">Edit</button>
					</div>
				</div>
			</div>
			<div id="editUser" class="hidden">
				<form enctype="multipart/form-data">
					<div class="modal-header">
						<h5 class="modal-title" id="newUserLabel">Edit <?php echo username_to_fullname($dbh, $user['username']) ?></h5>
					</div>
					<div class="modal-body">
						<div>
							<div id="dropzone" class="picture_dropzone">
								<p>Drag and drop your image here</p>
								<p>or</p>
								<label for="edit_picture_url">click here to browse
								</label>
								<input id="edit_picture_url" type="file" name="picture_url">
								<div class="file_info">

									<img src="<?php echo $user['picture_url'] ?>" alt="Preview of user picture" class="file_preview">
									<button type="button" class="file_delete"><img src="html/ico/times.png" alt="" /></button>
									<p class="file_name"></p>
								</div>
							</div>
						</div>
						<div class="form__control">
							<input id="first_name" required type="text" name="first_name" placeholder=" ">
							<label for="first_name">First Name</label>
						</div>
						<div class="form__control">
							<input id="last_name" required type="text" name="last_name" placeholder=" ">
							<label for="last_name">Last Name</label>
						</div>
						<div class="form__control">
							<input id="email" required type="text" name="email" placeholder=" ">
							<label for="email">Email</label>
						</div>
						<div class="form__control">
							<input id="mobile_phone" required type="text" name="mobile_phone" placeholder=" ">
							<label for="mobile_phone">Mobile Phone</label>
						</div>
						<div class="form__control">
							<input id="office_phone" type="text" name="office_phone" placeholder=" ">
							<label for="office_phone">Office Phone</label>
						</div>
						<div class="form__control">
							<input id="home_phone" type="text" name="home_phone" placeholder=" ">
							<label for="home_phone">Home Phone</label>
						</div>
						<div class="form__control form__control--select">
							<select id="grp" name="grp" required multiple class="new_user_group_slim_select" tabindex="2">
								<?php

								echo group_select($dbh, $_SESSION['group']);
								?>
							</select>
							<label for="grp">Group</label>
						</div>
						<div class="form__control form__control--select">
							<select name="supervisors" class="" tabindex="2">
								<option value="" selected disabled>Select...</option>
								<?php
								echo supervisors_select($_SESSION['group'], $supervisor_name_data);
								?>
							</select>
							<label for="supervisors">Supervisors</label>
						</div>
						<div class="form__control form__control--select">
							<select class="status" name="status" tabindex="2">
								<option value="active">Active</option>
								<option value="inactive">Inactive</option>
							</select>
						</div>

				</form>
				<div class="modal-footer">
					<button id="newUserCancel" data-target="newUserModal" class="new_user_cancel">Cancel</button>
					<button type="button" data-target="newUserModal" class="primary-button new_user_submit">Submit</button>
				</div>
			</div>
		</div>
	</div>