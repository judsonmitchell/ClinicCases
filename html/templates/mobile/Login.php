
<!-- Jquery Calls Specific to this page -->
	<script type="text/javascript" src="html/js/Login.js"></script>

</head>

<body  class="login">
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <a class="navbar-brand" href="#"><img class="img-responsive" src="html/images/logo_sm45.png"></a>
        <button class="btn btn-info navbar-btn btn-sm navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse-menu" aria-expanded="false">
        Menu
            <i class="fa fa-chevron-down"></i>
            <span class="sr-only">Toggle navigation</span>
        </button>
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

<div id="notifications" ></div>

<div class="container">

        <div class="row">
            <div class="col-xs-12">
            <H2 style = "color:<?php echo CC_SCHOOL_COLOR; ?>"><?php echo CC_PROGRAM_NAME; ?></H2>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12"   id="status" style="color:red">

            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <form  name = "getin" id="getin">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value = "<?php if (isset($_COOKIE['cc_user'])){$cookie_value = $_COOKIE['cc_user'];echo $cookie_value;} ?>">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password"></p>
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"  id="remember" value="remember"> Remember me
                    </label>
                </div>
                <a href="#" class="btn btn-primary"  id="login_button">Go</a>
                </form>
            </div>
        </div>
</div>

