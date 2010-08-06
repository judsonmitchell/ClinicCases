<?php
session_start();
if (!$_SESSION)
{die('Error: You are not logged in');}
include 'db.php';
include_once 'classes/get_allowed_posters.php';
include_once 'classes/get_names.php';


//Get the poster's name
$poster = new get_names;
$poster_name = $poster->get_users_name($_SESSION[login]);


if ($_POST[is_comment]):
	
		
		$q = mysql_query("INSERT INTO `cm_board` (
`id` ,
`title` ,
`body` ,
`created_by` ,
`last_modified_by` ,
`created` ,
`last_modified` ,
`attachment` ,
`locked` ,
`hidden` ,
`is_comment` ,
`orig_post_id`
)
VALUES (
NULL , '', '$_POST[pbody]', '$_SESSION[login]', '', '', '', '', '', '', 'yes', '$_POST[orig_post_id]'
);

	");
		



elseif ($_POST[edit]):

	
	$update = mysql_query("UPDATE `cm_board` SET `title` = '$_POST[title]',
`body` = '$_POST[pbody]',
`last_modified` = NOW( ) ,
`last_modified_by` = '$_SESSION[login]',
`locked` = '$_POST[locked]', `is_form` = '$_POST[isform]'
WHERE `id` = '$_POST[post_id]' LIMIT 1 ;");

	
	

else:


$update = mysql_query("UPDATE `cm_board` SET `title` = '$_POST[title]',
`body` = '$_POST[pbody]',
`created_by` = '$_SESSION[login]',
`created` = NOW( ) ,
`last_modified` = NOW( ) ,
`locked` = '$_POST[locked]', `is_form` = '$_POST[isform]'
WHERE `id` = '$_POST[post_id]' LIMIT 1 ;");


		

endif;

if($_POST[is_comment])
{$p_id = $_POST[orig_post_id];}
else
{$p_id = $_POST[post_id];}

$get_title = mysql_query("SELECT `id`,`title` FROM `cm_board` WHERE `id` = '$p_id' LIMIT 1");
$t = mysql_fetch_object($get_title);
$title = $t->title;


//Notify of changes
if ($_POST[is_comment]):

		$notify_group = get_allowed_posters($_SESSION[login]);
		$notify_group_clean = str_replace("'","",$notify_group);//strip the single-quotes from the returned string
		echo $notify_group_clean;
		$body_mod = "New Comment added to post \"$title\" from $poster_name.  <a href=\"cm_board.php\">Go to Board</a>";
	
		$send_msg = mysql_query("INSERT INTO `cm_messages` (`id`,`thread_id`,`to`,`from`,`ccs`,`subject`,`body`,`assoc_case`) VALUES (NULL,'','$notify_group_clean','system','','New Comment added to post $title from $poster_name','$body_mod','')");
		  
		$lst_id = mysql_insert_id();
		$set_thread = mysql_query("UPDATE `cm_messages` SET `thread_id` = '$lst_id' WHERE `id` = '$lst_id' LIMIT 1");


elseif ($_POST[edit]):

		$notify_group = get_allowed_posters($_SESSION[login]);
		$notify_group_clean = str_replace("'","",$notify_group);//strip the single-quotes from the returned string
		echo $notify_group_clean;
		$body_mod = "Post \"$title\" edited by $poster_name.  <a href=\"cm_board.php\">Go to Board</a>";
	
		$send_msg = mysql_query("INSERT INTO `cm_messages` (`id`,`thread_id`,`to`,`from`,`ccs`,`subject`,`body`,`assoc_case`) VALUES (NULL,'','$notify_group_clean','system','','Post \"$title\" edited by $poster_name','$body_mod','')");
		  
		$lst_id = mysql_insert_id();
		$set_thread = mysql_query("UPDATE `cm_messages` SET `thread_id` = '$lst_id' WHERE `id` = '$lst_id' LIMIT 1");




else:
$notify_group = get_allowed_posters($_SESSION[login]);
		$notify_group_clean = str_replace("'","",$notify_group);//strip the single-quotes from the returned string
		echo $notify_group_clean;
		$body_mod = "New Post regarding \"$_POST[title]\" from $poster_name.  <a href=\"cm_board.php\">Go to Board</a>";
	
		$send_msg = mysql_query("INSERT INTO `cm_messages` (`id`,`thread_id`,`to`,`from`,`ccs`,`subject`,`body`,`assoc_case`) VALUES (NULL,'','$notify_group_clean','system','','New Board Post from $poster_name','$body_mod','')");
		
		
		  
		$lst_id = mysql_insert_id();
		$set_thread = mysql_query("UPDATE `cm_messages` SET `thread_id` = '$lst_id' WHERE `id` = '$lst_id' LIMIT 1");
		endif;
?>
