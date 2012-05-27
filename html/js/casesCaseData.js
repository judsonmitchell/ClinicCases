//Scripts for Case data

//User clicks on Case Data in left-side navigation


$('.case_detail_nav #item2').live('click', function() {

	var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
    var caseId = $(this).closest('.case_detail_nav').siblings('.case_detail_panel').data('CaseNumber');
    var type;

    if ($(this).hasClass('new_case'))
    {type = 'new';}
	else
	{type = 'display';}

    thisPanel.load('lib/php/data/cases_case_data_load.php',{'id':caseId,'type':type},function(){

    });



});
