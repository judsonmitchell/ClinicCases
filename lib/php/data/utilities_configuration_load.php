<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');

//Get courts
$q = $dbh->prepare("SELECT * FROM cm_courts ORDER BY court ASC");

$q->execute();

$courts = $q->fetchAll(PDO::FETCH_ASSOC);

//Get case types
$q = $dbh->prepare("SELECT * FROM cm_case_types ORDER BY type ASC");

$q->execute();

$case_types= $q->fetchAll(PDO::FETCH_ASSOC);

//Get clinic types
$q = $dbh->prepare("SELECT * FROM cm_clinic_type ORDER BY clinic_name ASC");

$q->execute();

$clinic_types = $q->fetchAll(PDO::FETCH_ASSOC);

//Get dispositions
$q = $dbh->prepare("SELECT * FROM cm_dispos ORDER BY dispo ASC");

$q->execute();

$dispos = $q->fetchAll(PDO::FETCH_ASSOC);

//Get referral sources
$q = $dbh->prepare("SELECT * FROM cm_referral");

$q->execute();

$referral = $q->fetchAll(PDO::FETCH_ASSOC);

include '../../../html/templates/interior/utilities_configuration.php';

