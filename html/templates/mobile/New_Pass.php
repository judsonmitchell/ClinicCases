
<!-- Jquery Calls Specific to this page -->
	<script type="text/javascript" src="html/js/NewPass.js"></script>

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
                    <li class="active"><a href="index.php?i=Login.php">Login</a>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
<div id="idletimeout">
    You have been logged off due to 60 minutes inactivity. Please log in again.
</div>

<div id="notifications"></div>

<div class="container force_new_password_content">

        <h3>Welcome to ClinicCases 7!</h3>
        <p>Before you proceed, you must update your password.</p>
        <p>Your new password should be at least 8 characters long and contain both upper and lower case letters and at least one number.</p>
        <br />
        <form id="force_password_change">

            <p>
                <label>Enter your new password</label>
                <input type="password" name="new_pass">
            </p>

            <p>
                <label>Please enter again</label>
                <input type="password" name="new_pass_check">
            </p>

            <p><button>Go</button></p>

        </form>

</div>
</body>
</html>

