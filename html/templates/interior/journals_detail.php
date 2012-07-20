<div class="journal_detail">

	<div class="journal_header ui-widget-header ui-corner-tl ui-corner-all ui-helper-clearfix">

		<img src="<?php echo return_thumbnail($dbh,$username); ?>" border="0">

		<p>Journal Submitted by <?php echo username_to_fullname ($dbh,$username); ?> on <?php echo extract_date_time($date_added); ?>
		</p>

		<div class = "journal_detail_control">

			<button></button>

			<button></button>

		</div>

	</div>

	<div class="journal_body">

		<?php echo $text; ?>

		<div class="journal_comments">

			<div class = "comment">
				<img src="<?php echo return_thumbnail($dbh, $_SESSION['login']); ?>" border="0">
				<textarea class="expand">Your comment</textarea>

			</div>

		</div>

	</div>




</div>