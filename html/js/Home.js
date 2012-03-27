//Scripts for Home page


$(document).ready(function(){

	//set header widget
	$('#home_nav').addClass('ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr');

	//Add navigation buttons
	$('.home_nav_choices').buttonset();

	//Add quick add button
	$('button#quick_add').button({icons: {primary: "fff-icon-add"},text: true});

	//Add navigation actions

	var target = $('div#home_panel');

	$('#activity_button').click(function(){

		target.load('lib/php/data/home_activities_load.php');
	});

	$('#upcoming_button').click(function(){

		target.load('html/templates/interior/home_upcoming.php', function(){
			$('#calendar').fullCalendar({theme:true, aspectRatio:2});
		});

	});

	$('#trends_button').click(function(){

		target.html('<p>Trends Here</p>');

	});

	//Set default view - activities
	$('#activity_button').trigger('click');

});

