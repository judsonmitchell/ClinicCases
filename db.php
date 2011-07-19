<?php
include '_CONFIG.php';

$connection = mysql_pconnect("$CC_dbhost","$CC_dbusername","$CC_dbpasswd")
    or die ("Couldn't connect to server.");
$db = mysql_select_db("$CC_database_name", $connection)
    or die("Couldn't select database.");

