//Starts idletimer.js
$(document).ready(function () {

    $.idleTimeout('#idletimeout', '#idletimeout a', {
        idleAfter: 3600, //60 minutes
        pollingInterval: 30,
        keepAliveURL: 'lib/php/auth/keep_alive.php',
        serverResponseEquals: 'OK',
        onTimeout: function () {
            $(this).slideUp();
            window.location = 'html/templates/Logout.php';
        },
        onIdle: function () {
            $(this).slideDown(); // show the warning bar
        },
        onCountdown: function (counter) {
            $(this).find('span').html(counter); // update the counter
        },
        onResume: function () {
            $(this).slideUp(); // hide the warning bar
        },
        onAbort: function () {
            if ($('body').hasClass('isMobile')) {
                $('#notifications')
                .show()
                .html('Connection Error: Please reload this page to test your internet connection');
            } else {
                $('body').append('<div id="error"></div>');
                $('#error').html('There was an error connecting to the server. ' +
                'Either the server is down or there is a problem with your internet connection.' +
                '<br /><br />Please ensure that you have a connection to the server before ' +
                'doing any more work.')
                .dialog({modal: true, title: 'Connection Error'})
                .addClass('ui-state-error');
            }
        }
    });


});
