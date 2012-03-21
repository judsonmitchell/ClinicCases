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

		target.html('<p>Activities Here</p>');
	});

	$('#upcoming_button').click(function(){

		target.html('<p>Upcoming Here</p>');

	});

	$('#trends_button').click(function(){

		target.html('<p>Trends Here</p>');

	});

	//Set default view - activities
	$('#activity_button').trigger('click');

});

