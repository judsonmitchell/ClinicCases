//
//Scripts for documents panel on cases tab
//


//Listen for click
$('.case_detail_nav #item3').live('click', function(){

	var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
	var caseId = $(this).closest('.case_detail_nav').siblings('.case_detail_panel').data('CaseNumber');

	//Get heights
	var toolsHeight = $(this).outerHeight();
	var thisPanelHeight = $(this).closest('.case_detail_nav').height();
    var documentsWindowHeight = thisPanelHeight - toolsHeight;

	thisPanel.load('lib/php/data/cases_documents_load.php',{'id':caseId},function(){

		//Set css
		$('div.case_detail_panel_tools').css({'height': toolsHeight});
		$('div.case_detail_panel_casenotes').css({'height': caseNotesWindowHeight});

		//Set Buttons
		$('button.doc_new_folder').button({icons: {primary: "fff-icon-folder-add"},text:true}).next().button({icons: {primary: "fff-icon-page-white-get"},text:true});
			});

});