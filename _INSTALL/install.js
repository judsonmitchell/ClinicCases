//Scripts for install page

$(document).ready(function() {

	$('button.config_submit').click(function(event){
		event.preventDefault();
		var formVals = $(this).closest('form').serializeArray();
		$.post('install_process.php',formVals,function(data){
			var serverResponse = $.parseJSON(data);
            if (serverResponse.error === true)
                {
					$(window).scrollTop(0);
                    notify(serverResponse.message,true);
                }
                else
                {
                    notify(serverResponse.message);
                }
		});
	});
});
