<?php
session_start();
if (!$_SESSION)
{die('Error: You are not logged in');}
include 'db.php';
include_once 'fckeditor/fckeditor.php';


$q = mysql_query("SELECT * FROM `cm_board` WHERE `id` = '$_POST[id]' LIMIT 1");
$qq = mysql_fetch_object($q);
?>

<table width="800px" border="0" align="center">
<tr>
<td valign="top" width="550px">
<form name="new_post" id="new_post">
<label>Title</label><br><input name = "title" id = "title" type="text" value= "<?php echo $qq->title; ?>"style="width:500px;"><br></br>
<input type="hidden" name="post_id" id="post_id" value="<?php echo $_POST[id];  ?>">


<label>Body</label><br>
<?php
$oFCKeditor = new FCKeditor('pbody');
$oFCKeditor->Value = $qq->body;
$oFCKeditor->BasePath = 'fckeditor/';
$oFCKeditor->ToolbarSet = 'Basic' ;
$oFCKeditor->Width  = '500px' ;
$oFCKeditor->Height = '300px' ;
$oFCKeditor->Create();
?>

<table width="300px" align="center">
<tr>
<td><label>Locked</label><input type="checkbox" name="locked" id="locked" <?php if($qq->locked == 'on'){echo ' checked';} ?> >
</td>
<td>
<a href="#" alt="Submit" title="Submit" onClick="FckUpdate.UpdateEditorFormValue();new Ajax.Request('board_update_post.php',{method:'post',parameters:{post_id:$F('post_id'),title:$F('title'),pbody:$F('pbody'),locked:$F('locked'),edit:'yes'},
onSuccess:function(){
			new Ajax.Updater('psts','board_refresh.php',{evalScripts:true,parameters:{begin_value:'<?php echo $_POST[begin_value]; ?>'}});
			fp.resetAlrt();
			$('new_post').reset();
			$('attach_list').innerHTML ='';
			oEditor = FCKeditorAPI.GetInstance('pbody');
			HTML='';
			oEditor.SetHTML(HTML);
			Effect.BlindUp('poster');
			$('notifications').style.display = 'inline';
			$('notifications').update('Post Edited');
			Effect.Fade($('notifications'),{duration:3.0})
			return false;
			
			}
});return false"><img src="./images/check_yellow.png" border="0" ></a>
</td>
<td> <a href="#" title="Cancel" alt="Cancel" onClick="
	var check = confirm('Are you sure you want to abandon your edits?');
	if (check == true)
		{	
			
			fp.resetAlrt();
			$('new_post').reset();
			$('attach_list').innerHTML ='';
			oEditor = FCKeditorAPI.GetInstance('pbody');
			HTML='';
			oEditor.SetHTML(HTML);
			Effect.BlindUp('poster');
			$('notifications').style.display = 'inline';
			$('notifications').update('Edits Abandoned');
			Effect.Fade($('notifications'),{duration:3.0})
			return false;
			}
				else {return false;}"><img src="images/cancel_small.png" border="0">
				</a>
</td>
</tr></table>
</form>
</td>
<td valign="top" width="250px">

<div style="margin-top:80px;">
<label>Attachments</label> <a  class="singlenav" id="upload_button"><img src="images/add.png" border="0"></a><br><br><br>
</div>
<div id="attach_list">
<span style="color:gray;font-size:10px;">None</span>
</div>


</td>
</tr></table>

<script>



		var button = $('upload_button'),docs = $('attach_list'), interval,statusw = $('notifications'), postId=$('post_id').value;

				new AjaxUpload('upload_button', {action: 'attachment_upload.php', name:'new_post',data :{'post_id' : postId},autoSubmit: true,onSubmit : function(file, ext){

							// change button text, when user selects file
							statusw.style.display = 'inline';
							statusw.update('Uploading ' + file);

							// If you want to allow uploading only 1 file at time,
							// you can disable upload button
							//this.disable();

							// Animating upload button
							// Uploding -> Uploading. -> Uploading...
							interval = window.setInterval(function(){
								var text = statusw.innerHTML;

								if (text.length < 100){
									statusw.update(text + '.');
								} else {
									statusw.update('Uploading ' + file);
								}
							}, 200);

				},
				onComplete: function(file, response){
							//console.log(response);
							if (response !== 'Attachment Uploaded')
							{
								statusw.innerHTML = response;

								window.clearInterval(interval);
								Effect.Fade(statusw,{duration:6.0})
								return false;

							}
							else
							{

							window.clearInterval(interval);

							// enable upload button
							//this.enable();

							//statusw.innerHTML = 'Upload successful.';
							// add file to the list

								statusw.innerHTML = response;
								new Ajax.Updater(docs,'board_attchmnt_list.php',{evalScripts:true,method:'post',parameters: {id :postId}});
								Effect.Fade(statusw,{duration:3.0});
						}}

				});



fp = new FormProtector('new_post');fp.setMessage('If you proceed, your work will be lost.');
new Ajax.Updater('attach_list','board_attchmnt_list.php',{evalScripts:true,method:'post',parameters: {id :<?php echo $qq->id;?>}});
</script>


</div>








