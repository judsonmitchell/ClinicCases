
<!-- Jquery Calls Specific to this page -->
	<script type="text/javascript" src="html/js/Login.js"></script>

</head>

<body  class="login">
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

<div class="container">

        <div class="row">
            <H3 style = "color:<?php echo CC_SCHOOL_COLOR; ?>"><?php echo CC_PROGRAM_NAME; ?></H3>
        </div>
        <div class="row" id="status"></div>

        <form class="form-horizontal" name = "getin" id="getin">
        <div class="control-group">
            <label class="control-label" for="username">Username</label>
            <div class="controls">
                <input type="text" id = "username" name="username" value = "<?php if (isset($_COOKIE['cc_user'])){$cookie_value = $_COOKIE['cc_user'];echo $cookie_value;} ?>">
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="password">Password</label>
            <div class="controls">
            <input type="password" id = "password" name="password"></p>
            </div>
        </div>
        <div class="control-group">
            <div class="controls">
                <label class="checkbox">
                    <input type="checkbox" name="remember"  id="remember" value="remember"> Remember me
                </label>
                <button id="login_button">Go</button>
            </div>
        </div>

    </form>

</div>

