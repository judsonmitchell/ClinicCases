<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');

$user = $_SESSION['login'];

$get_events = $dbh->prepare("SELECT * from cm_events, cm_events_responsibles WHERE cm_events_responsibles.username = :user AND cm_events_responsibles.event_id = cm_events.id");