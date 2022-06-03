<div class="">

	<h3 style="text-align:center">Add a User</h3>

	<form>

		<select multiple class="slim-select" tabindex="2">

			<?php echo $user_list; ?>

		</select>

		<input type="hidden" id="user_chooser_case_id" value="<?php echo $case_id; ?>">

	</form>
	<div class="bottom-bar">

		<button class="cancel-add-user-button" tabindex="3">Cancel</button>

		<button class="primary-button add-user-button" tabindex="3">Save</button>
	</div>

</div>