 //Scripts for utilities page

var oTable;

$(document).ready(function() {

    //set header widget
    $('#utilities_nav').addClass('ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr');

    //Add navigation buttons
    $('.utilities_nav_choices').buttonset();

    //Add navigation actions
    target = $('div#utilities_panel');

    //User clicks reports button
    $('#reports_button').click(function() {

        //Show the report chooser
        $('#report_chooser').appendTo('#utilities_panel').show();

        //Add chosen
        $('select[name="type"]').chosen();

        //Add datepickers
        $('input[name="date_start_display"]').datepicker({
            dateFormat : "DD, MM d, yy",
            altField:$('input[name = "date_start"]'),
            altFormat: "yy-mm-dd"
            });

        $('input[name="date_start_display"]').datepicker('setDate', '-7');

        $('input[name="date_end_display"]').datepicker({
            dateFormat : "DD, MM d, yy",
            altField:$('input[name = "date_end"]'),
            altFormat: "yy-mm-dd"
            });

        $('input[name="date_end_display"]').datepicker('setDate', new Date());

        //Create a table
        $('#report_chooser').after( '<table cellpadding="0" cellspacing="0" border="0" class="display" id="table_reports"></table>' );

        //Dynamically create dataTable, load data
        $('button.report_submit').click(function(event){
            event.preventDefault();

            var q_type =$('select[name="type"]').val();

            var start = $('input[name="date_start"]').val();

            var end = $('input[name="date_end"]').val();

            var query = [];

            if (q_type.indexOf("_grp_") != -1) //user groups
            {
                query.push('grp',q_type);
            }
            else if (q_type.indexOf("_svn_") != -1) //supervisor groups
            {
                query.push('supvrsr_grp',q_type);
            }
            else if(q_type.indexOf("_cse_") != -1) //case
            {
                query.push('case',q_type);
            }
            else
            {
                query.push('user',q_type);//single user
            }

            //Load data from server
            oTable = $('#table_reports').dataTable({
                "sAjaxSource": 'lib/php/data/utilities_reports_load.php?type=' + query[0] +
                '&val=' + query[1],
                "sDom": '<"toolbar">frtip'
            });

        });

        //Create toolbar
        $('div.toolbar').html('toolbar here');
    });

    //User clicks configuration button
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
