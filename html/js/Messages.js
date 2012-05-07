//Scripts for messages page

$(document).ready(function(){

	//set header widget
	$('#msg_nav').addClass('ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr');

	//Add new message button
	$('button#new_msg').button({icons: {primary: "fff-icon-email-add"},text: true}).click(function(){
		$( "#quick_add_form" ).dialog( "open" );
	});

});
