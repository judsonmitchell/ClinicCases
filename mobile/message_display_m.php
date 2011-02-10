<?php
session_start();
if (!$_SESSION)
{die("You must be logged in to view this page.");}

include '../db.php';
include '../classes/get_names.php';
include '../classes/format_dates_and_times.class.php';
$message = $_GET[id];

//mark message read
$get_current_mark = mysql_query("SELECT `id`,`read` FROM `cm_messages` WHERE `id` = '$_GET[id]' LIMIT 1");
	$b = mysql_fetch_object($get_current_mark);
	$current_mark = $b->read;
	$new_mark = $b->read . "$_SESSION[login],";
	$set_new_mark_read = mysql_query("UPDATE `cm_messages` SET `read` = '$new_mark' WHERE `id` = '$_GET[id]' LIMIT 1");

//now get message

$query = mysql_query("SELECT * FROM `cm_messages` WHERE `id` = '$message' LIMIT 1");
while ($line = mysql_fetch_array($query, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($query,$i);
        $d[$field] = $col_value;
        $i++;
        }
        }

	   //get the to recipients
	   $tos = explode(",",$d[to]);
	   foreach ($tos as $v)
	   {
		   $nme = new get_names; $n = $nme->get_users_name($v);
		   $to_r .= $n . ",";
	   }

	     //get the cc recipients
	   $ccs = explode(",",$d[ccs]);
	   foreach ($ccs as $v)
	   {
		   $nmec = new get_names; $nc = $nmec->get_users_name($v);
		   $cc_r .= $nc . ",";
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
<?php
$name = new get_names;$from_name = $name->get_users_name($d[from]);
?>
<p><strong>Message from <?php echo $from_name; ?></strong></p>
<p>To: <?php echo substr($to_r,0,-1); ?></p>
<p>Cc: <?php echo substr($cc_r,0,-1) ?></p>
<p>Subject: <?php echo $d[subject]; ?></p>
<p>Date: <?php formatServerTime($d[time_sent]); ?></p>
<p>
<?php echo stripslashes($d[body]); ?>
</P>

<a href="message_reply_m.php?id=<?php echo $d[id]; ?>">Reply</a> <a href="message_reply_m.php?reply_all=y&id=<?php echo $d[id]; ?>">Reply All</a>
</body>
</html>

