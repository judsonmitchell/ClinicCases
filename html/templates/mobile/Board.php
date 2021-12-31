</head>
<body class="isMobile">
<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <a class="navbar-brand" href="index.php?i=Home.php"><img class="img-responsive" src="html/images/logo_sm45.png"></a>
        <button class="btn btn-info navbar-btn btn-sm navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse-menu" aria-expanded="false">
        Menu
            <i class="fa fa-chevron-down"></i>
            <span class="sr-only">Toggle navigation</span>
        </button>
        <a class="btn btn-info navbar-btn btn-sm navbar-toggle collapsed" href="index.php?i=QuickAdd.php">
            Quick Add
            <i class="fa fa-plus"></i>
        </a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="collapse-menu">
      <ul class="nav navbar-nav navbar-right">
            <li><a href="index.php?i=Home.php">Home</a></li>
            <li><a href="index.php?i=Cases.php">Cases</a></li>
            <li><a href="index.php?i=Messages.php">Messages</a>
            <?php if ($_SESSION['permissions']['view_board'] === '1'){ ?>
            <li class="active"><a href="index.php?i=Board.php">Board</a>
            <?php } ?>
            <li><a href="index.php?i=QuickAdd.php">Quick Add</a>
            <li><a href="index.php?i=Logout.php&user=1">Logout</a>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
<div class="container">
    <?php include 'html/templates/interior/idletimeout.php' ?>
    <?php include 'lib/php/data/board_load.php' ?>
    <div class="row">
        <div class="col-xs-12" id="notifications"></div> 
    </div>
    <div class="row">
        <div class="<col-xs-12">
            <h1>Board</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control board-search" placeholder="Search">
                    <span class="btn btn-default search-submit input-group-addon">Go</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row board-container">
            <?php if ($_SESSION['permissions']['view_board'] === '1'){ 
            if (empty($posts)) {
                echo "<p class='end'>There have been no posts to your Board yet.</p>"; die;
            } else {
                    foreach ($posts as $p) {extract($p);
                        echo "<div class='col-xs-12 col-sm-3'><div class='media board-item' style='background-color:rgb($color)'>" .
                        "<div class='media-left'><img class='media-object img-circle' src='" . return_thumbnail($dbh,$author) . 
                        "'></div><div class='media-body'><h3 class='media-heading'>" .
                        "<span class='searchable'>" .  htmlspecialchars($title,ENT_QUOTES,'UTF-8') . "</span></h3>" .
                        "<div class='searchable'>$body</div>" . 
                        "<br /><div class='text-muted searchable'>Posted By " . username_to_fullname($dbh,$author) . " on " .
                        extract_date_time($time_added) . "</div>"; 

                        $attach = check_attachments($dbh,$post_id); if ($attach == true){ 
                        echo "<br /><div class='searchable'><label>Attachments:</label>$attach</div>"; 
                        }
                        echo "</div></div></div>";
                    }
                }
            } else { echo "<p>You do not have permission to view the board."; }
            ?>
    </div>
</div>
</body>
</html>
