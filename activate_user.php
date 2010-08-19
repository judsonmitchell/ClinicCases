<?php
session_start();
include 'session_error.php';
include 'db.php';
$id = $_GET['id'];
$activate = mysql_query("UPDATE `cm_users` SET `status` = 'active', `new` = 'no' WHERE `id` = '$id' LIMIT 1");
ECHO "<span style=\"color:red;\">Activated.</span>";

/* New send the email to user */
$get_user = mysql_query("SELECT * FROM `cm_users` WHERE `id` = '$id'");
while ($line = mysql_fetch_array($get_user, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_user,$i);
        $d[$field] = $col_value;
        $i++;

    }

$to = $d[email];
$subject = "Your ClinicCases Account is Activated";
$message = "The clinic adminstrator has activated your account, $d[first_name]. You can now log and begin work!  Your username is $d[username].  Your password is the one you choose when you filled out the form.  If you have problems, please contact your system adminstrator." . "\r\n" . "\r\n" . "$CC_base_url" ;
$headers = "From: " . $CC_default_email . "\n" .
   "Reply-To: " . $CC_default_email . "\n" .
   "X-Mailer: PHP/" . phpversion();
mail($to,$subject,$message,$headers);

}





?>
