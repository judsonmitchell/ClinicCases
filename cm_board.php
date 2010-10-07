<?php
session_start();
if (!$_SESSION)
{header('Location: index.php?login_error=3');}
include 'db.php';
include 'fckeditor/fckeditor.php';
include_once 'classes/get_allowed_posters.php';
include_once 'classes/format_dates_and_times.class.php';
include_once 'classes/get_names.php';

//These are page-specific functions for creating the post view

function get_photo($username)
	{

		$q = mysql_query("SELECT `picture_url`,`username` FROM `cm_users` WHERE `username` = '$username' LIMIT 1");
		$qq = mysql_fetch_object($q);
		echo "<img src = '$qq->picture_url' border='0' width=32 height=32>";


	}

function list_attachments($id)
	{
		$a = mysql_query("SELECT `id`,`attachment` FROM `cm_board` WHERE `id` = '$id' LIMIT 1");
		$aa = mysql_fetch_object($a);
		$list = explode('|',$aa->attachment);
			foreach ($list as $item)
				{

					echo "<a class='small' href='docs/" . $item . "' target='new'>$item</a>     ";

				}

	}

function shorten($string)
	{
		$br = explode(" ", $string);
		$short = array_slice($br,0,50);

			foreach ($short as $word)
				{
					$shortened .= $word . " ";
				}

		return $shortened;
	}

?>

<html>
<head>
<title>Board - ClinicCases</title>
<link rel="stylesheet" href="cm.css" type="text/css">
<link rel="stylesheet"  href="cm_tabs.css" type="text/css">
<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico">

<script src="./javascripts/ajax_scripts.js" type="text/javascript"></script>
<script src="./javascripts/validations.js" type="text/javascript"></script>
<script src="scriptaculous/lib/prototype.js" type="text/javascript"></script>
<script src="scriptaculous/src/scriptaculous.js" type="text/javascript"></script>
<script src="./javascripts/ajaxUpload.js" type="text/javascript"></script>
<script src="./javascripts/FormProtector.js" type="text/javascript"></script>
<script type="text/javascript" src="./javascripts/print.js"></script>


<script>

new Ajax.PeriodicalUpdater('session_info', 'session_keep_alive.php', {
    method: 'post',parameters:{sid:'<?php echo $_COOKIE[PHPSESSID]; ?>'},
    frequency: 300
  });


	//Hash to get the start value for paging

	pager = new Hash({end:'-10'});

	//Loads the first ten posts
	Event.observe(window, 'load', function() {
	new Ajax.Updater('psts', 'board_refresh.php', {evalScripts:true, method:'post',parameters:{begin_value:pager.get('end')}});
	})

	//Checks for new posts
	<?php $x =  date("Y-m-d H:i:s", time()); ?>
	lTime = new Hash({time:'<?php echo $x;?>'});
	refresh = new PeriodicalExecuter(function(){
		new Ajax.Updater('psts','board_refresh.php',{evalScripts:true,method:'post',parameters:{time:lTime.get('time')},insertion: 'top'});

		},300);


	 //Infinite Scroll
	 Event.observe(window, 'load', function(event) {
Event.observe('frame','scroll',function(){

	p = this.cumulativeScrollOffset();

	t = $('psts').getHeight();

	//This will demonstrate the multiple firing in IE and Opera.  See below.
	//alert('you have scrolled');
	if (p.top / t > .40)
	{

	//Unfixed bug.  In IE and Opera, if you use mousewheel to scroll, it will fire this multiple times, putting 2 and 3 copies of the new data in the scroll window. FF,Safari,Chrome are OK. Event.stop(event) does not fix this.

		new Ajax.Updater('psts','board_refresh.php',{evalScripts:true,method:'post',parameters:{begin_value:pager.get('end')},insertion: 'bottom'});



	}

})
})


//This is to update the pbody field so it can be read by other scripts
//Thanks to this fine gentleman: http://www.fckeditor.net/forums/viewtopic.php?f=6&t=10986&p=28939&hilit=prototype+serialize#p28939

	function getFckValue()
	{
	  this.UpdateEditorFormValue = function()
	  {
		for ( i = 0; i < parent.frames.length; ++i )
		if ( parent.frames[i].FCK )
		parent.frames[i].FCK.UpdateLinkedField();
	  }

	}
	var FckUpdate = new getFckValue();

	function getNumComments(id)
	{
		new Ajax.Request('board_display_comments.php',{parameters:{comment_no:'yes',post_id:id},onSuccess: function(transport){$('comments_notify_' +id).innerHTML = transport.responseText;}});
	}



</script>
</head>
<body>
<div id="notifications"></div>
<div id = "bug" style="display:none;">
</div>

<div id = "nav_container">
<div id="header">

  <ul>
  <?php
  if ($_SESSION['class'] == 'admin')
		{
		echo <<<TABS
<li><a href="cm_admin_home.php"><span id="tab1">At A Glance</span></a></li>
<li><a href="cm_admin_cases.php"><span id="tab2">Cases</span></a></li>
<li><a href="cm_admin_students.php"><span id="tab4">Students</span></a></li>
<li><a href="cm_admin_users.php"><span id="tab5">Users</span></a></li>
<li><a href="cm_board.php"><span id="tab3">Board</span></a></li>
<li><a href="cm_admin_preferences.php"><span id="tab7">Prefs</span></a></li>
TABS;
		}
		else
		{
			ECHO <<<TABS
<li><a href="cm_home.php"><span id="tab1">At A Glance</span></a></li>
TABS;
			if ($_SESSION['pref_case'] == 'on')
				{
				echo "<li><a href=\"cm_cases.php\"><span id=\"tab2\">Cases</span></a></li>";
				}

				if ($_SESSION['pref_journal'] == 'on')
				{
				echo "<li><a href=\"cm_journals.php\"><span id=\"tab3\">Journals</span></a></li>";
				}

				if ($_SESSION['class'] == 'prof')
				{echo "<li><a href=\"cm_students.php\"><span id=\"tab4\">Students</span></a></li>";}

				ECHO <<<TABS
   <li id="current"><a href="cm_board.php"><span id="tab5">Board</span></a></li>

   <li><a href="cm_utilities.php"><span id="tab6">Utilities</span></a></li>

   <li><a href="cm_preferences.php"><span id="tab7">Prefs</span></a></li>
TABS;
}
?>
  </ul>
