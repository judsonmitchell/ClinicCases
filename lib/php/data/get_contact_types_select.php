<?php
require('../../../db.php');
require('../html/gen_select.php');
$_POST = json_decode(file_get_contents("php://input"), true);
$case_id = $_POST['case_id'];

echo gen_contact_types($dbh, $case_id);
