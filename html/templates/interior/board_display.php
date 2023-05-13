<?php if (count($posts) == 0) {
?>
	<p> No boards. </p>

<?php
}

?>
<?php foreach ($posts as $post) {
	var_dump($post);
	extract($post);
	$post_id = $post['post_id']
?>

	<div>
		<h2> <?php echo $title; ?> </h2>
		<h2> <?php echo $author; ?> </h2>
		<h2> <?php echo $body; ?> </h2>
		<h2><?php echo $post_id ?></h2>
		<h3><?php echo get_viewers($dbh, $post_id); ?></h3>
		<?php $attach = check_attachments($dbh, $post_id);
		if ($attach == true) { ?>

			<p><label>Attachments:</label>
			<p>

			<div class="attachment_container">

				<p><?php echo $attach; ?> </p>

			</div>

		<?php } ?>
	</div>
	<!-- <div class="board_item" data-id="<?php echo $post_id; ?>" data-viewers="" data-color="<?php echo $color; ?>">

		<img class="thumbnail-mask" src="<?php echo return_thumbnail($dbh, $author); ?>" >

		<h3><?php echo htmlspecialchars($title, ENT_QUOTES, 'UTF-8'); ?></h3>

		<div class="body_text">

			<?php echo $body; ?>

		</div>

		<p>
			<label>

				Posted by <?php echo username_to_fullname($dbh, $author); ?> on

				<?php echo extract_date_time($time_added); ?>

			</label>

			<?php if ($author == $_SESSION['login'] || $_SESSION['permissions']['can_configure'] == '1') { ?>

				<a href="#" class="small board_item_edit">Edit</a>

				<a href="#" class="small board_item_delete">Delete</a>


			<?php } ?>

		</p>


		<?php $attach = check_attachments($dbh, $post_id);
		if ($attach == true) { ?>

			<p><label>Attachments:</label>
			<p>

			<div class="attachment_container">

				<p><?php echo $attach; ?> </p>

			</div>

		<?php } ?>

	</div> -->

<?php } ?>