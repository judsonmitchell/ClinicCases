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
                            data-date="<?php echo date('m/d/y'); ?>" value="<?php echo date('m/d/y'); ?>" name="cn_date" id="cn_date" placeholder="MM/DD/YY" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for "cn_hours">Hours:</label>
                            <input type="text" class="form-control" id="cn_hours" name="csenote_hours" value="0">
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
                        <input type="hidden" name="csenote_date" id="cn_date">
                        <p id = "quick_add_cn">
                            <button class="btn btn-success"  id="quick_add_cn_submit">Add</button>
                        </p>
                    </form>
                    <?php } else {echo "<p>You do not have permission to add case notes.</p>";} ?>
                </div>
                <div role="tabpanel" class="tab-pane" id="qaEvent">
                    <?php if ($_SESSION['permissions']['add_events'] === '1'){ ?>
                    <form name = "quick_event">
                        <div class="form-group">
                            <label class="control-label" for "task">What: </label>
                            <input type="text" name="task" id="task" class="form-control required">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for "where">Where: </label>
                            <input type="text" name="where" id="where" class="form-control">
                        </div>
                        <hr />
                        <div class="form-group">
                            <label class="control-label" for "c_start">Start Date:</label>
                            <div class="input-group">
                                <input type="text" class="date-picker form-control" data-date-format="mm/dd/yy"
                            data-date="<?php echo date('m/d/y'); ?>" value="<?php echo date('m/d/y'); ?>" name="c_start" id="c_start" placeholder="MM/DD/YY" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-12">
                                    <label>Start Time:</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    <input type="text" class="form-control hour-chooser" id="ce_hour_start" name="ce_hours" value="9">
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control minute-chooser" id="ce_minute_start"  name="c_minutes" value="00">
                                </div>
                                <div class="col-xs-4">
                                    <select class="form-control ampm-chooser" id="ce_ampm_start" name="c_ampm">
                                        <option value="AM">AM</option>
                                        <option value="PM">PM</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="form-group">
                            <label class="control-label" for "c_end">End Date:</label>
                            <div class="input-group">
                                <input type="text" class="date-picker form-control" data-date-format="mm/dd/yy"
                            data-date="<?php echo date('m/d/y'); ?>" value="<?php echo date('m/d/y'); ?>" name="c_end" id="c_end" placeholder="MM/DD/YY" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-4">
                                    <label>End Time:</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4">
                                    <input type="text" class="form-control hour-chooser" id="ce_hour_end" name="c_hours" value="10">
                                </div>
                                <div class="col-xs-4">
                                    <input type="text" class="form-control minute-chooser" id="ce_minute_end" name="c_minutes" value="00">
                                </div>
                                <div class="col-xs-4">
                                    <select class="form-control ampm-chooser" id="ce_ampm_end" name="c_ampm">
                                        <option value="AM">AM</option>
                                        <option vPlue="PM">PM</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="all_day" value="off"> All Day?
                            </label>
                        </div>
                        <div class="form-group">
                            <label>Case: </label>
                            <select id="ev_case" class="form-control" data-placeholder="Select a Case" name="case_id">
                                <option selected=selected value="NC">Non-Case</option>
                                <?php $options = generate_active_cases_select($dbh,$_SESSION['login']);
                                echo $options;?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Who Sees This?</label>
                                <select multiple id="ev_users" class="form-control"  data-placeholder="Select Users" name="responsibles" class="required">
                                    <?php echo all_active_users_and_groups($dbh,false,$_SESSION['login']); ?>
                                </select>
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea class="form-control" name="notes" rows="4"></textarea>
                        </div>
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
                        <div class="form-group">
                            <label>First Name</label><input class="form-control" type="text" name="first_name">
                        </div>
                        <div class="form-group">
                            <label>Last Name</label><input class="form-control" type="text" name="last_name">
                        </div>
                        <div class="form-group">
                            <label>Organization</label><input class="form-control" type="text" name="organization">
                        </div>
                        <div class="form-group">
                            <label>Contact Type</label>
                            <select  class="form-control" name="contact_type">
                                <option></option>
                                <?php echo gen_default_contact_types($dbh); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>File In:</label>
                            <select  class="form-control" name="case_id">
                                <option></option>
                                <?php echo generate_active_cases_select($dbh,$_SESSION['login']); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for "emailr">Email</label>
                            <div class="input-group">
                                <input type="text" name="email" id="emailr" class="form-control email">
                                <span class="input-group-btn">
                                    <select name="email_type" class="btn btn-default">
                                        <option value="home" selected=selected>Home</option>
                                        <option value="work">Work</option>
                                        <option value="other">Other</option>
                                    </select>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Website</label>
                            <input type="text" class="form-control" name="url" class="url">
                        </div>
                        <div class="form-group">
                            <label for "phonr">Phone</label>
                            <div class="input-group"> 
                                <input type="text" name="phone" id="phonr" class="form-control phoneUS">
                                <span class="input-group-btn"> 
                                    <select name="phone_type" class="btn btn-default">
                                        <option value="mobile" selected=selected>Mobile</option>
                                        <option value="home">Home</option>
                                        <option value="work">Work</option>
                                        <option value="other">Other</option>
                                    </select>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control"> </textarea>
                        </div>
                        <div class="form-group">
                            <label>City</label><input type="text" class="form-control" name="city">
                        </div>
                        <div class="form-group">
                            <label>State</label>
                            <?php echo state_selector('state','state'); ?> 
                        </div>
                        <div class="form-group">
                            <label>Zip</label>
                            <input type="text" class="form-control" name="zip">
                        </div>
                        <div class="form-group">
                            <label>Notes</label>
                            <textarea name="notes" class="form-control" ></textarea>
                        </div>
                        <input type="hidden" name="action" value="add">
                        <div class="form-group">
                            <button class="btn btn-success">Add</button>
                        </div>
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
        $('input.hour-chooser').TouchSpin({
            min: 0,
            max: 12,
            step: 1,
            verticalbuttons: true,
            verticalupclass: 'glyphicon glyphicon-plus',
            verticaldownclass: 'glyphicon glyphicon-minus'
        });
        $("input.minute-chooser").TouchSpin({
            min: 00,
            max: 60,
            step: 5,
            verticalbuttons: true,
            verticalbuttons: true,
            verticalupclass: 'glyphicon glyphicon-plus',
            verticaldownclass: 'glyphicon glyphicon-minus'
        });
    </script>
    <script>
    //Activate auto-close for datapicker
    $.fn.datepicker.defaults.autoclose = true;
    </script>
</body>
</html>
