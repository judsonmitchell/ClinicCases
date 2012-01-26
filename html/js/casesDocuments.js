//
//Scripts for documents panel on cases tab
//

function createTrail(path)
{
	
	var pathArray = path.split('/');
	var pathString = '';
	$.each(pathArray,function(i,v){
		pathItem = '<a class="doc_trail_item" href="#" data="' + path + '">' + v + '</a>/';
		pathString += pathItem;
	});

	return pathString;
}

//User clicks to open document window
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
			//TODO fix the problem where the context menu tries to open outside the viewport
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
					$(el).css({'border':'1px solid #AAA'}).next('.doc_properties').addClass('ui-corner-all').css({'top':'20%','left':'30%'}).show().focus().focusout(function(){$(this).hide();$(el).css({'border':'0px'});});
					break;
			}

        // alert(
        //     'Action: ' + action + '\n\n' +
        //     'Element ID: ' + $(el).attr('id') + '\n\n' +
        //     'X: ' + pos.x + '  Y: ' + pos.y + ' (relative to element)\n\n' +
        //     'X: ' + pos.docX + '  Y: ' + pos.docY+ ' (relative to document)'
        //     );
		});

		//Expand div to include full file name on mouse enter
		$('div.doc_item > a').live('mouseenter',function(event){
			$(this).closest('div').css({'height':'auto','overflow':'auto'});

			if ($(this).closest('div').hasClass('doc'))
				{$(this).closest('div').draggable({revert:'invalid'});}
				else
				{$(this).closest('div').droppable();}

		});

		//Reset on leave
		$('div.doc_item > a').live('mouseleave',function(event){
			$(this).closest('div').css({'height':'120px','overflow':'hidden'});
		});

});

//User clicks a folder or document
$('div.doc_item > a').live('click',function(event){
	event.preventDefault();

	if ($(this).closest('div').hasClass('folder'))
	{
		var path = $(this).closest('div').attr('path');
		var container = $(this).find('p').html();
		var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
		var pathDisplay = $(this).closest('.case_detail_panel_casenotes').siblings('.case_detail_panel_tools').find('.path_display');
		
		$(this).closest('.case_detail_panel_casenotes').load('lib/php/data/cases_documents_load.php',{'id':caseId,'container':container,'path':path,'update':'y'},function(){
			var pathString = createTrail(path);
			pathDisplay.html(pathString);

		});
	}

	else

	{// TODO insert document actions

	}

});

//User clicks new folder button
$('button.doc_new_folder').live('click',function(){
	var target = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
	target.prepend("<div class='doc_item folder' path=''><img src='html/ico/folder.png'><p><textarea id='new_folder_name'>New Folder</textarea></p></div>");
	$('#new_folder_name').css({'background-color':'#EDF09F'})
		.mouseenter(function(){$(this).val('').focus().css({'background-color':'white'});})
		.keypress(function(e){if(e.which == 13) {
			event.preventDefault();
			//TODO insert submit code here.
			alert('submit');
			}
		});

});

//User clicks the Home link in the directory path
$('a.doc_trail_home').live('click',function(event){
	event.preventDefault();
	var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
	var thisPanel = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
	thisPanel.load('lib/php/data/cases_documents_load.php',{'id':caseId,'update':'yes'},function(){
		$(this).siblings('.case_detail_panel_tools').find('.path_display').html('');
	});
});

//User clicks one of the other links in the directory path
$('a.doc_trail_item').live('click',function(event){
	event.preventDefault();
	var container = $(this).html();
	var upLevel = $(this).attr('data').lastIndexOf('/');
	var path = $(this).attr('data').substr(0,upLevel);
	alert('cont: ' + container + ' path: ' + path);
	var caseId = $(this).closest('.case_detail_panel').data('CaseNumber');
	var thisPanel = $(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes');
	var pathDisplay = $(this).closest('.case_detail_panel_casenotes').siblings('.case_detail_panel_tools').find('.path_display');

	thisPanel.load('lib/php/data/cases_documents_load.php',{'id':caseId,'update':'yes','path':path,'container':container},function(){
		$(this).siblings('.case_detail_panel_tools').find('.path_display').html('');
		var pathString = createTrail(path);
		pathDisplay.html(pathString);
	});

});