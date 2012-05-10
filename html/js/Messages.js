//Scripts for messages page

//Load new messages on scroll
function addMoreMessages(scrollTarget,view) {

    var scrollAmount = scrollTarget[0].scrollTop;
    var scrollHeight = scrollTarget[0].scrollHeight;

    scrollPercent = (scrollAmount / (scrollHeight - scrollTarget.height())) * 100;

    if (scrollPercent > 70)
    {
        //the start for the query is added to the scrollTarget object
        if (typeof scrollTarget.data('startVal') == "undefined")
        {
            startNum = 20;
            scrollTarget.data('startVal', startNum);
        }
        else
        {
            startNum = scrollTarget.data('startVal') + 20;
            scrollTarget.data('startVal', startNum);
        }

        $.post('lib/php/data/messages_load.php', {'type': view,'start': scrollTarget.data('startVal')}, function(data) {

            //var t represents number of messages in returned data; if 0,return false;
            var t = $(data).find('div').length;

            if (t === 0)

            {
                return false;
            }

            else
            {
                scrollTarget.append(data);
                $('div.msg').addClass('ui-corner-all');
            }

        });

    }
}

$(document).ready(function(){

	var target = $('div#msg_panel');

	var view = $('select#msg_view_chooser').val();

	var start = target.data('startVal');

	//set header widget
	$('#msg_nav').addClass('ui-toolbar ui-widget-header ui-corner-tl ui-corner-tr');

	//Add new message button
	$('button#new_msg').button({icons: {primary: "fff-icon-email-add"},text: true}).click(function(){
		$( "#quick_add_form" ).dialog( "open" );
	});

	//Load messages and refresh
	msgLoad = function(){

		$.post('lib/php/data/messages_load.php', {'type' : 'inbox','start' : '0'},function(data){
				target.html(data);
				//Round Corners
				$('div.msg').addClass('ui-corner-all');
				//Set the start value for scroll
				target.data('startVal',0);
			});
	};

	msgLoad();

	//msgRefresh = setInterval("msgLoad()",9000);

	//Toggle message message open/closed state
	$('div.msg_bar').live('click',function(){

		if ($(this).parent().hasClass('msg_closed'))
		{
			$(this).parent().removeClass('msg_closed').addClass('msg_opened');
		}
		else
		{
			$(this).parent().removeClass('msg_opened').addClass('msg_closed');
		}

	});

	//Change views
	$('select#msg_view_chooser').change(function(){
		var view = $(this).val();

		$.post('lib/php/data/messages_load.php', {'type' : view, 'start' : '0'},function(data){
				target.html(data);
				//Round Corners
				$('div.msg').addClass('ui-corner-all');
				//Set the start value for scroll
				target.data('startVal',0);
			});
	});

	//bind the scroll event for the window and add more messages on scroll
    target.bind('scroll', function() {
        addMoreMessages(target,$('select#msg_view_chooser').val());
    });


});
