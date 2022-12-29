<?php
session_start();
// require('../auth/session_check.php');
// require('../../../db.php');
$_POST = json_decode(file_get_contents("php://input"), true);

$case_id = $_POST['case_id'];

$user_list = all_active_users_and_groups($dbh, $case_id, $_SESSION['login']);

include '../../../html/templates/interior/cases_detail_user_chooser.php';
