//Javascript functions to be called on every page

$(document).ready(function() {

	//Click on "Report Problems"
	$('#report_problems').click(function(event){

		event.preventDefault();

		//Position dialog to the bottom of the quick add button
		var x = $("#report_problems").offset().left - 175;
		var y = $("#report_problems").offset().top + 40;

		var bugForm = '<div class="bug_form"><form><label>What went wrong?</label>'+
		'<input type="hidden" name="user_agent" value="' + navigator.userAgent + '">'+
		'<input type="hidden" name="url" value="' + window.location.href + '">'+
		'<input type="hidden" name="path" value="' + window.location.pathname + '">' +
		'<input type="hidden" name="jscript_error" value="' + localStorage.getItem('ClinicCasesErrorData') + '">' +
		'<input type="hidden" name="name_of_user" value="' + $(this).attr('data-name') + '">' +
		'<input type="hidden" name="user_email" value="' + $(this).attr('data-email') + '">' +
		'<textarea></textarea><input type="submit" value="Submit"></form></div>';

		$(bugForm).dialog({
			autoOpen: true,
			height: 420,
			width: 325,
			modal: true,
			position: [x, y]
			}).siblings('.ui-dialog-titlebar').remove();
		});


});

//Log jscript errors to local storage
window.onerror = function(error, url, line) {

	var errorObject = { 'error': error, 'url': url, 'line': line };

	// Put the object into storage
	localStorage.setItem('ClinicCasesErrorData', JSON.stringify(errorObject));

	//Next log error to ClinicCases server so we can fix it
	var key = '4fe9b4800176e';

	$.post('https://cliniccases.com/jscript-logger/logger.php',{'key':key,'error':errorObject});

};