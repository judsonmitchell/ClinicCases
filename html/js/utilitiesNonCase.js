 //
//Scripts for casenotes
//

/* global ccTimer, elPrint, validCaseNote, notify */

//Set max height for case notes and add toggle
function sizeCaseNotes(notes, panelTarget) {
    var windowHeight = panelTarget.height();
    var minCaseNoteHeight = panelTarget.height() * 0.3;
    notes.each(function () {
        //cache the height values for use later
        $(this).data('maxCaseNoteHeight', $(this).height());
        $(this).data('minCaseNoteHeight', minCaseNoteHeight);

        var notePercent = $(this).height() / windowHeight * 100;
        if (notePercent > 40) {
            $(this).height(minCaseNoteHeight);
            $(this).append('<div class="more"><a href="#">More</a></div>');
            $(this).css({'overflow': 'hidden'});
        }

    });
}

function loadCaseNotes(panelTarget, id) {

    $(panelTarget).load('lib/php/data/cases_casenotes_load.php', {
        'case_id': id,
        'non_case': '1',
        'start': '0'
    }, function () {
        //set css for casenotes
        $('div.case_detail_panel_tools').css({'height': '15%'});
        $('div.case_detail_panel_tools').css({'max-height': '60px'});
        $('div.case_detail_panel_casenotes').css({'height': '85%'});

        //add buttons; style only one button if user doesn't have permission to add casenotes

        if (!$('.case_detail_panel_tools_right button.button1').length) {
            $('.case_detail_panel_tools_right button.button3')
            .button({icons: {primary: 'fff-icon-printer'}, text: true});
        } else {
            $('.case_detail_panel_tools_right button.button1')
            .button({icons: {primary: 'fff-icon-add'}, text: true})
            .next().button({icons: {primary: 'fff-icon-time'}, text: true})
            .next().button({icons: {primary: 'fff-icon-printer'}, text: true});
        }

        //define div to be scrolled
        var scrollTarget = $(panelTarget).find('.case_detail_panel_casenotes');

        //embed the case number in the scrollTarget object
        scrollTarget.data('CaseNumber', id);

        //bind the scroll event for the window
        $(scrollTarget).bind('scroll', function () {
            addMoreNotes(scrollTarget);
        });

        //set heights
        sizeCaseNotes($(panelTarget).find('.csenote'), $(panelTarget).find('.case_detail_panel_casenotes'));

        //round corners
        $('div.csenote').addClass('ui-corner-all');

        $('#casesearch_NC').val('Search Non-Case');
    });
}

//Load new case notes on scroll
function addMoreNotes(scrollTarget) {
    var caseId = scrollTarget.data('CaseNumber');
    var scrollAmount = scrollTarget[0].scrollTop;
    var scrollHeight = scrollTarget[0].scrollHeight;

    if (scrollAmount === 0 && scrollTarget.hasClass('csenote_shadow')) {
        scrollTarget.removeClass('csenote_shadow');
    } else {
        scrollTarget.addClass('csenote_shadow');
    }

    var scrollPercent = (scrollAmount / (scrollHeight - scrollTarget.height())) * 100;
    var startNum;

    if (scrollPercent > 70) {
        //the start for the query is added to the scrollTarget object
        if (typeof scrollTarget.data('start') === 'undefined') {
            startNum = 20;
            scrollTarget.data('start', startNum);
        } else {
            startNum = scrollTarget.data('start') + 20;
            scrollTarget.data('start', startNum);
        }

        $.post('lib/php/data/cases_casenotes_load.php', {
            'case_id': caseId,
            'start': scrollTarget.data('start'),
            'non_case': '1',
            'update': 'yes'
        }, function (data) {

            //var t represents number of case notes; if 0,return false;
            var t = $(data).find('p.csenote_instance').length;
            if (t === 0) {
                return false;
            } else {
                scrollTarget.append(data);
                sizeCaseNotes($('.csenote'), scrollTarget);
                $('div.csenote').addClass('ui-corner-all');
                //if user has the add case note widget open, make sure opacities are uniform
                if (scrollTarget.find('div.csenote_new').css('display') === 'block') {
                    $('div.csenote').css({'opacity': '.5'});
                }
            }
        });
    }
}

