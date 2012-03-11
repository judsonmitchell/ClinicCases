<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/states.php');

function contact_types ($dbh)
{
	$get_types = $dbh->prepare("SELECT type from cm_contacts_types ORDER BY type ASC");

	$get_types->execute();

	$types = $get_types->fetchAll(PDO::FETCH_ASSOC);

	$type_options = null;

	foreach ($types as $type) {

		$type_options .= "<option value = '$type[type]'>$type[type]</option>";
	}

	return $type_options;
}

$case_id = $_POST['case_id'];

//Get all contacts associated with the case

$contacts_query = $dbh->prepare("SELECT * FROM cm_contacts where assoc_case = :case_id ORDER BY id desc");

$data = array('case_id' => $case_id);

$contacts_query->execute($data);

$contacts = $contacts_query->fetchAll(PDO::FETCH_ASSOC);

include('../../../html/templates/interior/cases_contacts.php');
