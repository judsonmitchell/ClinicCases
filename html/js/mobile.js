/* global unescape, moment, notify */

//Get url parameters
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function escapeHtml(text) {
  return text
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/'/g, '&quot;')
      .replace(/'/g, '&#039;');
}

//Responsive tabs feature
(function($) {

    'use strict';

    $(document).on('show.bs.tab', '.nav-tabs-responsive [data-toggle="tab"]', function(e) {
        var $target = $(e.target);
        var $tabs = $target.closest('.nav-tabs-responsive');
        var $current = $target.closest('li');
        var $parent = $current.closest('li.dropdown');
        $current = $parent.length > 0 ? $parent : $current;
        var $next = $current.next();
        var $prev = $current.prev();
        var updateDropdownMenu = function($el, position) {
            $el
            .find('.dropdown-menu')
            .removeClass('pull-xs-left pull-xs-center pull-xs-right')
            .addClass('pull-xs-' + position);
        };

        $tabs.find('>li').removeClass('next prev');
        $prev.addClass('prev');
        $next.addClass('next');

        updateDropdownMenu($prev, 'left');
        updateDropdownMenu($current, 'center');
        updateDropdownMenu($next, 'right');
    });

})(jQuery);


$(document).ready(function () {
    // show active tab on reload
    if (location.hash !== '') {
        $('a[href="' + location.hash + '"]').tab('show');
    }

    // remember the hash in the URL without jumping
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        if(history.pushState) {
            history.pushState(null, null, '#'+$(e.target).attr('href').substr(1));
        } else {
            location.hash = '#'+$(e.target).attr('href').substr(1);
        }
    });

    //Documents have multiple levels, so user will expect to go to root when clicking
    //documents tab if they have nagivated down folders
    $('a[href="#caseDocs"]').click(function (e){
        if (location.search.indexOf('path') !== -1){
            location.search = '?i=Case.php&id=' + getParameterByName('id');
        }
    });

    //Display cases based on open/closed status
    $('select[name="case-status"]').change(function () {
        $('li.table-case-item').removeClass('search-result-hit search-result-miss');
        $('.table-case-item').toggle();
    });

    //Search Cases
    $('input.case-search').keyup(function () {
        var searchVal = $(this).val().toLowerCase();
        var caseStatus = $('select[name="case-status"]').val();
        var targetClass;
        if (caseStatus === 'open') {
            targetClass = 'table-case-open';
        } else {
            targetClass = 'table-case-closed';
        }
        $('li.table-case-item').removeClass('search-result-hit search-result-miss');
        $('li.' + targetClass).each(function () {
            if ($(this).find('a').text().toLowerCase().indexOf(searchVal) !== -1) {
                $(this).addClass('search-result-hit');
            } else {
                $(this).addClass('search-result-miss');
            }
        });

    });

    //Hide system case id
    $('#caseData dd:eq(0)').hide();
    $('#caseData dt:eq(0)').hide();

    //Handle document downloads
    $('a.doc-item').click(function (event) {
        event.preventDefault();
        var itemId = $(this).attr('data-id');
        var itemExt = $(this).attr('data-ext');
        if (itemExt === 'url') {
            $.post('lib/php/data/cases_documents_process.php',
            {'item_id': itemId, 'action': 'open', 'doc_type': 'document'},
            function (data) {
                var serverResponse = $.parseJSON(data);
                window.open(serverResponse.target_url, '_blank');
            });
        } else if (itemExt === 'ccd') {
            $.post('lib/php/data/cases_documents_process.php',
            {'item_id': itemId, 'action': 'open', 'doc_type': 'document'},
            function (data) {
                var serverResponse = $.parseJSON(data);
                var hideList;
                var ccdItem = '<a class="btn btn-primary btn-sm ccd-clear btn" href="#"><span class="fa fa-chevron-left"></span> Back</a><h2>' +
                unescape(serverResponse.ccd_title) + '</h2>' + serverResponse.ccd_content;
                if ($('.doc-list').length) {
                    hideList = $('.doc-list').detach();
                    $('#caseDocs').append(ccdItem);
                    //Close a ccd document after viewing
                    $('.tab-content').on('click', 'a.ccd-clear', function (event) {
                        event.preventDefault();
                        $('#caseDocs').html('').append(hideList);
                    });
                } else {
                    hideList = $('#activities').detach();
                    $('.container .row').eq(2).append(ccdItem).addClass('pad-home-ccd');
                    $('.container').on('click', 'a.ccd-clear', function (event) {
                        event.preventDefault();
                        $('.container .row').eq(2).html('').removeClass('pad-home-ccd').append(hideList);
                    });
                }
            });
        } else {
            $.download('lib/php/data/cases_documents_process.php', {'item_id': itemId, 'action': 'open', 'doc_type': 'document'});
        }
    });

    //Add chosen to selects
    //Must initialize with size on hidden div: see https://github.com/harvesthq/chosen/issues/1297
    $('#ev_users').chosen({ width: '100%'});
    //Make chzn a little more bootstrappy
    $('.chzn-choices').css({'padding' : '5px'}).addClass('form-control');
    $('#state').addClass('form-control');

    //Submit Quick Adds
    //Case notes
    $.validator.addMethod('timeReq', function (value) {
        return !(value === '0' && $('input[name="csenote_hours"]').val() === '0');
    }, 'You must enter some time.');

    $.validator.addMethod('nameReq', function (value) {
        return !(value === '' && $('input[name="first_name"]').val() === '' && $('input[name="organization"]').val() === '');
    }, 'Please provide the name of a person or organziation');

    $('form[name="quick_cn"]').validate({
        errorClass: 'text-danger',
        errorElement: 'span',
        rules: {
            csenote_minutes: {timeReq: true}
        },
        submitHandler: function (form) {
            var thisForm = $('form[name="quick_cn"]');
            var dateVals = $('#cn_date').val().split('-');
            var dateVal = dateVals[1] + '/' + dateVals[2] + '/' +  dateVals[0];
            $('input[name="csenote_date"]').val(dateVal);
            $.post('lib/php/data/cases_casenotes_process.php', thisForm.serialize(), function (data) {
                var serverResponse = $.parseJSON(data);
                if (serverResponse.error) {
                    notify(serverResponse.message, true,'error');
                } else {
                    var successMsg = '<p class="text-success">' + serverResponse.message +
                    '</p><p><a class="btn btn-primary show-form" href="#">Add Another?</a></p>';
                    $('#cn_hours, #cn_minutes').val('0');
                    $('#csenote_description').val('');
                    var hideForm = $('form[name="quick_cn"]').detach();
                    $('#qaCaseNote').append(successMsg);
                    $('a.show-form').click(function (event) {
                        event.preventDefault();
                        $('#qaCaseNote').html('').append(hideForm);
                    });
                }
            });
        }
    });

    //Case events
    $('form[name="quick_event"]').validate({
        errorClass: 'text-danger',
        errorElement: 'span',
        submitHandler: function () {
            var thisForm = $('form[name="quick_event"]');
            var dateValStart = $('#c_start').val().split('-');
            var startVal = dateValStart[1] + '/' + dateValStart[2] + '/' + dateValStart[0] + ' ' +
            $('#ce_hour_start').val() + ':' +  $('#ce_minute_start').val() + ' ' + $('#ce_ampm_start').val();
            $('input[name="start"]').val(startVal);

            var dateValEnd = $('#c_end').val().split('-');
            var endVal = dateValEnd[1] + '/' + dateValEnd[2] + '/' + dateValEnd[0] + ' ' +
            $('#ce_hour_end').val() + ':' +  $('#ce_minute_end').val() + ' ' + $('#ce_ampm_end').val();
            $('input[name="end"]').val(endVal);

            //serialize form values
            var evVals = thisForm.not('select[name="responsibles"]').serializeArray();
            var resps = thisForm.find('select[name="responsibles"]').val();
            var respsObj = $.extend({}, resps);
            evVals.unshift(respsObj); //put this object at the beginning
            var allDayVal = null;
            if (thisForm.find('input[name = "all_day"]').is(':checked')) {
                allDayVal = 'on';
            } else {
                allDayVal = 'off';
            }

            $.post('lib/php/data/cases_events_process.php', {
                'task': thisForm.find('input[name = "task"]').val(),
                'where': thisForm.find('input[name = "where"]').val(),
                'start': thisForm.find('input[name = "start"]').val(),
                'end': thisForm.find('input[name = "end"]').val(),
                'all_day': allDayVal,
                'notes': thisForm.find('textarea[name = "notes"]').val(),
                'responsibles': resps,
                'action': 'add',
                'case_id': thisForm.find('select[name = "case_id"]').val()
            }, function (data) {
                var serverResponse = $.parseJSON(data);
                if (serverResponse.error) {
                    notify(serverResponse.message, true,'error');
                } else {
                    notify(serverResponse.message, false,'success');
                    var successMsg = '<p class="text-success">' + serverResponse.message +
                    '</p><p><a class="btn btn-primary show-form" href="#">Add Another?</a></p>';
                    $('#qaEvent').html(successMsg);
                    $('a.show-form').click(function (event) {
                        event.preventDefault();
                        location.reload();
                    });
                }
            });
        }
    });

    //Convenience methods for advancing end date and time
    $('#c_start').change(function(e) {
        $('#c_end').val($(this).val());
    });

    $('#ce_hour_start').change(function(e) {
        if ($(this).val() === '12'){
            $('#ce_hour_end').val('1');
        } else {
            $('#ce_hour_end').val(parseInt($(this).val()) + 1);
        }

        if ($(this).val() === '11'){
            $('#ce_ampm_start').val() === 'AM' ? $('#ce_ampm_end').val('PM') : $('#ce_ampm_end').val('AM');
        }
    });

    $('#ce_ampm_start').change(function(e) {
        $('#ce_ampm_end').val($(this).val());
    });

    //Disable times if all day event
    $('input[name="all_day"]').change(function (){
        $('.hour-chooser, .minute-chooser, .ampm-chooser').prop('disabled', function(i, v) { return !v; });
    });

    //Case contacts
    $('form[name="quick_contact"]').validate({
        errorClass: 'text-danger',
        errorElement: 'span',
        rules: {
            last_name: {nameReq: true}
        },
        submitHandler: function () {
            var thisForm = $('form[name="quick_contact"]');
            var phoneData = {};
            phoneData[$('#qaContact select[name="phone_type"]').val()] = $('#qaContact input[name="phone"]').val();
            var phone = JSON.stringify(phoneData);
            var emailData = {};
            emailData[$('#qaContact select[name="email_type"]').val()] = $('#qaContact input[name="email"]').val();
            var email = JSON.stringify(emailData);
            $.post('lib/php/data/cases_contacts_process.php', {
                    'first_name': thisForm.find('input[name = "first_name"]').val(),
                    'last_name': thisForm.find('input[name = "last_name"]').val(),
                    'organization': thisForm.find('input[name = "organization"]').val(),
                    'contact_type': thisForm.find('select[name = "contact_type"]').val(),
                    'address': thisForm.find('textarea[name = "address"]').val(),
                    'city': thisForm.find('input[name = "city"]').val(),
                    'state': thisForm.find('select[name = "state"]').val(),
                    'zip': thisForm.find('input[name = "zip"]').val(),
                    'phone': phone,
                    'email': email,
                    'url': thisForm.find('input[name = "url"]').val(),
                    'notes': thisForm.find('textarea[name = "notes"]').val(),
                    'action': 'add',
                    'case_id': thisForm.find('select[name = "case_id"]').val()
                }, function (data) {
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error === true) {
                        notify(serverResponse.message, true,'error');
                    } else {
                        notify(serverResponse.message, false,'success');
                        var successMsg = '<p class="text-success">' + serverResponse.message +
                        '</p><p><a class="btn btn-primary show-form" href="#">Add Another?</a></p>';
                        $('#qaContact').html(successMsg);
                        $('a.show-form').click(function (event) {
                            event.preventDefault();
                            location.reload();
                        });
                    }
                });
        }
    });

    //Case sections
    $('.li-expand > a').click(function (event) {
        event.preventDefault();
        $(this).parent().find('ul').toggle();
    });

    //Messages
    $('.msg_display li.media').each(function (){
        if ($(this).hasClass('msg_unread')){
            $(this).find('.media-heading').append('<span class="fa fa-envelope-o"></span>');
        }

    });

    $('.container').on('click', 'span.msg-header', function (event) {
        event.stopPropagation();
        var target = $(this).closest('li');
        $(this).next('ul').toggle();
        $(this).find('span.fa').remove();
        //mark as read
        $.post('lib/php/data/messages_process.php', {action: 'mark_read', id: target.attr('data-thread')}, function (){
            target.removeClass('msg_unread');
        });
        if (!target.find('.ul-reply').length) { //if we haven't already loaded replies
            $.get('html/templates/mobile/Messages.php', {type: 'replies', thread_id: target.attr('data-thread')}, function (data) {
                target.find('li').last().append(data);
                //Don't show archive button in archive, duh.
                if (getParameterByName('type') === 'archive'){
                    target.find('span.archive-msg').remove();
                }
            });
        }
        //Set opacity for readability
        // If the msg body is not visible
        if (!$(this).closest('li.media').hasClass('msg_unread')){
            $(this).closest('li.media').toggleClass('msg_read');
        }
    });


    $('.truncate').click(function (event) {
        event.preventDefault();
        event.stopPropagation();
        $(this).toggleClass('truncate');
    });

    var folderType = getParameterByName('type');
    var folder;

    if (folderType) {
        folder =  folderType;
        if (folderType === 'search') {
            $('.search-query').val(getParameterByName('s'));
            $('select[name="msg-status"]').append('<option  value="sr">Search Results</option>');
            setTimeout(function () {
                $('select[name="msg-status"] option').last().prop('selected', true);
            }, 1);
        }
    } else {
        folder = 'inbox';
    }

    $('select[name="msg-status"]').val(getParameterByName('type')).change(function () {
        location.href = 'index.php?i=Messages.php&type=' + $(this).val();
    });

    $('.search-submit').click(function () {
        location.href = 'index.php?i=Messages.php&type=search&s=' + $('.search-query').val();
    });

    $('input.search-query').keydown(function (e) {
        if (e.keyCode === 13){
            location.href = 'index.php?i=Messages.php&type=search&s=' + $('.search-query').val();
        }
    });

    $('.container').on('click', '.send-reply', function (event) {
        event.preventDefault();
        var threadId =  $(this).closest('.li-expand-msg').attr('data-thread');
        var replyText = $(this).prev().val();
        var msgBody = $(this).parents('ul').eq(1);
        $.post('lib/php/data/messages_process.php',
            {action: 'reply', thread_id: threadId, reply_text: replyText},
            function (data) {
                var serverResponse = $.parseJSON(data);
                notify(serverResponse.message, false,'success');
                msgBody.find('.ul-reply').remove();
                msgBody.hide();
            });
    });
    //archive
    $('.container').on('click', '.archive-msg', function (event) {
        event.preventDefault();
        var threadId =  $(this).closest('.li-expand-msg').attr('data-thread');
        var msgBody = $(this).closest('.li-expand-msg');
        $.post('lib/php/data/messages_process.php', {
            action: 'archive',
            id: threadId,
        }, function (data) {
                var serverResponse = $.parseJSON(data);
                notify(serverResponse.message, false,'success');
                msgBody.remove();
            });
    });
    $('.btn-new-msg').click(function (event) {
        event.preventDefault();
        var hideMsg = $('.msg_display, .row:eq(1),.row:eq(2)').detach();
        $('.msg-new').show();
        $('#msg_tos, #msg_ccs, #msg_file').chosen({ width: '100%' });
        $('.chzn-choices').css({'padding' : '5px'}).addClass('form-control');
        $('.chzn-container-single').css({'padding': '5px', 'font-size':'16px'}).addClass('form-control');
        $('.alert').remove();
        $('form[name="send_message"]').validate({
            errorClass: 'text-error',
            errorElement: 'span',
            errorPlacement: function (error, element) {
                if (element.is(':hidden')) {
                    element.next().parent().append(error);
                }
                else {
                    error.insertAfter(element);
                }
            },
            onsubmit: function () { //special handling for chosen selects. see http://goo.gl/myKIz
                var ChosenDropDowns = $('.chzn-done');
                ChosenDropDowns.each(function () {
                    var ID = $(this).attr('id');
                    if (!$(this).valid()) {
                        $('#' + ID + '_chzn a').addClass('input-validation-error');
                    } else {
                        $('#' + ID + '_chzn a').removeClass('input-validation-error');
                    }
                });
            },
            submitHandler: function () {
                var thisForm = $('form[name="send_message"]');
                $.post('lib/php/data/messages_process.php', thisForm.serialize(), function (data) {
                    var serverResponse = $.parseJSON(data);
                    if (serverResponse.error) {
                        notify(serverResponse.message, true,'error');
                    } else {
                        notify(serverResponse.message, false,'success');
                        thisForm[0].reset();
                        $('select').trigger('liszt:updated');
                        $('.text-error').remove();
                        $('.msg-new').hide();
                        $('.container > .row').after(hideMsg);
                    }
                });
            }
        });

        var settings = $.data($('form[name="send_message"]')[0], 'validator').settings;
        settings.ignore += ':not(.chzn-done)';

        $('.msg-cancel').click(function (event) {
            event.preventDefault();
            $('form[name="send_message"]')[0].reset();
            $('select').trigger('liszt:updated');
            $('.msg-new').hide();
            $('.container > .row').after(hideMsg);
        });

    });

    //Pagination for messages
    $('.container').on('click', '.add-more', function (event) {
        event.preventDefault();
        $(this).remove();
        var msgUrl = $(this).attr('href');
        $.get(msgUrl, function (data) {
            var moreMsg = $(data).find('.media-list').html();
            $('.media-list').append(moreMsg);
        });

    });

    //Handle board downloads
    $('.board-container a.attachment').click(function (event) {
        event.preventDefault();
        var itemId = $(this).attr('data-id');
        $.download('lib/php/data/board_process.php', {'item_id': itemId, 'action': 'download'});
    });

    $('input.board-search').keyup(function () {
        var searchVal = $(this).val().toLowerCase();
        $('div.board-item').removeClass('search-result-hit search-result-miss');
        $('div.board-item').each(function () {
            if ($(this).find('.searchable').children().andSelf().text().toLowerCase().indexOf(searchVal) !== -1) {
                $(this).addClass('search-result-hit');
            } else {
                $(this).addClass('search-result-miss');
            }
        });

    });

    //Home Nav
    var calendarViewed = false;
    $('#home-nav-toggle input').change(function() {
            if ($(this).attr('id') === 'option1'){
                $('#upcoming').removeClass('visible-xs-block').addClass('hidden-xs');
                $('#activities').removeClass('hidden-xs').addClass('visible-xs-block');
            } else {
                $('#activities').removeClass('visible-xs-block').addClass('hidden-xs');
                $('#upcoming').removeClass('hidden-xs').addClass('visible-xs-block');
                if (!calendarViewed){
                    showEvent();
                    calendarViewed = true;
                }
            }
        });

    //Initialize calendar

    //function to pad month values with leading zero
    function pad(n){return n<10 ? '0'+n : n;}

    function showEvent (monthSearch){
        $('#fail').hide();
        if (monthSearch === undefined){
            var curDate  = new Date();
            monthSearch = curDate.getFullYear() + '-' + pad(curDate.getMonth() + 1);
        }

        if ($('[id^=' + monthSearch + ']').length > 0){ //if there are any events this month
            $('#upcoming_events_list').stop(true).scrollTo('#' + $('[id^=' + monthSearch + ']')[0].id, {duration:0, interrupt:true});
            if($('[id^=' + monthSearch + ']').closest('a').hasClass('noncase-event')){
                $('[id^=' + monthSearch + ']').closest('a').addClass('cal-noncase-event');
            } else {
                $('[id^=' + monthSearch + ']').closest('a').addClass('cal-case-event');
            }
        } else {
            $('#fail').show();
            $('#upcoming_events_list').stop(true).scrollTo('#fail', {duration:0, interrupt:true});
        }
    }

    $('#calendar').zabuto_calendar({
        legend: [
            {type: 'block', label: 'Case Event', classname: 'cal-case-event'},
            {type: 'block', label: 'Non-case Event', classname: 'cal-noncase-event'}
        ],
        ajax: {
            url: 'lib/php/data/home_events_load.php?summary=1',
            modal: false
        },
        action: function() {
            var target = this.id.substr(this.id.lastIndexOf('_') +1);
            $('#upcoming_events_list').stop(true).scrollTo('#' + target, {duration:1000, interrupt:true});
            $('.list-group-item').removeClass('cal-noncase-event cal-case-event');
            if ($('#' + target).closest('a').hasClass('noncase-event')){
                $('#' + target).closest('a').addClass('cal-noncase-event');
            } else {
                $('#' + target).closest('a').addClass('cal-case-event');
            }
        },
        action_nav: function() {
            //find events for current month in the events list
            showEvent($('#' + this.id).data('to').year + '-' +  pad($('#' + this.id).data('to').month));
        }
    });

    if ($('#upcoming_events_list').length > 0){
        $.ajax({
            url: 'lib/php/data/home_events_load.php',
            dataType: 'json',
            success: function (data) {
                var display = '<div class="list-group">';
                var startTime, endTime, bgType;
                data.forEach(function(data){
                    //Create (non-unique, I'm afraid) id for date
                    var d = data.start;
                    var zabId = d.split(' ');
                    //Format times
                    if (data.allDay){
                        startTime = moment(data.start).format('MMMM Do YYYY');
                        endTime = moment(data.end).format('MMMM Do YYYY');
                    } else {
                        startTime = moment(data.start).format('MMMM Do YYYY, h:mm a');
                        endTime = moment(data.end).format('MMMM Do YYYY, h:mm a');
                    }
                    //Bgcolor based on case/non-case
                    if (data.caseId === 'NC'){
                        bgType = 'noncase-event';
                    } else {
                        bgType = 'case-event';
                    }
                    display += '  <a href="#" class="list-group-item list-group-item-cal ' + bgType +
                    '"> <h3 class="list-group-item-heading text-center" id="' + zabId[0] + '">' + escapeHtml(data.shortTitle) + '</h3>' +
                    '<dl class="dl-horizontal">' +
                    '<dt class="list-group-item-text">Start:</dt><dd> ' + startTime + '</dd>' +
                    '<dt class="list-group-item-text">End:</dt><dd> ' + endTime + '</dd>' +
                    '<dt class="list-group-item-text">Where:</dt><dd> ' + escapeHtml(data.where) +  '</dd>' +
                    '<dt class="list-group-item-text">Case: </dt><dd> ' + escapeHtml(data.caseName) +  '</dd></dl>' +
                    '<p class="list-group-item-text text-center">' + escapeHtml(data.description) +  '</p></a>';

                });
                display += '</div>';
                $('#upcoming_events_list').html(display)
                .append('<h3 id="fail">No events this month</h3><div style="height:400px"></div>');
                //Look for any events in current month
                showEvent();
            }
        });
    }

});
