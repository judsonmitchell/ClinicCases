//Scripts for Home page


$(document).ready(function(){
	$('#home_nav').addClass('ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr');
	$('.home_nav_choices').buttonset();
	$('button#quick_add').button({icons: {primary: "fff-icon-add"},text: true});
});
