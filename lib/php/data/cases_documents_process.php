<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');

function update_paths($dbh,$path,$new_path,$case_id)
{
	//Change paths of documents which reside in the recently changed folder
	$find_old_paths = $dbh->prepare('SELECT * FROM cm_documents WHERE folder = :old_path AND case_id = :case_id');

	$find_old_paths->bindParam(':old_path',$path);

	$find_old_paths->bindParam(':case_id',$case_id);

	$find_old_paths->execute();

	$paths = $find_old_paths->fetchAll(PDO::FETCH_ASSOC);

	foreach ($paths as $path_item) {

		$update_folders = $dbh->prepare("UPDATE cm_documents SET folder = '$new_path' WHERE id = '$path_item[id]'");

		$update_folders->execute();
	}

	//change the containing folder for every folder which resides in the recently changed folder

	$find_old_containers = $dbh->prepare("SELECT * FROM cm_documents WHERE containing_folder LIKE :container AND case_id = $case_id");

	$find_old_containers->bindParam(':container',$path);

	$find_old_containers->execute();

	$containers = $find_old_containers->fetchAll(PDO::FETCH_ASSOC);

	foreach ($containers as $container_item) {

		$pp = str_replace($path, '', $container_item['folder']);

		$new_subfolder_path = $new_path . $pp;

		$update_containers = $dbh->prepare("UPDATE cm_documents SET containing_folder = '$new_path', folder = '$new_subfolder_path' WHERE id = '$container_item[id]'");

		$update_containers->execute();
	}

	//Now, find all documents and subfolders that are further down the tree
	//1. Update the folder field
	$find_folder_fields = $dbh->prepare("SELECT * FROM cm_documents WHERE folder LIKE '$path%' AND case_id = $case_id");

	$find_folder_fields->execute();

	$folder_fields = $find_folder_fields->fetchAll(PDO::FETCH_ASSOC);

	foreach ($folder_fields as $folder_field) {
		//if the new path is not already in the folder field as a result of previous queries
		if (!stristr($folder_field['folder'],$new_path))
		{
			$descendants = str_replace($folder_field['folder'], '', $path);
			$new_folder_field = $new_path . $descendants;
			$update_folder_fields = $dbh->prepare("UPDATE cm_documents SET folder = '$new_folder_field' WHERE id = '$folder_field[id]'");
		}
	}

	//2.update the container field

	$find_container_fields = $dbh->prepare("SELECT * FROM cm_documents WHERE container LIKE '$path%' AND case_id = $case_id");

	$find_container_fields->execute();

	$container_fields = $find_container_fields->fetchAll(PDO::FETCH_ASSOC);

	foreach ($container_fields as $container_field) {
		//if the new path is not already in the container field as a result of previous queries
		if (!stristr($container_field['container'],$new_path))
		{
			$descendants = str_replace($folder_field['container'], '', $path);
			$new_container_field = $new_path . $descendants;
			$update_container_fields = $dbh->prepare("UPDATE cm_documents SET container = '$new_container_field' WHERE id = '$folder_container[id]'");
		}
	}

}

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

if (isset($_POST['new_name'])) {
	$new_name = $_POST['new_name'];
}

if (isset($_POST['item_id'])) {
	$item_id = $_POST['item_id'];
}

if (isset($_POST['doc_type'])) {
	$doc_type = $_POST['doc_type'];
}

if (isset($_POST['path'])) {
	$path = $_POST['path'];
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
{
	if ($doc_type === 'folder')
		{
			$sql = "UPDATE cm_documents SET folder = :new_path WHERE id = :item_id";

			if (strripos($path,'/'))
			{
				$last_slash = strripos($path,'/');
				$d = substr($path, 0,$last_slash);
				$new_path = $d . "/"  . $new_name;
			}
			else
			{
				$new_path = $new_name;
			}

			$rename_query = $dbh->prepare($sql);

			$rename_query->bindParam(':item_id',$item_id);

			$rename_query->bindParam(':new_path',$new_path);

			//TODO must change the paths of every file and folder contained in the changed folder

		}
		else
		{
			$sql = "UPDATE cm_documents SET name = :new_name WHERE id = :item_id";

			$rename_query->bindParam(':new_name',$new_name);

			$rename_query->bindParam(':item_id',$item_id);

			//TODO change the name of the underlying file on the server.
		}



	$rename_query->execute();

	$error = $rename_query->errorInfo();

	if ($doc_type === 'folder'  and !$error[1])
	{
		update_paths($dbh,$path,$new_path,$case_id);//$path is the old path, $new_path is the new path
	}

}

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