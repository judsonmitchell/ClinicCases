</head>
<body class="isMobile">
<div class="navbar navbar-fixed-top navbar-headnav">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar btn-push-down" data-toggle="collapse" data-target=".nav-collapse">
                Menu
                <i class="icon-chevron-down icon-white"></i>
            </a>
            <a class="btn btn-navbar btn-push-down" href="index.php?i=QuickAdd.php">
                Quick Add
                <i class="icon-plus icon-white"></i>
            </a>
            <a class="brand" href="#"><img src="html/images/logo_sm.png"></a>
            <div class="nav-collapse collapse">
                <ul class="nav">
                    <li><a href="index.php?i=Home.php">Home</a></li>
                    <li><a href="index.php?i=Cases.php">Cases</a></li>
                    <li><a href="index.php?i=Messages.php">Messages</a>
                    <?php if ($_SESSION['permissions']['view_board'] === '1'){ ?>
                    <li class="active"><a href="index.php?i=Board.php">Board</a>
                    <?php } ?>
                    <li><a href="index.php?i=QuickAdd.php">Quick Add</a>
                    <li><a href="index.php?i=Logout.php&user=1">Logout</a>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
<h1>Board</h1>
<div class="container">
    <div class="row" id="notifications"></div>
    <?php include 'html/templates/interior/idletimeout.php' ?>
    <?php include 'lib/php/data/board_load.php' ?>
    <div class="row">
        <form class="navbar-search">
            <input type="text" class="board-search search-query" placeholder="Search">
        </form>
    </div>
    <div class="row board-container">
            <?php if (empty($posts)) {
                echo "<p class='end'>There have been no posts to your Board yet.</p>"; die;
            }

            foreach ($posts as $p) {extract($p);
                echo "<div class='container board-item' style='background-color:rgb($color)'>" .
                "<h3><img class='img-rounded' src='" . return_thumbnail($dbh,$author) . 
                "'><span class='searchable'> $title</span></h3>" .
                "<div class='searchable'>$body</div>" . 
                "<br /><div class='muted searchable'>Posted By " . username_to_fullname($dbh,$author) . " on " .
                extract_date_time($time_added) . "</div>"; 

                $attach = check_attachments($dbh,$post_id); if ($attach == true){ 
                echo "<br /><div class='searchable'><label>Attachments:</label>$attach</div>"; 
                }
                echo "</div>";
            }
            ?>
    </div>
</div>
</body>
</html>
