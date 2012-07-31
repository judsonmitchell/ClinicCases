<div class="board_item new_post">

	<input name="post_title" value="New Post Title">

	<br />

	<textarea class="post_edit">text here</textarea>

</div>

<?php foreach ($posts as $post) {extract($post) ?>

<div class="board_item" style="background-color:rgba(<?php echo $color; ?>, 0.5)">

	<img class="board_thumb" src="<?php echo return_thumbnail($dbh,$author); ?>" border="0">

	<h3><?php echo $title; ?></h3>

	<p><?php echo $body; ?></p>

</div>

<?php } ?>