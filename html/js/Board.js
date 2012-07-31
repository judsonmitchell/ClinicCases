//Scripts for Board

$(document).ready(function() {

	//Add new post button
    $('#board_nav button').button({icons: {primary: "fff-icon-add"},text: true})
		.click(function(event) {
			event.preventDefault();
			$('div.new_post').show().animate({'width':'900','height':'500'});

			//Create lwrte
            var arr = $('div.new_post').find('.post_edit').rte({
            css: ['lib/javascripts/lwrte/default2.css'],
            width: 900,
            height: 350,
            controls_rte: rte_toolbar
            });

            $('input[name="post_title"]').focusin(function(event){
				event.stopPropagation();
				$(this).parent().unbind('click.sizePost');
				$(this).val('').css({'color':'black'});
            });

    });

	$('#board_panel').load('lib/php/data/board_load.php',function(){


	});


	//Listeners
	$('.board_item').live('click.sizePost', function(){

		$(this).not('.new_post').animate({'width':'400','height':'300'},function(){
			$(this).css({'height':'auto','max-width':'400'});
			$('.board_item').not(this).animate({'width':'200','height':'200'});
		});
	});

});