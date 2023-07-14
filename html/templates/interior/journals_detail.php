<div class="journal_detail">
	<div class="container ">

		<div class="journal_actions">
			<div class="journal_group">
				<button class="d-flex back_to_journals">
					<img class="chevron_left" src="html/ico/chevron-up.svg" alt="">
					<p>Back to Journals</p>
				</button>
			</div>
			<div class="journal_group">
				<?php if ($view !== 'edit'  && $_SESSION['permissions']['writes_journals'] == '1') { ?>

					<button data-id="<?php echo $id; ?>" class="journal_delete">Delete</button>
					<button class="journal_edit">Edit</button>
					<button class="button--secondary print-button" data-print=".case_detail_panel_conflicts[data-id='<?php echo $id; ?>']" data-filename="Conflicts from <?php echo case_id_to_casename($dbh, $id); ?>">
						<img src="html/ico/printer.svg" alt="Print Icon"> <span>&nbsp;Print</span>
					</button>
				<?php } elseif ($view !== 'edit') { ?>

					<button class="button--secondary print-button" data-print=".case_detail_panel_conflicts[data-id='<?php echo $id; ?>']" data-filename="Conflicts from <?php echo case_id_to_casename($dbh, $id); ?>">
						<img src="html/ico/printer.svg" alt="Print Icon"> <span>&nbsp;Print</span>
					</button> <?php } ?>
			</div>

		</div>
		<div class="journal_header">
			<img class="thumbnail-mask" src="<?php echo return_thumbnail($dbh, $username); ?>">
			<div>
				<h1><?php echo username_to_fullname($dbh, $username); ?></h1>
				<p><?php echo extract_date_time($date_added); ?>
			</div>
		</div>
		<div class="journal_body" data-id="<?php echo $id; ?>">

			<?php if ($view == 'edit') { ?>

				<form id="editJournalForm" enctype="multipart/form-data">
					<div class="form__control form__control--select">
						<select class="edit_reader_select" multiple name="reader_select" data-placeholder="Submit this journal to">

							<option value=""></option>
							<?php echo get_journal_readers($dbh, $reader); ?>

						</select>
						<label for="supervisors">Submit journal to</label>
					</div>

					<div class="px-4">

						<input type="checkbox" name="remember_choice">
						<label>Remember</label>
					</div>
					<div id="editEditor">
						<?php echo $text ?>
					</div>
					<input type="text" hidden name="id" value="<?php echo $id ?>">
					<div class="d-flex justify-content-end py-4">
						<button id="newJournalCancel" data-target="editJournalForm" class="edit_journal_cancel px-4">Cancel</button>
						<button type="button" data-target="editJournalForm" class="primary-button edit_journal_submit">Submit</button>
					</div>
				</form>


			<?php } ?>


			<?php if ($view != 'edit') {
			?>

				<div class="journal_text">
					<?php echo $text; ?>

				</div>
			<?php

			} ?>


			<div class="journal_comments">

				<?php if ($comments) {
					$c_array = unserialize($comments);
					foreach ($c_array as $key => $value) {
						
				?>
						<div class="comment <?php if ($value['by'] == $_SESSION['login']) {
																	echo "can_delete";
																} ?>">

							<div class="comment_info">

								<div class="d-flex align-items-center">
									<img class="thumbnail-mask mx-3" src="<?php echo return_thumbnail($dbh, $value['by']); ?>" border="0">
									<div>
										<h6><?php echo username_to_fullname($dbh, $value['by']); ?></h6>
										<h6><?php echo extract_date_time($value['time']); ?>
									</div>

								</div>

								<p><?php echo  strip_tags($value['text'], '<br><br />'); ?></p>
							</div>

							<button data-journal_id="<?php echo $value['id'] ?>" data-comment_id="<?php echo $key; ?>" class="comment_delete">Delete</button>


						</div>

				<?php }
				}
				?>

				<?php if ($view !== 'edit') { ?>

					<div class="comment new">
						<div class="comment_info">
							<img class="thumbnail-mask" src="<?php echo return_thumbnail($dbh, $_SESSION['login']); ?>" border="0">

							<form id="commentForm">
								<textarea name="comment_text" required placeholder="Your comment" class="expand"></textarea>
							</form>
						</div>

						<button data-id="<?php echo $id; ?>" data-target="#commentForm" href="#" class="button--primary comment_save">Save</button>

					</div>

				<?php } ?>

			</div>

		</div>
	</div>




</div>