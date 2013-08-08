$(document).ready(function() {

	//"Disable" navigation tabs
	$("#tabs a").click(function(event){
		event.preventDefault();
		notify('<p>Please change your password before proceeding.</p>',true);
		return false;
	});

	//Submit new password
	$('button').click(function(event){
		event.preventDefault();

		var pass = $('input[name="new_pass"]').val();
		var passCheck = $('input[name="new_pass_check"]').val();
		var error = null;

		if (pass != passCheck)
			{
				$('input').val('');
				notify("<p>The passwords you entered do not match</p>",true);
			}
		else
		{
			var errors = validPassword(pass);
			if (errors.length < 1)
				{
					//submit the form
					$.post('lib/php/auth/change_password.php',{'upgrade' : 'y','pass':pass},function(data){
						var serverResponse = $.parseJSON(data);
						if (serverResponse.error === true)
						{
							notify(serverResponse.message, true);
						}
						else
						{
							notify(serverResponse.message);
							var successText = '<p><b>Password change successful.</b></p><p><a href="index.php?i=Home.php">Continue</a></p>';
							$('div.force_new_password_content').html(successText);

						}
					});
				}
			else
				{
					$('input').val('');
					notify(errors,true);
				}
		}
  });

});
