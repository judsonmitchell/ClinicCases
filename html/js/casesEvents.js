//
// Scripts for events panel on cases
//

//User clicks on Events in left-side navigation
$('.case_detail_nav #item4').live('click', function() {

	var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
    var caseId = $(this).closest('.case_detail_nav')
        .siblings('.case_detail_panel')
        .data('CaseNumber');

    //Get heights
    var toolsHeight = $(this).outerHeight();
    var thisPanelHeight = $(this).closest('.case_detail_nav').height();
    var documentsWindowHeight = thisPanelHeight - toolsHeight;

    thisPanel.load('lib/php/data/cases_events_load.php', {'case_id': caseId}, function() {

        //Set css
        $('div.case_detail_panel_tools').css({'height': toolsHeight});
        $('div.case_detail_panel_casenotes').css({'height': caseNotesWindowHeight});
        $('div.case_detail_panel_tools_left').css({'width': '30%'});
        $('div.case_detail_panel_tools_right').css({'width': '70%'});

        //Set buttons
        $('button.new_event').button({icons: {primary: "fff-icon-calendar-add"},text: true})
            .next().button({icons: {primary: "fff-icon-printer"},text: true});

        //Round Corners
        $('div.csenote').addClass('ui-corner-all');

        //Size
        //sizeContacts($(this).find('.contact'), thisPanel);

        //Apply shadow on scroll
        $(this).children('.case_detail_panel_casenotes').bind('scroll', function() {
            var scrollAmount = $(this).scrollTop();
            if (scrollAmount === 0 && $(this).hasClass('csenote_shadow'))
            {
                $(this).removeClass('csenote_shadow');
            }
            else
            {
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
        addEventWidget.find('span.event_name_live').html("New Event");

        addEventWidget.find('select option')
            .filter(function() {return this.text == 'You';})
            .attr('selected',true);

        //refresh Chosen
        addEventWidget.find('select').trigger("liszt:updated");

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
        if (errString.length)
        {
            notify(errString, true);
        }
        else
        {
            //submit the form
            var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');

            var target = $(this).closest('.case_detail_panel_casenotes');

            $.post('lib/php/data/cases_events_process.php', {
                'task': eventForm.find('input[name = "task"]').val(),
                'where': eventForm.find('input[name = "where"]').val(),
                'start': eventForm.find('input[name = "start"]').val(),
                'end': eventForm.find('input[name = "end"]').val(),
                'all_day': eventForm.find('input[name = "all_day"]').val(),
                'notes': eventForm.find('textarea[name = "notes"]').val(),
                'responsibles': resps,
                'action': 'add',
                'case_id': caseId
                }, function(data) {

                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error === true)
                                {notify(serverResponse.message,true);}
                        else
                        {
                            notify(serverResponse.message);
                            target.load('lib/php/data/cases_events_load.php div.case_detail_panel_casenotes', {'case_id': caseId});
                        }

                });
        }

    });

});

//Updates the displayed event name when user creates a new event
$('input[name="task"]').live('keyup', function() {
    $(this).closest('.new_event').find('span.event_name_live').html($(this).val());
});


