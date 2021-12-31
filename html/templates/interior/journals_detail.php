<div class="journal_detail">

	<div class="journal_header ui-widget-header ui-corner-tl ui-corner-tr ui-helper-clearfix">

		<img class="thumbnail-mask" src="<?php echo return_thumbnail($dbh,$username); ?>" border="0">

		<p>Journal Submitted by <?php echo username_to_fullname ($dbh,$username); ?> on <?php echo extract_date_time($date_added); ?>
		</p>

		<div class = "journal_detail_control">

			<?php if ($view !== 'edit'  && $_SESSION['permissions']['writes_journals'] == '1'){ ?>

			<button class="journal_delete">Delete</button>
			<button class="journal_edit">Edit</button>
			<button class="journal_print">Print</button>

				<?php } elseif ($view !=='edit'){?>

			<button class="journal_print">Print</button>
				<?php } ?>

			<button class="journal_close">Close</button>

		</div>

	</div>

	<div class="journal_body" data-id="<?php echo $id; ?>">

		<?php if ($view == 'edit'){ ?>

		<div class="journal_write_data">

			<label>Send to:</label>

			<select multiple name="reader_select[]" data-placeholder="Submit this journal to" style="width:350px">

				<option value = ""></option>
				<?php echo get_journal_readers($dbh,$reader); ?>

			</select>

			<label>Remember</label>
			<input type="checkbox" name="remember_choice">

		</div>

		<div class="journal_status">

			<span class= "save_status">Unchanged</span>

		</div>

		<?php } ?>

		<div class="journal_text">

		<?php if ($view == 'edit'){echo "<textarea class='journal_edit'>" . htmlspecialchars($text ,ENT_QUOTES,'UTF-8'). "</textarea>";}else{echo $text;} ?>

		</div>

		<div class="journal_comments">

			<?php if ($comments)
			{
				$c_array = unserialize($comments);

				foreach ($c_array as $key => $value) {?>

					<div class = "comment <?php if ($value['by'] == $_SESSION['login']){echo "can_delete";} ?>" data-id="<?php echo $key; ?>">

						<img class="thumbnail-mask" src="<?php echo return_thumbnail($dbh, $value['by']); ?>" border="0">

						<p><?php echo  strip_tags($value['text'], '<br><br />'); ?></p>

						<a href="#" class="comment_delete">Delete</a>


					</div>

				<?php }
			}
			?>

			<?php if ($view !== 'edit'){ ?>

			<div class = "comment">
				<img class="thumbnail-mask" src="<?php echo return_thumbnail($dbh, $_SESSION['login']); ?>" border="0">

				<textarea class="expand">Your comment</textarea>

				<a href="#" class="comment_save">Save</a>

			</div>

			<?php } ?>

		</div>

	</div>

</div>
