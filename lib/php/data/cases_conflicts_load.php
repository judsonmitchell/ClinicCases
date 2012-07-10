<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/names.php');
require('../utilities/convert_times.php');


$user = $_SESSION['login'];

$id = $_POST['case_id'];


include('../../../html/templates/interior/cases_events.php');
