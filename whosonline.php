<?php
include 'db.php';
?>
<html>
<head>
<meta http-equiv="refresh" content="180">

</head>
<body>
<?php
$unixtime = time();
$five_minutes = 300;
$get_logs = mysql_query("SELECT * FROM `cm_logs`");
while ($r = mysql_fetch_array($get_logs))
{
if (!empty($r[last_ping]))
{
if (($unixtime - $r[last_ping]) <= $five_minutes)
{
echo "$r[username]<br>";



}

}
}
?>
</body>
</html>
