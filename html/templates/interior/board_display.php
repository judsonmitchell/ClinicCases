<div class="board_item new_post">

	<form name = "new_post_form">

	<div class="board_new_item_menu">

		<label>Title</label>

		<input name="post_title" value="New Post Title">

		<label>Who Sees This?</label>

		<select multiple name="viewer_select[]" data-placeholder ="Who Sees This?">

			<option value=""></option>

			<?php echo all_active_users_and_groups($dbh,false,true); ?>

		</select>

		<label>Color</label>

		<select name="post_color">
			<option value="255,250,240" style="background-color:rgba(255,250,240,0.5)">White</option>
			<option value="0,255,0" style="background-color:rgba(0,255,0,0.5)">Green</option>
			<option value="0,0,255" style="background-color:rgba(0,0,255,0.5)">Blue</option>
			<option value="255,0,0" style="background-color:rgba(255,0,0,0.5)">Red</option>
			<option value="255,255,0" style="background-color:rgba(255,255,0,0.5)">Yellow</option>
		</select>

	</div>

	<textarea class="post_edit"></textarea>

	<div class="board_new_item_menu_bottom">

		<label>Attachments</label>

		<div class="board_upload">

		</div>

		<div class = "board_new_item_menu_bottom_inner">

			<button>Cancel</button>
			<button>Save</button>

		</div>

	</div>

	</form>

</div>

<?php foreach ($posts as $post) {extract($post) ?>

<div class="board_item" style="background-color:rgba(<?php echo $color; ?>,0.5)"
	data-id="<?php echo $post_id; ?>" data-viewers = "<?php echo get_viewers($dbh,$post_id);?>" data-color = "<?php echo $color; ?>">

	<img class="thumbnail-mask" src="<?php echo return_thumbnail($dbh,$author); ?>" border="0">

	<h3><?php echo htmlspecialchars($title,ENT_QUOTES,'UTF-8'); ?></h3>

	<div class="body_text">

		<?php echo $body; ?>

	</div>

	<p>
		<label>

			Posted by <?php echo username_to_fullname($dbh,$author); ?> on

			<?php echo extract_date_time($time_added); ?>

		</label>

	<?php if ($author == $_SESSION['login'] || $_SESSION['permissions']['can_configure'] == '1'){ ?>

		<a href="#" class="small board_item_edit">Edit</a>

		<a href="#" class="small board_item_delete">Delete</a>


	<?php } ?>

	</p>


	<?php $attach = check_attachments($dbh,$post_id); if ($attach == true){ ?>

	<p><label>Attachments:</label><p>

	<div class="attachment_container">

		<p><?php echo $attach; ?> </p>

	</div>

	<?php } ?>

</div>

<?php } ?>
