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
                //if user has the add case note widget open, make sure opacities are uniform
                if (scrollTarget.find('div.csenote_new').css('display') == 'block')
					{$('div.csenote').css({'opacity':'.5'})}
            
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
        
        resultTarget.scrollTop(0);
        
        $('div.csenote').addClass('ui-corner-all');
        
        resultTarget.unbind('scroll.search');
        
        resultTarget.bind('scroll', function() {
            addMoreNotes(resultTarget)
        })
    
    })
    
    $(this).hide();
})

//Load new case note widget

$('.case_detail_panel_tools_right button.button1').live('click',function(){
	//make sure case notes are scrolled to top
	$(this).closest('.case_detail_panel_tools').siblings('.case_detail_panel_casenotes').scrollTop(0)
	
	//display the new case note widget
	var newNote = $(this).closest('.case_detail_panel_tools').siblings().find('.csenote_new');newNote.show()
	
	//apply textarea expander and focus on the textarea
	$(this).closest('.case_detail_panel_tools').siblings().find('textarea').TextAreaExpander(52,200).css({'color':'#AAA'}).html('Describe what you did...').mouseenter(function(){
		$(this).html('');
		$(this).css({'color':'black'})
		});
	
	//reduce opacity on the previously entered case notes
	$('div.csenote').not('div.csenote_new').css({'opacity':'.5'})
	
	//create datepicker buttons and style time buttons
	var thisDate = $('input.csenote_date_value').val();
	
	$('input.csenote_date_value').datepicker({dateFormat:'m/d/yy',showOn:'button',buttonText:thisDate,onSelect:function(dateText, inst){$(this).next().html(dateText)}})	
	
	newNote.find('.csenote_action_submit').button({icons: {primary: "fff-icon-add"}}).next().button({icons: {primary: "fff-icon-cancel"},text: true})
	
	})


$('button.csenote_action_cancel').live('click',function(){
	
	//reset form
	$(this).closest('.csenote_new').children('textarea').val('')
	$(this).siblings('select').val('0')
	$(this).siblings().datepicker('setDate','m/d/yy')
	$(this).siblings().datepicker()[2].innerHTML = $(this).siblings('input.hasDatepicker').val()
	
	//reset opacity of other case notes
	$(this).closest('.case_detail_panel_casenotes').find('.csenote').css({'opacity':'1'});
	//hide the widget
	$(this).closest('.csenote_new').hide();
	
	})

$('button.csenote_action_submit').live('click',function(){
	event.preventDefault()
	cseVals = $(this).closest('form').serializeArray();
	var errString = validCaseNote(cseVals);
	if (errString.length)
	{notify(errString,'wait')}
	else
	{//submit form
	}
	
	
	})






