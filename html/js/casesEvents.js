//
// Scripts for events panel on cases
//

/* global notify, elPrint, validEvent */

//User clicks on Events in left-side navigation
$('.case_detail_nav #item4').live('click', function() {
	var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
    var caseId = $(this).closest('.case_detail_nav')
        .siblings('.case_detail_panel')
        .data('CaseNumber');

    //Get heights
    var toolsHeight = $(this).outerHeight();
    var thisPanelHeight = $(this).closest('.case_detail_nav').height();
    var eventsWindowHeight = thisPanelHeight - toolsHeight;

    thisPanel.load('lib/php/data/cases_events_load.php', {
        'case_id': caseId
    }, function() {
        //Set css
        $('div.case_detail_panel_tools').css({'height': toolsHeight});
        $('div.case_detail_panel_casenotes').css({'height': eventsWindowHeight});
        $('div.case_detail_panel_tools_left').css({'width': '30%'});
        $('div.case_detail_panel_tools_right').css({'width': '70%'});

        //Set buttons
        $('button.new_event').button({icons: {primary: 'fff-icon-calendar-add'},text: true})
            .next().button({icons: {primary: 'fff-icon-printer'},text: true});

        //Round Corners
        $('div.csenote').addClass('ui-corner-all');

        //Apply shadow on scroll
        $(this).children('.case_detail_panel_casenotes').bind('scroll', function() {
            var scrollAmount = $(this).scrollTop();
            if (scrollAmount === 0 && $(this).hasClass('csenote_shadow')) {
                $(this).removeClass('csenote_shadow');
            } else {
                $(this).addClass('csenote_shadow');
            }
        });
    });
});

//Add event
$('.case_detail_panel_tools_right button.new_event').live('click', function() {
    //make sure events are scrolled to top
    $(this).closest('.case_detail_panel_tools')
        .siblings('.case_detail_panel_casenotes').scrollTop(0);

    //display the new contact widget
    var addEventWidget = $(this).closest('.case_detail_panel_tools')
        .siblings()
        .find('div.new_event');
    addEventWidget.show();

    //reduce opacity on the previously entered contact
    $(this).closest('.case_detail_panel_tools').siblings()
        .find('div.event')
        .not('div.csenote_new')
        .css({'opacity': '.5'});

    //Add date/time pickers

    addEventWidget.find('input[name="start"]').datetimepicker({
        ampm: true,
        stepHours: 1,
        stepMinute: 5,
        hour:9,
        minute:0,
        onSelect: function(dateText,inst){ //set the end datetime to conincide with the start
            addEventWidget.find('input[name="end"]')
                .datetimepicker('setDate',dateText);
        }
    });

    addEventWidget.find('input[name="end"]').datetimepicker({
        ampm: true,
        stepHours: 1,
        stepMinute: 5,
        hour:9,
        minute:0
    });

    //Add the chosen widget
    addEventWidget.find('select[name="responsibles"]').chosen();

    //User cancels adding new event
    $(this).closest('.case_detail_panel_tools').siblings()
    .find('button.event_action_cancel').click(function(event) {

        event.preventDefault();
        //reset form
        addEventWidget.find('form')[0].reset();
        addEventWidget.find('span.event_name_live').html('New Event');

        addEventWidget.find('select option')
            .filter(function() {return this.text === 'You';})
            .attr('selected',true);

        //refresh Chosen
        addEventWidget.find('select').trigger('liszt:updated');

        //reset opacity of other case notes
        $(this).closest('.case_detail_panel_casenotes')
            .find('.event').css({'opacity': '1'});
        //hide the widget
        $(this).closest('.new_event').hide();
    });

    //User adds new event
    $(this).closest('.case_detail_panel_tools').siblings()
        .find('button.event_action_submit')
        .click(function(event) {

        event.preventDefault();
        //Do validation
        var eventForm = $(this).closest('form');
        var eventVals = eventForm.serializeArray();
        var resps = eventForm.find('select[name="responsibles"]').val();
        var resps_obj = $.extend({},resps); //convert array to object, thank you, sir: http://stackoverflow.com/a/8547509/49359
        eventVals.unshift(resps_obj); //put this object at the beginning

        //get errors, if any
        var errString = validEvent(eventVals);

        //notify user or errors or submit form
        if (errString.length) {
            notify(errString, true);
        } else {
            //submit the form
            var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
            var target = $(this).closest('.case_detail_panel_casenotes');
            var allDay = false;
            if (eventForm.find('input[name = "all_day"]').is(':checked')){
                allDay = true;
            }
            $.post('lib/php/data/cases_events_process.php', {
                'task': eventForm.find('input[name = "task"]').val(),
                'where': eventForm.find('input[name = "where"]').val(),
                'start': eventForm.find('input[name = "start"]').val(),
                'end': eventForm.find('input[name = "end"]').val(),
                'all_day': allDay,
                'notes': eventForm.find('textarea[name = "notes"]').val(),
                'responsibles': resps,
                'action': 'add',
                'case_id': caseId
            }, function(data) {
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error === true) {
                        notify(serverResponse.message,true);
                    } else {
                        notify(serverResponse.message);
                        target.load('lib/php/data/cases_events_load.php div.case_detail_panel_casenotes', {
                            'case_id': caseId
                        });
                    }
                });
        }
    });

});

