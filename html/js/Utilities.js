 //Scripts for utilities page

$(document).ready(function() {

    //set header widget
    $('#utilities_nav').addClass('ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr');

    //Add navigation buttons
    $('.utilities_nav_choices').buttonset();

    //Add navigation actions
    target = $('div#utilities_panel');

    $('#reports_button').click(function() {
        target.load('lib/php/data/utilities_reports_load.php');
    });

    $('#config_button').click(function() {
        target.load('lib/php/data/utilities_configuration_load.php');

    });

    //Set default load
    $('#reports_button').trigger('click');
});

//
//Listeners
//

//Toggle
$('a.config_item_link').live('click', function(event) {
    event.preventDefault();

    // Toggle open/closed state
    if ($(this).hasClass('closed'))
    {
        $(this).removeClass('closed').addClass('opened');
    }
    else
    {
        $(this).removeClass('opened').addClass('closed');
    }

    $('div.config_item > a').not($(this)).removeClass('opened').addClass('closed');


    // Show/hide the form
    $(this).next().toggle();
    $('form.config_form').not($(this).next()).hide();

});

//Submit changes
$('a.change_config').live('click', function(event) {

    event.preventDefault();

    var formTarget = $(this).closest('form');

    var formParent = $(this).closest('div.config_item');

    var target = $(this).closest('div').attr('id');

    //If clicking delete button, remove the form element
    if (!$(this).hasClass('add'))
    {
        $(this).parent().remove();
    }

    var formVals = formTarget.serializeArray();

    formVals.push({'name': 'type','value': formTarget.attr('data-type')});

    $.post('lib/php/data/utilities_configuration_process.php', formVals, function(data) {
        var serverResponse = $.parseJSON(data);
        if (serverResponse.error === true)
        {
            notify(serverResponse.message, true);
        }
        else
        {
            notify(serverResponse.message);
            formParent.load('lib/php/data/utilities_configuration_load.php #' + target, function() {
                $(this).find('form').show();
            });
        }

    });
});
