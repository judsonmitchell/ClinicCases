</head>
<body class="isMobile">
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <a class="navbar-brand" href="#"><img class="img-responsive" src="html/images/logo_sm45.png"></a>
        <button class="btn btn-info navbar-btn btn-sm navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse-menu" aria-expanded="false">
        Menu
            <i class="fa fa-chevron-down"></i>
            <span class="sr-only">Toggle navigation</span>
        </button>
        <button class="btn btn-info navbar-btn btn-sm navbar-toggle collapsed" href="index.php?i=QuickAdd.php">
            Quick Add
            <i class="fa fa-plus"></i>
        </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="collapse-menu">
      <ul class="nav navbar-nav navbar-right">
            <li class="active" ><a href="index.php?i=Home.php">Home</a></li>
            <li ><a href="index.php?i=Cases.php">Cases</a></li>
            <li><a href="index.php?i=Messages.php">Messages</a>
            <?php if ($_SESSION['permissions']['view_board'] === '1'){ ?>
            <li><a href="index.php?i=Board.php">Board</a>
            <?php } ?>
            <li><a href="index.php?i=QuickAdd.php">Quick Add</a>
            <li><a href="index.php?i=Logout.php&user=1">Logout</a>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav><div class="container">
    <div class="row" id="notifications"></div>
    <div class="row"><h1>Home</h1></div>
    <?php include 'html/templates/interior/idletimeout.php' ?>
    <?php include 'lib/php/data/home_activities_load.php' ?>
    <div class="home-container row">
        <div id="activities_feed">
            <?php if (empty($activities)) {
                echo "<p class='end'>There has been no activity in the last sixty days. 
                If you have just installed ClinicCases 7, it may take a while for this to start filling up.</p>"; die;
            }

            foreach ($activities as $activity) {
                echo "<h5><img class='img-rounded' src='" . $activity['thumb'] . "'> " .
                $activity['by'] . $activity['action_text'] . "<a style='font-size:1em' href='" .  $activity['mobile_url'] .
                "'>" . $activity['casename'] . "</a></h5><p>" . $activity['what'] .
                "</p><p class='muted'>" . $activity['time_formatted'] . "</p><hr>";
            }
            ?>

            <p class="end">End of activities from the last sixty days</p>
        </div>
    </div>
</div>
</body>
</html>
