<div class="journal_detail">

	<div class="journal_header ui-widget-header ui-corner-tl ui-corner-all ui-helper-clearfix">

		<img src="<?php echo return_thumbnail($dbh,$username); ?>" border="0">

		<p>Journal Submitted by <?php echo username_to_fullname ($dbh,$username); ?> on <?php echo extract_date_time($date_added); ?>
		</p>

		<div class = "journal_detail_control">

			<?php if ($view !== 'edit'){ //no print on edit?>
			<button></button>
			<?php } ?>

			<button></button>

		</div>

	</div>

	<div class="journal_body" data-id="<?php echo $id; ?>">

		<?php if ($view == 'edit'){ ?>

		<div class="text_editor_status">

			<span class= "status">Unchanged</span>

		</div>
		<?php } ?>

		<?php if ($view == 'edit'){echo "<textarea class='journal_edit'>$text</textarea>";}else{echo $text;} ?>

		<div class="journal_comments">

			<?php if ($comments)
			{
				$c_array = unserialize($comments);

				foreach ($c_array as $key => $value) {?>

					<div class = "comment <?php if ($value['by'] == $_SESSION['login']){echo "can_delete";} ?>" data-id="<?php echo $key; ?>">

						<img src="<?php echo return_thumbnail($dbh, $value['by']); ?>" border="0">

						<p><?php echo strip_tags($value['text'],'<br>'); ?></p>

						<a href="#" class="comment_delete">Delete</a>


					</div>

				<?php }
			}
			?>

			<?php if ($view !== 'edit'){ ?>

			<div class = "comment">
				<img src="<?php echo return_thumbnail($dbh, $_SESSION['login']); ?>" border="0">

				<textarea class="expand">Your comment</textarea>

				<a href="#" class="comment_save">Save</a>

			</div>

			<?php } ?>

		</div>

	</div>




</div>