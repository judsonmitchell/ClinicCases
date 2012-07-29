<?php foreach ($posts as $post) {extract($post) ?>

<div class="board_item" style="background-color:rgba(<?php echo $color; ?>, 0.5)">

	<h3><?php echo $title; ?></h3>

	<p><?php echo $body; ?></p>

</div>

<?php } ?>