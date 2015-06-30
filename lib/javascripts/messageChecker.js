//Periodically check for new messages
/* global notify, msgLoad */

//Jquery check to see if element exists http://stackoverflow.com/a/31047/49359
jQuery.fn.exists = function(){return this.length>0;};

//Check for new messages
var msgCheck = function() {
    $.post('lib/php/utilities/messages_check.php', function(data) {
        var serverResponse = $.parseJSON(data);
        var word;
        var notice;
        var msgCountText;
        var titleText;
        if (parseInt(serverResponse.new_msg) > 0) {
            if (parseInt(serverResponse.new_msg) > 1) {
                word = 'messages';
            } else {
                word = 'message';
            }

            notice = 'You have ' + serverResponse.new_msg + ' new ' + word;
            notify(notice);

            if ($('#msg_panel').exists() && !$('div.msg_opened').exists()){
                //If user is in messages tab and is not in the middle of reading, show new message(s).
                msgLoad();//defined in Messages.js
            }
        }
        if (parseInt(serverResponse.unread) > 0) {
            msgCountText = 'Messages ('  + serverResponse.unread + ')</span>';
            $('#tab_Messages').find('span').html(msgCountText);

            titleText = 'ClinicCases (' + serverResponse.unread + ')';
            document.title = titleText;//has to be done this way because of IE8
        } else {
            msgCountText = 'Messages';
            $('#tab_Messages').find('span').html(msgCountText);

            titleText = 'ClinicCases';
            document.title = titleText;
        }
    });
};
msgCheck();

//Reload inbox every ninety seconds to check for new messages
var msgCheckRefresh = setInterval(msgCheck, 90000);

