<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');

function update_paths($dbh,$old_path,$new_folder_path,$case_id)
{
	//echo $old_path . " and " . $new_folder_path . " and " . $case_id;die;
	//Change paths of documents which reside in the recently changed folder
	$find_old_paths = $dbh->prepare('SELECT * FROM cm_documents WHERE folder = :old_path AND case_id = :case_id');

	$find_old_paths->bindParam(':old_path',$old_path);

	$find_old_paths->bindParam(':case_id',$case_id);

	$find_old_paths->execute();

	$paths = $find_old_paths->fetchAll(PDO::FETCH_ASSOC);
	//print_r($paths);die;
	foreach ($paths as $path) {

		$update_folders = $dbh->prepare("UPDATE cm_documents SET folder = '$new_folder_path' WHERE id = '$path[id]'");

		//$update_folders->execute();
	}

	//change the containing folder for every folder which resides in the recently changed folder and their descendants

	$find_old_containers = $dbh->prepare("SELECT * FROM cm_documents WHERE containing_folder LIKE :contain AND case_id = $case_id");

		if (strripos($old_path, '/'))
		{
			$index = strripos($old_path, '/');
			$contain = substr($old_path, 0,$index) . "/%";
		}
		else
		{
			$contain = $old_path . "/%";
		}

	$find_old_containers->bindParam(':contain',$contain);

	$find_old_containers->execute();

	$containers = $find_old_containers->fetchAll(PDO::FETCH_ASSOC);
//print_r($containers);
	foreach ($containers as $container) {

		$part = str_replace($container['containing_folder'],'',$old_path);

		$new_container = $new_folder_path . $part;

		$pp = str_replace($old_path, '', $container['folder']);

		$new_folder_field = $new_folder_path . $pp;

		//echo $new_container;die;

echo "part = $part" . "\n " .
	 "container[containing_folder] = " . $container['containing_folder'] . "\n" .
	 "old_path = " . $old_path . "\n" .
	 "new_folder_path = " . $new_folder_path . "\n" .
	 "new_container =  " . $new_container  . "\n\n";

//echo "field containing_folder will equal " . $new_container . "\n field folder will equal " . $new_folder_field;die;

		$test_array[] = $new_container . ",". $new_folder_field . "," . $container['id'];
		$update_containers = $dbh->prepare("UPDATE cm_documents SET containing_folder = '$new_container', folder = '$new_folder_field' WHERE id = '$container[id]'");

		//$update_containers->execute();
	}
print_r($test_array);

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
			$sql = "UPDATE cm_documents SET folder = :new_folder_name WHERE id = :item_id";

			if (strripos($container,'/'))
			{
				$last_slash = strripos($container,'/');
				$d = substr($container, 0,$last_slash);
				$new_folder_name = $d . "/"  . $new_name;
			}
			else
			{
				$new_folder_name = $new_name;
			}

			//$new_path = $container . "/" . $new_name;

			$rename_query = $dbh->prepare($sql);

			$rename_query->bindParam(':item_id',$item_id);

			$rename_query->bindParam(':new_folder_name',$new_folder_name);

			//TODO must change the paths of every file and folder contained in the changed folder

		}
		else
		{
			$sql = "UPDATE cm_documents SET name = :new_name WHERE id = :item_id";

			$rename_query->bindParam(':new_name',$new_name);

			$rename_query->bindParam(':item_id',$item_id);

			//TODO change the name of the underlying file on the server.
		}



	//$rename_query->execute();

	$error = $rename_query->errorInfo();

	if ($doc_type === 'folder'  and !$error[1])
	{
		update_paths($dbh,$container,$new_folder_name,$case_id);//$container is the old path, $new_folder_name is the new path
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