//
//Scripts for documents panel on cases tab
//

//Listen for click
$('.case_detail_nav #item3').live('click', function(){

	var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
	thisPanel.load('lib/php/data/cases_documents_load.php');

})