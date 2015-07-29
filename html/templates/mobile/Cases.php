<?php 
include 'html/templates/interior/idletimeout.php'; 
include 'lib/php/data/cases_load.php';
?>
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
            <li class="active" ><a href="index.php?i=Cases.php">Cases</a></li>
            <li><a href="index.php?i=Messages.php">Messages</a>
            <?php if ($_SESSION['permissions']['view_board'] === '1'){ ?>
            <li><a href="index.php?i=Board.php">Board</a>
            <?php } ?>
            <li><a href="index.php?i=QuickAdd.php">Quick Add</a>
            <li><a href="index.php?i=Logout.php&user=1">Logout</a>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>


<div class="container">
    <div class="row">
        <div class="col-xs-12" id="notifications"></div> 
    </div>
    <div class="row">
        <div class="col-xs-12"> 
           <h1>Cases</h1> 
               <form class="form-inline">
               <div class="form-group"> 
                    <label class="sr-only" for="exampleInputEmail3">Search</label>
                    <input type="text" id="search" class="form-control case-search search-query" placeholder="Search">
                </div>
                <div class="form-group">
                <label class="sr-only" for="filter">Open or Closed</label>
                <select name="case-status" id="filter" class="form-control search-query">
                        <option value="open">Open Cases</option>
                        <option value="closed">Closed Cases</option>
                    </select>
                </div>
                </form>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12"> 
    <?php if (empty($raw_results)) {
        echo "<p class='end'>No cases found</p>";
        } else {
            echo "<ul class=\"list-group case-list\">";
                foreach ($raw_results as $r) {extract($r);
                    if ($date_close !== ''){
                        echo "<li class='list-group-item list-group-item-less-border table-case-item table-case-closed'>";
                    } else {
                        echo "<li class='list-group-item list-group-item-less-border table-case-item table-case-open'>";
                    }
                    echo "<a href='index.php?i=Case.php&id=$id'>" .  case_id_to_casename($dbh,$id) . 
                    "<i class=\"pull-right fa fa-chevron-right\"></i></a></li>";
                }
            echo "</ul>";
        }
    ?>
        </div>
    </div>
</div>
</body>
</html>
