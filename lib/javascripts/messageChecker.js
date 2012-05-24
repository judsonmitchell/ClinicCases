//Periodically check for new messages

 $(document).ready(function() {

	//Check for new messages
    msgCheck = function() {

        $.post('lib/php/utilities/messages_check.php', function(data) {

			var serverResponse = $.parseJSON(data);
			console.log(serverResponse);
        });
    };

    msgCheck();

    //Reload inbox every ninety seconds to check for new messages
    msgCheckRefresh = setInterval(msgCheck, 9000);

 });