<?php 
include_once('lib/php/html/gen_select.php');
include_once('lib/php/utilities/names.php');
include_once('lib/php/utilities/states.php');
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
        <button class="btn btn-info navbar-btn btn-sm navbar-toggle collapsed" href="index.php?i=QuickAdd.php">
            Quick Add
            <i class="fa fa-plus"></i>
        </button>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="collapse-menu">
      <ul class="nav navbar-nav navbar-right">
            <li><a href="index.php?i=Home.php">Home</a></li>
            <li><a href="index.php?i=Cases.php">Cases</a></li>
            <li><a href="index.php?i=Messages.php">Messages</a>
            <?php if ($_SESSION['permissions']['view_board'] === '1'){ ?>
            <li><a href="index.php?i=Board.php">Board</a>
            <?php } ?>
            <li class="active" ><a href="index.php?i=QuickAdd.php">Quick Add</a>
            <li><a href="index.php?i=Logout.php&user=1">Logout</a>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<div class="container">
    <?php include 'html/templates/interior/idletimeout.php' ?>
    <div id="notifications"></div>
    <div class="row">
        <div class="col-xs-12">
            <h1>Quick Add</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <ul class="nav nav-tabs nav-tabs-responsive" role="tablist" id="qaTab">
                <li role="presentation" class="active"><a role="tab" data-toggle="tab" aria-controls="Case Note" href="#qaCaseNote"><span class="text">Case Note</span></a></li>
                <li role="presentation" class="next"><a role="tab" data-toggle="tab" aria-controls="Event" href="#qaEvent"><span class="text">Event</span></a></li>
                <li role="presentation"><a role="tab" data-toggle="tab" aria-controls="Contact" href="#qaContact"><span class="text">Contact</span></a></li>
            </ul>
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="qaCaseNote">
                    <?php if ($_SESSION['permissions']['add_case_notes'] === '1'){ ?>
                    <form name="quick_cn">
                        <div class="form-group">
                            <label class="control-label" for "cn_case">Case:</label>
                            <select name="csenote_case_id" class="form-control" id="cn_case">
                                <option value="NC">Non-Case Time</option>
                                <?php
                                $options = generate_active_cases_select($dbh,$_SESSION['login']);
                                echo $options;
                            ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for "cn_date">Date:</label>
                            <div class="input-group">
                                <input type="text" class="date-picker form-control" data-date-format="mm/dd/yy"
                            data-date="<?php echo date('m/d/y'); ?>" value="<?php echo date('m/d/y'); ?>" name="cn_date" placeholder="MM/DD/YY" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for "cn_hours">Hours:</label>
                            <input type="text" class="form-control" name="csenote_hours" value="0">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for "cn_minutes">Minutes:</label>
                            <input type="text" class="form-control" name="csenote_minutes" id="cn_minutes" value="0">
                        </div>
                        <div class="form-group">
                            <label for "csenote_description">Description</label><br />
                            <textarea name="csenote_description" id="csenote_description" class="form-control" rows="4" required></textarea>
                        </div>
                        <input type="hidden" name="query_type" value="add">
                        <input type="hidden" name="csenote_user" value="<?php echo $_SESSION['login'];?>">
                        <input type="hidden" name="csenote_date">
                        <input type="hidden" name="csenote_date" id="cn_date"></p>
                        <p id = "quick_add_cn">
                            <button class="btn btn-success"  id="quick_add_cn_submit">Add</button>
                        </p>
                    </form>
                    <?php } else {echo "<p>You do not have permission to add case notes.</p>";} ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="qaEvent">
                    <?php if ($_SESSION['permissions']['add_events'] === '1'){ ?>
                    <form name = "quick_event">
                        <p><label>What: </label><input type="text" name="task" class="required"></p>
                        <p><label>Where: </label><input type="text" name="where"></p>
                        <p><label>Start: </label> <?php echo gen_mobile_datepicker(true); ?> </p>
                        <p><label>End: </label><?php echo gen_mobile_datepicker(true); ?></p>
                        <p><label>All Day? </label><input type="checkbox" class="check" name="all_day" value="off"></p>
                        <p><label>Case: </label>
                            <select id="ev_case" style="width:230px;" data-placeholder="Select a Case" name="case_id">
                                <option selected=selected value="NC">Non-Case</option>
                                <?php $options = generate_active_cases_select($dbh,$_SESSION['login']);
                                echo $options;?>
                            </select>
                        </p>
                        <p><label>Who Sees This?</label>
                            <select multiple id="ev_users" style="width:33px;" data-placeholder="Select Users" name="responsibles" class="required">
                                <?php echo all_active_users_and_groups($dbh,false,$_SESSION['login']); ?>
                            </select>
                        </p>
                        <p><label>Notes</label>
                            <textarea name="notes"></textarea>
                        </p>
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="start">
                        <input type="hidden" name="end">
                        <p id = "quick_add_ev">
                            <button class="btn btn-success" id="quick_add_ev_submit">Add</button>
                        </p>
                    </form>
                    <?php } else {echo "<p>You do not have permission to add events.</p>";} ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="qaContact">
                    <?php if ($_SESSION['permissions']['add_contacts'] === '1'){ ?>
                    <form name = "quick_contact">
                        <p><label>First Name</label><input type="text" name="first_name"></p>
                        <p><label>Last Name</label><input type="text" name="last_name"></p>
                        <p><label>Organization</label><input type="text" name="organization"></p>
                        <p><label>Contact Type</label><select name="contact_type">
                        <option></option>
                        <?php echo gen_default_contact_types($dbh); ?></select></p>
                        <p><label>File In:</label><select name="case_id">
                        <option></option>
                        <?php echo generate_active_cases_select($dbh,$_SESSION['login']); ?></select></p>
                        <div class="control-group">
                            <label class="control-label">Email</label>
                            <div class="controls"> 
                            <select name="email_type" class="inline small-select">
                                <option value="home">Home</option>
                                <option value="work">Work</option>
                                <option value="other">Other</option>
                            </select>
                            <input type="text" name="email" class="email">
                            </div>
                        </div>
                        <p><label>Website</label><input type="text" name="url" class="url"></p>
                        <div class="control-group">
                            <label class="control-label">Phone</label>
                            <div class="controls"> 
                            <select name="phone_type" class="inline small-select">
                                <option value="mobile">Mobile</option>
                                <option value="home">Home</option>
                                <option value="work">Work</option>
                                <option value="other">Other</option>
                            </select>
                            <input type="text" name="phone" class="phoneUS">
                            </div>
                        </div>
                        <p><label>Address</label><textarea name="address"></textarea></p>
                        <p><label>City</label><input type="text" name="city"></p>
                        <p><label>State</label>
                        <?php echo state_selector('state','state'); ?> 
                        </p>
                        <p><label>Zip</label><input type="text" name="zip"></p>
                        <p><label>Notes</label>
                            <textarea name="notes"></textarea>
                        </p>
                        <input type="hidden" name="action" value="add">
                        <p><button class="btn btn-success">Add</button>
                        </p>
                    </form>
                    <?php } else {echo "<p>You do not have permission to add contacts.</p>";} ?>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="lib/javascripts/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="lib/javascripts/bootstrap-datepicker/bootstrap-datepicker-mobile.js"></script>
    <script src="lib/javascripts/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script>
        $("input[name='csenote_hours']").TouchSpin({
            min: 0,
            max: 8,
            step: 1,
            decimals: 0 ,
            boostat: 5,
            maxboostedstep: 10,
        });
        $("input[name='csenote_minutes']").TouchSpin({
            min: 0,
            max: 60,
            step: <?php echo CC_TIME_UNIT; ?>,
            decimals: 0 ,
            boostat: 5,
            maxboostedstep: 10
        });
    </script>
</body>
</html>