</div>
<?php include 'cm_menus.php';?>

</div>
<div id="content" >
<!-- This is the form to add a new board post -->

<div class="box_menu">
<p style = "margin-left:15px">
<a href="#" onClick="new Ajax.Request('board_create_new_post.php',{method:'get', onSuccess: function(transport) {$('post_id').value=transport.responseText;}});$('frame').scrollTop = '0';Effect.BlindDown('poster');return false;">New Post</a>  
  | <span id="filter"> <a href="#" onClick="	pager = new Hash({end:'-10'});
new Ajax.Updater('psts', 'board_refresh.php', {evalScripts:true, method:'post',parameters:{forms_only:'y',begin_value:pager.get('end')}});$('filter').update('<a href=\'#\' onclick=\'window.location.reload();\'>Show All</a>');return false;">Show Forms Only</a>
</span>
 | 
<input type="text" id="psearch" name="psearch" value="Search" size="30" onFocus="this.value=''" onkeyup="new Ajax.Updater('psts','board_refresh.php',{evalScripts:true,method:'post',parameters:{search:'y',search_val:$('psearch').value}})">
</p>

</div>




<div id="frame">


<div id="poster" style="display:none;">
<table width="800px" border="0" align="center">
<tr><td valign="top" width="550px">
<form name="new_post" id="new_post">
<label>Title</label><br><input name = "title" id = "title" type="text" style="width:500px;"><br>
<input type="hidden" name="post_id" id="post_id">


<label>Body</label><br>
<?php
$oFCKeditor = new FCKeditor('pbody');
$oFCKeditor->BasePath = 'fckeditor/';
$oFCKeditor->ToolbarSet = 'Basic' ;
$oFCKeditor->Width  = '500px' ;
$oFCKeditor->Height = '300px' ;
$oFCKeditor->Create();
?>
<table width="300px" align="center">
<tr><td><label>Is a form</label><input type="checkbox" name="isform" id="isform"></td><td><label>Locked</label><input type="checkbox" name="locked" id="locked" checked></td><td>
<a href="#" alt="Submit" title="Submit" onClick="FckUpdate.UpdateEditorFormValue();new Ajax.Request('board_update_post.php',{method:'post',parameters:{post_id:$F('post_id'),title:$F('title'),pbody:$F('pbody'),locked:$F('locked'),isform:$F('isform')},
onSuccess:function(){
			new Ajax.Updater('psts','board_refresh.php',{evalScripts:true,parameters:{begin_value:'-10'}});
			fp.resetAlrt();
			$('new_post').reset();
			$('attach_list').innerHTML ='';
			oEditor = FCKeditorAPI.GetInstance('pbody');
			HTML='';
			oEditor.SetHTML(HTML);
			Effect.BlindUp('poster');
			$('notifications').style.display = 'inline';
			$('notifications').update('Post Added');
			Effect.Fade($('notifications'),{duration:3.0})
			return false;

			}
});return false"><img src="./images/check_yellow.png" border="0" ></a></td><td> <a href="#" title="Cancel" alt="Cancel" onClick="
	var check = confirm('Are you sure you want to abandon this post?');
	if (check == true)
		{
			new Ajax.Request('board_delete.php',{method:'post',parameters:{id:$F('post_id'),del_type:'cancel'}});
			fp.resetAlrt();
			$('new_post').reset();
			$('attach_list').innerHTML ='';
			oEditor = FCKeditorAPI.GetInstance('pbody');
			HTML='';
			oEditor.SetHTML(HTML);
			Effect.BlindUp('poster');
			$('notifications').style.display = 'inline';
			$('notifications').update('Post Deleted');
			Effect.Fade($('notifications'),{duration:3.0})
			return false;
			}
				else {return false;}"><img src="images/cancel_small.png" border="0"></a></td></tr></table>

</td><td valign="top" width="250px">

<div style="margin-top:80px;">
<label>Attachments</label> <a  class="singlenav" id="upload_button"><img src="images/add.png" border="0"></a><br><br><br>
</div>
<div id="attach_list">
<span style="color:gray;font-size:10px;">None</span>
</div>






</div>
</td></tr></table>
</form>

<script>
//This observer waits until the id is returned from board_create_new_post.php and then fires
new Form.Element.Observer(
  'post_id',
  0.2,  // 200 milliseconds
  function(el, value){


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
})


fp = new FormProtector('new_post');fp.setMessage('If you proceed, your work will be lost.');
</script>

</div>
<div id="single_view" style="display:none;"></div>
<div id="psts">

</div>
</div>

<script>
//script to set the height of #frame
dimensions = $('content').getDimensions();
ac = dimensions.height - 39;

$('frame').setStyle({height:ac + 'px'});






</script>
</body>
</html>
