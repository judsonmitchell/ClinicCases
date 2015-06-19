//Starts idletimer.js
$(document).ready(function () {
    $.idleTimeout('#idletimeout', '#idletimeout a', {
        idleAfter: 3600, //60 minutes
        pollingInterval: 30,
        keepAliveURL: 'lib/php/auth/keep_alive.php',
        serverResponseEquals: 'OK',
        onTimeout: function () {
            $(this).slideUp();
            window.location = 'index.php?i=Logout.php';
        },
        onIdle: function () {
            $(this).slideDown(); // show the warning bar
        },
        onCountdown: function (counter) {
            $(this).find('span').html(counter); // update the counter
        },
        onResume: function () {
            $(this).slideUp(); // hide the warning bar
        }
    });
});
