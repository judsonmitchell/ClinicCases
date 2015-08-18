 //
//Scripts for messages panel on cases tab
//

/* global notify, elPrint */

//Load new messages on scroll
function addMoreMessages(scrollTarget, view, caseId) {

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

        if (scrollTarget.data('searchOn') === 'y') { //we are searching
            $.post('lib/php/data/cases_messages_load.php', {
                'type': view,
                'start': scrollTarget.data('startVal'),
                's': scrollTarget.data('searchTerm'),
                'case_id': caseId,
                'update': 'y'
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
            $.post('lib/php/data/cases_messages_load.php', {
                'type': view,
                'start': scrollTarget.data('startVal'),
                'case_id': caseId,
                'update': 'y'
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
    var oFlow = null;
    $('p.tos').each(function() {
        if (!$(this).next('p').hasClass('msg_to_more'))  {//we haven't already fixed overflow
            oFlow = checkOverflow($(this));
            if (oFlow === true) {
                $(this).css({'overflowY': 'hidden'});
                $(this).after('<p class="msg_to_more ex_tos"><a href="#">and others</a></p>');
            }
        }
    });

    $('p.ccs').each(function() {
        if (!$(this).next('p').hasClass('msg_to_more'))  {//we haven't already fixed overflow
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


//User clicks to open messages window
$('.case_detail_nav #item5').live('click', function() {
    var caseId = $(this).closest('.case_detail_nav').siblings('.case_detail_panel').data('CaseNumber');
    var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
    var scrollTarget = thisPanel.find('.case_detail_panel_casenotes');

    //Get heights
    var toolsHeight = $(this).outerHeight();
    var thisPanelHeight = $(this).closest('.case_detail_nav').height();
    var caseNotesWindowHeight = thisPanelHeight - toolsHeight;

    thisPanel.load('lib/php/data/cases_messages_load.php', {
        'type': 'main',
        'case_id': caseId,
        'start': '0'
    }, function() {
        //Set css
        $('div.case_detail_panel_tools').css({'height': toolsHeight});
        $('div.case_detail_panel_casenotes').css({'height': caseNotesWindowHeight});
        $('div.case_detail_panel_tools_left').css({'width': '30%'});
        $('div.case_detail_panel_tools_right').css({'width': '70%'});

        //Set buttons and handle sending of new message
        $('button.cse_new_msg').button({
            icons: {primary: 'fff-icon-email-go'},text: true
        })
        .click(function() {
            $(this).closest('.case_detail_panel_tools')
                .siblings('.case_detail_panel_casenotes')
                .load('lib/php/data/cases_messages_load.php #msg_new', {
                    'new_message': 'y',
                    'case_id': caseId
                }, function() {
                    //unbind the scroll so we don't see messages here on scroll
                    $(this).unbind('scroll.msg');

                    //if we are scrolled down, get rid of shadow
                    if ($(this).hasClass('csenote_shadow')) {
                        $(this).removeClass('csenote_shadow');
                    }

                    //define new messsage
                    var newMsg = $('div#msg_new');
                    newMsg.show();
                    newMsg.find('select[name = "new_tos[]"], select[name = "new_ccs[]"], select[name = "new_file_msg"]')
                    .chosen();

                    //Cancel
                    $('#msg_new_button_cancel').click(function(event) {
                        event.preventDefault();
                        $('#new_msg_form')[0].reset();
                        //show list of messages again
                        $(this).closest('.case_detail_panel').siblings('.case_detail_nav').find('li#item5').trigger('click');
                        //Rebind the scroll
                        $(this).bind('scroll.msg');
                        notify('New message cancelled');
                    });

                    //Submit
                    $('#msg_new_button_submit').click(function(event) {
                        event.preventDefault();
                        var msgVals = $('#new_msg_form').serializeArray();
                        var target = $(this).closest('.case_detail_panel')
                            .siblings('.case_detail_nav')
                            .find('li#item5');
                        if ($('select[name="new_tos"]').val() === null) {
                            notify('<p>You must select at least one recipient</p>');
                            return false;
                        } else {
                            $.post('lib/php/data/messages_process.php', msgVals, function(data) {
                                var serverResponse = $.parseJSON(data);
                                if (serverResponse.error === true) {
                                    notify(serverResponse.message, true);
                                } else {
                                    $('#new_msg_form')[0].reset();
                                    target.trigger('click');
                                    notify('Message sent');
                                    //bind scroll again
                                    $(this).bind('scroll.msg');
                                }
                            });
                        }
                    });
                });
        });

        //Format messages
        layoutMessages();

        //We are not searching
        thisPanel.data('searchOn', 'n');

        //Apply shadow on scroll
        $(this).children('.case_detail_panel_casenotes').bind('scroll.msg', function() {
            var scrollAmount = $(this).scrollTop();
            if (scrollAmount === 0 && $(this).hasClass('csenote_shadow')) {
                $(this).removeClass('csenote_shadow');
            } else {
                $(this).addClass('csenote_shadow');
                var view = null;

                if (thisPanel.data('searchOn') === 'y')  {//we are searching
                    view = 'search';
                } else {
                    view = 'main';
                }
                addMoreMessages($(this), view, caseId);
            }
        });
    });
});

//Listeners

//Toggle message message open/closed state, retrieve replies
$('div.msg_bar').live('click', function() {
    var msgParent = $(this).parent();
    var thisPanel = $(this).closest('.case_detail_panel');
    if ($(this).parent().hasClass('msg_closed')) {
        msgParent.removeClass('msg_closed').addClass('msg_opened');
        msgParent.find('p.tos, p.ccs, p.subj').css({'color': 'black'});
        msgParent.css({'opacity': '1'});
        var thisMsgId = msgParent.attr('data-id');

        $(this).next('div').find('div.msg_replies').load('lib/php/data/cases_messages_load.php', {
            'type': 'replies',
            'thread_id': thisMsgId
        }, function() {
            //Set the height
            var newHeight = $(this).closest('div.msg')[0].scrollHeight;
            msgParent.animate({'height': newHeight});
            if (thisPanel.data('searchOn') === 'y') {
                var target = $(this).closest('.case_detail_panel');
                thisPanel.highlight(target.data('searchTerm'));
            }
        });

        //Mark message as read
        $.post('lib/php/data/messages_process.php', {'action': 'mark_read','id': thisMsgId});

    } else {
        msgParent.removeClass('msg_opened').addClass('msg_closed');
        msgParent.find('div.msg_reply_text').hide().find('textarea').val('');
        msgParent.find('div.msg_forward').hide();
        msgParent.css({'opacity': '.5'});
        msgParent.animate({'height': '90'});
    }
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
        $.post('lib/php/data/messages_process.php', {'action': 'star_off','id': thisId}, function(data) {
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
    var h = null;
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
        //add textarea
        $(this).parent().siblings('div.msg_reply_text').show().find('textarea').addClass(clickType);
    }

});

//Handle print
$('a.print').live('click', function() {
    elPrint($(this).closest('.msg'), 'Messsage');
});

//Expand 'to' field when it overflows
$('p.msg_to_more').live('click', function(event) {
    event.preventDefault();
    var newHeight;
    var newMsgHeight;

    if ($(this).hasClass('ex_tos')){
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
$('input.cse_msg_search').live('focusin', function() {
    $(this).val('');
    $(this).css({'color': 'black'});
    $(this).next('.cse_msg_search_clear').show();
});

$('input.cse_msg_search').live('keyup', function(event) {
    if (event.which === 13  && $(this).val().length) {
        var target = $(this).closest('.case_detail_panel');
        var caseId = target.data('CaseNumber');
        var dataTarget = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
        target.data('startVal', '0'); //reset start val for infinite scroll
        target.data('searchOn', 'y'); //notify other functions we are searching
        target.data('searchTerm', $(this).val()); //save search term
        dataTarget.html('<p>Searching...</p>');
        var search = $(this).val();

        dataTarget.load('lib/php/data/cases_messages_load.php', {
            'type': 'search',
            'start': '0',
            's': search,
            'case_id': caseId
        }, function() {
            dataTarget.scrollTop(0);
            layoutMessages();
            dataTarget.highlight(search);
        });
    }
});

$('.cse_msg_search_clear').live('click', function() {
    var target = $(this).closest('.case_detail_panel');
    var dataTarget = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
    dataTarget.html('<p>Loading...</p>');
    target.data('searchOn', 'n');
    target.data('searchTerm', '');
    target.data('startVal', 0);
    $(this).prev().val('Search Messages');
    $(this).prev().css({'color': '#AAA'});

    //show list of messages again
    $(this).closest('.case_detail_panel').siblings('.case_detail_nav').find('li#item5').trigger('click');
    dataTarget[0].scrollTop = 0;
    $(this).hide();
});

//Send message
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
            refreshTarget.load('lib/php/data/cases_messages_load.php', {
                'type': 'replies',
                'thread_id': threadId
            }, function() {
                msgParent.animate({'height': '90'}); //close message
                msgParent.removeClass('msg_read msg_opened').addClass('msg_unread msg_closed');
                //reset form
                msgParent.find('div.msg_reply_text').hide().find('textarea').val('');
                msgParent.find('div.msg_forward').hide();
                msgParent.find('div.msg_forward select').val('');

                //scroll closed message into view
                $(this).closest('.case_detail_panel_casenotes').scrollTop(msgParent.data('verticalPos'));
            });
        }
    });
});
