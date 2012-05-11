<?php

if (!$replies) //these are not replies to a message
{
	foreach($msgs as $msg) {extract($msg);?>

	<div class = "msg msg_closed" data-id = "<?php echo $id; ?>">

		<div class = "msg_bar <?php if (in_string($username,$read)){echo "msg_bar_read";}else{echo "msg_bar_unread";} ?>">

			<div class = "msg_bar_left">

				<img src = "<?php echo return_thumbnail($dbh,$from); ?>">

				<?php echo username_to_fullname($dbh,$from); ?>

				<div class = "msg_subject">

					<?php echo $subject; ?>

				</div>

			</div>

			<div class = "msg_bar_right">

				<?php echo extract_date_time($time_sent); ?>

				<span

					<?php

					if (in_string($username,$starred))
						{echo "class = 'star_msg star_on'><img src='html/ico/starred.png' title = 'Remove star from message'>";}
						else
						{echo "class = 'star_msg star_off'><img src='html/ico/not_starred.png' title='Star this message'>";}
					?>


				</span>

			</div>

		</div>

		<div class = "msg_body">

			<p class = "tos">To: <?php echo format_name_list($dbh,$to); ?></p>

			<?php if ($ccs){echo "<p class='ccs'>Cc: " . format_name_list($dbh,$ccs) . "</p>";} ?>

			<div class = "msg_body_text"><?php echo $body; ?></div>

			<div class = "msg_replies">


			</div>

			<textarea class="msg_reply_text"></textarea>

		</div>

	</div>


<?php } } else {foreach($msgs as $msg) {extract($msg);?>

	<div class = "msg_reply" data-id = "<?php echo $id; ?>">

			<div class = "msg_reply_left">

				<img src = "<?php echo return_thumbnail($dbh,$from); ?>">

				<?php echo username_to_fullname($dbh,$from); ?>

			</div>

			<div class = "msg_reply_right">

				<?php echo extract_date_time($time_sent); ?>

			</div>

			<p><?php echo $body; ?></p>

	</div>

<?php }} ?>

