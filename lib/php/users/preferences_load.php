<?php
session_start();
require('../auth/session_check.php');
include '../../../db.php';
include '../utilities/thumbnails.php';

$q = $dbh->prepare("SELECT * from cm_users WHERE username = ?");

$q->bindParam(1, $_SESSION['login']);

$q->execute();

$user = $q->fetch(PDO::FETCH_ASSOC);

include '../../../html/templates/Prefs.php';
