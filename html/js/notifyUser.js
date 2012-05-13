//js functions to notify user.


function notify(str,wait)
	{
		$("#notifications").html(str).css({'opacity':'.8'});
		$("#notifications").addClass('ui-corner-all').show();

		if (wait === true)
		{

			$('#notifications').append('<p><a href="">Dismiss</a></p>');
			$('#notifications a').click(function(event){
				event.preventDefault();
				$('#notifications').fadeOut();
				});
		}
		else
		{
			$('#notifications').delay(2000).fadeOut();
		}
	}