//functions to mimic php nl2br and br2nlP
String.prototype.nl2br = function () {
    var br;
    if (typeof arguments[0] !== 'undefined') {
        br = arguments[0];
    }
    else {
        br = '<br />';
    }
    return this.replace(/\r\n|\r|\n/g, br);
};

String.prototype.br2nl = function () {
    var nl;
    if (typeof arguments[0] !== 'undefined') {
        nl = arguments[0];
    }
    else {
        nl = '\r';
    }
    return this.replace(/\<br(\s*\/|)\>/g, nl);
};


//
//Listeners
//

//show hidden text on clipped case note
$('div.more').live('click', function (event) {
    event.preventDefault();
    var cseNoteParent = $(this).closest('.csenote');
    var cseNoteParentMinHeight = $(this).closest('.csenote').data('minCaseNoteHeight');

    if ($(this).find('a').html() === 'Less') {
        cseNoteParent.css({'height': cseNoteParentMinHeight});
        $(this).find('a').html('More');
    } else {
        cseNoteParent.css({'height': 'auto'});
        $(this).find('a').html('Less');
    }
});

$('input.casenotes_search').live('focusin', function () {
    $(this).val('');
    $(this).css({'color': 'black'});
    $(this).next('.casenotes_search_clear').show();
});


$('input.casenotes_search').live('keyup', function () {
    var resultTarget = $(this).closest('div.case_detail_panel_tools').next();
    var search = $(this).val();
    var caseId = 'NC';

    if (search.length > 2) {
        resultTarget.unbind('scroll');
        resultTarget.load('lib/php/data/cases_casenotes_load.php', {
            'case_id': caseId,
            'search': search,
            'update': 'yes',
            'non_case': '1'
        }, function () {
            resultTarget.scrollTop(0);
            if (search.length) {
                resultTarget.highlight(search);
            }

            sizeCaseNotes($('.csenote'), resultTarget);
            if (resultTarget.hasClass('csenote_shadow')) {
                resultTarget.removeClass('csenote_shadow');
            }

            $('div.csenote').addClass('ui-corner-all');
            resultTarget.bind('scroll.search', function () {
                if ($(this).scrollTop() > 0) {
                    $(this).addClass('csenote_shadow');
                } else {
                    $(this).removeClass('csenote_shadow');
                }
            });
        });
    }
});

$('.casenotes_search_clear').live('click', function () {
    $(this).prev().val('Search Non-Case');
    $(this).prev().css({'color': '#AAA'});
    var resultTarget = $(this).closest('div.case_detail_panel_tools').next();
    var thisCaseNumber = resultTarget.data('CaseNumber');

    resultTarget.load('lib/php/data/cases_casenotes_load.php', {
        'case_id': thisCaseNumber,
        'start': '0',
        'update': 'yes',
        'non_case' : '1'
    }, function () {
        resultTarget.scrollTop(0);
        sizeCaseNotes($('.csenote'), resultTarget);
        $('div.csenote').addClass('ui-corner-all');
        resultTarget.unbind('scroll.search');
        resultTarget.bind('scroll', function () {
            addMoreNotes(resultTarget);
        });
    });
    $(this).hide();
});

//Load new case note widget

