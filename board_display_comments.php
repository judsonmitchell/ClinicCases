<?php
session_start();
if (!$_SESSION)
{die("Error:You are not logged in");}

include 'db.php';
include_once 'classes/get_names.php';

function get_photo($username)
	{
		
		$q = mysql_query("SELECT `picture_url`,`username` FROM `cm_users` WHERE `username` = '$username' LIMIT 1");
		$qq = mysql_fetch_object($q);
		echo "<img src = '$qq->picture_url' border='0' width=32 height=32>";
		
		
	}
	
	
	if ($_POST[comment_no])
			//Just get the number of comments
		{
			
			$q = mysql_query("SELECT * FROM `cm_board` WHERE `orig_post_id` = '$_POST[post_id]'");
			$comment_no = mysql_num_rows($q);
				
				switch($comment_no)
					{
						
						case 1:
						$resp = "View 1 comment";
						echo $resp;
						break;
						
						case $comment_no>1:
						$resp = "View $comment_no comments";
						echo $resp;
						break;
						
						case 0:
						$resp = "";
						echo $resp;
						break;	
				
					}
			
		}
		
		else
		{
			//display all comments
			
$q = mysql_query("SELECT * FROM `cm_board` WHERE `is_comment` = 'yes' AND `orig_post_id` = '$_POST[post_id]' ORDER BY `created` DESC");
while ($r = mysql_fetch_array($q))
{
echo<<<COMM
<div class="comment" id="a_comment_$r[id]" style=""><div style="float:left;margin-top:5px;margin-right:5px;">
COMM;
get_photo($r[created_by]);
echo<<<COMM
</div><div style="float:right;"><div id="comment_show_$r[id]" style="width:490px;margin:0px;">$r[body] <br><span class='smallgray_nohref'>--
COMM;
$data = new get_names;
$nm = $data->get_users_name($r[created_by]);
echo<<<COMM
$nm</span></div></div>

</div>
COMM;
}

echo <<<hide
<a class = "smallgray" href="#" onClick="$('comment_roll_$_POST[post_id]').setStyle({display:'none'});return false;">Hide Comments</a>
hide;
}

?>
