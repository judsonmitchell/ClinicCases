<?php
if (empty($posts)) {
	if (isset($search)) {
		echo "<p class='board_no_posts'>No board posts found matching <i>$search</i></p>";
	} else {
		echo "<p class='board_no_posts'>No board posts found.</p>";
		die;
	}
}

?>
<?php foreach ($posts as $post) {
	extract($post);
	$post_id = $post['post_id']
?>


	<div class="board_item board_item_card" data-id="<?php echo $post_id; ?>" data-viewers="" data-color="<?php echo $color; ?>">

		<div class="board_item_header">
			<p class="board_item_header_date">
				<?php echo extract_date_time($time_added); ?>
			</p>
			<img class="thumbnail-mask board_item_header_tn" src="<?php echo return_thumbnail($dbh, $author); ?>">
			<h2 class="board_item_header_title">
				<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
			</h2>

			<p class="board_item_author">
				<?php echo username_to_fullname($dbh, $author); ?>
			</p>

		</div>

		<div class="board_item_content">

			<div class="border_item_body">

				<?php echo $body; ?>

			</div>
			<?php $attach = check_attachments($dbh, $post_id);
			if ($attach == true) { ?>

				<div class="board_item_attachments">

					<p><label>Attachments:</label>
					<p>

					<div class="attachment_container">

						<p><?php echo $attach; ?> </p>
					</div>

				</div>

			<?php } ?>
		</div>

		<div class="board_actions">

			<?php if ($author == $_SESSION['login'] || $_SESSION['permissions']['can_configure'] == '1') { ?>

				<a href="#" data-id="<?php echo $post_id ?>" class="small board_item_edit">Edit</a>

				<a href="#" data-id="<?php echo $post_id ?>" class="small board_item_delete">Delete</a>


			<?php } ?>
		</div>



	</div>
	<div class="modal fade" id="viewPostModal-<?php echo $post_id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="viewPostLabel-<?php echo $post_id ?>" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">

			<div class="modal-content board_item">
				<button type="button" data-target="#viewPostModal-<?php echo $post_id ?>" class="close modal_close">
					<img src="html/ico/times.png" alt="">
				</button>
				<div class="board_item_header">
					<p class="board_item_header_date">
						<?php echo extract_date_time($time_added); ?>
					</p>
					<img class="thumbnail-mask board_item_header_tn" src="<?php echo return_thumbnail($dbh, $author); ?>">
					<h2 class="board_item_header_title">
						<?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?>
					</h2>

					<p class="board_item_author">
						<?php echo username_to_fullname($dbh, $author); ?>
					</p>

				</div>
				<div class="modal-body">
					<div class="board_item_content">

						<div class="border_item_body">

							<?php echo $body; ?>

						</div>
						<?php $attach = check_attachments($dbh, $post_id);
						if ($attach == true) { ?>

							<div class="board_item_attachments">

								<p><label>Attachments:</label>
								<p>

								<div class="attachment_container">

									<p><?php echo $attach; ?> </p>
								</div>

							</div>

						<?php } ?>
					</div>


					<div class="board_actions">

						<?php if ($author == $_SESSION['login'] || $_SESSION['permissions']['can_configure'] == '1') { ?>

							<a href="#" data-id="<?php echo $post_id ?>" class="small board_item_edit">Edit</a>

							<a href="#" data-id="<?php echo $post_id ?>" class="small board_item_delete">Delete</a>


						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="editPostModal-<?php echo $post_id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editPostModal-<?php echo $post_id ?>" aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-centered">

			<form enctype="multipart/form-data">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="newPostLabel"><?php echo $title ?></h5>
					</div>
					<div class="modal-body">
						<div class="form__control">
							<input id="post_title" value="<?php echo $title ?>" required type="text" name="post_title" placeholder=" ">
							<label for="post_title">Title</label>
						</div>
						<div class="form__control form__control--select">
							<select data-viewers="<?php echo get_viewers($dbh, $post_id); ?>" multiple class="edit_post_slim_select-<?php echo $post_id ?>" name="viewer_select">

								<option value=""></option>

								<?php echo all_active_users_and_groups($dbh, false, true); ?>

							</select>
							<label for="supervisors">Who See's This?</label>
						</div>
						<div data-body="<?php echo $body ?>" id="editor-<?php echo $post_id ?>"></div>
						<div class="edit_post_attachments">

							<?php $attach = check_attachments($dbh, $post_id);
							if ($attach == true) { ?>

								<div class="board_item_attachments">

									<p><label>Attachments:</label>
									<p>

									<div class="attachment_container">

										<p><?php echo $attach; ?> </p>
									</div>

								</div>

							<?php } ?>
						</div>
						<div class="form__control">
							<input id="attachments" type="file" name="attachments" multiple>
							<label for="attachment">Attach files</label>
						</div>
			</form>
			<div class="modal-footer">
				<button data-id="<?php echo $post_id ?>" type="button" id="editPostModal-<?php echo $post_id ?>" data-target="editPostModal-<?php echo $post_id ?>" class="edit_post_cancel">Cancel</button>
				<button data-id="<?php echo $post_id ?>" type="button" data-target="editPostModal-<?php echo $post_id ?>" class="primary-button edit_post_submit">Save</button>
			</div>
		</div>
	</div>
	</div>
	</div>
<?php } ?>