$('.case_detail_panel_tools_right button.button1').live('click', function () {
    //make sure case notes are scrolled to top
    $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes').scrollTop(0);

    //display the new case note widget
    var newNote = $(this).closest('.case_detail_panel_tools').siblings().find('.csenote_new');
    newNote.show();

    //apply textarea expander and focus on the textarea
    $(this).closest('.case_detail_panel_tools').siblings()
    .find('textarea').TextAreaExpander(52, 200)
    .css({'color': '#AAA'}).html('Describe what you did...').mouseenter(function () {
        $(this).focus().val('').css({'color': 'black'}).unbind('mouseenter');
    });

    //reduce opacity on the previously entered case notes
    $(this).closest('.case_detail_panel_tools').siblings()
    .find('div.csenote').not('div.csenote_new').css({'opacity': '.5'});

    //create datepicker buttons and style time buttons
    var thisDate = $('input.csenote_date_value').val();

    $('input.csenote_date_value')
    .datepicker({
        dateFormat: 'm/d/yy',
        showOn: 'button',
        buttonText: thisDate,
        onSelect: function (dateText) {
            $(this).next().html(dateText);
        }
    });
    newNote.find('.csenote_action_submit')
    .button({icons: {primary: 'fff-icon-add'}})
    .next().button({icons: {primary: 'fff-icon-cancel'}, text: true});

});

//Start timer
$('.case_detail_panel_tools_right button.button2').live('click', function () {
    var caseName = 'Non-Case';
    var d = new Date();
    var startTime = d.getTime();
    $('#timer .timer_case_name').html(caseName);
    $.cookie('timer_status', 'on');
    $.cookie('timer_start_time', startTime);
    $.cookie('timer_case_name', caseName);
    var caseId = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes').data('CaseNumber');
    $.cookie('timer_case_id', caseId);
    ccTimer(true, startTime);
    $('#timer').show();
});

//Print displayed case notes
$('.case_detail_panel_tools_right button.button3').live('click', function () {
    elPrint($(this).closest('div.case_detail_panel_tools')
    .siblings('div.case_detail_panel_casenotes'), 'Non-Case Time');
});


//User cancels adding new case note
$('button.csenote_action_cancel').live('click', function (event) {
    event.preventDefault();
    loadCaseNotes($('#noncase_panel'), 'NC');
});

//User click to add new case note
$('button.csenote_action_submit').live('click', function (event) {
    event.preventDefault();

    //serialize form values
    var cseVals = $(this).closest('form').serializeArray();

    //get target to load in new casenote
    var thisForm = $(this).closest;
    var resultTarget = $(this).closest('div.case_detail_panel_casenotes');
    var thisCaseNumber = resultTarget.data('CaseNumber');

    //get errors, if any
    var errString = validCaseNote(cseVals);

    //notify user or errors or submit form
    if (errString.length) {
        notify(errString, true);
    } else {
        $.post('lib/php/data/cases_casenotes_process.php', cseVals, function (data) {
            var serverResponse = $.parseJSON(data);
            if (serverResponse.error === true) {
                notify(serverResponse.message, true);
            } else {
                notify(serverResponse.message);
                resultTarget.load('lib/php/data/cases_casenotes_load.php', {
                    'case_id': thisCaseNumber,
                    'start': '0',
                    'non_case' : '1',
                    'update': 'yes'
                }, function () {
                    sizeCaseNotes($('.csenote'), resultTarget);
                });
            }
        });
    }
});

//User deletes a case note.  By rule, user can only delete casenote he created
$('a.csenote_delete').live('click', function (event) {
    event.preventDefault();
    var thisCseNote = $(this).closest('.csenote');
    var thisCseNoteId = thisCseNote.attr('id').split('_');
    var dialogWin = $('<div class=".dialog-casenote-delete" title="Delete this Case Note?">' +
    'This case note will be permanently deleted.  Are you sure?</div>')
    .dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        buttons: {
            'Yes': function () {
                $.post('lib/php/data/cases_casenotes_process.php',
                {query_type: 'delete', csenote_casenote_id: thisCseNoteId[1]}, function (data) {
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error === true) {
                        notify(serverResponse.message, true);
                    } else {
                        thisCseNote.remove();
                        notify(serverResponse.message);
                    }
                });

                $(this).dialog('destroy');
            },
            'No': function () {
                $(this).dialog('destroy');
            }
        }
    });
    $(dialogWin).dialog('open');
});

