<?php
include '_CONFIG.php';

try {
		$dbh = new PDO("mysql:host=$CC_dbhost;dbname=$CC_database_name", $CC_dbusername, $CC_dbpasswd);
		
		 // MS SQL Server and Sybase with PDO_DBLIB
			//$dbh = new PDO("mssql:host=$CC_dbhost;dbname=$CC_database_name, $CC_dbusername, $CC_dbpasswd");
			//$dbh = new PDO("sybase:host=$CC_dbhost;dbname=$CC_database_name, $CC_dbusername, $CC_dbpasswd");
			
		// SQLite Database  
			#$dbh = new PDO("sqlite:my/database/path/database.db");  		
		
    }
catch(PDOException $e)
    {
		echo $e->getMessage();
    }
