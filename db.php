<?php
include '_CONFIG.php';

try {
		$dbh = new PDO("mysql:host=" . CC_DBHOST . ";dbname=" . CC_DATABASE_NAME , CC_DBUSERNAME, CC_DBPASSWD);
		
		 // MS SQL Server and Sybase with PDO_DBLIB
			//$dbh = new PDO("mssql:host=" . CC_DBHOST . ";dbname=" . CC_DATABASE_NAME, CC_DBUSERNAME, CC_DBPASSWD");
			//$dbh = new PDO("sybase:host=" . CC_DBHOST" . ";dbname=" . CC_DATABASE_NAME, CC_DBUSERNAME, CC_DBPASSWD");
			
		// SQLite Database  
			//$dbh = new PDO("sqlite:" . CC_SQLITE_PATH);  		
		
    }
catch(PDOException $e)
    {
		echo $e->getMessage();
    }