//edit case note
$('a.csenote_edit').live('click', function (event) {
    event.preventDefault();

    //test to see if there is another note being edited.  If so , return false
    if ($(this).closest('.case_detail_panel_casenotes').find('.csenote_edit_submit').length) {
        notify('Only one case note can be edited at a time', true);
        return false;
    }

    //define case note to be edited
    var thisCseNote = $(this).closest('.csenote');

    //Extract form values from that case note
    var cseNoteId = thisCseNote.attr('id').split('_');

    //Remove highlight html if the user has been searching
    if (thisCseNote.find('span.highlight').length) {
        thisCseNote.find('span.highlight').contents().unwrap();
    }

    var txtVal = thisCseNote.find('p.csenote_instance').html().br2nl();
    var timeVal = $(this).closest('div').children('.csenote_time').html();
    var hourVal;
    var minuteVal;
    if (timeVal.indexOf('.') === -1) {
        hourVal = '0';
        minuteVal = parseInt(timeVal);
    } else {
        var timeParts = timeVal.split('.');
        hourVal = timeParts[0];
        minuteVal = parseInt(timeParts[1]);
    }
    var dateVal = $(this).closest('div').children('.csenote_date').html();

    //define the dummy version of the case note used for editing
    var editNote = $(this).closest('div.csenote').siblings('div.csenote_new').eq(0).clone();
    thisCseNote.after(editNote);
    editNote.show();
    thisCseNote.hide();
    editNote.find('.csenote_bar').css({'background-color': '#FEBBBB'});
    editNote.find('textarea').html(txtVal).TextAreaExpander(52, 200);
    editNote.find('select[name="csenote_hours"]').val(hourVal);
    editNote.find('select[name="csenote_minutes"]').val(minuteVal);
    editNote.find('input[name="query_type"]').val('modify');
    editNote.find('button.csenote_action_submit').html('Done').addClass('csenote_edit_submit').removeClass('csenote_action_submit');
    editNote.find('input.csenote_date_value')
    .val(dateVal)
    .datepicker({dateFormat: 'm/d/yy', showOn: 'button', buttonText: dateVal, onSelect: function (dateText) {
            $(this).next().html(dateText);
        }});

    editNote.find('form').append('<input type="hidden" name="csenote_casenote_id" value="' + cseNoteId[1] + '">');
    editNote.find('button.csenote_action_cancel').unbind('click').bind('click', function () {
        editNote.remove();
        thisCseNote.show();
    });

    //remove the previously bound event from the dummy case note and add a new one
    editNote.find('button.csenote_action_submit').unbind();
    editNote.find('button.csenote_edit_submit').bind('click', function (event) {
        event.preventDefault();
        //serialize form values
        var cseVals = $(this).closest('form').serializeArray();

        //get errors, if any
        var errString = validCaseNote(cseVals);

        //notify user or errors or submit form
        if (errString.length) {
            notify(errString, 'wait');
        } else {
            $.post('lib/php/data/cases_casenotes_process.php', cseVals, function(data) {

                var serverResponse = $.parseJSON(data);
                if (serverResponse.error === true) {
                    notify(serverResponse.message, true);
                } else {
                    //populate the original case note with the new values
                    thisCseNote.find('.csenote_date').html(cseVals[0]['value']);
                    var txtFormat = cseVals[6]['value'].nl2br();
                    thisCseNote.find('p.csenote_instance').html(txtFormat);

                    if (cseVals[1]['value'] === '0') {
                        thisCseNote.find('.csenote_time').html(cseVals[2]['value'] + ' minutes');
                    } else {
                        thisCseNote.find('.csenote_time').html(cseVals[1]['value'] + '.' + cseVals[2]['value'] + ' hours');
                    }

                    notify(serverResponse.message);
                    //remove dummy case note and show newly edited note.
                    editNote.remove();
                    thisCseNote.show();
                }

            });
        }
    });
});
