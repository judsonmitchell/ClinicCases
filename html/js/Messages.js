// Scripts for messages page

/* global msgCheck, notify, elPrint */

//Load new messages on scroll
function addMoreMessages(scrollTarget, view) {

    var scrollAmount = scrollTarget[0].scrollTop;
    var scrollHeight = scrollTarget[0].scrollHeight;
    var scrollPercent = (scrollAmount / (scrollHeight - scrollTarget.height())) * 100;
    var startNum;

    if (scrollPercent > 70) {
        //the start for the query is added to the scrollTarget object
        if (typeof scrollTarget.data('startVal') === 'undefined') {
            startNum = 20;
            scrollTarget.data('startVal', startNum);
        } else {
            startNum = scrollTarget.data('startVal') + parseInt(20);
            scrollTarget.data('startVal', startNum);
        }

        if (scrollTarget.data('searchOn') === 'y') {//we are searching
            $.post('lib/php/data/messages_load.php', {
                'type': view,
                'start': scrollTarget.data('startVal'),
                's':scrollTarget.data('searchTerm')
            }, function(data) {
                //var t represents number of messages in returned data; if 0,return false;
                var t = $(data).find('div').length;
                if (t === 0) {
                    return false;
                } else {
                    scrollTarget.append(data);
                    layoutMessages();
                    scrollTarget.highlight(scrollTarget.data('searchTerm'));
                }
            });
        } else {
            $.post('lib/php/data/messages_load.php', {
                'type': view,
                'start': scrollTarget.data('startVal')
            }, function(data) {
                //var t represents number of messages in returned data; if 0,return false;
                var t = $(data).find('div').length;
                if (t === 0) {
                    return false;
                } else {
                    scrollTarget.append(data);
                    layoutMessages();
                }
            });
        }
    }
}

//Checks if a div is overflowing.  See http://stackoverflow.com/a/143889/49359
function checkOverflow(target) {
    var el = target[0];
    var curOverflow = el.style.overflow;
    if (!curOverflow || curOverflow === 'visible'){
        el.style.overflow = 'hidden';
    }

    var isOverflowing = el.clientWidth < el.scrollWidth || el.clientHeight < el.scrollHeight;
    el.style.overflow = curOverflow;
    return isOverflowing;
}

function layoutMessages() {
    //Check to see if the list ofs recipients are overflowing.  If so, add a link to expand
    var oFlow;

    $('p.tos').each(function() {
        if (!$(this).next('p').hasClass('msg_to_more')) {//we haven't already fixed overflow
            oFlow = checkOverflow($(this));
            if (oFlow === true) {
                $(this).css({'overflowY': 'hidden'});
                $(this).after('<p class="msg_to_more ex_tos"><a href="#">and others</a></p>');
            }
        }
    });


    $('p.ccs').each(function() {
        if (!$(this).next('p').hasClass('msg_to_more')) {//we haven't already fixed overflow
            oFlow = checkOverflow($(this));
            if (oFlow === true) {
                $(this).css({'overflowY': 'hidden'});
                $(this).after('<p class="msg_to_more"><a href="#">and others</a></p>');
            }
        }
    });

    //round corners
    $('div.msg').addClass('ui-corner-all');

    //Set opacity of read messages
    $('div.msg_read').css({'opacity': '.5'});
}

function msgLoad () {
    $.post('lib/php/data/messages_load.php', {
        'type': 'inbox',
        'start': '0'
    }, function(data) {
        $('div#msg_panel').html(data);
        //Round Corners
        $('div.msg').addClass('ui-corner-all');
        //Set the start value for scroll
        $('div#msg_panel').data('startVal', 0);
        layoutMessages();
    });
}

