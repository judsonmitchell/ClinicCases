$(document).ready(function() {

    function login_user()
    {
        $.post("lib/php/auth/login.php", $("#getin").serialize(), function(data) {

            var response = $.parseJSON(data);
            if (response.login == 'true')
            {
                $("#status").html(response.message);
                window.location = response.url;
            }

            else

            {
                $("#status").html(response.message);
                $("input").addClass('error');
            }

        });
    }

    //Deal with IE
    if ($('html').hasClass('ie7') || $('html').hasClass('ie6'))
    {
        var warn = "<p>Sorry, your browser is out of date and cannot display ClinicCases.  Please either <a href='http://windows.microsoft.com/en-US/internet-explorer/downloads/ie/'>update your version of Internet Explorer</a> or, even better, install <a href='http://www.google.com/chrome'>Google Chrome</a>.</p><br /><p>If you are not allowed to add or modify software on your system, you can use <a href='http://www.google.com/chromeframe/'>Chrome Frame</a>.";
        $('#content').html(warn);
    }

    //Round corner
    //$('div.wrapper').addClass('ui-corner-all');

    //Lost password form
    $('a.lost_password').click(function(event) {
        event.preventDefault();
        var forgotForm = '<div class="forgot_form">' +
        '<a class="forgot_close" href="#"><img src="html/ico/cross.png" border=0 title="Close"></a>' +
        '<br /><br /><form>' +
        '<label>Please provide your email address:</label>' +
        '<input type="text" name="email" value="">' +
        '<br /><br /><button>Cancel</button><button>Submit</button></form></div>';

        $(forgotForm).dialog({
            autoOpen: true,
            height: 300,
            width: 325,
            modal: true,
            open: function()
            {
                var diag = $(this);

                //Close
                $('a.forgot_close').click(function(event) {
                    event.preventDefault();
                    $('div.bug_form').remove();
                    diag.dialog("destroy");
                });

                //Cancel
                $('div.forgot_form button:first').click(function(event) {
                    event.preventDefault();
                    $('div.forgot_form').remove();
                    diag.dialog("destroy");
                });

                //Submit
                $('div.forgot_form button:first').next().click(function(event) {
                    event.preventDefault();
                    $('#status').remove();
                    var email = $(this).siblings('input').val();
                    $.post('lib/php/auth/lost_password.php', ({'email': email}), function(data) {
                        var serverResponse = $.parseJSON(data);
                        $('div.forgot_form').remove();
                        diag.dialog("destroy");
                        notify(serverResponse.message, true);
                    });
                });
            }

        }).siblings('.ui-dialog-titlebar').remove();

    });

    $("#login_button").bind('click', function(event) {
        event.preventDefault();
        login_user();
    });

    $("#password").keyup(function(event) {
        event.preventDefault();
        if (event.keyCode == '13') {
            login_user();
        }
    });


});
