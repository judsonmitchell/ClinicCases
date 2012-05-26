$(document).ready(function() {

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