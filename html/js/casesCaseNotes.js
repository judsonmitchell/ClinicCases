 //Scripts for casenotes


function loadCaseNotes(panelTarget, id) 
{
    
    $(panelTarget + ' .case_detail_panel').load('lib/php/data/cases_casenotes_load.php', {'case_id': id,'start': '0'}, function() 
    {
        $('.case_detail_panel_tools_right button#button1').button({icons: {primary: "fff-icon-add"},text: true}).next().button({icons: {primary: "fff-icon-time"},text: true}).next().button({icons: {primary: "fff-icon-printer"},text: true});
        
        var scrollTarget = $('#case_' + id);
        
        $(scrollTarget).bind('scroll', function() {
            addMoreNotes(scrollTarget)
        });
        
        $('div.csenote').addClass('ui-corner-all');
    
    })
}


//Load new case notes on scroll
function addMoreNotes(scrollTarget) {
    
    var divId = scrollTarget.attr('id');
    var caseId = divId.split("_");
    var scrollAmount = scrollTarget[0].scrollTop;
    var documentHeight = scrollTarget.height();
    var scrollHeight = scrollTarget[0].scrollHeight;


    // calculate the percentage the user has scrolled down the page
    var scrollY = (scrollAmount / scrollHeight) * 100;
    
    if (scrollY > 80) 
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
        
        $.post('lib/php/data/cases_casenotes_load.php', {'case_id': caseId[1],'start': scrollTarget.data('start'),'update': 'yes'}, function(data) {

            //var t represents number of case notes; if 0,return false;
            var t = $(data).find('p.csenote_instance').length
            
            if (t === 0) 
            
            {
                return false;
            } 
            
            else 
            {
                scrollTarget.append(data);
            }
        
        })
    
    }
}


















