<?php
session_start();
if (!$_SESSION)
{die("You must be logged in to view this page.");}

include '../db.php';
include '../get_name.php';
if (isset($_GET[s]))
{$start = $_GET[s];}
else
{$start = "0";}
?>
<html>
<head>
<title>ClinicCases Mobile - Messages</title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<p><a href="cm_home_m.php"><img src="../images/logo_mobile.png" border="0"></a></p>
<a class="nav" href="cm_home_m.php">Main Menu</a> >
<strong>Messages</strong>
<p><a href="add_recipients_m.php">Create New Message</a></p>
<ul>
<?php
$query = mysql_query("SELECT * FROM `cm_messages` WHERE `to` LIKE '%$_SESSION[login]%' OR `ccs` LIKE '%$_SESSION[login]%' ORDER BY `time_sent` desc LIMIT $start,8");
while ($r = mysql_fetch_array($query))
{
echo <<<ITEM
<li><a href="message_display_m.php?id=$r[id]">$r[subject]</a>, from
ITEM;
echo " ";
getName($r[from]);
ECHO <<<ITEM
</li>
ITEM;


}

?>
</ul>
<CENTER>
<?php
$new_start = $start + 8;
$newer = $start - 8;
if ($start > 0)
{echo <<<BACK
<a href="messages_m.php?s=$newer">>>>Newer</a>   |
BACK;
}


?>

<a href="messages_m.php?s=<?php echo $new_start ?>">Older >>></a>
</CENTER>
</body>
</html>