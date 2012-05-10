<?php foreach($msgs as $msg) {extract($msg);?>

	<div class = "msg msg_closed" data-id = "<?php echo $id; ?>">

		<div class = "msg_bar <?php if (in_string($username,$read)){echo "msg_bar_read";}else{echo "msg_bar_unread";} ?>">

			<img src = "<?php echo return_thumbnail($dbh,$from); ?>">

			<?php echo username_to_fullname($dbh,$from); ?>

			<?php echo $subject; ?>

		</div>

	</div>


<?php } ?>

