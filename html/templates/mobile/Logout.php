<?php
include CC_PATH . '/lib/php/auth/log_write.php';

write_log($dbh,$_SESSION['login'],$_SERVER['REMOTE_ADDR'],$_SESSION['cc_session_id'],'out');
session_unset();
session_destroy();

if (isset($_GET['user'])) {
    //if the logout has been initiated by the user
    echo "<meta http-equiv=\"refresh\" content=\"5;URL=" . CC_BASE_URL .  "index.php\"";
} else {
    //logout is the result of inactivity
    echo "<meta http-equiv=\"refresh\" content=\"5;URL=" . CC_BASE_URL .  "index.php?force_close=1\"";
}

?>


</head>

<body class="isMobile">

<div class="navbar navbar-fixed-top navbar-headnav">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar btn-push-down" data-toggle="collapse" data-target=".nav-collapse">
                Menu
                <i class="icon-chevron-down icon-white"></i>
            </a>
            <a class="brand" href="#"><img src="html/images/logo_sm.png"></a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li><a href="https://cliniccases.com/help">Help</a>
                    <li><a href="index.php?i=Login.php">Login</a>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
<div id="content" class="container" style="height:560px">
    <div class="row">
        <h4>You have been logged out of ClinicCases</h4>
    </div>
</div>

</body>

</html>
