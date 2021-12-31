<?php
include CC_PATH . '/db.php';
include CC_PATH . '/lib/php/auth/log_write.php';
write_log($dbh,$_SESSION['login'],$_SERVER['REMOTE_ADDR'],$_SESSION['cc_session_id'],'out');
session_unset();
session_destroy();
?>
<html>
	<head>
		<link rel="stylesheet" href="<?php echo CC_BASE_URL; ?>html/css/cm.css" type="text/css"  media="screen"/>
<?php
if (isset($_GET['user'])){
    //if the logout has been initiated by the user
    echo "<meta http-equiv=\"refresh\" content=\"5;URL=" . CC_BASE_URL .  "index.php\"";
} else { 
    //logout is the result of inactivity
    echo "<meta http-equiv=\"refresh\" content=\"5;URL=" . CC_BASE_URL .  "index.php?force_close=1\"";
}

?>
</head>

<body class="login">

    <?php include CC_PATH . '/html/templates/interior/timer.php' ?>
    <?php include CC_PATH . '/html/templates/interior/idletimeout.php' ?>

    <div id="content" class="content_login" style="height:560px">

            <div class="wrapper">

                <br /><br />
                <h2>You have been logged out of ClinicCases</h2>

            </div>
    </div>

</body>

</html>