//Updates the displayed event name when user creates a new event
$('input[name="task"]').live('keyup', function() {
    $(this).closest('.new_event').find('span.event_name_live').html(escapeHtml($(this).val()));
});

//handle search
$('input.events_search').live('focusin', function() {
    $(this).val('');
    $(this).css({'color': 'black'});
    $(this).next('.events_search_clear').show();
});

$('input.events_search').live('keyup', function() {
    var resultTarget = $(this).closest('div.case_detail_panel_tools').next();
    var search = $(this).val();
    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');

    resultTarget.load('lib/php/data/cases_events_load.php div.case_detail_panel_casenotes', {
        'case_id': caseId,
        'q': search
    }, function() {
        resultTarget.scrollTop(0);
        if (search.length) {
            resultTarget.highlight(search);
        }

        if (resultTarget.hasClass('csenote_shadow')) {
            resultTarget.removeClass('csenote_shadow');
        }

        $('div.event').addClass('ui-corner-all');
        resultTarget.bind('scroll.search', function() {
            if ($(this).scrollTop() > 0) {
                $(this).addClass('csenote_shadow');
            } else {
                $(this).removeClass('csenote_shadow');
            }
        });
    });
});

$('.events_search_clear').live('click', function() {
    $(this).prev().val('Search Events');
    $(this).prev().css({'color': '#AAA'});
    var resultTarget = $(this).closest('div.case_detail_panel_tools').next();
    var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
    resultTarget.load('lib/php/data/cases_events_load.php div.case_detail_panel_casenotes', {
        'case_id': caseId
    }, function() {
        resultTarget.scrollTop(0);
        $('div.event').addClass('ui-corner-all');
    });

    $(this).hide();
});

