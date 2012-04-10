<?php
//Upgrade documents from ClinicCases 6 to 7.  Files are moved out 
//of the webroot and the renamed to relevant db id number

require('db.php');

function stripallslashes($string) { 
    	while(strchr($string,'\\')) { 
    		$string = stripslashes($string); 
	} 
return $string;
} 

//Check to see if the new doc folder has been created
if (file_exists(CC_DOC_PATH))
{
	echo "Good, your docs folder exists.\n";
}
else
{
	die("Your docs folder as defined in the config file(CC_DOC_PATH) doesn't exist.  Please create it.\n");
}


//Check to see if the new doc folder is writable
if (is_writable(CC_DOC_PATH))
	
	{echo "Good, Your docs folder is writable.  Let's proceed.\n";}
	
	else

	{die( "Your docs folder as defined in the config file (CC_DOC_PATH) is not writable. Please fix this and try again.\n"); }

$query = $dbh->prepare("SELECT * FROM cm_documents WHERE local_file_name != '' AND extension != 'url'");

$query->execute();

$count = $query->rowCount();

echo "We will be moving $count documents from your old docs directory to ". CC_DOC_PATH . "\n";

$docs = $query->fetchAll(PDO::FETCH_ASSOC);

$done = 0;

foreach ($docs as $doc)
{
	$doc_id = $doc['id'];

	$new_doc_name = $doc['id'] . '.' . $doc['extension'];

	$old_doc_path = stripallslashes($doc['local_file_name']);//still not getting all slashes
	
	$new_doc_path = CC_DOC_PATH . '/' .  $new_doc_name;
	
	//exec("mv " .  escapeshellarg($doc['local_file_name']) . " " . CC_DOC_PATH  . "/" . $new_doc_name );

	rename($old_doc_path,$new_doc_path);

	if (!empty($doc['folder']))
	{$escaped_folder = rawurlencode($doc['folder']);}
	else
	{$escaped_folder = '';}

	$update_db = $dbh->prepare("UPDATE cm_documents SET local_file_name = :new_doc,folder = :folder	WHERE id = :id ");
	
	$data = array('new_doc' => $new_doc_name, 'id' => $doc_id, 'folder' => $escaped_folder);
	
	$update_db->execute($data);
	
	$done = $done + 1;

	$completed = $done / $count * 100;

	echo round($completed, 2) . "% completed\n";
}
