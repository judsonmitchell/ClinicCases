<?php

//This script is to update the clinic_id field in the cm table when upgrading to cc7.
//The clinic_id field is a string that functions as a case number for the user.
//In cc6, this was by default just a number that was auto-incremented; the year was
//added to this number only when displayed to the user.

//Now, the field should have at least a year and a case number value;  The case
//number can be customized -- see _CONFIG.php -- to have a 2 or 4 year date, and
//to include either a case code or a clinic code.  So, instead of appearing like
// '126' in the db, the clinic_id can look something like '2012-00126-FAM' (
// i.e., a family law case opened in 2012 bearing the case number 00126).

//Do not run this script if you have in any way customized your case numbering
//in cc6.  If you have questions, contact Judson Mitchell at jmitchel@loyno.edu.

//As always with these upgrade scripts, please create a backup of your db
//before running.

require('../db.php');

//First, check to see if the date_open field is empty in any row.  If it is,
//the field will need to be manually fixed before proceeding.

echo "Beginning case number upgrade.\n";

$q = $dbh->prepare("SELECT * FROM cm WHERE date_open = ''");

$q->execute();

$count = $q->rowCount();

if ($count > 0)

	{die('You have ' . $count . ' case records which have an empty date_open field.  This needs to be fixed before you proceed.  Please go to the `cm` table in your db and enter a date value for these fields.');}

//Next, change adverse parties table to show case_ids, not clinic_ids
$q = $dbh->prepare("SELECT id,clinic_id FROM cm_adverse_parties");

$q->execute();

$numbers = $q->fetchAll(PDO::FETCH_ASSOC);

foreach ($numbers as $n) {
	//get case id
	$q2 = $dbh->prepare("SELECT id FROM cm WHERE clinic_id = ?");

	$q2->bindParam(1, $n['clinic_id']);

	$q2->execute();

	$vals = $q2->fetch(PDO::FETCH_ASSOC);

	//replace clinic_id with case_id
	$q3 = $dbh->prepare("UPDATE cm_adverse_parties SET clinic_id = ? WHERE id = ?");

	$q3->bindParam(1, $vals['id']);

	$q3->bindParam(2, $n['id']);

	$q->execute();

}

//change field title for clarity
$q = $dbh->prepare("ALTER TABLE `cm_adverse_parties` CHANGE `clinic_id` `case_id` VARCHAR(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT ''");

$q->execute();

//Proceed with upgrade
$q = $dbh->prepare("SELECT * FROM cm");

$q->execute();

$rows = $q->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $row) {
		$year = substr(trim($row['date_open']), 0,4);
		$m = CC_CASE_NUMBER_MASK;

		if (stristr($m,'YYYY'))
			{
				$year_val = $year;
				$result = str_replace('YYYY', $year_val, $m);
			}
		else
			{
				$year_val = substr($year, 2,4);
				$result = str_replace('YY', $year_val, $m);
			}

		//Create new case number, ensure the number is five digits
		$result = str_replace('Number', str_pad($row['clinic_id'], 5,'0', STR_PAD_LEFT), $result);

		//Strip out the CaseType, ClinicType mask, if used.  This will
		//have to be empty for old cases, sorry.
		$new_case_number = preg_replace("/[^0-9-]/", "",$result);

		$id = $row['id'];

		$update = $dbh->prepare("UPDATE cm SET clinic_id = '$new_case_number' WHERE id = '$id'");

		$update->execute();

		$error = $update->errorInfo();


}

	if ($error[1])
			{echo "Sorry, there was an error: " . $error[1];}
		else
			{echo "Case numbers successfully upgraded.";}
