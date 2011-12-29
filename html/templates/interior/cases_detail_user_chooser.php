<div class="add_user_detail">

<h3 style="text-align:center">Add a User</h3>

<form>
	
	<select multiple data-placeholder = "Type the user's name"  class="chzn-select">

		<?php echo $user_list; ?>

	</select>
	
	<input type="hidden" id="user_chooser_case_id" value="<?php echo $case_id; ?>">
	
</form>

<button class="user-action-adduser-button">Add</button>

</div>
