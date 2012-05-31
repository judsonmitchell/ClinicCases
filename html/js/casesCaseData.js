//Scripts for Case data

//User clicks on Case Data in left-side navigation


$('.case_detail_nav #item2').live('click', function() {

	var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
    var caseId = $(this).closest('.case_detail_nav').siblings('.case_detail_panel').data('CaseNumber');
    var type;
    var toolsHeight = $(this).outerHeight();
    var thisPanelHeight = $(this).closest('.case_detail_nav').height();
    var documentsWindowHeight = thisPanelHeight - toolsHeight;


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
			$('input[name="id"]').parent().remove();
			$('input[name="opened_by"]').parent().remove();

		}

        //Add chosen to selects
        thisPanel.find('select').chosen();

        //Add datepicker
        thisPanel.find('input[name="date_open"],input[name="date_closed"]')
            .datepicker({dateFormat: 'm/d/yy',showOn: 'focus'});

        //Add textarea expander
        thisPanel.find('textarea').TextAreaExpander(100, 250);

		//highlight the tab so user knows there are unsaved changes
		$('#case_detail_tab_row').find('li.ui-state-active').addClass('ui-state-highlight');
    });



});
