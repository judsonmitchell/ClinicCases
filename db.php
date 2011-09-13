<?php
include '_CONFIG.php';

try {
		$dbh = new PDO("mysql:host=$CC_dbhost;dbname=$CC_database_name", $CC_dbusername, $CC_dbpasswd);
    }
catch(PDOException $e)
    {
		echo $e->getMessage();
    }
