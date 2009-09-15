<?php
session_start();
if (!$_SESSION)
{die("You must be logged in to view this page.");}

include '../db.php';
include '../get_name.php';
include '../classes/get_names.php';
include '../classes/format_dates_and_times.class.php';

if ($_POST)
{
$do_reply = mysql_query("INSERT INTO `cm_messages`  (`id` ,`thread_id` ,`to` ,`from` , `ccs`, `subject` ,`body` ,`assoc_case` ,`time_sent` ,`read` ,`archive`
) VALUES (
NULL , '$_POST[thread_id]', '$_POST[to]', '$_POST[from]', '$_POST[ccs]','$_POST[subject]', '$_POST[body]', '$_POST[assoc_case]',CURRENT_TIMESTAMP , '', '');");




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
<a class="nav"  href="cm_home_m.php">Main Menu</a> > <a class="nav"  href="messages_m.php">Message List</a> > <a href="message_display_m.php?id=<?php echo $message; ?>">View Original Message</a><br>
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
<strong>Reply to Message</strong>

<?php
if (isset($_GET[reply_all]))
{
	  //get the to recipients
	   $tos = explode(",",$d[to]);
	   foreach ($tos as $v)
	   {
		   if ($v !== $_SESSION[login]) //take the responser out of the tos
		   {

		   $nme = new get_names; $n = $nme->get_users_name($v);
		   $to_r .= $n . ",";
		   $to_usernames .= $v . ",";
	   	}
	   }

	     //get the cc recipients

	   $ccs = explode(",",$d[ccs]);
	   foreach ($ccs as $v)
	   {
		   if ($v !== $_SESSION[login])  //take the responser out of the ccs
		   {
		   $nmec = new get_names; $nc = $nmec->get_users_name($v);
		   $cc_r .= $nc . ",";
		   $cc_usernames .= $v . ",";
	   }
	   }

	   echo "<p>To: " . substr($to_r,0,-1) . "</p><p>Cc:" . substr($cc_r,0,-1) .  "</p>";



}
else
{
	$to_usernames = $d[from] . ",";
	echo "<p>To: "; getName($d[from]); echo "</p>";
}
?>


Subject: Re: <?php echo $d[subject]; ?>
<br><br>
<form name="reply" method="post" action="message_reply_m.php">

<textarea cols=40 rows=8 name="body"></textarea>
<br>
<input type="hidden" name="thread_id" value="<?php echo $d[thread_id]; ?>">
<input type="hidden" name="to" value="<?php echo substr($to_usernames,0,-1); ?>">
<input type="hidden" name="ccs" value="<?php echo substr($cc_usernames,0,-1); ?>">
<input type="hidden" name="from" value="<?php echo $_SESSION[login]; ?>">
<input type="hidden" name="assoc_case" value="<?php echo $d[assoc_case]; ?>">
<input type="hidden" name="subject" value="<?php echo $d[subject]; ?>">
<input type="hidden" name="orig_msg" value="<?php echo $_GET[id]; ?>">
<input type="submit" value="Send">

</form>
</body>
</html>
