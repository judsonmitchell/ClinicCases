<?php
require('db.php');

// echo "Beginning upgrade process</br>";

// //This fixes incorrect date entries from cc6.  Date field on case notes had 00:00:00 in the
// // time part which led to incorrect sorts when displaying case notes.  This was fixed in r663
// // of add_time.php, but was not corrected in casenote_edit.php. There are therefore, a lot of
// //incorrect entries in some dbs.
// echo "Correcting date entries on case notes...<br>";

// $query = $dbh->prepare("SELECT id, date, datestamp from cm_case_notes ORDER BY datestamp desc");
// $query->execute();
// $notes = $query->fetchAll(PDO::FETCH_ASSOC);

// foreach ($notes as $note) {
// 	$id = $note['id'];
// 	$date_parts = explode(' ',$note['date']);
// 	$datestamp_parts = explode(' ',$note['datestamp']);

// 	if ($date_parts[1] = '00:00:00')
// 	{
// 		$new_date = $date_parts[0] . " " . $datestamp_parts[1];
// 		$update = $dbh->prepare("UPDATE cm_case_notes set date = :new_date WHERE id = :id LIMIT 1 ");
// 		$data = array(':new_date'=>$new_date,':id'=>$id);
// 		$update->execute($data);
// 	}

// }

// $error = $query->errorInfo();

// if ($error[1])
// 	{echo $error[1];}
// else
// 	{echo "Case note date entries corrected.<br>";}

// //
// //Documents db has to be updated
// //

// echo "Updating documents db...<br />";

// $query = $dbh->prepare("ALTER TABLE  `cm_documents` ADD  `containing_folder` VARCHAR( 100 ) NOT NULL AFTER  `folder`;
// 	ALTER TABLE  `cm_documents` ADD  `extension` VARCHAR( 10 ) NOT NULL AFTER  `url`;
// 	ALTER TABLE  `cm_documents` CHANGE  `url`  `local_file_name` VARCHAR( 200 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  '';
// 	ALTER TABLE  `cm_documents` ADD  `text` TEXT NOT NULL AFTER  `containing_folder`;
// 	ALTER TABLE  `cm_documents` ADD  `write_permission` VARCHAR( 500 ) NOT NULL AFTER  `text`
// ");

// $query->execute();


// //get the document extension and put it in extension column

// $query = $dbh->prepare("SELECT * from cm_documents");
// $query->execute();
// $documents = $query->fetchAll(PDO::FETCH_ASSOC);

// foreach ($documents as $document) {

// if (stristr($document['local_file_name'], 'http://') || stristr($document['local_file_name'], 'https://') || stristr($document['local_file_name'], 'ftp://'))
// 		{$ext = 'url';}
// 		else
// 		{$ext = strtolower(substr(strrchr($document['local_file_name'], "."), 1));}

// 		$id = $document['id'];

// 		if ($ext != '')
// 		{
// 		$update = $dbh->prepare("UPDATE cm_documents SET extension = :ext WHERE id = :id");
// 		$data = array(':ext'=>$ext,':id'=>$id);
// 		$update->execute($data);
// 		}
// }

// //rename all files in docs directory to the id + extension; update local_file_name to the new name;then move them to new CC_DOC_PATH

// 	// TODO

// $error = $query->errorInfo();

// if ($error[1])
// 	{echo $error[1];}
// else
// 	{echo "Documents have been updated.<br>";}


// //Update contacts db
// echo "Updating contacts db...<br />";

// 	//Update fields
// $query = $dbh->prepare("ALTER TABLE  `cm_contacts` ADD  `organization` VARCHAR( 200 ) NOT NULL AFTER  `last_name`;ALTER TABLE  `cm_contacts` ADD  `url` TEXT NOT NULL AFTER  `email`;ALTER TABLE  `cm_contacts` CHANGE  `address`  `address` TEXT CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT  '';ALTER TABLE  `cm_contacts` CHANGE  `phone1`  `phone1` TEXT NOT NULL DEFAULT  '', CHANGE  `email`  `email` TEXT NOT NULL DEFAULT  '';");

