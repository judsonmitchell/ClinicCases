//
//Functions for the case note timer.
//

/* global notify */

//Starts and stops the timer
function ccTimer(toggle, startTime) {
    var timerLoop;
    if (toggle === true) {//start timer
        var getElapsed = function (startTime) {
            var timeNow = new Date();
            var timeNowMs = timeNow.getTime();
            var elapsedMs = timeNowMs - startTime;
            var elapsedMn = elapsedMs / 60000;
            $('.timer_time_elapsed').html(elapsedMn.toFixed());
            timerLoop = setTimeout(function () {
                getElapsed(startTime);
            }, 60000);
        };
        getElapsed(startTime);
        $('#timer_controls button:first').button({icons: {primary: 'fff-icon-stop'}, label: 'Stop'});

        //stop the idle event for idletimeout.js while this timer is running.
        $(document).unbind('idle.idleTimer');
    } else {//stop timer
        clearTimeout(timerLoop);
        $('#timer_inner img').replaceWith('<img src="html/images/timer_stop.jpg">');
        $('#timer_inner .timer_status_text').html('finished');
        $('#timer_controls').css({'text-align': 'left'})
        .append('<textarea name="description"></textarea><button class="timer_add_button">' +
        '</button><button class="timer_cancel_add_button"></button>');
        $('#timer_controls textarea').css({'color': '#AAA', 'width': '60%'})
        .html('Describe What You Did.')
        .mouseenter(function () {
            $(this).focus().val('').css({'color': 'black'}).unbind('mouseenter');
        });
        $('#timer_controls button.timer_add_button').button({icons: {primary: 'fff-icon-add'}, label: 'Add'})
        .next().button({icons: {primary: 'fff-icon-cancel'}, label: 'Cancel'});
    }
}

//Destroys timer
function timerDestroy() {
	$('#timer').hide();
	//Destroy all cookies
	$.cookie('timer_status', null);
	$.cookie('timer_start_time', null);
	$.cookie('timer_case_name', null);
	$.cookie('timer_case_id', null);

	//Put content window back to original height
	var currentContentHeight = $('#content').height();
	$('#content').height(currentContentHeight + 45);

	//Reset the timer html
	$.get('html/templates/interior/timer.php', function (data) {
        $('#timer').replaceWith(data);
    });

	//restart the idletimer.js timer
	$(document).bind('idle.idleTimer', function () {
        if ($.data(document, 'idleTimer') === 'idle' && !self.countdownOpen) {
            var theTimer = $('#idletimeout').data('idletimeout');
            theTimer._stopTimer();
            theTimer.countdownOpen = true;
            theTimer._idle();
        }
    });
}

$(document).ready(function () {
	if ($.cookie('timer_status') === 'on') {
		var caseName = $.cookie('timer_case_name');
		var startTime =     $.cookie('timer_start_time');
		$('#timer .timer_case_name').html(caseName);
		ccTimer(true, startTime);
		$('#timer').show();

		//make room for the timer widget
		var currentContentHeight = $('#content').height();
		$('#content').height(currentContentHeight - 45);
	}

	//Stop timer
	$('#timer_controls button.timer_stop').live('click', function (event) {
		event.preventDefault();
		$(this).hide();
		ccTimer(false);
	});

	//Submit time
	$('#timer_controls button.timer_add_button').live('click', function () {
		//Get variables
		var timerUser = $.cookie('cc_user');
		var d = new Date();
		var timerDate = (d.getMonth() + 1) + '/' + d.getDate() + '/' + d.getFullYear();
		var now = d.getTime();
		var start = $.cookie('timer_start_time');
		var elapsed = ((now - start) / 1000).toFixed();
		var description = $('#timer_controls textarea').val();
		var caseId = $.cookie('timer_case_id');

		//Put variables in an object
		var cseVals = [{name: 'csenote_date', value: timerDate}, {name: 'csenote_seconds', value: elapsed},
        {name: 'csenote_user', value: timerUser}, {name: 'csenote_case_id', value: caseId},
        {name: 'query_type', value: 'add'}, {name: 'csenote_description', value: description}];

		//Check to see if textarea is valid
		if (description === '' || description === 'Describe What You Did.') {
            notify('<p>Please provide a description of what you did.</p>', true);
            return false;
        } else {
			$.post('lib/php/data/cases_casenotes_process.php', cseVals, function (data) {
				timerDestroy();
				//If user is looking at case notes for the timed case, refresh so that it shows new casenote
				if ($('.case_' + caseId).length) {
                    if ($('#utilities_panel').length) { //user is viewing non-case time
                        $('.case_' + caseId)
                        .load('lib/php/data/cases_casenotes_load.php',
                        {'case_id': caseId, 'start': '0', 'non_case': '1', 'update': 'yes'});
                    } else {
                        $('.case_' + caseId)
                        .load('lib/php/data/cases_casenotes_load.php',
                        {'case_id': caseId, 'start': '0', 'update': 'yes'});
                    }
				}
                var serverResponse = $.parseJSON(data);
                notify(serverResponse.message);
            });
		}
	});

	//Cancel adding time
	$('#timer_controls button.timer_cancel_add_button').live('click', function () {
            var dialogWin = $('<div class=".dialog-casenote-delete" title="Delete this Time?">' +
            'You will lose the time you have recorded.  Are you sure?</div>')
            .dialog({
                autoOpen: true,
                resizable: false,
                modal: true,
                buttons: {
                    'Yes': function () {
                        timerDestroy();
                        $(this).dialog('destroy');
                        notify('Timer removed');
                    },
                    'No': function () {
                        $(this).dialog('destroy');
                    }
                }
            });
        });
});
