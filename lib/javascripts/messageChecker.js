//Periodically check for new messages

 $(document).ready(function() {

	//Check for new messages
    msgCheck = function() {
        $.post('lib/php/utilities/messages_check.php', function(data) {

			var serverResponse = $.parseJSON(data);
            var word;
            var notice;
            if (parseInt(serverResponse.new_msg) > 0)
            {

                if (parseInt(serverResponse.new_msg) > 1)
                    {word = 'messages';}
                else
                    {word = 'message';}

                notice = 'You have ' + serverResponse.new_msg + ' new ' + word;
                notify(notice);

                if ($('#msg_panel')) //If user is in messages tab, show new message(s).
                    {
                        msgLoad();//defined in Messages.js
                    }

            }
            if (parseInt(serverResponse.unread) > 0)
            {
                var msgCountText = 'Messages ('  + serverResponse.unread + ')</span>';
                $('#tab_Messages').find('span').html(msgCountText);

                var titleText = 'ClinicCases (' + serverResponse.unread + ')';
                document.title = titleText;//has to be done this way because of IE8
            }

        });
    };

    msgCheck();

    //Reload inbox every ninety seconds to check for new messages
    msgCheckRefresh = setInterval(msgCheck, 90000);

 });