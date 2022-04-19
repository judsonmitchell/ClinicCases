<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');

//Get clinic types
$q = $dbh->prepare("SELECT * FROM cm_clinic_type ORDER BY clinic_name ASC");

$q->execute();

$clinic_types = $q->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($clinic_types);