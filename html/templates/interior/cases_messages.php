<?php if ($replies === false and $refresh === false) { ?>
	<div class="user_display ui-widget ui-corner-bottom user_widget" tabindex="1">

	</div>

	<div class="case_documents_toolbar">

		<div class="form__control search">
			<input id="caseMessagesSearch-<?php echo $case_id ?>" data-caseid="<?php echo $case_id ?>" type="text" class="messages_search" placeholder=" " value="<?php if (isset($s)) {
																																																																															echo $s;
																																																																														} ?>">
			<label for="caseMessagesSearch-<?php echo $case_id ?>">Search Messages <span><img src="html/ico/search.png" /></span></label>
		</div>
		<div class="case_documents_toolbar--right">
			<button class="button--primary new_message" data-caseid="<?php echo $case_id ?>"  data-casename="<?php echo case_id_to_casename($dbh, $case_id);  ?>">
				+ <span>&nbsp;New Message</span>
			</button>



		</div>

	</div>

	<div class="case_detail_panel_casenotes">

		<!-- This is markup for a new message, hidden from the user until new message button clicked -->
		<!-- <div id = "msg_new" class="cse_msg_new">

	<form id = "new_msg_form">

		<p><label>To:</label>

			<select multiple name = "new_tos[]" data-placeholder = "Choose recipients">

				<?php echo all_active_users_and_groups($dbh, $case_id, false); ?>

			</select>

		</p>

		<p><label>Cc:</label>

			<select multiple name = "new_ccs[]" data-placeholder = "Choose recipients">

				<?php echo all_active_users_and_groups($dbh, $case_id, false); ?>

			</select>

		</p>

		<p><label>Subject:</label>

			<input name="new_subject">

		</p>

		<p><label>File In:</label>

			<select name = "new_file_msg" data-placeholder = "Choose case file">

				<option selected=selected value = "<?php echo $case_id; ?>"><?php echo case_id_to_casename($dbh, $case_id);  ?></option>

			</select>

		</p>

		<p>
			<textarea name = "new_msg_text"></textarea>

		</p>

		<p class="msg_new_buttons">

			<input type="hidden" name="action" value="send">

			<button id="msg_new_button_submit">Send</button>

			<button id = "msg_new_button_cancel">Cancel</button>

		</p>

	</form>

</div> -->


	<?php } ?>
	<!-- This displays messages and replies -->
	<?php

	if ($replies === false) //these are not replies to a message
	{
		foreach ($msgs as $msg) {
			extract($msg); ?>

			<div class="msg msg_closed <?php if (in_string($username, $read)) {
																		echo "msg_read";
																	} else {
																		echo "msg_unread";
																	} ?> cse_msg" data-id="<?php echo $id; ?>">

				<div class="msg_bar cse_msg_bar">

					<div class="msg_bar_left cse_msg_bar_left">

						<div>
							<img class="thumbnail-mask" src="<?php echo return_thumbnail($dbh, $from); ?>">
							<p class="username">
								<?php echo username_to_fullname($dbh, $from) . "     "; ?>
							</p>
							<div class="horizontal-line"></div>
						</div>

						<span class="cse_msg_subject">

							<?php echo htmlspecialchars($subject, ENT_QUOTES, 'UTF-8'); ?>

						</span>

					</div>

					<div class="msg_bar_right cse_msg_bar_right">

						<?php echo extract_date_time($time_sent); ?>

						<span <?php

									if (in_string($username, $starred)) {
										echo "class = 'star_msg star_on'><img src='html/ico/starred.png' title = 'Remove star from message'>";
									} else {
										echo "class = 'star_msg star_off'><img src='html/ico/not_starred.png' title='Star this message'>";
									}
									?> </span>

					</div>

				</div>

				<div class="msg_body">
					<div class="msg_info">

						<p class="tos"> <strong>
								To:
							</strong> </p>
						<p><?php echo format_name_list($dbh, $to); ?></p>

						<?php if ($ccs) {
							echo "<p class='ccs'><strong>Cc: </strong></p><p>" . format_name_list($dbh, $ccs) . "</p>";
						} ?>

						<p class="subj"><strong>Subject: </strong></p>
						<p><?php echo htmlspecialchars($subject, ENT_QUOTES, 'UTF-8'); ?></p>

						<p class="assoc_case"><strong>Filed in: </strong></p>
						<p><?php if (!$assoc_case) {
									echo "(Not Filed)";
								} else {
									echo case_id_to_casename($dbh, $assoc_case);
								} ?></p>
					</div>

					<div class="msg_body_text"><?php echo nl2br(htmlentities(text_prepare($body))); ?></div>

					<div class="msg_replies">


					</div>

					<div class="msg_actions">
						<button class="button--secondary">
							<img src="html/ico/reply.png" alt="Reply Icon"> <span>&nbsp;Reply</span>
						</button>
						<button class="button--secondary">
							<img src="html/ico/forward.png" alt="Forward Icon"> <span>&nbsp;Forward</span>
						</button>
						<button class="button--secondary">
							<img src="html/ico/printer.svg" alt="Print Icon"> <span>&nbsp;Print</span>
						</button>


					</div>

					<!-- <div class="msg_forward">

						<select name="forward_r" multiple class="msg_forward_choices" data-placeholder="Choose Recipients">

							<?php echo all_active_users($dbh); ?>

						</select>

					</div>

					<div class="msg_reply_text">

						<textarea></textarea>

						<button class="msg_send">Send</button>

					</div> -->

				</div>

			</div>


		<?php }
		if (empty($msgs) and $new_message === false) {

			if (isset($s)) {
				echo "<p>No messages found matching <i>$s</i></p>";
			} else {
				echo "<p>No messages in this case</p>";
			}
		}
	} else {
		foreach ($msgs as $msg) {
			extract($msg); //these are replies 
		?>

			<div class="msg_reply" data-id="<?php echo $id; ?>">

				<div class="msg_reply_left">

					<img class="thumbnail-mask" src="<?php echo return_thumbnail($dbh, $from); ?>">

					<?php echo username_to_fullname($dbh, $from); ?>

				</div>

				<div class="msg_reply_right">

					<?php echo extract_date_time($time_sent); ?>

				</div>

				<p><?php echo nl2br(htmlentities(text_prepare($body))); ?></p>

			</div>

	<?php }
	} ?>



	</div>