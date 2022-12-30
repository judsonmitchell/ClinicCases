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
			<button class="button--primary new_message" data-caseid="<?php echo $case_id ?>" data-casename="<?php echo case_id_to_casename($dbh, $case_id);  ?>">
				+ <span>&nbsp;New Message</span>
			</button>

		</div>

	</div>

	<div class="case_detail_panel_casenotes">


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
																	} ?> cse_msg" data-id="<?php echo $id; ?>" data-caseid="<?php echo $case_id; ?>" data-threadid="<?php echo $thread_id; ?>">
				<div class="modal fade" role="dialog" id="replyToMessage-<?php echo $id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="replyToMessage-<?php echo $id ?>" aria-hidden="true">
					<div class="modal-dialog modal-lg modal-dialog-centered">
						<form>
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title" id="replyToMessage-<?php echo $id ?>">Reply to <?php echo username_to_fullname($dbh, $from) ?> </h5>
								</div>
								<div class="modal-body">
									<p>Subject: RE: <?php echo $subject ?></p>
									<div class="form__control">
										<textarea rows="10" id="reply_text" required name="reply_text" placeholder=" "></textarea>
										<label for="reply_text">Message</label>
									</div>
									<input type="text" hidden name="case_id" value="<?php echo $case_id ?>">
									<input type="text" hidden name="id" value="<?php echo $id ?>">
									<input type="text" hidden name="subject" value="<?php echo $subject ?>">
									<input type="text" hidden name="assoc_case" value="<?php echo $assoc_case ?>">
									<input type="text" hidden name="thread_id" value="<?php echo $thread_id ?>">
								</div>
						</form>
						<div class="modal-footer">
							<button data-target="#replyToMessage-<?php echo $id ?>" class="case_reply_to_message_cancel">Cancel</button>
							<button data-target="#replyToMessage-<?php echo $id ?>" type="button" class="primary-button reply_to_message_submit">Submit</button>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" role="dialog" id="forwardMessage-<?php echo $id ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="forwardMessage-<?php echo $id ?>" aria-hidden="true">
				<div class="modal-dialog modal-lg modal-dialog-centered">
					<form>
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title" id="forwardMessage-<?php echo $id ?>">Forward message from <?php echo username_to_fullname($dbh, $from) ?> </h5>
							</div>
							<div class="modal-body">
								<p>Subject: FWD: <?php echo $subject ?></p>
								<div class="form__control form__control--select">
									<select multiple class="forward_tos_slim_select-<?php echo $id?>"  tabindex="2">
									</select>
									<label for="forward_tos">To: Choose Recipients</label>
								</div>
								<div class="form__control">
									<textarea rows="10" id="reply_text" required name="reply_text" placeholder=" "></textarea>
									<label for="reply_text">Message</label>
								</div>
								<input type="text" hidden name="case_id" value="<?php echo $case_id ?>">
								<input type="text" hidden name="id" value="<?php echo $id ?>">
								<input type="text" hidden name="subject" value="<?php echo $subject ?>">
								<input type="text" hidden name="assoc_case" value="<?php echo $assoc_case ?>">
								<input type="text" hidden name="thread_id" value="<?php echo $thread_id ?>">
							</div>
					</form>
					<div class="modal-footer">
						<button data-target="#forwardMessage-<?php echo $id ?>" data-id="<?php echo $id ?>" class="case_forward_message_cancel">Cancel</button>
						<button data-target="#forwardMessage-<?php echo $id ?>" data-id="<?php echo $id ?>" type="button" class="primary-button forward_message_submit">Submit</button>
					</div>
				</div>
			</div>
	</div>
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
			<button class="button--secondary reply_button" data-target="#replyToMessage-<?php echo $id ?>">
				<img src="html/ico/reply.png" alt="Reply Icon"> <span>&nbsp;Reply</span>
			</button>
			<button class="button--secondary forward_button" data-target="#forwardMessage-<?php echo $id ?>" data-id="<?php echo $id ?>" data-caseid="<?php echo $case_id ?>">
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