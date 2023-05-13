<!-- Jquery Calls Specific to this page -->
<script src="lib/axios/axios.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/37.1.0/classic/ckeditor.js"></script>

<?php if ($_SESSION['permissions']['view_board'] == '1') { ?>

	<script src="html/js/board.js" type="module"></script>

<?php } ?>




<!-- Css Specific to this Page -->
<link rel="stylesheet" type="text/css" href="html/css/board.min.css" />
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.27.1/slimselect.min.css">

</head>

<body>

	<div id="notifications"></div>


	<?php include 'html/templates/interior/idletimeout.php' ?>
	<div class="header">
		<?php $t = tabs($dbh, $_GET['i']);
		echo $t; ?>
	</div>

	<div class="container my-4 board_content">


		<div id="board_nav">

			<div class="form__control">
				<input class="board_posts_search" id="board_posts_search" name="board_posts_search" type="text" placeholder="search" />
				<label id="board_posts_search_label" for="board_posts_search">Search Board Posts</label>
			</div>
			<?php if ($_SESSION['permissions']['post_in_board'] == '1') { ?>

				<button class="button--primary" data-bs-toggle="modal" data-bs-target="#newPostModal">+ New Post</button>

			<?php } ?>

		</div>

		<div id="board_panel">

			<?php if ($_SESSION['permissions']['view_board'] == '0') { ?>

				<p>Sorry, you do not have permission to view the Board.</p>

			<?php } else { ?>

				Loading...


			<?php } ?>

		</div>

	</div>
	<?php include('html/templates/interior/board_new_post.php'); ?>