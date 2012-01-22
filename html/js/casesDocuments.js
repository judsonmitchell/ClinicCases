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

		//Set buttons
		$('button.doc_new_folder').button({icons: {primary: "fff-icon-folder-add"},text:true}).next().button({icons: {primary: "fff-icon-page-white-get"},text:true});
			});

		//Create context menu

		$("div.doc_item").contextMenu({menu: 'docMenu'},function(action, el, pos) {

			switch(action)
			{
				case 'open':
					var thisUrl = $(el).find('a').attr('href');
					window.open(thisUrl,'_new');
					break;

				case 'cut':
					$(el).css({'opacity':'.5'});
					break;

				case 'copy':
					$(el).css({'border':'1px solid #AAA'});
					break;

				case 'delete':

					var dialogWin = $('<div class=".dialog-casenote-delete" title="Delete this Document?">This document will be permanently deleted from the server.  Are you sure?</div>').dialog({
							autoOpen: true,
							resizable: false,
							modal: true,
							buttons: {
							"Yes": function() {
								//insert delete code here
								$(this).dialog("destroy");
								},
							"No": function() {
								$(this).dialog("destroy");
								}
							}
						});
					break;

				case 'properties':
					$(el).next('.doc_properties').addClass('ui-corner-all').css({'top':'20%','left':'30%'}).show().focus().focusout(function(){$(this).hide();});
					break;
			}

        // alert(
        //     'Action: ' + action + '\n\n' +
        //     'Element ID: ' + $(el).attr('id') + '\n\n' +
        //     'X: ' + pos.x + '  Y: ' + pos.y + ' (relative to element)\n\n' +
        //     'X: ' + pos.docX + '  Y: ' + pos.docY+ ' (relative to document)'
        //     );
		});

});