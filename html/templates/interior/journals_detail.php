<div class="journal_detail">
	<div class="container ">

		<div class="journal_actions">
			<div class="journal_group">
				<img class="chevron_left" src="html/ico/chevron-up.svg" alt="">
				<p>Back to Journals</p>
			</div>
			<div class="journal_group">
				<?php if ($view !== 'edit'  && $_SESSION['permissions']['writes_journals'] == '1') { ?>

					<button class="journal_delete">Delete</button>
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

				<div class="journal_write_data">

					<label>Send to:</label>

					<select multiple name="reader_select[]" data-placeholder="Submit this journal to" style="width:350px">

						<option value=""></option>
						<?php echo get_journal_readers($dbh, $reader); ?>

					</select>

					<label>Remember</label>
					<input type="checkbox" name="remember_choice">

				</div>

				<div class="journal_status">

					<span class="save_status">Unchanged</span>

				</div>

			<?php } ?>

			<div class="journal_text">

				<?php if ($view == 'edit') {
					echo "<textarea class='journal_edit'>" . htmlspecialchars($text, ENT_QUOTES, 'UTF-8') . "</textarea>";
				} else {
					echo $text;
				} ?>

			</div>

			<div class="journal_comments">

				<?php if ($comments) {
					$c_array = unserialize($comments);

					foreach ($c_array as $key => $value) { ?>

						<div class="comment <?php if ($value['by'] == $_SESSION['login']) {
																	echo "can_delete";
																} ?>" data-id="<?php echo $key; ?>">

							<img class="thumbnail-mask" src="<?php echo return_thumbnail($dbh, $value['by']); ?>" border="0">

							<p><?php echo  strip_tags($value['text'], '<br><br />'); ?></p>

							<a href="#" class="comment_delete">Delete</a>


						</div>

				<?php }
				}
				?>

				<?php if ($view !== 'edit') { ?>

					<div class="comment">
						<img class="thumbnail-mask" src="<?php echo return_thumbnail($dbh, $_SESSION['login']); ?>" border="0">

						<textarea class="expand">Your comment</textarea>

						<a href="#" class="comment_save">Save</a>

					</div>

				<?php } ?>

			</div>

		</div>
	</div>




</div>