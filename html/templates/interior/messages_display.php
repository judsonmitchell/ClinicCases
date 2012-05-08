<?php foreach($msgs as $msg) {extract($msg);?>

	<div class = "msg" data-id = "<?php echo $id; ?>">

		<?php echo $subject; ?>

	</div>


<?php } ?>

