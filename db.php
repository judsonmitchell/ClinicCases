<? 
# database connection scripts
# the next 4 lines you can modify
$dbhost = 'localhost';
$dbusername = 'clinic';
$dbpasswd = 'likes69';
$database_name = 'cm_test';

#under here, don't touch!
$connection = mysql_pconnect("$dbhost","$dbusername","$dbpasswd") 
    or die ("Couldn't connect to server.");
$db = mysql_select_db("$database_name", $connection)
    or die("Couldn't select database.");
?>
