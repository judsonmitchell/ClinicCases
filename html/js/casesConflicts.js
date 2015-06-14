//Scripts for conflicts

/* global elPrint */

$('.case_detail_nav #item7').live('click', function() {
	var thisPanel = $(this).closest('.case_detail_nav').siblings('.case_detail_panel');
    var caseId = $(this).closest('.case_detail_nav').siblings('.case_detail_panel').data('CaseNumber');

    //Get heights
    var toolsHeight = $(this).outerHeight();
    var thisPanelHeight = $(this).closest('.case_detail_nav').height();
    var conflictsWindowHeight = thisPanelHeight - toolsHeight;

    thisPanel.load('lib/php/data/cases_conflicts_load.php',{
        'case_id':caseId
    },function(data){
		//Set css
        $('div.case_detail_panel_tools').css({'height': toolsHeight});
        $('div.case_detail_panel_casenotes').css({'height': conflictsWindowHeight});
        $('div.case_detail_panel_tools_left').css({'width': '70%'});
        $('div.case_detail_panel_tools_right').css({'width': '30%'});

        //Apply shadow on scroll
        $(this).children('.case_detail_panel_casenotes').bind('scroll', function() {
            var scrollAmount = $(this).scrollTop();
            if (scrollAmount === 0 && $(this).hasClass('csenote_shadow')) {
                $(this).removeClass('csenote_shadow');
            } else {
                $(this).addClass('csenote_shadow');
            }
        });

		$('button.conflicts_print').button({
            icons: {primary: 'fff-icon-printer'},
            text: true
        }).click(function() {
            elPrint($(this).closest('div.case_detail_panel_tools')
                .siblings('div.case_detail_panel_casenotes'),'Conflicts: ' + $(this).closest('.case_detail_panel')
                .siblings('.case_detail_bar')
                .find('.case_title')
                .text()
            );
        });
    });
});
