//Scripts for conflicts

$('.case_detail_nav #item7').live('click', function() {

	var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
    var caseId = $(this).closest('.case_detail_nav').siblings('.case_detail_panel').data('CaseNumber');

    thisPanel.load('lib/php/data/cases_conflicts_load.php',{'case_id':caseId},function(data){

		$('button.conflicts_print').button({icons: {primary: "fff-icon-printer"},text: true}).click(function() {
            alert('Working on it!');  //TODO add print functions
            });

        });
});