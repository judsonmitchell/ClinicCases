//
//Scripts for Case data
//

function formatCaseData(thisPanel,type)
{

    //Apply CSS
    var navItem = thisPanel.siblings('.case_detail_nav').find('#item2');
    var toolsHeight = navItem.outerHeight();
    var thisPanelHeight = navItem.closest('.case_detail_nav').height();
    var documentsWindowHeight = thisPanelHeight - toolsHeight;
    if (typeof caseNotesWindowHeight == 'undefined')
        {caseNotesWindowHeight = thisPanelHeight - toolsHeight;}

    $('div.case_detail_panel_tools').css({'height': toolsHeight});
    $('div.case_detail_panel_casenotes').css({'height': caseNotesWindowHeight});
    $('div.case_detail_panel_tools_left').css({'width': '30%'});
    $('div.case_detail_panel_tools_right').css({'width': '70%'});

    //Apply shadow on scroll
    $('.case_detail_panel_casenotes').bind('scroll', function() {
        var scrollAmount = $(this).scrollTop();
        if (scrollAmount === 0 && $(this).hasClass('csenote_shadow'))
        {
            $(this).removeClass('csenote_shadow');
        }
        else
        {
            $(this).addClass('csenote_shadow');
        }
    });

    //format the form
    if (type === 'new'  || type === 'edit')
    {
        $('input[name="id"]').parent().hide();
        $('input[name="opened_by"]').parent().remove();
        $('input[name="organization"]').val('');

        //Enable dynamic replacement of clinic and case codes in case number
        var cN = thisPanel.find('input[name="clinic_id"]');
        var cnVal = cN.val();
        if (cnVal.indexOf("ClinicType") != -1)
            {
                thisPanel.find('select[name="clinic_type"]').change(function(){
                cN.val(cnVal.replace('ClinicType',$(this).val()));
                });
            }

        if (cnVal.indexOf("CaseType") != -1)
            {
                thisPanel.find('select[name="case_type"]').change(function(){
                cN.val(cnVal.replace('CaseType',$(this).val()));
                });
            }

        //Add onbeforeunload event to prevent empty cases
        $(window).bind('beforeunload', function(){
            return "You have an unsaved new case.  Please either save it or close its tab before leaving this page";
        });

        //Disable case number editing
        thisPanel.find('input[name="clinic_id"]').attr('disabled',true).after('<a class="force_edit small" href="#">Let me edit this</a>');

        thisPanel.find('a.force_edit').click(function(event){event.preventDefault();$('input[name="clinic_id"]').attr('disabled',false).focus();$(this).remove();});

        //Add chosen to selects
        thisPanel.find('select').chosen();

        //Align multiple phone and email fields with the first ones
        thisPanel.find('span.dual_input').not('label + span.dual_input').css({'margin-left':'190px'});

        //Add link to trigger a new email/phone field
        thisPanel.find('span.phone_dual').last().after('<a class="add_another_phone small" href="#">Add another</a>');

        thisPanel.find('span.email_dual').last().after('<a class="add_another_email small" href="#">Add another</a>');

        //Add datepickers
        thisPanel.find('input.date_field').each(function(){
            var b = $.datepicker.parseDate('yy-mm-dd',$(this).val());
            var buttonVal = $.datepicker.formatDate('mm/dd/yy',b);
            $(this).datepicker({dateFormat: 'yy-mm-dd',showOn: 'button',buttonText:buttonVal,onSelect: function(dateText, inst) {
                var c = $.datepicker.parseDate('yy-mm-dd',dateText);
                var displayDate = $.datepicker.formatDate('mm/dd/yy',c);
            $(this).next().html(displayDate);
            }});
        });

        //Add textarea expander
        thisPanel.find('textarea').TextAreaExpander(100, 250);

        //highlight the tab so user knows there are unsaved changes
        $('#case_detail_tab_row').find('li.ui-state-active').addClass('ui-state-highlight');

        //Change name on tab when user enters last name
        thisPanel.find('input[name="first_name"]').focus();

        $('input[name = "last_name"]').keyup(function(){
            var fname = thisPanel.find('input[name="first_name"]').val();
            $(this).closest('#case_detail_tab_row')
                .find('li.ui-state-active').find('a').html($(this).val() + ', ' + fname);
            //Put client name on case title
            $(this).closest('#case_detail_tab_row').find('div.case_title').html('<h2>' + fname + ' ' + $(this).val() + '</h2>');

        });

        //If there is no last name, put the organization name on the tab
        $('input[name = "organization"]').keyup(function(event){

            lnameVal = $(this).closest('form').find('input[name="last_name"]').val();

            if (lnameVal === '')
            {
                $(this).closest('#case_detail_tab_row')
                .find('li.ui-state-active').find('a').html($(this).val());

                //Put organization name on case title
                $(this).closest('#case_detail_tab_row').find('div.case_title').html('<h2>' + $(this).val() + '</h2>');
            }
        });
    }

    else //display case data
    {
        //format buttons
        thisPanel.find('button.case_data_edit').button({icons: {primary: "fff-icon-page-edit"},text: true});

         thisPanel.find('button.case_data_print').button({icons: {primary: "fff-icon-printer"},text: true}).click(function() {
            alert('Working on it!');  //TODO add print functions
            });

         //remove the id
         thisPanel.find('div.id_display').remove();
    }
}


