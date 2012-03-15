<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/states.php');

function array_unique_deep($array) {

    $values=array();

    foreach ($array as $part) {
        if (is_array($part)) $values=array_merge($values,array_unique_deep($part));
        else $values[]=$part;
    }

    return array_unique($values);
}


function contact_types ($dbh,$case_id)
{
	// get default contact types for this ClinicCases installation

	$get_default_types = $dbh->prepare("SELECT type from cm_contacts_types ORDER BY type ASC");

	$get_default_types->execute();

	$default_types = $get_default_types->fetchAll(PDO::FETCH_ASSOC);

	// foreach ($types as $type) {

	// 	$type_options .= "<option value = '$type[type]'>$type[type]</option>";
	// }

	// add any user-defined contact types

	$get_db_types = $dbh->prepare("SELECT DISTINCT type from  `cm_contacts` WHERE assoc_case = :case_id");

	$data = array('case_id' => $case_id);

	$get_db_types->execute($data);

	$db_types = $get_db_types->fetchAll(PDO::FETCH_ASSOC);

	//$custom_types = array_diff_assoc($default_types, $db_types);

	$all_types = array_unique_deep(array_merge($db_types,$default_types));

	$type_options = null;

	foreach ($all_types as $type) {
		$type_options .= "<option value = '$type'>$type</option>";
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
