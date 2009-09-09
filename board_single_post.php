<?php
session_start();
if (!$_SESSION)
{die("Error:You are not logged in");}

include 'db.php';
include_once 'classes/format_dates_and_times.class.php';
include_once 'classes/get_names.php';

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
	
	
	
	$q = mysql_query("SELECT * FROM `cm_board` WHERE `id` = '$_POST[id]' LIMIT 1");
	
	echo "<a href=\"#\" class=\"smallgray\" onClick=\"\$('single_view').setStyle({display:'none'});$('psts').setStyle({display:'block'});
	$('frame').scrollTop=$('ps_$_POST[id]').offsetTop;return false;\">Go Back</a><br><br>";
	
	WHILE ($z = mysql_fetch_array($q))
	{
		echo " 
		<div class=\"post_spot\">
		
		<div id = \"print_div_$z[id]\">";
		get_photo($z[created_by]);

echo <<<POST
<div class="title">  $z[title]</div>    <div style="margin-bottom:7px;"><span class="smallgray_nohref">
POST;

formatDate($z[created]);
echo " by ";

$data = new get_names;
$data->get_users_name($z[created_by]);


echo " </span></div>";
	
echo <<<POST
   <div id = "$z[id]">$z[body]</div>
   </div>
POST;

    if ($z[attachment])
    {
    	echo "<div class = \"p_attach\" id = \"$z[id]_attach\"><span style=\"font-size:10pt;font-weight:bold;\">Attachments:</span>";
	list_attachments($z[id]);
	echo "</div>";
	}
	
echo <<<POST
<div><a href="#" class="smallgray" onClick="printPost('print_div_$z[id]');return false;">Print</a>  <a href="#" class="smallgray" onClick="$('new_comment_$z[id]').setStyle({display:'block'});return false;">Comment</a>  
POST;

	if ($z[created_by] == $_SESSION[login] || $z[locked] !== 'on')
	{
 		echo "<a href=\"#\" class=\"smallgray\" onClick=\"new Ajax.Updater('poster','board_edit_post.php',{evalScripts:true,parameters:{id:'$z[id]'},onComplete:function(){Effect.BlindDown($('poster'));}});return false;\">Edit</a>";
	}
	
	
	if ($z[created_by] == $_SESSION[login])
	{
  		echo "  <a href=\"#\" class=\"smallgray\" onClick=\"var ask = confirm('Are you sure you want to delete your post?');if (ask==true){new Ajax.Request('board_delete_post.php',{parameters:{id:'$z[id]'},onSuccess:function(){\$('single_view').setStyle({display:'none'});$('psts').setStyle({display:'block'});
	$('frame').scrollTop=$('ps_$_POST[id]').offsetTop;$('ps_$_POST[id]').hide();}});return false} else {return false;}\">Delete</a>";
	}
	
/* Get to this Later
echo<<<POST
  <a href="#" class="smallgray" onClick="">Hide</a>
POST;
*/

$get_comments = mysql_query("SELECT * FROM `cm_board` WHERE `orig_post_id` = '$z[id]'");
$comment_no = mysql_num_rows($get_comments);
if ($comment_no > 0)
{
	echo "  <a class='smallgray' href='#' onClick=\"new Ajax.Updater('comment_roll_$z[id]','board_display_comments.php',{method:'post',parameters:{post_id:'$z[id]',suppress:'yes'}});$('comment_roll_$z[id]').setStyle({display:'block'});return false;\"><span id=\"comments_notify_$z[id]\">View $comment_no comments</span></a>"; 
}

else
{
	
	echo "  <a class='smallgray' href='#' onClick=\"new Ajax.Updater('comment_roll_$z[id]','board_display_comments.php',{method:'post',parameters:{post_id:'$z[id]'}});$('comment_roll_$z[id]').setStyle({display:'block'});return false;\"><span id=\"comments_notify_$z[id]\"></span></a>"; 
	
}

echo<<<POST

</div></div>
POST;

//This adds the comments
echo<<<POST
<div class="comment" id="new_comment_$z[id]" name="new_comment_$z[id]" style="display:none;"><div style="float:left;margin-top:5px;margin-right:10px;">
POST;
get_photo($_SESSION[login]);
echo<<<POST
</div>
<div><textarea id="comment_area_$z[id]" name="comment_area_$z[id]" style="width:495px;height:70px;"></textarea></div>

<div style="text-align:right">
<a class="smallgray" href="#" onClick="new Ajax.Request('board_update_post.php',{evalScripts:true,method:'post',parameters:{orig_post_id:'$z[id]',pbody:$('comment_area_$z[id]').value,is_comment:'yes'},onSuccess:function()
	{
		$('new_comment_$z[id]').setStyle({display: 'none'});
 		$('comment_area_$z[id]').value = '';
 		
		new Ajax.Updater('comment_roll_$z[id]','board_display_comments.php',{evalScripts:true, method:'post',parameters:{post_id:'$z[id]',suppress:'yes'},onSuccess:function(){getNumComments($z[id]);$('comment_roll_$z[id]').setStyle({display:'block'});}});
			
		
		}
});return false;">Submit</a>  <a class="smallgray" href="#" onClick="$('new_comment_$z[id]').setStyle({display:'none'});return false;">Cancel</a></div>
</div>
<div id="comment_roll_$z[id]" class="comments_block" style="display:none;"></div>
<hr>

POST;
   

	}
?>
