//js functions to notify user.

function notify(str, wait, state) {
    $('#notifications').html(str);
    //.css({'opacity': '.8'});
    $('#notifications').addClass('ui-corner-all').show();
    if (state === 'error'){
        $('#notifications').css({'color':'white', 'font-weight': 'bold',  'background-color':'red'});
    } else if (state ==='success'){
        $('#notifications').css({'color':'white', 'font-weight': 'bold', 'background-color':'green'});
    } else {
        if ($('body').hasClass('isMobile')){
            $('#notifications').css({'color':'#3a87ad', 'font-weight': 'normal', 'background-color':'#d9edf7'});
        } else {
            $('#notifications').css({'color':'black', 'font-weight': 'normal', 'background-color':'white'});
        }
    }

    if (wait === true) {
        $('#notifications').append('<p><a href="">Dismiss</a></p>');
        $('#notifications a').click(function (event) {
            event.preventDefault();
            $('#notifications').fadeOut();
        });
    } else {
        $('#notifications').delay(2000).fadeOut();
    }
}
