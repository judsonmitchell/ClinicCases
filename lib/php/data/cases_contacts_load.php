<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/states.php');
require('../html/gen_select.php');

function array_unique_deep($array) {

    $values=array();

    foreach ($array as $part) {
        if (is_array($part)) $values=array_merge($values,array_unique_deep($part));
        else $values[]=$part;
    }

    return array_unique($values);
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
