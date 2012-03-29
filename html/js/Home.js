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

	var target = $('div#home_panel');

	$('#activity_button').click(function(){

		//Update activities stream periodically while it is being viewed
		activitiesRefresh = setInterval(function(){

			$.ajax({ url: "lib/php/data/home_activities_load.php", success: function(data){
				target.html(data);
				//Remove last hr for styling purposes
				target.find('hr').last().remove();
				}, dataType: "html"});}, 90000);


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
	});

	$('#upcoming_button').click(function(){

		clearInterval(activitiesRefresh);

		target.load('html/templates/interior/home_upcoming.php', function(){
			$('#calendar').fullCalendar({theme:true, aspectRatio:2});
		});

	});

	$('#trends_button').click(function(){

		clearInterval(activitiesRefresh);

		target.html('<p>Trends Here</p>');

	});

	//Set default view - activities
	$('#activity_button').trigger('click').next('label').addClass('ui-state-active');

	//Create modal quick add form
	var x = $("button#quick_add").offset().left - 150;
	var y = $("button#quick_add").offset().top + 40;

	//$('div#quick_add_nav').buttonset();

	$( "#quick_add_form" ).dialog({
			autoOpen: false,
			height: 400,
			width: 300,
			modal: true,
			position: [x,y]
		}).siblings('.ui-dialog-titlebar').remove();

});
