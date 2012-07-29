<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');

$q = $dbh->prepare("SELECT * FROM cm_board_7");

$q->execute();

$posts = $q->fetchAll(PDO::FETCH_ASSOC);

include '../../../html/templates/interior/board_display.php';


