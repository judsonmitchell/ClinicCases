<?php
session_start();
if (!$_SESSION)
{die('Error: You are not logged in');}
include 'db.php';

$id = $_POST[id];
$doc_name = $_POST[doc_name];
$del_type = $_POST[del_type];

if ($_POST[del_type] == 'doc')
	{
		$doc_q = mysql_query("SELECT * FROM `cm_board` WHERE `id` = '$id' LIMIT 1");
		$get_doc_list = mysql_fetch_array($doc_q);
		$doc_list = $get_doc_list[attachment];
		
				$new_doc_list = str_replace("|" . $doc_name,"",$doc_list);
				$update= mysql_query("UPDATE `cm_board` SET `attachment` = '$new_doc_list' WHERE `id` = '$id' LIMIT 1");
				unlink("docs/" . $doc_name);
	
	}


if ($_POST[del_type] == 'cancel')
	{
		
		//here the script to delete the abondoned post and to unlink all attachments
		//First find and unlink attachments
		$q = mysql_query("SELECT `id`,`attachment` FROM `cm_board` WHERE `id` = '$_POST[id]' LIMIT 1");
			$qq = mysql_fetch_object($q);
			if ($qq->attachment)
			{
				
				//strip first pipe
				$att = substr($qq->attachment, 1);
				//make into array
				$att_array = explode('|',$att);
			foreach ($att_array as $file)
				{
					
					unlink("docs/" . $file);
					
				}
			}
			
		$del = mysql_query("DELETE FROM `cm_board` WHERE `id` = '$_POST[id]' LIMIT 1");
		
	}