//User clicks on Case Data in left-side navigation
$('.case_detail_nav #item2').live('click', function() {

	var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
    var caseId = $(this).closest('.case_detail_nav').siblings('.case_detail_panel').data('CaseNumber');
    var type;

    if ($(this).hasClass('new_case'))
    {type = 'new';}
	else
	{type = 'display';}

    thisPanel.load('lib/php/data/cases_case_data_load.php',{'id':caseId,'type':type},function(data){
        formatCaseData(thisPanel,type);
        });
});



//Listen for edit
$('button.case_data_edit').live('click',function(){

    var thisPanel = $(this).closest('.case_detail_panel');
    var thisCaseId = thisPanel.data('CaseNumber');
    thisPanel.load('lib/php/data/cases_case_data_load.php',{'id':thisCaseId,'type':'edit'},function(){
        formatCaseData(thisPanel,'edit');
    });
});

//Submit the form
$('button.case_modify_submit').live('click',function(event){

    event.preventDefault();

    var resultTarget = $(this).closest('.case_detail_panel');
    var thisCaseId = resultTarget.data('CaseNumber');

    //Server-side script does different things depending on whether
    //this is a new case which is just being opened or this is an
    //existing case which is being edited.  So, set variable:
    if ($(this).hasClass('update_new_case'))
        {actionType = 'update_new_case';}
    else
        {actionType = 'edit';}

    $(window).unbind("beforeunload");

    var formVals = $(this).closest('form');

    //enable clinic_id field or else serializeArray won't pick up value
    formVals.find('input[name="clinic_id"]').attr({'disabled':false});

    var errString = newCaseValidate(formVals);

    var formValsArray = formVals.serializeArray();

    formValsArray.push({'name':'action','value':actionType});

    //notify user or errors or submit form
    if (errString.length)
    {
        notify(errString,true);
        return false;
    }
    else
    {

        $.post('lib/php/data/cases_case_data_process.php', formValsArray,function(data){
            var serverResponse = $.parseJSON(data);

            if (serverResponse.error === true)
                {notify(serverResponse.message, true);}
            else
            {
                notify(serverResponse.message);
                $('#case_detail_tab_row').find('li.ui-state-active').removeClass('ui-state-highlight ui-state-error');

                resultTarget.load('lib/php/data/cases_case_data_load.php',{'id':thisCaseId,'type':'display'},function(data){
                        formatCaseData(resultTarget,'display');
                        });

                //Refresh the table; see Cases.js
                oTable.fnReloadAjax();
            }
        });

    }
});

//Listen for print
$('button.case_data_print').live('click',function(){
    var thisPanel = $(this).closest('.case_detail_panel');
    alert('Working on it!');  //TODO add print functions
});

//Add another phone or email
$('a.add_another_phone, a.add_another_email').live('click',function(event){
    event.preventDefault();
    var newPhone = $(this).prev('span').clone();
    newPhone.find('select').val('');
    newPhone.find('input').val('');
    newPhone.css({'margin-left':'190px'});
    $(this).prev('span').after(newPhone);

});

