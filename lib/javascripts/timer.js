//
//Functions for the case note timer.
//

//Starts and stops the timer
function ccTimer(toggle,startTime){
	
if (toggle === true)
{
	function getElapsed(startTime)
	{
		var timeNow = new Date();
		var timeNowMs = timeNow.getTime();
		var elapsedMs = timeNowMs - startTime;
		var elapsedMn = elapsedMs / 60000;
		$('.timer_time_elapsed').html(elapsedMn.toFixed());
		var timerLoop = setTimeout(function(){getElapsed(startTime)},60000);

	}

	getElapsed(startTime);
}

else

{

	$('#timer > img').replace('<img src="html/images/timer_stop.jpg">');
	timeloop.clearTimeout();
}

}

$(document).ready(function(){
	
	if ($.cookie('timer_status') == 'on')
	{
		
		var caseName = $.cookie('timer_case_name');
		var startTime =     $.cookie('timer_start_time');

		$('#timer .timer_case_name').html(caseName);

		ccTimer(true,startTime);

		$('#timer').show();

	} 


});