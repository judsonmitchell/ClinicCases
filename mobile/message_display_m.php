<?php
session_start();
include '../db.php';
include '../get_name.php';
include '../classes/format_dates_and_times.class.php';
$message = $_GET[id];
$query = mysql_query("SELECT * FROM `cm_messages` WHERE `id` = '$message' LIMIT 1");
while ($line = mysql_fetch_array($query, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($query,$i);
        $d[$field] = $col_value;
        $i++;
        }
        }
?>
<html>
<head>
<title>ClinicCases Mobile - Messages</title>
<link rel="stylesheet" href="mobile_style.css" type="text/css">
</head>
<body>
<p><a href="cm_home_m.php"><img src="../images/logo_mobile.png" border="0"></a></p>
<a class="nav"  href="cm_home_m.php">Main Menu</a> > <a class="nav"  href="messages_m.php">Message List</a><br>
<strong>Message from <?php getName($d[from]); ?></strong><br>
Subject: <?php echo $d[subject]; ?><br>
Date: <?php formatServerTime($d[time_sent]); ?><br><br>

<?php echo stripslashes($d[body]); ?>
<br>
<center>
<a href="message_reply_m.php?id=<?php echo $d[id] ?>">Reply</a>
</body>
</html>

