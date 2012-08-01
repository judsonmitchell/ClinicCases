<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('../utilities/thumbnails.php');
require('../utilities/names.php');
require('../html/gen_select.php');

$q = $dbh->prepare("SELECT * FROM cm_board");

$q->execute();

$posts = $q->fetchAll(PDO::FETCH_ASSOC);

include '../../../html/templates/interior/board_display.php';