$(document).ready(function() {
    var target = $('div#msg_panel');
    var start = target.data('startVal');

    //set header widget
    $('#msg_nav').addClass('ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr');

    //Load messages and refresh
    msgLoad();

    //Reload inbox every ninety seconds to check for new messages
    var msgRefresh = setInterval(msgLoad, 90000);

    //Toggle message message open/closed state, retrieve replies
    $('div.msg_bar').live('click', function() {
        var msgParent = $(this).parent();
        if ($(this).parent().hasClass('msg_closed')) {
            msgParent.removeClass('msg_closed').addClass('msg_opened');
            msgParent.find('p.tos, p.ccs, p.subj').css({'color': 'black'});
            msgParent.css({'opacity': '1'});
            var thisMsgId = msgParent.attr('data-id');
            msgParent.data('verticalPos', $('div#msg_panel')[0].scrollTop);

            //turn off auto-refresh
            clearTimeout(msgRefresh);

            $(this).next('div').find('div.msg_replies').load('lib/php/data/messages_load.php', {
                'type': 'replies',
                'thread_id': thisMsgId
            }, function() {
                //Set the height
                var newHeight = $(this).closest('div.msg')[0].scrollHeight;
                msgParent.animate({'height': newHeight});
                if (target.data('searchOn') === 'y') {
                    target.highlight(target.data('searchTerm'));
                }
            });

            //Mark message as read
            $.post('lib/php/data/messages_process.php', {
                'action': 'mark_read',
                'id': thisMsgId
            },function(){
                msgCheck(); //defined in messageCheck.js

            });
        } else {
            msgParent.removeClass('msg_opened').addClass('msg_closed');
            msgParent.find('div.msg_reply_text').hide().find('textarea').val('');
            msgParent.find('div.msg_forward').hide();
            msgParent.css({'opacity': '.5'});
            msgParent.animate({'height': '90'});

            //turn auto refresh back on
            msgRefresh = setInterval(msgLoad, 90000);
        }
    });

    //Add buttons
    $('button#msg_archive_all_button').button(
        {icons: {primary: 'fff-icon-email-go'},
        text: true
    })
    .click(function() {
            var dialogWin = $('<div title="Send All to Archive?">Send all messages in inbox to archive?</div>')
            .dialog({
                autoOpen: false,
                resizable: false,
                modal: true,
                buttons: {
                    'Yes': function() {
                        $.post('lib/php/data/messages_process.php', {action: 'archive_all'}, function(data) {
                            var serverResponse = $.parseJSON(data);
                            if (serverResponse.error === true) {
                                notify(serverResponse.message, true);
                            } else {
                                notify(serverResponse.message);
                                msgLoad();
                                msgCheck();//defined in messageChecker.js
                            }
                        });
                        $(this).dialog('destroy');
                    },
                    'No': function() {
                        $(this).dialog('destroy');
                    }
                }
            });
            $(dialogWin).dialog('open');
        });

    //Set buttons and handle sending of new message
	$('button#new_msg_button').button({
        icons: {primary: 'fff-icon-email-add'},
        text: true
    })
	.click(function(){
		$('div#msg_panel').load('lib/php/data/messages_load.php #msg_new',{
            'new_message':'y'
        }, function(){
			//turn off auto-refresh
			clearTimeout(msgRefresh);

			//define new messsage
			var newMsg = $('div#msg_new');
			newMsg.show().addClass('msg_opened');
			newMsg.find('select[name = "new_tos[]"], select[name = "new_ccs[]"], select[name = "new_file_msg"]').chosen();

			//Cancel
			$('#msg_new_button_cancel').click(function(event){
					event.preventDefault();
					$('#new_msg_form')[0].reset();
                    newMsg.removeClass('msg_opened');
					msgLoad();
					//turn auto refresh back on
					msgRefresh = setInterval(msgLoad, 90000);
					notify('New message cancelled');
				});

			//Submit
			$('#msg_new_button_submit').click(function(event){
				event.preventDefault();
                var msgVals = $('#new_msg_form').serializeArray();
                if ($('select[name="new_tos"]').val() === null) {
                    notify('<p>You must select at least one recipient</p>');
                    return false;
                } else {
                    $.post('lib/php/data/messages_process.php',msgVals,function(data){
                        var serverResponse = $.parseJSON(data);
                        if (serverResponse.error === true) {
                            notify(serverResponse.message, true);
                        } else {
                            $('#new_msg_form')[0].reset();
                            msgLoad();
                            //turn auto refresh back on
                            msgRefresh = setInterval(msgLoad, 90000);
                            notify('Message sent');
                        }
                    });
                }
			});
		});
	});

    //Change views
    $('select#msg_view_chooser').change(function() {
        var view = $(this).val();

        //Turn off the auto refresh if we are not in inbox
        if (view !== 'inbox') {
            clearTimeout(msgRefresh);
        } else {
            msgRefresh = setInterval(msgLoad, 90000);
        }

        target.html('<p>Loading...</p>');

        //Load messages
        $.post('lib/php/data/messages_load.php', {
            'type': view,
            'start': '0'
        }, function(data) {
            target.html(data);
            //Round Corners
            //Set the start value for scroll
            target.data('startVal', 0);
            layoutMessages();
            $('div#msg_panel')[0].scrollTop = 0;
        });
    });

    //bind the scroll event for the window and add more messages on scroll
    target.bind('scroll', function() {
        var view = null;
        if (target.data('searchOn') === 'y') {//we are searching
            view = 'search';
        } else {
            view = $('select#msg_view_chooser').val();
        }
        addMoreMessages(target,view );
    });

    //Listen for when user stars message
    $('span.star_msg').live('click', function(event) {
        event.stopPropagation();
        var thisId = $(this).closest('div.msg').attr('data-id');

        if ($(this).hasClass('star_off')) {
            $(this).removeClass('star_off').addClass('star_on');
            $(this).html('<img src = "html/ico/starred.png">');

            $.post('lib/php/data/messages_process.php', {
                'action': 'star_on',
                'id': thisId
            }, function(data) {
                var serverResponse = $.parseJSON(data);
                if (serverResponse.error === true) {
                    notify(serverResponse.message, true);
                }
            });
        } else {
            $(this).removeClass('star_on').addClass('star_off');
            $(this).html('<img src = "html/ico/not_starred.png">');
            $.post('lib/php/data/messages_process.php', {
                'action': 'star_off',
                'id': thisId
            }, function(data) {
                var serverResponse = $.parseJSON(data);
                if (serverResponse.error === true) {
                    notify(serverResponse.message, true);
                }
            });
        }
    });

    //Show reply textarea
    $('div.msg_actions a').live('click', function(event) {
        event.preventDefault();
        var clickType = $(this).attr('class');
        var h;

        //If the user has previously entered reply/forward, then changed mind
        $(this).parent().siblings('div.msg_reply_text').show().find('textarea').removeClass();

        //add choice of recipients if forward is selected
        if (clickType === 'forward') {
            h = $(this).closest('div.msg').height() + 300;
            $(this).parent().siblings('div.msg_forward').show().find('select').chosen();
            //we need more space for forwards
            $(this).closest('div.msg').height(h);
            $(this).parent().siblings('div.msg_reply_text').show().find('textarea').addClass(clickType);
        }

        //Show textarea and add class name to tell send function what action to take.
        if (clickType === 'reply') {
            //make room for textarea
            h = $(this).closest('div.msg').height() + 250;
            $(this).closest('div.msg').height(h);

            //in case user had previously selected forward, then just decided to reply
            $(this).parent().siblings('div.msg_forward').hide();
            //add textarea
            $(this).parent().siblings('div.msg_reply_text').show().find('textarea').addClass(clickType);
        }
    });

    //Send reply to message
    $('button.msg_send').live('click', function() {
        var replyText = $(this).prev().val();
        var threadId = $(this).closest('div.msg').attr('data-id');
        var actionType = $(this).prev().attr('class'); //use the class name to determine action
        var refreshTarget = $(this).parent().siblings('div.msg_replies');
        var msgParent = $(this).closest('div.msg');
        var forwardTos = $(this).parent().siblings('div.msg_forward').find('select').val();

        $.post('lib/php/data/messages_process.php', {
            'action': actionType,
            'thread_id': threadId,
            'reply_text': replyText,
            'forward_tos': forwardTos
        }, function(data) {
            var serverResponse = $.parseJSON(data);
            if (serverResponse.error === true) {
                notify(serverResponse.message, true);
            } else {
                notify(serverResponse.message);
                //refresh to show new replies
                refreshTarget.load('lib/php/data/messages_load.php', {
                    'type': 'replies',
                    'thread_id': threadId
                }, function() {
                    msgParent.animate({'height': '90'}); //close message
                    msgParent.removeClass('msg_opened').addClass('msg_closed').css({'opacity':'.5'});
                    //reset form
                    msgParent.find('div.msg_reply_text').hide().find('textarea').val('');
                    msgParent.find('div.msg_forward').hide();
                    msgParent.find('div.msg_forward select').val('');

                    //scroll closed message into view
                    $('div#msg_panel').scrollTop(msgParent.data('verticalPos'));
                });
            }
        });
    });

    //Handle archiving
    $('a.archive, a.unarchive').live('click', function() {
        var msg = $(this).closest('div.msg');
        var msgId = msg.attr('data-id');
        var thisAction = $(this).attr('class');
        $.post('lib/php/data/messages_process.php', {
            'action': thisAction,
            'id': msgId
        }, function(data) {
            var serverResponse = $.parseJSON(data);
            if (serverResponse.error === true) {
                notify(serverResponse.message, true);
            } else {
                notify(serverResponse.message);
                msg.remove();
            }
        });
    });

    //Handle print
    $('a.print').live('click',function(){
        elPrint($(this).closest('.msg'),'Message');
    });

    //Expand 'to' field when it overflows
    $('p.msg_to_more').live('click',function(event) {
        event.preventDefault();
        var newHeight, newMsgHeight;

        if ($(this).hasClass('ex_tos')) {
        //row of 'to' recipients needs to be expanded
            var tos = $(this).siblings('p.tos');
            newHeight = tos[0].scrollHeight;

            if ($(this).hasClass('expanded')) {
                $(this).removeClass('expanded');
                tos.css({'height': '20'});
                $(this).html('<a href="#">and others</a>');
                //Clip message now tos are hidden
                newMsgHeight = $(this).closest('div.msg').height() - newHeight;
                $(this).closest('div.msg').height(newMsgHeight);
            } else {
                tos.css({'height': newHeight});
                $(this).addClass('expanded');
                $(this).html('<a href="#" class="msg_to_more_hide">Hide</a>');
                //resize message to fit all this information
                newMsgHeight = $(this).closest('div.msg').height() + newHeight;
                $(this).closest('div.msg').height(newMsgHeight);
            }
        } else {
        //row of 'cc' recipients needs to be expanded
            var ccs = $(this).siblings('p.ccs');
            newHeight = ccs[0].scrollHeight;

            if ($(this).hasClass('expanded')) {
                $(this).removeClass('expanded');
                ccs.css({'height': '20'});
                $(this).html('<a href="#">and others</a>');
                //Clip message now ccs are hidden
                newMsgHeight = $(this).closest('div.msg').height() - newHeight;
                $(this).closest('div.msg').height(newMsgHeight);
            } else {
                ccs.css({'height': newHeight});
                $(this).addClass('expanded');
                $(this).html('<a href="#" class="msg_to_more_hide">Hide</a>');
                //resize message to fit all this information
                newMsgHeight = $(this).closest('div.msg').height() + newHeight;
                $(this).closest('div.msg').height(newMsgHeight);
            }
        }
    });

    //handle search
    $('input.messages_search').live('focusin', function() {
        $(this).val('');
        $(this).css({'color': 'black'});
        $(this).next('.msg_search_clear').show();
    });

    $('input.messages_search').live('keyup', function(event) {
        if (event.which === 13) {
            //turn off auto-refresh
            clearTimeout(msgRefresh);
            target.data('startVal',0); //reset start val for infinite scroll
            target.data('searchOn','y');//notify other functions we are searching
            target.data('searchTerm',$(this).val()); //save search term
            target.html('<p>Searching...</p>');
            var search = $(this).val();
            target.load('lib/php/data/messages_load.php', {
                'type': 'search',
                'start':'0',
                's': search
            }, function() {
                target.scrollTop(0);
                layoutMessages();
                //make room for labels
                $('div.msg_bar_left').css({'width':'470px'});
                $('div.msg_bar_right').css({'width':'310px'});
                target.highlight(search);
            });
        }
    });

    $('.msg_search_clear').live('click', function() {
        target.html('<p>Loading...</p>');
        target.data('searchOn','n');
        target.data('searchTerm','');
        target.data('startVal',0);
        $(this).prev().val('Search Messages');
        $(this).prev().css({'color': '#AAA'});
        msgLoad();

        //Restart auto-refresh
        msgRefresh = setInterval(msgLoad, 90000);
        $('div#msg_panel')[0].scrollTop = 0;
        $(this).hide();
    });
});
