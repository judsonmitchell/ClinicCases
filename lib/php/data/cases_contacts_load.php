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

//Get variables

$case_id = $_POST['case_id'];

if (isset($_POST['q']))
	{$q = $_POST['q'];}

if (isset($q))

	{
		$sql = "SELECT * from cm_contacts WHERE assoc_case = :case_id and (first_name LIKE :q OR last_name LIKE :q OR organization LIKE :q OR type LIKE :q OR address LIKE :q OR city LIKE :q OR zip LIKE :q OR phone LIKE :q OR email LIKE :q OR url LIKE :q OR notes LIKE :q)";
	}

	else

		{$sql = "SELECT * FROM cm_contacts where assoc_case = :case_id ORDER BY id desc";}

//Get all contacts associated with the case

$contacts_query = $dbh->prepare($sql);

if (isset($q))
	{
		$search_term = '%' . $q . '%';

		$data = array('case_id' => $case_id, 'q' => $search_term);

	}

	else

	{

		$data = array('case_id' => $case_id);

	}


$contacts_query->execute($data);

$contacts = $contacts_query->fetchAll(PDO::FETCH_ASSOC);

include('../../../html/templates/interior/cases_contacts.php');