// $query->execute();

// 	//Change phone and email fields
// $query = $dbh->prepare('SELECT id,phone1, phone2, fax FROM cm_contacts ORDER BY id asc');

// $query->execute();

// $phones = $query->fetchAll(PDO::FETCH_ASSOC);

// foreach ($phones as $phone) {

// 	//Make a guess at what kind of phone this is

// 	if (stristr($phone['phone1'], 'cell')  || stristr($phone['phone1'], 'mobile')|| stristr($phone['phone1'], 'c'))
// 		{
// 			$phone1_type = 'mobile';
// 		}
// 		elseif (stristr($phone['phone1'], 'home')  || stristr($phone['phone1'], 'h'))
// 		{
// 			$phone1_type = 'home';
// 		}
// 		elseif (stristr($phone['phone1'], 'work')  || stristr($phone['phone1'], 'office') || stristr($phone['phone1'], 'w') || stristr($phone['phone1'], 'o'))
// 		{
// 			$phone1_type = 'office';
// 		}
// 		else
// 			{$phone1_type = 'other';}

// 	if (stristr($phone['phone2'], 'cell')  || stristr($phone['phone2'], 'mobile') || stristr($phone['phone2'], 'c'))
// 		{
// 			$phone2_type = 'mobile';
// 		}
// 		elseif (stristr($phone['phone2'], 'home')|| stristr($phone['phone2'], 'h'))
// 		{
// 			$phone2_type = 'home';
// 		}
// 		elseif (stristr($phone['phone2'], 'work')  || stristr($phone['phone2'], 'office')|| stristr($phone['phone2'], 'w') || stristr($phone['phone2'], 'o'))
// 		{
// 			$phone2_type = 'office';
// 		}
// 		else
// 			{$phone2_type = 'other ';}


// 	$new_phone = array($phone1_type => $phone['phone1'], $phone2_type => $phone['phone2'], 'fax' => $phone['fax']);

// 	$new_phone_filtered = array_filter($new_phone);//take out empty phone fields

// 	$json = json_encode($new_phone_filtered);

// 	if ($json != '[]')//empty set
// 	{

// 	$update = $dbh->prepare("UPDATE cm_contacts SET phone1 = :phone, phone2 = '', fax = '' WHERE id = :id");

// 	$data = array('phone'=>$json,'id'=>$phone['id']);

// 	$update->execute($data);

// 	}

// }

// //update email field
// $query = $dbh->prepare('SELECT id,email FROM cm_contacts');

// $query->execute();

// $emails = $query->fetchAll(PDO::FETCH_ASSOC);

// foreach ($emails as $email) {

// 	if ($email['email'])
// 	{

// 		$new_email = array('other' => $email['email']);

// 		$json = json_encode($new_email);

// 		//echo $json . "<br />";

// 		$update = $dbh->prepare("UPDATE cm_contacts SET email = :email WHERE id = :id");

// 		$data = array('email' => $json, 'id' => $email['id']);

// 		$update->execute($data);
// 	}

// }

// $query = $dbh->prepare("ALTER TABLE `cm_contacts` DROP `phone2`, DROP `fax`;");

// $query->execute();

// $query = $dbh->prepare("ALTER TABLE  `cm_contacts` CHANGE  `phone1`  `phone` TEXT NOT NULL DEFAULT  ''");

// $query->execute();

// echo "Finished updating contacts.<br />";

echo "Adding timestamps to enable activities feed. <br />";

$query = $dbh->prepare("ALTER TABLE  `cm` ADD  `time_added` DATETIME NOT NULL AFTER  `opened_by`
");

$query->execute();

$query = $dbh->prepare("ALTER TABLE  `cm_events` ADD  `time_added` DATETIME NOT NULL");

$query->execute();

$query = $dbh->prepare("ALTER TABLE  `cm_journals` ADD  `time_added` DATETIME NOT NULL AFTER  `comments`
");

$query->execute();




