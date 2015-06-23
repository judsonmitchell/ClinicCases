 //Javascript functions to be called on every page

/* global notify, validPasswordChange, validProfile */

$(document).ready(function () {

    //Click on "Report Problems"
    $('#report_problems').click(function (event) {
        event.preventDefault();

        //Position dialog to the bottom of the quick add button
        var x = $('#report_problems').offset().left - 175;
        var y = $('#report_problems').offset().top + 30;

        var bugForm = '<div class="bug_form">' +
        '<a class="bug_close" href="#"><img src="html/ico/cross.png" border=0 title="Close"></a>' +
        '<form><label>What went wrong?</label>' +
        '<input type="hidden" name="user_agent" value="' + navigator.userAgent + '">' +
        '<input type="hidden" name="url" value="' + window.location.href + '">' +
        '<input type="hidden" name="path" value="' + window.location.pathname + '">' +
        '<input type="hidden" name="name_of_user" value="' + $(this).attr('data-name') + '">' +
        '<input type="hidden" name="user_email" value="' + $(this).attr('data-email') + '">' +
        '<textarea name="description"></textarea><button>Cancel</button><button>Submit</button></form></div>';

        $(bugForm).dialog({
            autoOpen: true,
            height: 430,
            width: 325,
            modal: true,
            position: [x, y],
            open: function () {
                var diag = $(this);

                //Close
                $('a.bug_close').click(function (event) {
                    event.preventDefault();
                    $('div.bug_form').remove();
                    diag.dialog('destroy');
                });

                //Cancel
                $('div.bug_form button:first').click(function (event) {
                    event.preventDefault();
                    $('div.bug_form').remove();
                    diag.dialog('destroy');
                });

                //Submit
                $('div.bug_form button:first').next().click(function (event) {
                    event.preventDefault();
                    var key = '4fe9b4800176e';
                    var formVals = $('div.bug_form form').serializeArray();
                    formVals.push({'name': 'key', 'value': key});
                    formVals.push({'name': 'jscript_error', 'value': localStorage.getItem('ClinicCasesErrorData')});
                    $.post('lib/php/utilities/bug_reporter.php', formVals, function (data) {
                        var serverResponse = $.parseJSON(data);
                        $('div.bug_form').remove();
                        diag.dialog('destroy');
                        notify(serverResponse.message, true);
                    });
                });
            }
        }).siblings('.ui-dialog-titlebar').remove();
    });

    $('#show_preferences').click(function (event) {
        event.preventDefault();

        //Position dialog to the bottom of the quick add button
        var x = $('#show_preferences').offset().left - 375;
        var y = $('#show_preferences').offset().top + 30;

        var prefForm = '<div class = "pref_form"><div id="pref_nav">' +
        '<a href="#" class="active toggle">Profile</a> | <a href="#" class="toggle">Change Password</a> ' +
        ' | <a href="#" class="toggle">Private Key</a> ' +
        '  <a class="pref_close" href="#"><img src="html/ico/cross.png" border=0 title="Close"></a>' +
        '</div>' +
        '<div id="pref_body"></div></div>';

        $(prefForm).dialog({
            autoOpen: true,
            height: 460,
            width: 525,
            modal: true,
            position: [x, y],
            open: function () {
                var diag = $(this);

                //Cancel
                $('a.pref_close').click(function (event) {
                    event.preventDefault();
                    $('div.pref_form').remove();
                    diag.dialog('destroy');
                });

                //Load default view
                $('#pref_body').load('lib/php/users/preferences_load.php div.pref_profile', function () {
                    $(this).children().show();
                });

                //Next functions toggle between different view, load appropriate form

                //Profile view
                $('div#pref_nav a').eq(0).click(function (event) {
                    event.preventDefault();
                    $('div#pref_nav a').removeClass('active');
                    $(this).addClass('active');
                    $('#pref_body').load('lib/php/users/preferences_load.php div.pref_profile', function () {
                        $(this).children().show();
                    });
                });

                //Password view
                $('div#pref_nav a').eq(1).click(function (event) {
                    event.preventDefault();
                    $('div#pref_nav a').removeClass('active');
                    $(this).addClass('active');
                    $('#pref_body').load('lib/php/users/preferences_load.php div.pref_change_pword', function () {
                        $(this).children().show();
                    });
                });

                //Private key view
                $('div#pref_nav a').eq(2).click(function (event) {
                    event.preventDefault();
                    $('div#pref_nav a').removeClass('active');
                    $(this).addClass('active');
                    $('#pref_body').load('lib/php/users/preferences_load.php div.pref_private_key', function () {
                        $(this).children().show();
                    });
                });

                //Next functions submit the various forms

                //Submit profile changes
                $('button.profile_form_submit').live('click', function (event) {
                    event.preventDefault();

                    var formVals = $(this).closest('form');
                    var errString = validProfile(formVals);
                    if (errString.length) {
                        $('#profile_error').html(errString);
                        formVals.find('.ui-state-error').click(function () {
                            $(this).removeClass('ui-state-error');
                        });

                        return false;
                    } else {
                        var formValsArray = formVals.serializeArray();
                        formValsArray.push({'name': 'action', 'value': 'update_profile'});
                        $.post('lib/php/users/preferences_process.php', formValsArray, function (data) {
                            var serverResponse = $.parseJSON(data);
                            if (serverResponse.error === true) {
                                $('#profile_error').html(serverResponse.message);
                            } else {
                                $('#profile_error').html(serverResponse.message)
                                .css({'color': 'green'});
                            }
                        });
                    }
                });

                //Submit password changes
                $('button.change_pword_form_submit').live('click', function (event) {
                    event.preventDefault();

                    var formVals = $(this).closest('form');

                    //Valid
                    var errString = validPasswordChange(formVals);
                    if (errString.length) {
                        $('#pword_error').html(errString);
                        formVals.find('.ui-state-error').click(function () {
                            $(this).removeClass('ui-state-error');
                        });

                        return false;
                    } else {
                        var formValsArray = formVals.serializeArray();
                        formValsArray.push({'name': 'action', 'value': 'change_password'});
                        $.post('lib/php/users/preferences_process.php', formValsArray, function (data) {
                            var serverResponse = $.parseJSON(data);
                            if (serverResponse.error === true) {
                                $('#pword_error').html(serverResponse.message);
                            } else {
                                $('#pword_error').html(serverResponse.message)
                                .css({'color': 'green'});
                            }
                        });
                    }
                });

                //Submit private key changes
                $('button.change_private_key_form_submit').live('click', function (event) {
                    event.preventDefault();
                    var userId = $(this).attr('data-id');

                    $.post('lib/php/users/preferences_process.php', ({'action': 'change_private_key', 'id': userId}), function (data) {
                        var serverResponse = $.parseJSON(data);
                        if (serverResponse.error === true) {
                            $('#private_key_error').html(serverResponse.message);
                        } else {
                            $('#pref_body').load('lib/php/users/preferences_load.php div.pref_private_key', function() {
                                $(this).children().show();
                            });
                        }
                    });
                });
            }
        }).siblings('.ui-dialog-titlebar').remove();
    });
});

