//Scripts for conflicts

$('.case_detail_nav #item7').live('click', function() {

	var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
    var caseId = $(this).closest('.case_detail_nav').siblings('.case_detail_panel').data('CaseNumber');

    thisPanel.load('lib/php/data/cases_conflicts_load.php',{'id':caseId},function(data){
        formatCaseData(thisPanel,type);
        });
});