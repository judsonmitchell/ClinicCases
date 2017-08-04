<?php
require_once('_CONFIG.php');

try {
		$dbh = new PDO("mysql:host=" . CC_DBHOST . ";dbname=" . CC_DATABASE_NAME . ";charset=utf8mb4" , CC_DBUSERNAME, CC_DBPASSWD);
    }
catch(PDOException $e)
    {

		//400 is sent to trigger an error for ajax requests.
		header('HTTP/1.1 400 Bad Request');

		echo $e->getMessage();
    }
