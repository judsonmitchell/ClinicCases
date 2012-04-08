//Scripts for Home page


$(document).ready(function(){

	//set header widget
	$('#home_nav').addClass('ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr');

	//Add navigation buttons
	$('.home_nav_choices').buttonset();

	//Add quick add button
	$('button#quick_add').button({icons: {primary: "fff-icon-add"},text: true}).click(function(){
		$( "#quick_add_form" ).dialog( "open" );
	});

	//Add navigation actions

	target = $('div#home_panel');

	$('#activity_button').click(function(){

		//Update activities stream periodically while it is being viewed
		activitiesRefresh = setInterval(function(){

			$.ajax({ url: "lib/php/data/home_activities_load.php", success: function(data){
				target.html(data);
				//Remove last hr for styling purposes
				target.find('hr').last().remove();
				}, dataType: "html"});}, 90000);

		activitiesLoad = function(target){

		target.load('lib/php/data/home_activities_load.php',function(){

			//enable download when user clicks a document link
			$('a.doc_view').live('click',function(event){

				event.preventDefault();
				var itemId = $(this).attr('data-id');

				if ($(this).hasClass('url'))  //Link is a url
					{
						$.post('lib/php/data/cases_documents_process.php', {'action': 'open','item_id': itemId,'doc_type': 'document'}, function(data) {
									var serverResponse = $.parseJSON(data);
									window.open(serverResponse.target_url, '_blank');
									});
					}
				else if ($(this).hasClass('ccd')) //Link is a ClinicCases document.  Just direct user to case documents for now
					{
						var url = $(this).closest('p').prev('p').find('a').attr('href');
						window.location.href = url;
					}
				else
					{
						$.download('lib/php/data/cases_documents_process.php', {'item_id': itemId,'action': 'open','doc_type': 'document'});//any other document, download it.
					}

			});

			//Remove last hr for styling purposes
			target.find('hr').last().remove();

			});
		};
		activitiesLoad(target);

	});

	$('#upcoming_button').click(function(){

		clearInterval(window.activitiesRefresh);

		target.load('html/templates/interior/home_upcoming.php', function(){
			$('#calendar').fullCalendar({
				theme:true,
				aspectRatio:2,
				header:{
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
				},
				eventSources: ['lib/php/data/home_events_load.php'],
				eventClick: function(event){
					console.log(event);
					$('div#event_detail_window')
					.html("<a class='event_detail_close' href='#'><img src='html/ico/cross.png' border=0 title='Close'></a><h3>" + event.title +"</h3><hr />" + event.description)
					.dialog("open");
				}
			});
		});

	});

	$('#trends_button').click(function(){

		clearInterval(window.activitiesRefresh);

		target.html('<p>Trends Here</p>');

	});

	//Set default view - activities
	$('#activity_button').trigger('click').next('label').addClass('ui-state-active');

	//Create modal quick add form dialog.

	//Position dialog to the bottom of the quick add button
	var x = $("button#quick_add").offset().left - 150;
	var y = $("button#quick_add").offset().top + 40;

	$( "#quick_add_form" ).dialog({
			autoOpen: false,
			height: 410,
			width: 300,
			modal: true,
			position: [x,y]
		}).siblings('.ui-dialog-titlebar').remove();

	//Create modal dialog for event detail
	$("div#event_detail_window").dialog({
			autoOpen: false,
			height: 400,
			width: 500,
			modal: true
		}).siblings('.ui-dialog-titlebar').remove();

	//Toggle between adding casenote and event
	$("#quick_add_form a.toggle").click(function(event){

		event.preventDefault();

		if ($(this).hasClass('active'))
		{
			return false;
		}
		else
		{
			$(this).addClass('active');
			$(this).siblings('a.toggle').removeClass('active');
			$('div.toggle_form').toggle();
		}

	});

	//Create datepickers
	$('#cn_date').datepicker();
	$('#cn_date').datepicker('setDate',new Date());

	//Create combobox for case ids
	$('select[name = "csenote_case_id"]').combobox();
	$('input.ui-autocomplete-input').click(function(){$(this).select();});

	//Style case note submit button and handle case note submit
	$('button#quick_add_cn_submit').button({icons: {primary: "fff-icon-add"},text: true})
	.click(function(event){
		event.preventDefault();

		//serialize form values
		var cseVals = $(this).closest('form').serializeArray();

		var errString = validQuickCaseNote(cseVals);

		//notify user or errors or submit form
		if (errString.length)
		{
			$(this).closest('p').siblings('p.error').html(errString);

			return false;
		}
		else
		{
			$.post('lib/php/data/cases_casenotes_process.php', cseVals, function(data) {
				var serverResponse = $.parseJSON(data);
				if (serverResponse.error === true)
				{

					$(this).closest('p').siblings('p.error').html(serverResponse.message);

					return false;

				}
				else
				{
					notify(serverResponse.message);
					if($('input#activity_button').next().hasClass('ui-state-active'))//We are looking at activities
						{
							activitiesLoad(target);
						}
					$('a.quick_add_close').trigger('click');
				}
			});
		}
	});

	//Close quick add dialog
	$('a.quick_add_close').click(function(event){
		event.preventDefault();

		//reset forms, clear errors
		$('#quick_add_form form').each(function(){this.reset();}).find('p.error').html('');
		//Put date back to default - today
		$('#cn_date').datepicker('setDate',new Date());
		//Reset case select value to Non-Case time
		$('select#cn_case').val('NC');
		$('input.ui-autocomplete-input').val( $("select#cn_case option:selected").text());

		//Close dialog
		$("#quick_add_form").dialog("close");
	});

	//Close event detail dialog
	$('a.event_detail_close').live('click',function(event){
		event.preventDefault();

		$("#event_detail_window").dialog("close");
	});

});
