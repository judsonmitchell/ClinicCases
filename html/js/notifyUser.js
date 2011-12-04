//js functions to notify user.


function notify(str)
	{
		$("#notifications p").text(str);
		$("#notifications").addClass('ui-corner-all').show().delay(1200).fadeOut();	
		
	}
