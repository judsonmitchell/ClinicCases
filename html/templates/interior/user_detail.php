<!-- Modal -->
<?php
// require_once('lib/php/utilities/names.php');
// $supervisor_name_data  = supervisor_names_array($dbh);
// require_once('lib/php/html/gen_select.php');
var_dump($user);
?>

<div class="modal fade" id="viewUserModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="newCaseLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">

		<div class="modal-content">
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
				<div class="user-details__detail">
					<h3>Total Hours:</h3>
					<p><?php echo $total_hours[0] . ' ' . $total_hours[1] ?></p>
					<h3><?php echo count($cases) ?> active cases<h3>
							<?php
							foreach ($cases as $key => $value) {
								echo '<a href="?i=Cases.php#case/' . $key . '">' . $value . '</a>';
							}
							?>
				</div>

				<div class="modal-footer">
					<button id="viewUserCancel" data-target="viewUserModal" class="new_user_cancel">Cancel</button>
					<button type="button" data-target="viewUserModal" class="primary-button new_user_submit">Add</button>
				</div>
			</div>
		</div>
	</div>
</div>