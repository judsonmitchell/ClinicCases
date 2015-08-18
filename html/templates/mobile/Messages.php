<?php
require_once dirname(__FILE__) . '/../../../db.php';
include( CC_PATH . '/lib/php/data/messages_load.php');

if ($replies) { //render replies partial; called by ajax

    echo "<ul class='media-list ul-reply'>";
    foreach ($msgs as $m) {extract($m);  ?>
        <li class="media">
            <div class="media-left reply-item">
                <img class="media-object img-circle" src="<?php echo return_thumbnail($dbh,$from);?>">
            </div>
            <div class="media-body">
                <h5 class="media-heading">
                    <?php echo username_to_fullname($dbh,$from); ?> -
                    <?php echo extract_date_time($time_sent); ?>
                </h5>
                <?php echo htmlspecialchars($body,ENT_QUOTES,'UTF-8'); ?>
            </div>
        </li>
    <?php } ?>
        <li>
            <form class="form-inline">
                <div class="form-group">
                    <div class="input-group">
                        <textarea class="reply-text-box" rows="5"></textarea>
                        <span class="btn btn-default btn-sm send-reply" >Reply</span>
                        <span class="btn btn-default btn-sm archive-msg" >Archive</span>
                    </div>
                </div>
            </form>
        </li>
    </ul>
<?php } else { ?>
<?php include( CC_PATH . '/html/templates/interior/idletimeout.php');  ?>
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
            <li class="active" ><a href="index.php?i=Messages.php">Messages</a>
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
    <div class="row" id="msg-head">
        <div class="col-xs-12 col-md-9">
            <h1 style="display:inline-block">Messages</h1>
        </div>
        <div class="col-xs-12 col-md-3">
            <a class="btn btn-success btn-new-msg" style="margin-top:20px">
            <span class="fa fa-envelope"></span> New Message</a>
        </div>
    </div>
    <div class="row" style="margin-top:10px">
        <div class="col-xs-12 col-md-9">
            <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control case-search search-query" placeholder="Search">
                    <span class="btn btn-default search-submit input-group-addon">Go</span>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-md-3">
            <form class="form-inline">
            <div class="form-group">
                <label class="control-label">Show:</label>
                <select name="msg-status" class="form-control">
                    <option value="inbox">Inbox</option>
                    <option value="sent">Sent</option>
                    <option value="archive">Archive</option>
                    <option value="starred">Starred</option>
                </select>
            </div>
            </form>
        </div>
    </div>
    <br />
    <div class="row msg_display">
        <div class="col-xs-12">
            <ul class="media-list">
                <?php foreach ($msgs as $m) {extract($m);?>
                    <li class="media li-expand-msg <?php if (in_string($_SESSION['login'],$read)){echo "msg_read";}else{echo "msg_unread";} ?>" 
                    data-thread="<?php echo $thread_id; ?>">
                        <div class="media-left">
                            <img class="media-object img-circle" src="<?php echo return_thumbnail($dbh,$from);?>">
                        </div>
                        <div class="media-body ">
                            <span class="msg-header">
                            <h4 class="media-heading"> <?php echo username_to_fullname($dbh,$from); ?> - <?php echo extract_date_time($time_sent); ?> </h4>
                            <h5><?php echo htmlentities($subject); ?></h5>
                            </span>
                            <ul class="msg-body-text">
                                <li class="truncate">To: <?php echo format_name_list($dbh,$to); ?></li>
                                <?php if (!empty($ccs)){ ?>
                                <li>Cc: <?php echo format_name_list($dbh,$ccs); ?></li>
                                <?php } ?>
                                <?php if (!empty($assoc_case)){ ?>
                                <li>Filed In: <?php echo case_id_to_casename($dbh,$assoc_case); ?></li>
                                <?php } ?>
                                <br />
                                <li><?php echo htmlspecialchars($body,ENT_QUOTES,'UTF-8'); ?> </li>
                            </ul>
                        </div>
                        <hr />
                    </li>
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
                        <a class="add-more btn btn-default" href="index.php?i=Messages.php<?php echo $query_string; ?>">More</a>
                        <?php } ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <div class="msg-new">
        <form name = "send_message">
            <div class="form-group">
                <label>To:</label>
                <select multiple name = "new_tos[]" class="required form-control" id="msg_tos" data-placeholder = "Choose recipients">
                    <?php echo all_active_users_and_groups($dbh,false,false); ?>
                </select>
            </div>
            <div class="form-group">
                <label>Cc:</label>
                <select multiple name = "new_ccs[]" class="form-control" id="msg_ccs" data-placeholder = "Choose recipients">
                    <?php echo all_active_users_and_groups($dbh,false,false); ?>
                </select>
            </div>
            <div class="form-group">
                <label>Subject:</label>
                <input type="text" class="form-control" name="new_subject">
            </div>
            <div class="form-group">
                <label>File In:</label>
                <select name = "new_file_msg" class="form-control" id="msg_file" data-placeholder = "Choose case file">
                    <option value = "">No file</option>
                    <?php echo generate_active_cases_select($dbh,$username) ?>
                </select>
            </div>
            <div class="form-group">
                <label>Message:</label>
                <textarea name="new_msg_text" rows=8 class="required form-control"></textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Send</button>
                <button class="btn btn-default msg-cancel">Cancel</button>
            </div>
			<input type="hidden" name="action" value="send">
        </form>
    </div>
</div>
</body>
</html>
<?php } ?>
