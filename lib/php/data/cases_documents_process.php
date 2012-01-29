<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');

//Create New Folder
$username = $_SESSION['login'];
$action = $_POST['action'];

if (isset($_POST['case_id'])) {
	$case_id = $_POST['case_id'];
}

if (isset($_POST['container'])){
	$container = $_POST['container'];}

if (isset($_POST['new_folder'])) {
	$new_folder = $_POST['new_folder'];
}

if ($action == 'newfolder')
{

	$new_folder_query = $dbh->prepare("INSERT INTO cm_documents (`id`, `name`, `url`, `folder`, `containing_folder`, `username`, `case_id`, `date_modified`) VALUES (NULL, '', '', :new_folder, :container, '$username', :case_id, CURRENT_TIMESTAMP);");

	$new_folder_query->bindParam(':container',$container);

	$new_folder_query->bindParam(':new_folder',$new_folder);

	$new_folder_query->bindParam(':case_id',$case_id);

	$new_folder_query->execute();

	$error = $new_folder_query->errorInfo();

}

if ($action == 'rename')
{}

if ($action == 'delete')
{}

//Handle mysql errors

	if($error[1])

		{
			$return = array('message'=>'Sorry,there was an error.');
			echo json_encode($return);
		}

		else
		{
			
			switch($_POST['action']){
			case "newfolder":
			$new_id = $dbh->lastInsertId();
			$return = array('message'=>'Folder Created','id'=>$new_id);
			echo json_encode($return);
			break;
			
			case "rename":
			echo "Folder Renamed.";
			break;
			
			case "delete":
			echo "Folder Deleted.";
			break;	
				
			}		
			
		}