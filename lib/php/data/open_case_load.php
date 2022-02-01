<?php
@session_start();
require_once dirname(__FILE__) . '/../../../db.php';
$_POST = json_decode(file_get_contents("php://input"), true);

$id = $_POST['id'];

include '../../../html/templates/interior/open_case.php';
?>
