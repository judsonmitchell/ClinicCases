<?php
require('db.php');

echo "Beginning upgrade process</br>";
    
//This fixes incorrect date entries from cc6.  Date field on case notes had 00:00:00 in the
// time part which led to incorrect sorts when displaying case notes.  This was fixed in r663
// of add_time.php, but was not corrected in casenote_edit.php. There are therefore, a lot of
//incorrect entries in some dbs.  
echo "Correcting date entries on case notes...<br>";

$query = $dbh->prepare("SELECT id, date, datestamp from cm_case_notes ORDER BY datestamp desc");
$query->execute();
$notes = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($notes as $note) {
	$id = $note['id'];
	$date_parts = explode(' ',$note['date']);
	$datestamp_parts = explode(' ',$note['datestamp']);

	if ($date_parts[1] = '00:00:00')
	{	
		$new_date = $date_parts[0] . " " . $datestamp_parts[1];
		$update = $dbh->prepare("UPDATE cm_case_notes set date = :new_date WHERE id = :id LIMIT 1 ");
		$data = array(':new_date'=>$new_date,':id'=>$id);
		$update->execute($data);	
	}
	
}

$error = $query->errorInfo();

if ($error[1])
	{echo $error[1];}
else
	{echo "Case note date entries corrected.<br>";}

//
//Documents db has to be updated
//

echo "Updating documents db...<br />";

$query = $dbh->prepare("ALTER TABLE  `cm_documents` ADD  `containing_folder` VARCHAR( 100 ) NOT NULL AFTER  `folder`;
	ALTER TABLE  `cm_documents` ADD  `extension` VARCHAR( 10 ) NOT NULL AFTER  `url`;
	ALTER TABLE  `cm_documents` CHANGE  `url`  `local_file_name` VARCHAR( 200 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  '';
	ALTER TABLE  `cm_documents` ADD  `text` TEXT NOT NULL AFTER  `containing_folder`;
	ALTER TABLE  `cm_documents` ADD  `write_permission` VARCHAR( 500 ) NOT NULL AFTER  `text`
");

$query->execute();


//get the document extension and put it in extension column

$query = $dbh->prepare("SELECT id,name from cm_documents");
$query->execute();
$documents = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($documents as $document) {

if (stristr($document['local_file_name'], 'http://') || stristr($document['local_file_name'], 'https://') || stristr($document['local_file_name'], 'ftp://'))
		{$ext = 'url';}
		else
		{$ext = strtolower(substr(strrchr($document['local_file_name'], "."), 1));}
		if ($ext != '')
		{
		$update = $dbh->prepare("UPDATE cm_documents SET extension = :ext WHERE id = :id");
		$data = array(':ext'=>$ext,':id'=>$id);
		$update->execute($data);
		}
}

//rename all files in docs directory to the id + extension; update local_file_name to the new name;then move them to new CC_DOC_PATH

