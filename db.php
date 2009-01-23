<?
# database connection scripts
# the next 4 lines you can modify
$dbhost = 'localhost';
$dbusername = '';
$dbpasswd = '';
$database_name = '';

#under here, don't touch!
$connection = mysql_pconnect("$dbhost","$dbusername","$dbpasswd")
    or die ("Couldn't connect to server.");
$db = mysql_select_db("$database_name", $connection)
    or die("Couldn't select database.");
?>
