<?php 
require_once(__DIR__ . '/../../../db.php');
include( CC_PATH . '/lib/php/data/messages_load.php');

if ($replies) { //render replies partial; called by ajax 

    echo "<ul class='ul-reply'>";
    foreach ($msgs as $m) {extract($m);  ?>
        <li>
            <div class="reply-item">
                <img class="img-rounded" src="<?php echo return_thumbnail($dbh,$from);?>">
                <span>
                    <?php echo username_to_fullname($dbh,$from); ?> - 
                    <?php echo extract_date_time($time_sent); ?> 
                </span> 
            </div>
            <?php echo stripslashes($body); ?>
        </li>
    <?php } ?> 
        <li>
            <textarea class="reply-text-box"></textarea><a class="btn btn-small send-reply" href="#">Reply</a>
        </li>
    </ul>
<?php } else { ?>
<?php include( CC_PATH . '/html/templates/interior/idletimeout.php');  ?>
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
                    <li class="active"><a href="index.php?i=Messages.php">Messages</a>
                    <li><a href="index.php?i=QuickAdd.php">Quick Add</a>
                    <li><a href="index.php?i=Logout.php&user=1">Logout</a>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
<div class="container">
    <div class="row" id="notifications"></div>
    <div class="row" id="msg-head">
        <h1 style="display:inline-block">Messages</h1>
        <h1 class="pull-right">
            <a class="btn btn-success pull-right btn-new-msg btn-push-down-a-little">
            <i class="icon-envelope icon-white"></i> New Message</a>
        </h1>
    </div>
    <div class="row" style="margin-top:10px">
        <form class="form-inline">
        <div class="input-append">
            <input type="text" class="case-search search-query" placeholder="Search">
            <button class="btn search-submit" type="button">Go</button>
        </div>
        <select name="msg-status" class="small-select pull-right">
            <option value="inbox">Inbox</option>
            <option value="sent">Sent</option>
            <option value="archive">Archive</option>
            <option value="starred">Starred</option>
        </select>
        </form>
    </div>
    <br />
    <div class="row msg_display">
        <ul class="unstyled">
            <?php foreach ($msgs as $m) {extract($m);?>
                <li class="li-expand-msg" data-thread="<?php echo $thread_id; ?>">
                    <div class="msg-header">
                        <div>
                        <img class="img-rounded" src="<?php echo return_thumbnail($dbh,$from);?>">
                        <span><?php echo username_to_fullname($dbh,$from); ?> - 
                            <?php echo extract_date_time($time_sent); ?> 
                        </span> 
                        </div>
                        <h5><?php echo htmlentities($subject); ?></h5> 
                    </div>
                    <ul class="unstyled">
                        <li class="truncate">To: <?php echo format_name_list($dbh,$to); ?></li> 
                        <?php if (!empty($ccs)){ ?>
                        <li>Cc: <?php echo format_name_list($dbh,$ccs); ?></li>
                        <?php } ?>
                        <?php if (!empty($assoc_case)){ ?>
                        <li>Filed In: <?php echo case_id_to_casename($dbh,$assoc_case); ?></li>
                        <?php } ?>
                        <br />
                        <li><?php echo stripslashes($body); ?> </li>
                    </ul>
                </li>
                <hr />
            <?php } ?>
            <li>
                <div class="navigation">
                    <?php if (count($msgs) == 20){ 
                    if (isset($_GET['start'])){
                        $start = $_GET['start'] + 20;
                    } else {
                        $start = '20';
                    }
                    $query_string = '&type=' . $_GET['type'] . '&start=' .$start;
                    ?>
                    <a class="add-more" href="index.php?i=Messages.php<?php echo $query_string; ?>">More</a>
                    <?php } ?>
                </div>
            </li>
        </ul>
    </div>
    <div class="msg-new">
        <form name = "send_message">
            <div class="control-group">
                <label class="control-label">To:</label>
                <div class="controls">
                    <select multiple name = "new_tos[]" id="msg_tos" data-placeholder = "Choose recipients" class="required">
                        <?php echo all_active_users_and_groups($dbh,false,false); ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Cc:</label>
                <div class="controls">
                    <select multiple name = "new_ccs[]" id="msg_ccs" data-placeholder = "Choose recipients">
                        <?php echo all_active_users_and_groups($dbh,false,false); ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Subject:</label>
                <div class="controls">
                    <input type="text" name="new_subject">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">File In:</label>
                <div class="controls">
                    <select name = "new_file_msg" id="msg_file" data-placeholder = "Choose case file">
                        <option value = "">No file</option>
                        <?php echo generate_active_cases_select($dbh,$username) ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Message:</label>
                <div class="controls">
                    <textarea name="new_msg_text" rows=8 class="required"></textarea>
                </div>
            </div>
            <div class="control-group">
                <div class="controls">
                    <button class="btn msg-cancel">Cancel</button>
                    <button type="submit" class="btn btn-success">Send</button>
                </div>
            </div>
			<input type="hidden" name="action" value="send">
        </form>
    </div>
</div>
</body>
</html>
<?php } ?>
