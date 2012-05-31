<?php //scripts for case data tab in case detail
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/convert_times.php');

if (isset($_POST['id'])) {
	$case_id = $_POST['id'];
}

if (isset($_POST['type'])) {
	$type = $_POST['type'];
}

//Get case data

$q = $dbh->prepare("SELECT * FROM cm WHERE id = '$case_id'");

$q->execute();

$case_data = $q->fetch(PDO::FETCH_ASSOC);

//Get columns config
$q = $dbh->prepare("SELECT * from cm_columns ORDER BY display_order ASC");

$q->execute();

$columns = $q->fetchAll(PDO::FETCH_ASSOC);

$data = null;

foreach ($columns as $col) {
	//push the value of the field in case_data onto $columns
	$field =  $col['db_name'];
	$field_value = $case_data[$field];
	$col['value'] = $field_value;
	$data[] = $col;
}

include '../../../html/templates/interior/cases_case_data.php';