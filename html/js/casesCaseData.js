//
//Scripts for Case data
//




//User clicks on Case Data in left-side navigation
$('.case_detail_nav #item2').live('click', function() {

	var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
    var caseId = $(this).closest('.case_detail_nav').siblings('.case_detail_panel').data('CaseNumber');
    var type;
    var toolsHeight = $(this).outerHeight();
    var thisPanelHeight = $(this).closest('.case_detail_nav').height();
    var documentsWindowHeight = thisPanelHeight - toolsHeight;
    if (typeof caseNotesWindowHeight == 'undefined')
        {caseNotesWindowHeight = thisPanelHeight - toolsHeight;}


    if ($(this).hasClass('new_case'))
    {type = 'new';}
	else
	{type = 'display';}

    thisPanel.load('lib/php/data/cases_case_data_load.php',{'id':caseId,'type':type},function(data){

        //Set css
        $('div.case_detail_panel_tools').css({'height': toolsHeight});
        $('div.case_detail_panel_casenotes').css({'height': caseNotesWindowHeight});
        $('div.case_detail_panel_tools_left').css({'width': '30%'});
        $('div.case_detail_panel_tools_right').css({'width': '70%'});

		//remove system fields
		if (type === 'new')
		{
			$('input[name="id"]').parent().hide();
			$('input[name="opened_by"]').parent().remove();
            $('input[name="organization"]').val('');

		}

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

        //Add datepicker
        var dateVal;

        thisPanel.find('input.date_field').each(function(){
            dateVal = $(this).val();
            $(this).datepicker({dateFormat: 'yy-mm-dd',showOn: 'button',buttonText:dateVal,onSelect: function(dateText, inst) {
                var c = $.datepicker.parseDate('yy-mm-dd',dateText);
                var displayDate = $.datepicker.formatDate('mm/dd/yy',c);
            $(this).next().html(displayDate);
            }});
        });

        //Add textarea expander
        thisPanel.find('textarea').TextAreaExpander(100, 250);

		//highlight the tab so user knows there are unsaved changes
		$('#case_detail_tab_row').find('li.ui-state-active').addClass('ui-state-highlight');

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

        //Change name on tab when user enters last name
        thisPanel.find('input[name="first_name"]').focus();

        $('input[name = "last_name"]').keyup(function(){
            var fname = thisPanel.find('input[name="first_name"]').val();
            $(this).closest('#case_detail_tab_row')
                .find('li.ui-state-active').find('a').html($(this).val() + ', ' + fname);

        });

        //If there is no last name, put the organization name on the tab
        $('input[name = "organization"]').keyup(function(event){

            lnameVal = $(this).closest('form').find('input[name="last_name"]').val();

            if (lnameVal === '')
            {
             $(this).closest('#case_detail_tab_row')
                .find('li.ui-state-active').find('a').html($(this).val());
            }
        });

        //Submit the form
        $('button.new_case_submit').live('click',function(event){

            event.preventDefault();

            $(window).unbind("beforeunload");

            var formVals = $(this).closest('form');

            var errString = newCaseValidate(formVals);

            var formValsArray = formVals.serializeArray();

            formValsArray.push({'name':'action','value':'update_new_case'});

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

                    }
                });

            }



        });

    });

});
