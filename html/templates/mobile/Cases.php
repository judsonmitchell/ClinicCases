<?php 
include 'html/templates/interior/idletimeout.php'; 
include 'lib/php/data/cases_load.php';
?>
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
                    <li ><a href="index.php?i=Home.php">Home</a></li>
                    <li class="active"><a href="index.php?i=Cases.php">Cases</a></li>
                    <li><a href="index.php?i=Messages.php">Messages</a>
                    <li><a href="index.php?i=QuickAdd.php">Quick Add</a>
                    <li><a href="index.php?i=Logout.php">Logout</a>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>


<div class="container">
    <div class="row">
        <div id="notifications"></div>
        <h1>Cases</h1>
        <form class="navbar-search pull-left">
            <input type="text" class="search-query" placeholder="Search">
        </form>
    </div>
    <div class="row">
    <?php if (empty($raw_results)) {
        echo "<p class='end'>No cases found</p>";
        } else {
            echo "<ul class=\"nav nav-pills nav-stacked\">";
                foreach ($raw_results as $r) {extract($r);
                    if ($date_close !== ''){
                        echo "<li class='table-case-closed'>";
                    } else {
                        echo "<li class='table-case-open'>";
                    }
                    echo "<a href='index.php?i=Case.php&id=$id'>$first_name $last_name <i class=\"pull-right icon-circle-arrow-right\"></i></a></li>";
                }
            echo "</ul>";
        }
    ?>
    </div>
</div>
