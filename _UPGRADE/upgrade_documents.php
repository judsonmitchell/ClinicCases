<?php
//Upgrade documents from ClinicCases 6 to 7.  Files are moved out
//of the webroot and the renamed to relevant db id number

require('../db.php');

ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);
ini_set('memory_limit', '-1');

//Specify the full path to your old ClinicCases doc directory
$path_to_old_docs = '/var/www/cliniccases/docs';

//Specify path to your old installation of ClinicCases
$path_to_old_cc = '/var/www/cliniccases';

//Check to see if the new doc folder has been created
if (file_exists(CC_DOC_PATH))
{
	echo "Good, your new docs folder exists.\n";
}
else
{
    die("Your new docs folder as defined in the config file(CC_DOC_PATH) doesn't
       exist.  Please create it.\n");
}


//Check to see if the new doc folder is writable
if (is_writable(CC_DOC_PATH))

	{echo "Good, Your new docs folder is writable.  Let's proceed.\n";}

	else

    {die( "Your new docs folder as defined in the config file (CC_DOC_PATH) is not
   writable. Please fix this and try again.\n"); }

//Remove all backslashes from file names in the docs directory

if ($handle = opendir($path_to_old_docs)) {

           /* This is the correct way to loop over the directory. */
           while (false !== ($entry = readdir($handle))) {
                        //echo "$entry\n";
                        $old_file_name = $path_to_old_docs . '/' . $entry;
                        $clean_file_name = $path_to_old_docs . '/' . str_replace("\\","",$entry);
                        rename($old_file_name,$clean_file_name);
           }
}

$query = $dbh->prepare("SELECT * FROM cm_documents WHERE local_file_name != ''
  AND extension != 'url'");

$query->execute();

$count = $query->rowCount();


$error = $query->errorInfo();


echo "We will be moving $count documents from your old docs directory
    to ". CC_DOC_PATH . "\n";

$docs = $query->fetchAll(PDO::FETCH_ASSOC);

$done = 0;

foreach ($docs as $doc)
{
	$doc_id = $doc['id'];

	$new_doc_name = $doc['id'] . '.' . $doc['extension'];

    $old_doc_path = $path_to_old_cc . "/" .  $doc['local_file_name'];

	$new_doc_path = CC_DOC_PATH . '/' .  $new_doc_name;

	rename($old_doc_path,$new_doc_path);

	if (!empty($doc['folder']))
	{
		//remove forward slashes from the folder name
		if (stristr($doc['folder'], '/'))
		{
			$f =str_replace('/', '-', $doc['folder']);

			$escaped_folder = rawurlencode($f);
		}
		else
		{
			$escaped_folder = rawurlencode($doc['folder']);
		}
	}
	else
	{
		$escaped_folder = '';
	}

    $update_db = $dbh->prepare("UPDATE cm_documents
    SET local_file_name = :new_doc,folder = :folder	WHERE id = :id ");

	$data = array('new_doc' => $new_doc_name, 'id' => $doc_id, 'folder' => $escaped_folder);

	$update_db->execute($data);

	$done = $done + 1;

	$completed = $done / $count * 100;

	echo round($completed, 2) . "% completed\n";
}

//Now url encode the folder names
$q = $dbh->prepare("SELECT * FROM `cm_documents` WHERE folder LIKE '% %' OR folder
    LIKE '%/%'");

$q->execute();

$fs = $q->fetchAll(PDO::FETCH_ASSOC);

foreach ($fs as $f) {

    if (stristr($f['folder'], '/'))
		{
			$fix =str_replace('/', '-', $f['folder']);

			$escaped_folder = rawurlencode($fix);
		}
		else
		{
			$escaped_folder = rawurlencode($f['folder']);
		}

	$update = $dbh->prepare("UPDATE cm_documents SET folder = :escaped WHERE id = :id");

	$data = array('escaped' => $escaped_folder,'id' => $f['id']);

	$update->execute($data);

}

echo "Done";

//TODO This still leaves files that have been uploaded via the board;  need to address these
