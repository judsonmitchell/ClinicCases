<?php

session_start();
if (!$_SESSION)
{header('Location: index.php?login_error=3');}
include 'db.php';
include_once 'fckeditor/fckeditor.php';
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

$user_posts = get_allowed_posters($_SESSION[login]);

//Paging: 10 per

	$num_rows_to_view = "10";
	
	if ($_POST[begin_value])
	 	{
		$begin = $_POST[begin_value];
	 	}
	else
		{
		$begin = "0";
		
		}
	$set_start = $begin + 10;
	
if (isset($_POST[time])):
//we are just looking for new posts
	
		
		$q = mysql_query("SELECT * FROM `cm_board` WHERE `created_by` IN ($user_posts) AND `created` > '$_POST[time]' AND `is_comment` = '' ");
		
	
	
elseif (isset($_POST[forms_only])):
//we only want to view forms
	
		
		$q = mysql_query("SELECT * FROM `cm_board` WHERE `created_by` IN ($user_posts) AND `is_form` = 'on' ORDER BY `created` DESC LIMIT $set_start,$num_rows_to_view");		
		
elseif (isset($_POST[search])):
// we are searching
		$val = $_POST[search_val];
		$q = mysql_query("SELECT * FROM `cm_board` WHERE `title` LIKE '%$val%' or `body` LIKE '%$val%' or `attachment` LIKE '%$val%'");
	
	else:
	
	
//we are viewing all posts and updating the scroller		
		$q = mysql_query("SELECT * FROM `cm_board` WHERE `created_by` IN ($user_posts) AND `is_comment` = '' ORDER BY `created` DESC LIMIT $set_start,$num_rows_to_view");
	
	endif;	
	
	
	WHILE ($z = mysql_fetch_array($q))
	{
		echo " 
		<div id = \"ps_$z[id]\" class=\"post_spot\">
		<div id = \"print_div_$z[id]\">";
		get_photo($z[created_by]);

echo <<<POST
<a class="title" href="#" alt="View Single Post" title="View Single Post" onClick="$('single_view').setStyle({display:'block'});$('psts').setStyle({display:'none'});new Ajax.Updater('single_view','board_single_post.php',{evalScripts:true,parameters:{id:$z[id]}});return false;">  $z[title]</a>    <div style="margin-bottom:7px;"><span class="smallgray_nohref">
Created by 
POST;

$data = new get_names;
$nam =  $data->get_users_name($z[created_by]);

echo "$nam on ";
formatDate($z[created]);





echo " </span></div>";

if ($z[last_modified_by])

{
	
	echo "<div style=\"margin-bottom:7px;margin-top:-7px;\"><span class=\"smallgray_nohref\">Last edited by ";
	
	$data2 = new get_names;
	$nm2 = $data2->get_users_name($z[last_modified_by]);
	
	echo "$nm2 on ";
	
	formatDate($z[last_modified]);
	
	echo "</span></div>";
	
	
}
	//Here we shorten the body text if it is has more than fifty spaces, roughly fifty words.
	$size = substr_count($z[body]," ");
	if ($size > 50)
	{$preview = shorten($z[body]);
	 $body_text = $preview . "  <span style='color:gray'>...</span> <a href='#' class='smallgray' onClick=\"$('single_view').setStyle({display:'block'});$('psts').setStyle({display:'none'});new Ajax.Updater('single_view','board_single_post.php',{evalScripts:true,parameters:{id:$z[id]}});return false;\">[Read More]</a></span>";
	}
	else
	{$body_text = $z[body];}
   
echo <<<POST
   <div id = "$z[id]">$body_text</div>
   </div>
POST;

    if ($z[attachment])
    {
    	echo "<div class = \"p_attach\" id = \"$z[id]_attach\"><span style=\"font-size:10pt;font-weight:bold;\">Attachments:</span>";
	list_attachments($z[id]);
	echo "</div>";
	}
	
echo <<<POST
<div><a href="#" class="smallgray" onClick="printPost('print_div_$z[id]');return false;">Print</a>  <a href="#" class="smallgray" onClick="\$('new_comment_$z[id]').setStyle({display:'block'});return false;">Comment</a>  
POST;

	if ($z[created_by] == $_SESSION[login] || $z[locked] !== 'on')
	{
 		echo "<a href=\"#\" class=\"smallgray\" onClick=\"new Ajax.Updater('poster','board_edit_post.php',{evalScripts:true,parameters:{id:'$z[id]',begin_value:'$begin'},onComplete:function(){Effect.BlindDown($('poster'));$('frame').scrollTop = '0';}});return false;\">Edit</a>";
	}
	
	
	if ($z[created_by] == $_SESSION[login])
	{
  		echo "  <a href=\"#\" class=\"smallgray\" onClick=\"var ask = confirm('Are you sure you want to delete your post?');if (ask==true){new Ajax.Request('board_delete_post.php',{parameters:{id:'$z[id]'},onSuccess:function(){\$('ps_$z[id]').hide();}});return false} else {return false;}\">Delete</a>";
	}
	
/* Have to get to this later
echo<<<POST
  <a href="#" class="smallgray" onClick="">Hide</a>
POST;
*/

$get_comments = mysql_query("SELECT * FROM `cm_board` WHERE `orig_post_id` = '$z[id]'");
$comment_no = mysql_num_rows($get_comments);
if ($comment_no > 0)
{
	echo "  <a class='smallgray' href='#' onClick=\"new Ajax.Updater('comment_roll_$z[id]','board_display_comments.php',{method:'post',parameters:{post_id:'$z[id]'}});$('comment_roll_$z[id]').setStyle({display:'block'});return false;\"><span id=\"comments_notify_$z[id]\">View $comment_no comments</span></a>"; 
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
 		
		new Ajax.Updater('comment_roll_$z[id]','board_display_comments.php',{method:'post',parameters:{post_id:'$z[id]'},onSuccess:function(){getNumComments($z[id]);$('comment_roll_$z[id]').setStyle({display:'block'});}});
			
		
		}
});return false;">Submit</a>  <a class="smallgray" href="#" onClick="$('new_comment_$z[id]').setStyle({display:'none'});return false;">Cancel</a></div>
</div>
<div id="comment_roll_$z[id]" class="comments_block" style="display:none;"></div>
<hr>

POST;
   

	}
	
	


		if (!isset($_POST[time]))
		{
			if (mysql_num_rows($q)<1)
		{echo "<span class='smallgray_nohref'>There are no posts at this time.</span>";}
		
		
		echo "<script>pager.update({end:'$set_start'});</script>";

			
			//we are not just check for new posts.
			if (mysql_num_rows($q)<$num_rows_to_view)
				{
				  die("<script>Event.stopObserving('frame','scroll');</script>");
				}
		}
		
		
		if (isset($_POST[time]))
		{
			//update the time hash with the current time 
			$x = date("Y-m-d H:i:s", time());
			echo "<script>lTime.update({time:'$x'});</script>";
		}


		
?>
