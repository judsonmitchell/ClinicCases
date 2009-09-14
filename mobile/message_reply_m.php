<?php
session_start();
include '../db.php';
include '../get_name.php';
include '../classes/format_dates_and_times.class.php';

if ($_POST)
{
$do_reply = mysql_query("INSERT INTO `cm_messages`  (`id` ,`thread_id` ,`to` ,`from` ,`subject` ,`body` ,`assoc_case` ,`time_sent` ,`read` ,`archive`
) VALUES (
NULL , '$_POST[thread_id]', '$_POST[to]', '$_POST[from]', '$_POST[subject]', '$_POST[body]', '$_POST[assoc_case]',CURRENT_TIMESTAMP , '', '');");




}

if ($_POST)
{$message = $_POST[orig_msg];}
else
{$message = $_GET[id];}

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
<p><a href="cm_home_m.php"><img src="../images/logo_mobile.png"></a></p>
<a class="nav"  href="cm_home_m.php">Main Menu</a> > <a class="nav"  href="messages_m.php">Message List</a> > <a href="message_display_m.php?id=<?php echo $message; ?>">View Origninal Message</a><br>
<?php
if ($_POST)
{
echo  <<<DONE
<CENTER>Your message has been sent.</CENTER>
</BODY></HTML>
DONE;
DIE;
}
?>
<strong>Reply to Message from <?php getName($d[from]); ?></strong>
Subject: Re: <?php echo $d[subject]; ?>
<form name="reply" method="post" action="message_reply_m.php">
<center>
<textarea cols=30 rows=8 name="body"></textarea>
<br>
<input type="hidden" name="thread_id" value="<?php echo $d[thread_id]; ?>">
<input type="hidden" name="to" value="<?php echo $d[from]; ?>">
<input type="hidden" name="from" value="<?php echo $d[to]; ?>">
<input type="hidden" name="assoc_case" value="<?php echo $d[assoc_case]; ?>">
<input type="hidden" name="subject" value="<?php echo $d[subject]; ?>">
<input type="hidden" name="orig_msg" value="<?php echo $_GET[id]; ?>">
<input type="submit" value="Send">
</center>
</form>
</body>
</html>
