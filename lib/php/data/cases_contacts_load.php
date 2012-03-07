<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');

$case_id = $_POST['case_id'];

$contacts_query = $dbh->prepare("SELECT * FROM cm_contacts where assoc_case = :case_id ORDER BY last_name asc");

$data = array('case_id' => $case_id);

$contacts_query->execute($data);

$contacts = $contacts_query->fetchAll(PDO::FETCH_ASSOC);

include('../../../html/templates/interior/cases_contacts.php');
