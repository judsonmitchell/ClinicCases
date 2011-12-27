//Scripts for casenotes


function loadCaseNotes(panelTarget,id)
{
	
	$(panelTarget + ' .case_detail_panel').load('lib/php/data/cases_casenotes_load.php', {'case_id': id,'start': '0'}, function() 
		{
			$('.case_detail_panel_tools_right button#button1').button({icons: {primary: "fff-icon-add"},text: true}).next().button({icons: {primary: "fff-icon-time"},text: true}).next().button({icons: {primary: "fff-icon-printer"},text: true});
			
			var scrollTarget = $('#case_' + id);
			
			//$('#case_' + id).jScrollPane({autoReinitialise:true});
			//$('.case_detail_panel_casenotes').jScrollPane({autoReinitialise:true});

			$(scrollTarget).bind('scroll',function(){
				addMoreNotes(scrollTarget)});
									 									
		})		
}


//Load new case notes on scroll
function addMoreNotes(scrollTarget) {
	

	
	var divId = scrollTarget.attr('id');
	var caseId = divId.split("_");

	console.log(divId);
	//var api = $('#' + divId).data('jsp');
	//var scrollY = api.getPercentScrolledY();
	
	var scrollAmount = scrollTarget.scrollTop();
    var documentHeight = scrollTarget.parent().height();
    
   // console.log(scrollAmount)
 
    // calculate the percentage the user has scrolled down the page
    var scrollY = (scrollAmount / documentHeight) * 100;
	console.log('scroll ' + scrollY)
	if (scrollY > 80)
	{
		//alert('x');
		if (typeof scrollTarget.data('start') == "undefined")
		{
			startNum = 0
			scrollTarget.data('start', startNum)
		}
		else
		{
			startNum = scrollTarget.data('start') + 20
			scrollTarget.data('start', startNum)
		}
		
		$.post('lib/php/data/cases_casenotes_load.php',{'case_id':caseId[1],'start':scrollTarget.data('start'),'update':'yes'},function(data){
			//If there are no case notes left, there will be no <br> in the data, so return false;
			//var t = data.split('div.csenote').length -1;
			//var t = data.$('.csenote').length;
			var t = $(data).find('p.csenote_instance').length
			console.log('start: ' + scrollTarget.data('start'));
			console.log('casenotes returned: ' + t);
			if (t === 0)
			{return false;}
			else
			{
			//	var alreadyScrolled = $(scrollTarget).height() + $(scrollTarget).get()[0].scrollTop >= $(scrollTarget).get()[0].scrollHeight;
				scrollTarget.append(data);
				//$(scrollTarget).attr({ scrollTop: $(scrollTarget).attr("scrollHeight") });
				//if (alreadyScrolled) {
           // $(scrollTarget).get()[0].scrollTop = $(scrollTarget).get()[0].scrollHeight;
        //}
				//api.getContentPane()
			    //api.reinitialise();
			    var numc = scrollTarget.parent().find('p.csenote_instance').length
			    console.log('casenotes total ' + numc)
			}
		})
		
		
	}
}


	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

