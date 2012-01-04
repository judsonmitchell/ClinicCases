 //Scripts for casenotes


function loadCaseNotes(panelTarget, id) 
{
    
    $(panelTarget + ' .case_detail_panel').load('lib/php/data/cases_casenotes_load.php', {'case_id': id,'start': '0'}, function() 
    {
        //set css for casenotes
        toolsHeight = $(panelTarget + " .case_detail_nav li:first").outerHeight();
        thisPanelHeight = $(panelTarget + ' .case_detail_nav').height()
        caseNotesWindowHeight = thisPanelHeight - toolsHeight;
        $('div.case_detail_panel_tools').css({'height': toolsHeight});
        $('div.case_detail_panel_casenotes').css({'height': caseNotesWindowHeight});

        //add buttons; style only one button if user doesn't have permission to add casenotes
        
        if (!$('.case_detail_panel_tools_right button.button1').length)
		{
				$('.case_detail_panel_tools_right button.button3').button({icons: {primary: "fff-icon-printer"},text: true});
		}
        else
        {
        $('.case_detail_panel_tools_right button.button1').button({icons: {primary: "fff-icon-add"},text: true}).next().button({icons: {primary: "fff-icon-time"},text: true}).next().button({icons: {primary: "fff-icon-printer"},text: true});
		}

        //define div to be scrolled TODO make unique if user has the case in more than one window
        var scrollTarget = $(panelTarget + ' .case_' + id);
        
        scrollTarget.data('CaseNumber', id);

        //bind the scroll event for the window    
        $(scrollTarget).bind('scroll', function() {
            addMoreNotes(scrollTarget)
        });

        //round corners
        $('div.csenote').addClass('ui-corner-all');
    
    })
}


//Load new case notes on scroll
function addMoreNotes(scrollTarget) {
    
    var caseId = scrollTarget.data('CaseNumber');
    var scrollAmount = scrollTarget[0].scrollTop;
    var scrollHeight = scrollTarget[0].scrollHeight;
    
    if (scrollAmount == 0 && scrollTarget.hasClass('csenote_shadow')) 
    {
        scrollTarget.removeClass('csenote_shadow')
    } 
    else 
    {
        scrollTarget.addClass('csenote_shadow')
    }
   
    scrollPercent = (scrollAmount / (scrollHeight-scrollTarget.height())) * 100;
    
    if (scrollPercent > 70) 
    {
        //the start for the query is added to the scrollTarget object
        if (typeof scrollTarget.data('start') == "undefined") 
        {
            startNum = 20
            scrollTarget.data('start', startNum)
        } 
        else 
        {
            startNum = scrollTarget.data('start') + 20
            scrollTarget.data('start', startNum)
        }
        
        $.post('lib/php/data/cases_casenotes_load.php', {'case_id': caseId,'start': scrollTarget.data('start'),'update': 'yes'}, function(data) {

            //var t represents number of case notes; if 0,return false;
            var t = $(data).find('p.csenote_instance').length
            
            if (t === 0) 
            
            {
                return false;
            } 
            
            else 
            {
                scrollTarget.append(data);
                $('div.csenote').addClass('ui-corner-all');
            
            }
        
        })
    
    }
}

//Listeners

$('input.casenotes_search').live('focusin', function() {
    
    $(this).val('');
    $(this).css({'color': 'black'});
    $(this).next('.casenotes_search_clear').show();
})


$('input.casenotes_search').live('keyup', function() {
    
    var resultTarget = $(this).closest('div.case_detail_panel_tools').next();
    
    var search = $(this).val();
    
    var caseId = resultTarget.data('CaseNumber');
    
    resultTarget.unbind('scroll');
    
    resultTarget.load('lib/php/data/cases_casenotes_load.php', {'case_id': caseId,'search': search,'update': 'yes'}, function() {
        
        resultTarget.scrollTop(0);
        
        if (resultTarget.hasClass('csenote_shadow')) 
        {
            resultTarget.removeClass('csenote_shadow')
        }
        
        $('div.csenote').addClass('ui-corner-all');
        
        resultTarget.bind('scroll.search', function() {
            if ($(this).scrollTop() > 0) 
            {
                $(this).addClass('csenote_shadow')
            } 
            else 
            {
                $(this).removeClass('csenote_shadow')
            }
        })
    
    })

})

$('.casenotes_search_clear').live('click', function() {
    
    $(this).prev().val('Search Case Notes');
    
    $(this).prev().css({'color': '#AAA'});
    
    var resultTarget = $(this).closest('div.case_detail_panel_tools').next();
    
    var thisCaseNumber = resultTarget.data('CaseNumber');
    
    resultTarget.load('lib/php/data/cases_casenotes_load.php', {'case_id': thisCaseNumber,'start': '0','update': 'yes'}, function() {


        //if (resultTarget.hasClass('csenote_shadow'))
        //{resultTarget.removeClass('csenote_shadow')}
        
        resultTarget.scrollTop(0);
        
        $('div.csenote').addClass('ui-corner-all');
        
        resultTarget.unbind('scroll.search');
        
        resultTarget.bind('scroll', function() {
            addMoreNotes(resultTarget)
        })
    
    })
    
    $(this).hide();
})

//Add a case note

$('.case_detail_panel_tools_right button.button1').live('click',function(){
	$(this).closest('.case_detail_panel_tools').siblings().find('.csenote_new').show();
	$(this).closest('.case_detail_panel_tools').siblings().find('textarea').TextAreaExpander(52,200);  
	$('div.csenote').not('div.csenote_new').css({'opacity':'.5'})
	$('div.csenote_new input').datepicker({dateFormat:'m/d/yy'});
	})










