 //Scripts for Board

$(document).ready(function() {

    //Add new post button
    $('#board_nav button').button({icons: {primary: "fff-icon-add"},text: true})
    .click(function(event) {
        event.preventDefault();

        //Show hidden new post div
        $('div.new_post').show().animate({'width': '900','height': '500'});

        //Get the id of this post from server
        $.post('lib/php/data/board_process.php', {'action': 'new'}, function(data) {
            var serverResponse = $.parseJSON(data);
            if (serverResponse.error === true)
            {
                notify(serverResponse.message, true);
            }
            else
            {
                $('div.new_post').attr('data-id', serverResponse.post_id);

                //handle attachment uploads
                var uploader = new qq.FileUploader({
                    element: $('.board_upload')[0],
                    action: 'lib/php/utilities/file_upload_board.php',
                    params: {'post_id': serverResponse.post_id},
                    template: '<div class="qq-uploader">' +
                    '<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>' +
                    '<div class="qq-upload-button">Choose Files</div>' +
                    '<ul class="qq-upload-list"></ul>' +
                    '</div>'

                });
            }

        });

        //Create lwrte
        var bodyText = $('div.new_post').find('.post_edit').rte({
            css: ['lib/javascripts/lwrte/default2.css'],
            width: 900,
            height: 300,
            controls_rte: rte_toolbar
        });

        //Define post title
        $('input[name="post_title"]').focusin(function(event) {
            event.stopPropagation();
            $(this).parent().unbind('click.sizePost');
            $(this).val('').css({'color': 'black'});
        });

        //Add Chosen to select
        $('select[name="viewer_select[]"]').chosen();

        //Add post color chooser
        $('select[name="post_color"]').change(function() {
            var colorVal = 'rgba(' + $(this).val() + ',0.5)';
            $('div.rte-zone').css({'background-color': colorVal});

        });

        //Cancel or submit
        $('.board_new_item_menu_bottom button')
        .first().click(function(event) {
            event.preventDefault();
            alert('cancel');
        })
        .next().click(function(event) {
            event.preventDefault();
            var formVals = $('form').serializeArray();
            var postId = $('div.new_post').attr('data-id');
            formVals.push({'name': 'id','value': postId});
            formVals.push({'name': 'action','value': 'edit'});
            formVals.push({'name': 'text','value': bodyText[0].get_content()});
            $.post('lib/php/data/board_process.php', formVals, function(data) {
                var serverResponse = $.parseJSON(data);
                if (serverResponse.error === true)
                {
                    notify(serverResponse.message, true);
                }
                else
                {
                    notify(serverResponse.message);
                    $('#board_panel').load('lib/php/data/board_load.php');
                }
            });
        });

    });

    $('#board_panel').load('lib/php/data/board_load.php', function() {


    });


    //Listeners

    //resize post on click
    $('.board_item').live('click.sizePost', function() {
        $(this).not('.new_post').animate({'width': '400','height': '300'}, function() {
            $(this).css({'height': 'auto','max-width': '400'});
            $('.board_item').not(this).animate({'width': '200','height': '200'});
        });
    });

    //download attachments
    $('a.attachment').live('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        var itemId = $(this).attr('data-id');
        $.download('lib/php/data/board_process.php', {'item_id': itemId,'action': 'download'});
    });

    //Delete post
    $('a.board_item_delete').live('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        var itemId = $(this).closest('div.board_item').attr('data-id');
        var dialogWin = $('<div class="dialog-casenote-delete" title="Delete this Post?">This post and any attachments will be permanently deleted.  Are you sure?</div>').dialog({
            autoOpen: false,
            resizable: false,
            modal: true,
            buttons: {
                "Yes": function() {
                    $.post('lib/php/data/board_process.php', {'action': 'delete','item_id': itemId}, function(data) {
                        var serverResponse = $.parseJSON(data);
                        if (serverResponse.error === true)
                        {
                            notify(serverResponse.message, true);
                        }
                        else
                        {
                            notify(serverResponse.message);
                            $('#board_panel').load('lib/php/data/board_load.php');
                        }
                    });

                    $(this).dialog("destroy");
                },
                "No": function() {
                    $(this).dialog("destroy");
                }
            }
        });

        $(dialogWin).dialog('open');

    });

    //Edit post
    $('a.board_item_edit').live('click', function(event) {
        event.preventDefault();
        event.stopPropagation();

        var thisItem = $(this).closest('div.board_item');
        var editItem = $('div.new_post').clone();
        var editId = thisItem.attr('data-id');

        thisItem.hide();
        thisItem.after(editItem);

        editItem.css({'display': 'inline-block','height': '500px','width': '900px'});

        //Create lwrte
        var bodyText = editItem.find('.post_edit').rte({
            css: ['lib/javascripts/lwrte/default2.css'],
            width: 900,
            height: 300,
            controls_rte: rte_toolbar
        });

        //Add uploader
        var editUploader = new qq.FileUploader({
            element: editItem.find('.board_upload')[0],
            action: 'lib/php/utilities/file_upload_board.php',
            params: {'post_id': editId},
            template: '<div class="qq-uploader">' +
            '<div class="qq-upload-drop-area"><span>Drop files here to upload</span></div>' +
            '<div class="qq-upload-button">Choose More Files</div>' +
            '<ul class="qq-upload-list"></ul>' +
            '</div>'

        });

        //Set background color
        editItem.find('div.rte-zone').css({'background-color': thisItem.css('background-color')});

        //Add post color chooser
        editItem.find('select[name="post_color"]').change(function() {
            var colorVal = 'rgba(' + $(this).val() + ',0.5)';
            editItem.find('div.rte-zone').css({'background-color': colorVal});

        });

        editItem.find('select[name="post_color"] option').first().before("<option value='' selected=selected></option>");

        //Set Values
        editItem.find('input[name="post_title"]').val(thisItem.find('h3').text())
        .css({'color': 'black'});

        editItem.find('div.new_post').attr('data-id', editId);

        //Get body text from selected item and put it in text editor
        bodyText[0].set_content(thisItem.find('div.body_text').html());

        //Add Chosen to select
        editItem.find('select[name="viewer_select[]"]').chosen();

        //Show the current attachments
        editItem.find('div.board_new_item_menu_bottom label').append(thisItem.find('div.attachment_container').html() + '<br />');

        editItem.find('button').first().click(function(event) {
            event.preventDefault();
            editItem.remove();
            thisItem.show();
        })
        .next()
        .click(function(event) {
            event.preventDefault();
            var formVals = $(this).closest('form').serializeArray();
            formVals.push({'name': 'id','value': editId});
            formVals.push({'name': 'action','value': 'edit'});
            formVals.push({'name': 'text','value': bodyText[0].get_content()});
            $.post('lib/php/data/board_process.php', formVals, function(data) {
                var serverResponse = $.parseJSON(data);
                if (serverResponse.error === true)
                {
                    notify(serverResponse.message, true);
                }
                else
                {
                    notify(serverResponse.message);
                    $('#board_panel').load('lib/php/data/board_load.php');
                }
            });

        });



    });


});