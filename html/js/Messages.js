//Scripts for messages page

$(document).ready(function(){

	//set header widget
	$('#msg_nav').addClass('ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr');

	//Add new message button
	$('button#new_msg').button({icons: {primary: "fff-icon-email-add"},text: true}).click(function(){
		$( "#quick_add_form" ).dialog( "open" );
	});

	//Load messages and refresh
	msgLoad = function(){

		var target = $('div#msg_panel');

		$.post('lib/php/data/messages_load.php', {'type' : 'inbox'},function(data){
				target.html(data);
				//Round Corners
				$('div.msg').addClass('ui-corner-all');
			});
	};

	msgLoad();

	//msgRefresh = setInterval("msgLoad()",9000);


	//Toggle message message open/closed state
	$('div.msg').live('click',function(){

		if ($(this).hasClass('msg_closed'))
		{
			$(this).removeClass('msg_closed').addClass('msg_opened');
		}
		else
		{
			$(this).removeClass('msg_opened').addClass('msg_closed');
		}

	});


});
