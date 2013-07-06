<?php 
$case_id = $_GET['id'];
include 'html/templates/interior/idletimeout.php'; 
include 'lib/php/data/cases_case_data_load.php';
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
        <li><a href="#caseContacts" data-toggle="tab">Contacts</a></li>
        <li><a href="#caseEvents" data-toggle="tab">Events</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="caseNotes">
            Case notes here. 
        </div>
        <div class="tab-pane" id="caseData">
            <?php foreach ($data as $d) {extract($d);

                if ($input_type == 'dual') //special handling for dual inputs
                    { ?>

                        <div class = "<?php echo $db_name; ?>_display case_data_display">

                            <div class="case_data_name"><?php echo $display_name; ?></div>

                            <?php if (!empty($value)){$duals = unserialize($value);

                                foreach ($duals as $v => $type) { ?>

                                <div class="case_data_value"><?php echo $v . " (" . $type . ")"; ?></div>

                                <?php }?>

                            <?php }?>

                        </div>

                <?php } else { ?>

            <div class = "<?php echo $db_name;?>_display case_data_display">
                    <div class = "case_data_name"><?php echo $display_name; ?></div>

                    <div class="case_data_value">
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
                    </div>

            </div>

                <?php }} ?>

        </div>

        <div class="tab-pane" id="caseContacts">
            Case Contacts here. 
        </div>
        <div class="tab-pane" id="caseEvents">
            Case Events here. 
        </div>
    </div>
</div>
