 //Scripts for Home page

/* global notify, validQuickCaseNote, validEvent */
function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };

  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

$(document).ready(function() {

    //set header widget
    $('#home_nav').addClass('ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr');

    //Add navigation buttons
    $('.home_nav_choices').buttonset();

    //Add quick add button
    $('button#quick_add').button({
        icons: {primary: 'fff-icon-add'},
        text: true
    }).click(function() {
        $('#quick_add_form').dialog('open');
    });

    //Add navigation actions
    var target = $('div#home_panel');


    var activitiesLoad = function(target) {
        target.load('lib/php/data/home_activities_load.php', function() {

            //enable download when user clicks a document link
            $('a.doc_view').live('click', function(event) {
                event.preventDefault();
                var itemId = $(this).attr('data-id');
                if ($(this).hasClass('url')) { //Link is a url
                    $.post('lib/php/data/cases_documents_process.php', {
                        'action': 'open',
                        'item_id': itemId,
                        'doc_type': 'document'
                    }, function(data) {
                        var serverResponse = $.parseJSON(data);
                        window.open(serverResponse.target_url, '_blank');
                    });
                }
                else if ($(this).hasClass('ccd')) {
                //Link is a ClinicCases document.  Just direct user to case documents for now TODO
                    var url = $(this).closest('p').siblings('p').find('a').attr('href');
                    window.location.href = url;
                } else if ($(this).hasClass('pdf')) {  //a pdf document, so load viewer
                    if (Object.create){ //informal browser check for ie8
                        //Show pdfjs viewer
                        $('#pdf-viewer').show();
                        $('#frme').attr('src', 'lib/javascripts/pdfjs/web/viewer.html?item_id=' + itemId);

                        //Add listener to close pdf viewer
                        $('#pdf-viewer').click(function(){
                            $('#frme').attr('src','');
                            $(this).hide();
                        });

                        //Close pdfviewer on escape key press
                        $('body').bind('keyup.pdfViewer', function (e){
                            if (e.keyCode === 27){
                                $('#frme').attr('src','');
                                $('#pdf-viewer').hide();
                            }
                        });
                    } else {
                        //pdfjs is not supported; revert to download
                        $.download('lib/php/data/cases_documents_process.php', {
                            'item_id': itemId,
                            'action': 'open',
                        });
                    }
                }
                else {
                    $.download('lib/php/data/cases_documents_process.php', {
                        'item_id': itemId,
                        'action': 'open',
                        'doc_type': 'document'
                    }); //any other document, download it.
                }
            });

            //Remove last hr for styling purposes
            target.find('hr').last().remove();
        });
    };

    $('#activity_button').click(function() {
        activitiesLoad(target);
        //Update activities stream periodically while it is being viewed
        window.activitiesRefresh = setInterval(function() {
            $.ajax({
                url: 'lib/php/data/home_activities_load.php',
                success: function(data) {
                    target.html(data);
                    //Remove last hr for styling purposes
                    target.find('hr').last().remove();
                },
                dataType: 'html'
            });
        },90000);

    });

    $('#upcoming_button').click(function() {
        clearInterval(window.activitiesRefresh);
        target.load('html/templates/interior/home_upcoming.php', function() {
            $('#calendar').fullCalendar({
                theme: true,
                aspectRatio: 2,
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                eventSources: ['lib/php/data/home_events_load.php'],
                viewDisplay: function(view){
                    if($('img.ical').length < 1) {
                        $('span.fc-button-month').before('<a href="feeds/ical.php?key=' + $('#home_nav').attr('data-key') +
                        '" target="_new"><img class="ical" title="Your iCal feed" src="html/ico/ical.png"></a>');
                    }
                },
                dayClick: function(date, allDay, jsEvent, view) {
                    $('#quick_add').trigger('click');
                    $('#quick_add_nav a:eq(1)').trigger('click');
                    $('#ev_start').datetimepicker('setDate',date);
                },
                eventClick: function(event) {
                    if (event.allDay === false) {
                        event.allDay = '';
                    } else {
                        event.allDay = 'Yes';
                    }

                    if (event.end === null) {
                        event.end = '';
                    }

                    var route = null;

                    if (event.caseId !== 'NC') { //only show case link if this is a case-related event
                        route = '<p><label>Case:</label> <a href="index.php?i=Cases.php#cases/' +
                        event.caseId + '">' + event.caseName + '</a></p>';
                    } else {
                        route = '';
                    }

                    //generate the event detail window
                    $('div#event_detail_window')
                    .html('<a class="event_detail_close" href="#"><img src="html/ico/cross.png" border=0 title="Close">' +
                    '</a><h3>' + escapeHtml(event.shortTitle)+ '</h3><div id = "event_users_display"></div><hr /><p><label>Start:' +
                    '</label> ' + event.start + '</p><p><label>End: </label> ' + event.end + '</p><p><label>All Day:</label>' +
                     event.allDay + '</p><p><label>Where: </label>' + escapeHtml(event.where) + '</p>' + route + '<p id =' +
                     '"event_detail_desc"><label>Description: </label>' + escapeHtml(event.description) + '</p>')
                    .dialog('open');

                    if (event.canDelete === true) {
                        $('p#event_detail_desc')
                        .after('<br /><br /><p><a href = "#" class="event_detail_delete" data-id="' +
                        event.id + '">Delete</a></p>');
                    }

                    if (event.largeGroup === true)  {//provide more space for numberous thumbnails
                        $('div#event_users_display').css({'height': '90px','overflow-y': 'scroll'});
                    }

                    //insert thumbnails of users who are assigned to event
                    $(event.users).each(function() {
                        var thumbImg = null;
                        var userData = this;
                        //check to see if the user has a thumbnail
                        $.ajax({
                            url: 'people/tn_' + userData.user_id + '.jpg',
                            type: 'HEAD',
                            error: function() {
                                thumbImg = 'people/tn_no_picture.png';
                                var userThumb = '<img class="thumbnail-mask" src="' + thumbImg + '" title="' + userData.full_name +
                                '" alt="' + userData.full_name + '">';
                                $('div#event_users_display').append(userThumb);
                            },
                            success: function() {
                                thumbImg = 'people/tn_' + userData.user_id + '.jpg';
                                var userThumb = '<img class="thumbnail-mask" src="' + thumbImg + '" title="' + userData.full_name + '" alt="' +
                                userData.full_name + '">';
                                $('div#event_users_display').append(userThumb);
                            }
                        });
                    });
                    //For now, to edit an event, you must go to the case itself and do the editing.  
                    //If it is a non-case event, you must delete and redo.  TODO  ability to edit non-case events
                    $('a.event_detail_delete').live('click', function(event) {
                        event.preventDefault();
                        var eventId = $(this).attr('data-id');
                        var dialogWin = $('<div class="dialog-casenote-delete" title="Delete this Event?">' +
                        'This event will be permanently deleted.  Are you sure?</div>')
                        .dialog({
                            autoOpen: false,
                            resizable: false,
                            modal: true,
                            buttons: {
                                'Yes': function() {
                                    $.post('lib/php/data/cases_events_process.php', {
                                        action: 'delete',
                                        event_id: eventId
                                    }, function(data) {
                                        var serverResponse = $.parseJSON(data);
                                        if (serverResponse.error === true) {
                                            notify(serverResponse.message, true);
                                        } else {
                                            notify(serverResponse.message);
                                            $('#calendar').fullCalendar('removeEvents', eventId);

                                            $('a.event_detail_close').trigger('click');
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
                }
            });
        });

    });

    //Set default view - activities
    $('#activity_button').trigger('click').next('label').addClass('ui-state-active');

    //Create modal quick add form dialog.
    //Position dialog to the bottom of the quick add button
    var x = $('button#quick_add').offset().left - 175;
    var y = $('button#quick_add').offset().top + 40;

    $('#quick_add_form').dialog({
        autoOpen: false,
        height: 520,
        width: 325,
        modal: true,
        position: [x, y]
    }).siblings('.ui-dialog-titlebar').remove();

    //Create modal dialog for event detail
    $('div#event_detail_window').dialog({
        autoOpen: false,
        height: 400,
        width: 500,
        modal: true
    }).siblings('.ui-dialog-titlebar').remove();

    //Toggle between adding casenote and event
    $('#quick_add_form a.toggle').click(function(event) {
        event.preventDefault();
        if ($(this).hasClass('active')) {
            return false;
        } else {
            $(this).addClass('active');
            $(this).siblings('a.toggle').removeClass('active');
            $('div.toggle_form').toggle();
        }
    });

    //Create datepickers
    $('#cn_date').datepicker();
    $('#cn_date').datepicker('setDate', new Date());
    $('#ev_start').datetimepicker({
        ampm: true,
        stepHours: 1,
        stepMinute: 5,
        hour: 9,
        minute: 0,
        onSelect: function(dateText, inst) { //set the end datetime to conincide with the start
            $('#ev_end').datetimepicker('setDate', dateText);
        }
    });
    $('#ev_end').datetimepicker({
        ampm: true,
        stepHours: 1,
        stepMinute: 5,
        hour: 10,
        minute: 0
    });

    //Add chosen to selects
    $('select#cn_case').chosen();
    $('select#ev_case').chosen();
    $('select#ev_users').chosen();


    //Style case note submit button and handle case note submit
    $('button#quick_add_cn_submit').button({
        icons: {primary: 'fff-icon-add'},
        text: true
    })
    .click(function(event) {
        event.preventDefault();

        //serialize form values
        var cseVals = $(this).closest('form[name="quick_cn"]').serializeArray();
        var errString = validQuickCaseNote(cseVals);

        //notify user or errors or submit form
        if (errString.length) {
            $(this).closest('p').siblings('p.error').html(errString);
            return false;
        } else {
            $.post('lib/php/data/cases_casenotes_process.php', cseVals, function(data) {
                var serverResponse = $.parseJSON(data);
                if (serverResponse.error === true) {
                    $(this).closest('p').siblings('p.error').html(serverResponse.message);
                    return false;
                } else {
                    notify(serverResponse.message);
                    if ($('input#activity_button').next().hasClass('ui-state-active'))  {
                        //We are looking at activities
                        activitiesLoad(target);
                    }
                    $('a.quick_add_close').trigger('click');
                }
            });
        }
    });

    //Style event submit button and handle event submit
    $('button#quick_add_ev_submit').button({
        icons: {primary: 'fff-icon-add'},
        text: true
    })
    .click(function(event) {
        event.preventDefault();

        //serialize form values
        var evForm = $('form[name="quick_event"]');
        var evVals = evForm.not('select[name="responsibles"]').serializeArray();
        var resps = evForm.find('select[name="responsibles"]').val();
        var resps_obj = $.extend({}, resps);
        evVals.unshift(resps_obj); //put this object at the beginning
        var errString = validEvent(evVals);

        //notify user or errors or submit form
        if (errString.length) {
            $(this).closest('p').siblings('p.error').html(errString);
            return false;
        } else {
            var allDayVal = null;
            if (evForm.find('input[name = "all_day"]').is(':checked')) {
                allDayVal = 'on';
            } else {
                allDayVal = 'off';
            }
            $.post('lib/php/data/cases_events_process.php', {
                'task': evForm.find('input[name = "task"]').val(),
                'where': evForm.find('input[name = "where"]').val(),
                'start': evForm.find('input[name = "start"]').val(),
                'end': evForm.find('input[name = "end"]').val(),
                'all_day': allDayVal,
                'notes': evForm.find('textarea[name = "notes"]').val(),
                'responsibles': resps,
                'action': 'add',
                'case_id': evForm.find('select[name = "case_id"]').val()
            }, function(data) {
                var serverResponse = $.parseJSON(data);
                if (serverResponse.error === true) {
                    $(this).closest('p').siblings('p.error').html(serverResponse.message);
                    return false;
                } else {
                    notify(serverResponse.message);
                    if ($('input#upcoming_button').next().hasClass('ui-state-active'))  {//We are looking at events
                        $('#calendar').fullCalendar('refetchEvents');

                    }
                    $('a.quick_add_close').trigger('click');

                    //reset selects
                    evForm[0].reset;
                    $('#ev_users').trigger('liszt:updated');
                }
            });
        }
    });

    //Close quick add dialog
    $('a.quick_add_close').click(function(event) {
        event.preventDefault();

        //reset forms, clear errors
        $('#quick_add_form form').each(function() {
            this.reset();
        }).find('p.error').html('');

        //Put date back to default - today
        $('#cn_date').datepicker('setDate', new Date());
        $('#ev_start').datetimepicker('setDate','');

        //Reset case select value to Non-Case time
        $('select#cn_case').val('NC');
        $('input.ui-autocomplete-input').val($('select#cn_case option:selected').text());

        //Close dialog
        $('#quick_add_form').dialog('close');
    });

    //Close event detail dialog
    $('a.event_detail_close').live('click', function(event) {
        event.preventDefault();
        $('#event_detail_window').dialog('close');
    });
});
