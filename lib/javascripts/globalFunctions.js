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
		'<input type="hidden" name="name_of_user" value="' + $(this).attr('data-name') + '">' +
		'<input type="hidden" name="user_email" value="' + $(this).attr('data-email') + '">' +
		'<textarea name="description"></textarea><button>Cancel</button><button>Submit</button></form></div>';

		$(bugForm).dialog({
			autoOpen: true,
			height: 420,
			width: 325,
			modal: true,
			position: [x, y],
			open: function()
			{
				var diag = $(this);

				//Cancel
				$('div.bug_form button:first').click(function(event){
					event.preventDefault();
					diag.dialog("destroy");
				});

				//Submit
				$('div.bug_form button:first').next().click(function(event){
					event.preventDefault();
					var key = '4fe9b4800176e';
					var formVals = $('div.bug_form form').serializeArray();
					formVals.push({'name':'key','value':key});
					formVals.push({'name':'jscript_error','value':localStorage.getItem('ClinicCasesErrorData')});
					$.post('lib/php/utilities/bug_reporter.php',formVals,function(data){
						var serverResponse = $.parseJSON(data);
						diag.dialog("destroy");
						notify(serverResponse.message, true);
					});
				});
			}
			}).siblings('.ui-dialog-titlebar').remove();
		});



});

//Log jscript errors to local storage and to ClinicCases.com
window.onerror = function(error, url, line) {

	var errorObject = { 'error': error, 'url': url, 'line': line };

	// Put the object into storage
	localStorage.setItem('ClinicCasesErrorData', JSON.stringify(errorObject));

	//Next log error to ClinicCases server so we can fix it
	var key = '4fe9b4800176e';

	var errorString = error + ' Line:' + line;
	$.post('lib/php/utilities/bug_reporter.php',{'key':key,'type':'errorLog','url':url,'error':errorString});

};