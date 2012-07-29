//Scripts for Board

$(document).ready(function() {

	//Add quick add button
    $('#board_nav button').button({icons: {primary: "fff-icon-add"},text: true})
		.click(function() {

    });

	$('#board_panel').load('lib/php/data/board_load.php',function(){

	});



});