//Delete Event
$('a.event_delete').live('click', function(event) {
    event.preventDefault();
    var thisEvent = $(this).closest('.event');
    var thisEventId = thisEvent.attr('data-id');
    var dialogWin = $('<div class=".dialog-casenote-delete" title="Delete this Event?">This event' +
    ' will be permanently deleted.  Are you sure?</div>')
    .dialog({
        autoOpen: false,
        resizable: false,
        modal: true,
        buttons: {
            'Yes': function() {
                $.post('lib/php/data/cases_events_process.php', {
                    'action': 'delete',
                    'event_id': thisEventId
                }, function(data) {
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error === true) {
                        notify(serverResponse.message,true);
                    } else {
                        notify(serverResponse.message);
                        thisEvent.remove();
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

//Print displayed events
$('.case_detail_panel_tools_right button.events_print').live('click', function() {
    elPrint($(this).closest('div.case_detail_panel_tools')
        .siblings('div.case_detail_panel_casenotes'),'Case Data: ' + $(this).closest('.case_detail_panel')
        .siblings('.case_detail_bar')
        .find('.case_title')
        .text());
});

//edit event
$('a.event_edit').live('click', function(event) {
    event.preventDefault();
    //test to see if there is another contact being edited.  If so , return false
    if ($(this).closest('.case_detail_panel_casenotes').find('.event_edit_submit').length) {
        notify('Only one event can be edited at a time', true);
        return false;
    }

    //define event to be edited
    var thisEvent = $(this).closest('.event');
    var eventId = thisEvent.attr('data-id');
    var allDayVal;

    //get form values
    var taskVal = thisEvent.find('span.event_task_title').html();
    var startVal = thisEvent.find('span.event_start').html();
    var endVal = thisEvent.find('span.event_end').html();
    var locationVal = thisEvent.find('span.event_location').html();
    var notesVal = thisEvent.find('span.event_notes').html();
    if (thisEvent.find('span.event_all_day').length) {
        allDayVal = 'on';
    }
    var usersArray = [];
    thisEvent.find('span.user_identifier').each(function(){
        usersArray.push($(this).attr('data'));
    });

    //define the dummy version of the event used for editing
    var editEvent = $(this).closest('div.event')
        .siblings('div.new_event')
        .clone()
        .addClass('event_edit');
    thisEvent.after(editEvent);

    //get position of contact so that it will displayed correctly after edit
    var editEventPosition = thisEvent.offset().top;

    editEvent.find('span.event_name_live').html(taskVal);
    editEvent.find('input[name="task"]').val(taskVal);
    editEvent.find('input[name="where"]').val(locationVal);
    editEvent.find('input[name="end"]').val(endVal);
    editEvent.find('textarea[name="notes"]').val(notesVal);
    editEvent.find('select[name="responsibles"]').val(usersArray);


    //Add datetimepickers to event to be edited
    var editDateTimeStart = editEvent.find('input[name="start"]').datetimepicker({
        ampm: true,
        stepHours: 1,
        stepMinute: 5
    });
    editDateTimeStart.datetimepicker('setDate',(new Date(startVal)));

    var editDateTimeEnd = editEvent.find('input[name="end"]').datetimepicker({
        ampm: true,
        stepHours: 1,
        stepMinute: 5
    });
    editDateTimeEnd.datetimepicker('setDate',(new Date(endVal)) );

    //Put Chosen on select
    editEvent.find('select[name="responsibles"]').chosen();
    //for some reason, Chosen needs to know the widths
    editEvent.find('div.chzn-container, div.chzn-drop').css({'width':'150px'});

    //set css
    editEvent.find('.csenote_bar').css({'background-color': '#FEBBBB'});
    editEvent.find('button.event_action_submit').html('Done')
        .addClass('event_edit_submit')
        .removeClass('event_action_submit');
    editEvent.find('button.event_action_cancel').addClass('event_edit_cancel').removeClass('event_action_cancel');
    editEvent.show();
    thisEvent.hide();

    //user cancels editing contact
    editEvent.find('button.event_edit_cancel').click(function(event) {
        event.preventDefault();
        editEvent.hide().remove();
        thisEvent.show();
    });

    //user submits edits
    editEvent.find('button.event_edit_submit').click(function(event) {
        event.preventDefault();
        //Do validation
        var eventForm = $(this).closest('form');
        var eventVals = eventForm.serializeArray();
        var resps = editEvent.find('select[name="responsibles"]').val();
        var resps_obj = $.extend({},resps);
        eventVals.unshift(resps_obj);

        //get errors, if any
        var errString = validEvent(eventVals);

        //notify user or errors or submit form
        if (errString.length) {
            notify(errString, true);
        } else {
            var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
            var target = $(this).closest('.case_detail_panel_casenotes');

            $.post('lib/php/data/cases_events_process.php', {
                'task': eventForm.find('input[name = "task"]').val(),
                'where': eventForm.find('input[name = "where"]').val(),
                'start': eventForm.find('input[name = "start"]').val(),
                'end': eventForm.find('input[name = "end"]').val(),
                'all_day': eventForm.find('input[name = "all_day"]').val(),
                'notes': eventForm.find('textarea[name = "notes"]').val(),
                'responsibles':resps,
                'action': 'edit',
                'case_id': caseId,
                'event_id' : eventId
            }, function(data) {
                var serverResponse = $.parseJSON(data);
                if (serverResponse.error === true) {
                    notify(serverResponse.message,true);
                } else {
                    notify(serverResponse.message);
                    target.load('lib/php/data/cases_events_load.php div.case_detail_panel_casenotes', {
                        'case_id': caseId
                    },function(){
                        //Scroll to original position of edited conact
                        var editedEvent = target.find('div.contact[data-id = "' + eventId + '"]');
                        target.scrollTop(editEventPosition);
                    });
                }
            });
        }
    });
});
