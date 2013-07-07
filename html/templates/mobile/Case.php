<?php 
$case_id = $_GET['id'];
include 'html/templates/interior/idletimeout.php'; 
include 'lib/php/data/cases_case_data_load.php';
include 'lib/php/data/cases_casenotes_load.php';
?>
<div class="navbar navbar-fixed-top navbar-headnav">
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
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
    <div id="notifications"></div>
    <ul class="nav nav-tabs" id="myTab">
        <li class="active" data-toggle="tab"><a href="#caseNotes">Case Notes</a></li>
        <li><a href="#caseData" data-toggle="tab">Case Data</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">More <b class="caret"></b></a>
            <ul class="dropdown-menu">
                <li><a href="#caseDocs" data-toggle="tab">Documents</a></li>
                <li><a href="#caseContacts" data-toggle="tab">Contacts</a></li>
                <li><a href="#caseEvents" data-toggle="tab">Events</a></li>
            </ul>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="caseNotes">
            <dl class="dl-sectioned">
            <?php foreach ($case_notes_data as $c) {extract($c);
                $thumb = thumbify($picture_url);
                $date_human = extract_date($date);
                $tc = convert_case_time($time);
                echo "<dd><img src='$thumb' />$first_name $last_name <span class='pull-right'> $date_human </span></dd>
                <dt>" . nl2br(htmlentities($description)) . "  <span class='muted'>($tc[0] $tc[1])</span></dt> ";
            }
            ?>
            </dl>
        </div>
        <div class="tab-pane" id="caseData">
            <dl class="dl-horizontal">
            <?php foreach ($data as $d) {extract($d);

                if ($input_type == 'dual') //special handling for dual inputs
                    { ?>

                        <dt><?php echo $display_name; ?></dt>

                            <dd>
                            <?php if (!empty($value)){$duals = unserialize($value);

                                foreach ($duals as $v => $type) { ?>

                                <?php echo $v . " (" . $type . ")"; ?>

                                <?php }?>

                            <?php }?>
                            </dd>

                <?php } else { ?>

                    <dt><?php echo $display_name; ?></dt>
                    <dd>
                        <?php
                        //first check if this is a serialized value
                        $items = @unserialize($value);
                        if ($items !== false)
                        {
                            $val = null;
                            foreach ($items as $key => $item) {
                                $val .= $key . ", ";
                            }

                            echo substr($val, 0,-2);
                        }
                        elseif ($input_type === 'date')
                        //then check if it's a date
                        {
                            echo sql_date_to_us_date($value);
                        }
                        else
                        {
                            echo $value;
                        }?>
                    </dd>

                <?php }} ?>

            </dl>
        </div>

        <div class="tab-pane" id="caseContacts">
            Case Contacts here. 
        </div>
        <div class="tab-pane" id="caseEvents">
            Case Events here. 
        </div>
    </div>
</div>