//Log jscript errors to ClinicCases.com
window.onerror = function (error, url, line, column, errorOb) {

    var errorObject;

    //Next log error to ClinicCases server so we can fix it
    if (typeof column === 'undefined' ){
        column = 'unsupported';
    }

    if (typeof errorOb === 'undefined'){
        errorObject = 'unsupported';
    } else {
        errorObject = errorOb.stack;
    }

    var key = '4fe9b48001754';
    var browser = navigator.userAgent;
    var errorString = error + ' Line: ' + line + ' Col: ' + column;
    $.post('lib/php/utilities/bug_reporter.php', {'key': key, 'type': 'errorLog', 'url': url,
    'browser': browser, 'error': errorString, 'error_obj' : errorObject});
};

$(document).ajaxError(function (event, request, settings) {
    var ignoredUrls = ['lib/php/data/home_activities_load.php',
    'lib/php/auth/keep_alive.php', 'lib/php/data/messages_load.php'];
    if ($.inArray(settings.url, ignoredUrls) === -1) {
        if (request.status === 401){
            var redirectUser = function() {
                location.href='index.php';
            };
            notify('You do not have permission to do this.', true, 'error');
            setTimeout(redirectUser, 2000);
        } else {
            notify('There was an error connecting to the server.<br />Please check your internet connection.', false, 'error');
            var reconnect;
            var attempt = 1;
            var attemptReconnect = function() {
                if (attempt > 20){
                    giveUp();
                } else {
                    notify('Trying to reconnect to the server...<br />Attempt: ' + attempt, false, 'error');
                    $.ajax({
                        global: false,
                        url: 'lib/php/auth/keep_alive.php',
                        error: function () {
                            attempt++;
                        },
                        success: function () {
                            successReconnect();
                        }
                    });
                }
            };

            var tryAgain = function() {
                reconnect = setInterval(attemptReconnect, 4000);
            };

            var successReconnect = function() {
                window.clearInterval(reconnect);
                notify('Successfully reconnected', false,'success');
                window.alreadyAttemptingToReconnect = false;
            };

            var giveUp = function() {
                window.clearInterval(reconnect);
                window.alreadyAttemptingToReconnect = false;
                notify('Unable to reach the server.<br />Please check your internet connection <br /> before doing any more work.', true,'error');
            };

            if (window.alreadyAttemptingToReconnect === true){
                return false;
            } else {
                window.alreadyAttemptingToReconnect = true;
                tryAgain();
            }
        }
    }
});
