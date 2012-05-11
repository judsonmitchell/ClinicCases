<?php

if (!$replies) //these are not replies to a message
{
	foreach($msgs as $msg) {extract($msg);?>

	<div class = "msg msg_closed" data-id = "<?php echo $id; ?>">

		<div class = "msg_bar <?php if (in_string($username,$read)){echo "msg_bar_read";}else{echo "msg_bar_unread";} ?>">

			<img src = "<?php echo return_thumbnail($dbh,$from); ?>">

			<?php echo username_to_fullname($dbh,$from); ?>

			<?php echo $subject; ?>

		</div>

		<div class = "msg_body">

			<p>To: <?php echo $to; ?></p>
			<p>Cc: <?php echo $ccs; ?></p>
			<p><?php echo $body; ?></p>

			<div class = "msg_replies">


			</div>

			<textarea class="msg_reply_text"></textarea>

		</div>

	</div>


<?php } } else {foreach($msgs as $msg) {extract($msg);?>

	<div class = "msg_reply" data-id = "<?php echo $id; ?>">

			<img src = "<?php echo return_thumbnail($dbh,$from); ?>">

			<?php echo $body; ?>

	</div>

<?php }} ?>

