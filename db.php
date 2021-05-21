<?php
require_once('_CONFIG.php');
try {
    $dbh = new PDO("mysql:host=" . CC_DBHOST . ";dbname=" . CC_DATABASE_NAME , CC_DBUSERNAME, CC_DBPASSWD, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET sql_mode="IGNORE_SPACE,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"') );
 }
catch(PDOException $e) {
    //400 is sent to trigger an error for ajax requests.
    header('HTTP/1.1 400 Bad Request');
    echo $e->getMessage();
 }
