
<!-- Jquery Calls Specific to this page -->
	<script type="text/javascript" src="html/js/Login.js"></script>

</head>

<body  class="login">
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse-menu" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><img class="img-responsive" src="html/images/logo_sm.png"></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="collapse-menu">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="https://cliniccases.com/help">Help</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
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

