//js functions to notify user.


function notify(str,wait)
	{
		$("#notifications").html(str);
		$("#notifications").addClass('ui-corner-all').show();	
		
		if (typeof wait == 'undefined')
		{
			$('#notifications').delay(2000).fadeOut();
		}
		else
		{
			$('#notifications').append('<p><a href="">Dismiss</a></p>');
			$('#notifications a').click(function(){
				event.preventDefault()
				$('#notifications').fadeOut();
				})
		}
	}
