<?php 
include 'html/templates/interior/idletimeout.php'; 
include 'lib/php/data/cases_case_data_load.php';
include 'lib/php/data/cases_casenotes_load.php';
include 'lib/php/data/cases_documents_load.php';
include 'lib/php/data/cases_contacts_load.php';
include 'lib/php/data/cases_events_load.php';
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
    <div class="row" id="notifications"></div>
    <div class="row">
        <div class="col-xs-12">
            <a href="index.php?i=Cases.php" class="btn btn-primary btn-sm"><span class="fa fa-chevron-left"></span> Cases</a>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <h3><?php echo case_id_to_casename($dbh,$id); ?></h3>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <ul class="nav nav-tabs nav-tabs-responsive" role="tablist" id="myTab">
                <li role="presentation" class="active"><a role="tab" data-toggle="tab" aria-controls="case notes" href="#caseNotes"><span class="text">Case Notes</span></a></li>
                <li role="presentation" class="next"><a role="tab" data-toggle="tab" aria-controls="case data" href="#caseData"><span class="text">Case Data</span></a></li>
                <li role="presentation"><a role="tab" data-toggle="tab" aria-controls="documents" href="#caseDocs"><span class="text">Documents</span></a></li>
                <li role="presentation"><a role="tab" data-toggle="tab" aria-controls="contacts" href="#caseContacts"><span class="text">Contacts</span></a></li>
                <li role="presentation"><a role="tab" data-toggle="tab" aria-controls="events" href="#caseEvents"><span class="text">Events</span></a></li>
            </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="caseNotes">
                <?php 
                    if (empty($case_notes_data)){
                        echo "No case notes found";
                    } else {
                        foreach ($case_notes_data as $c) {extract($c);
                        $thumb = thumbify($picture_url);
                        $date_human = extract_date($date);
                        $tc = convert_case_time($time);
                        echo "<div class='media'><div class='media-left media-top'><img class='media-object img-circle' src='$thumb' />
                        </div><div class='media-body'><h4 class='media-heading'>" . htmlspecialchars($first_name ,ENT_QUOTES,'UTF-8') . " " . htmlspecialchars($last_name ,ENT_QUOTES,'UTF-8') . "<span class='pull-right'> $date_human </span></h4>
                        " . nl2br(htmlentities($description)) . "  <span class='muted'>($tc[0] $tc[1])</span></div></div> ";
                    }
                }
                ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="caseData">
                <dl class="dl-horizontal">
                <?php foreach ($dta as $d) {extract($d);

                    if ($input_type == 'dual') //special handling for dual inputs
                        { ?>
                            <dt><?php echo htmlspecialchars($display_name,ENT_QUOTES,'UTF-8'); ?></dt>
                                <dd>
                                <?php if (!empty($value)){$duals = unserialize($value);

                                    foreach ($duals as $v => $type) {
                                    
                                    if ($display_name === "Phone"){
                                        echo "<a href='tel:$v'>" . htmlspecialchars($v ,ENT_QUOTES,'UTF-8'). "</a> (" . $type . ")<br />"; 
                                    } else if ($display_name === "Email"){
                                        echo "<a href='mailto:$v'>" . htmlspecialchars($v ,ENT_QUOTES,'UTF-8')."</a>" . " (" . $type . ")<br />"; 
                                    } else {
                                        echo htmlspecialchars($v ,ENT_QUOTES,'UTF-8'). " (" . $type . ")"; 
                                    }

                                    }?>

                                <?php } else { echo "-";} ?>
                                </dd>

                    <?php } else { ?>

                        <dt><?php echo htmlspecialchars($display_name,ENT_QUOTES,'UTF-8'); ?></dt>
                        <dd>
                            <?php
                            //first check if this is a serialized value
                            $items = @unserialize($value);
                            if ($value !== ''){
                                if ($items !== false) {
                                    $val = null;
                                    foreach ($items as $key => $item) {
                                        $val .= $key . ", ";
                                    }

                                    echo substr(htmlspecialchars($val,ENT_QUOTES,'UTF-8'), 0,-2);
                                }
                                elseif ($input_type === 'date') {
                                //then check if it's a date
                                    echo sql_date_to_us_date(htmlspecialchars($value,ENT_QUOTES,'UTF-8'));
                                } else {
                                    echo htmlspecialchars($value,ENT_QUOTES,'UTF-8');
                                }
                            } else {
                                echo "-";
                            }
                                ?>
                        </dd>

                    <?php }} ?>

                </dl>
            </div>

            <div role="tabpanel" class="tab-pane" id="caseContacts">
                <ul class="list-group">
                <?php 
                if (empty($contacts)){
                    echo "<li style='list-style:none'>No contacts found.</li>";
                } else {
                    foreach ($contacts as $c) {extract($c); ?>
                        <li style="list-style:none"> 
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="panel-info">
                                    <h4>
                        <?php 
                            if ($first_name === '' && $last_name === ''){
                                    echo htmlspecialchars($organization,ENT_QUOTES,'UTF-8');
                                } else {
                                    echo htmlspecialchars($first_name ,ENT_QUOTES,'UTF-8'). " " . htmlspecialchars($last_name,ENT_QUOTES,'UTF-8');
                                }
                            ?>
                            </h4>
                            <ul>
                                <?php foreach (array_filter($c) as $key => $value) {
                                    if (in_array($key,array('id','assoc_case','first_name','last_name'))) { //srip unnecessary elements
                                        continue;
                                    } else {
                                        if (is_array(json_decode($value, true))){ //if json encoded values
                                        $v = json_decode($value, true);
                                        foreach ($v as $k => $vl) {
                                            if (!empty($vl)){
                                                    if ($key === 'phone'){
                                                            echo "<li><span class='text-muted'>Phone:</span>" .
                                                            "<a href='tel:'" . htmlspecialchars($vl ,ENT_QUOTES,'UTF-8') . 
                                                            "'>" . htmlspecialchars($vl ,ENT_QUOTES,'UTF-8'). "</a> ($k)</li> "; 
                                                    } else if ($key === 'email'){
                                                            echo "<li><span class='text-muted'>Phone:</span> Email <a href='mailto:" . 
                                                            htmlspecialchars($vl,ENT_QUOTES,'UTF-8') . "'>" . 
                                                            htmlspecialchars($vl,ENT_QUOTES,'UTF-8') . "</a> (" . 
                                                            htmlspecialchars($k ,ENT_QUOTES,'UTF-8'). ")</li> "; 
                                                    } else {
                                                            echo "<li><li><span class='text-muted'>Phone:</span>" .  
                                                            htmlspecialchars($v,ENT_QUOTES,'UTF-8') . "</span> (" . 
                                                            htmlspecialchars($k ,ENT_QUOTES,'UTF-8'). ")</li> ";
                                                    }
                                            }
                                        }
                                        } else {
                                            echo "<li><span class='text-muted'>" . 
                                            ucwords(str_replace('_', ' ',htmlspecialchars($key,ENT_QUOTES,'UTF-8'))) . ":</span>" .  
                                            htmlspecialchars($value ,ENT_QUOTES,'UTF-8') . "</li>";
                                        }
                                    }
                                }
                                ?>
                            </ul>
                                </div>
                            </div>
                        </div>
                    <?php }
                }
                ?>
                    </li>
                </ul>
            </div>
            <div role="tabpanel" class="tab-pane" id="caseEvents">
                <ul class="list-group">
                <?php 
                if (empty($events)){
                    echo '<li style="list-style:none">No events found</li>';
                } else {
                foreach ($events as $e) {extract($e); ?>
                    <li style="list-style:none">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="panel-info">
                                    <h4><?php echo htmlspecialchars($task ,ENT_QUOTES,'UTF-8'). ' - '; echo extract_date($start); ?></h4>
                                    <ul class="unstyled">
                                        <li><span class="text-muted">Start:</span> <?php echo htmlspecialchars($e['start_text'], ENT_QUOTES,'UTF-8'); ?>  </li>
                                        <li><span class="text-muted">End: </span><?php echo htmlspecialchars($e['end_text'], ENT_QUOTES,'UTF-8'); ?>  </li>
                                        <li><span class="text-muted">All Day: </span><?php if($e['all_day'] === '1'){echo "Yes";}else{echo "No";} ?>  </li>
                                        <li><span class="text-muted">Where: </span><?php echo htmlspecialchars($e['location'], ENT_QUOTES,'UTF-8'); ?>  </li>
                                        <li><span class="text-muted">Notes: </span><?php echo nl2br(htmlspecialchars($e['notes'], ENT_QUOTES,'UTF-8')); ?>  </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php }
                } ?>
                </ul>
            </div>
            <div role="tabpanel" class="tab-pane" id="caseDocs">
                <div class="doc-list">
                    <ul class="list-unstyled">
                    <?php if (empty($folders) && empty($documents)){
                        echo "No documents found"; 
                    } else {
                        foreach ($folders as $f){
                            $ref = "index.php?i=Case.php&id=" . $case_id . 
                            "&path=" . $f['folder'] . "&container=" . $f['folder'];
                            echo "<li class='mobile-doc-list'><a href='$ref#caseDocs'><img src='html/ico/folder.png'>"
                            . urldecode($f['folder']) . "</a></li>";
                        }
                    } 
                    ?>
                    </ul>
                    <ul class="list-unstyled">
                    <?php foreach ($documents as $d){
                        echo "<li class='mobile-doc-list'><a class='doc-item'  data-id='" . $d['id'] . "' data-ext='" . $d['extension'] ."'  href='#'><img src='" . get_icon($d['type']). "'>" . urldecode($d['name']) . "</a></li>";
                    } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
