<?php 
include_once('lib/php/html/gen_select.php');
include_once('lib/php/utilities/names.php');
include_once('lib/php/utilities/states.php');
?>
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
                    <li><a href="index.php?i=Board.php">Board</a>
                    <?php } ?>
                    <li class="active" ><a href="index.php?i=QuickAdd.php">Quick Add</a>
                    <li><a href="index.php?i=Logout.php&user=1">Logout</a>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
</div>
<div class="container">
    <h1>Quick Add</h1>
    <div id="notifications"></div>
    <?php include 'html/templates/interior/idletimeout.php' ?>

    <ul class="nav nav-tabs" id="myTab">
        <li ><a class="default-tab" data-toggle="tab" href="#qaCaseNote">Case Note</a></li>
        <li><a href="#qaEvent" data-toggle="tab">Event</a></li>
        <li><a href="#qaContact" data-toggle="tab">Contact</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" id="qaCaseNote">
            <form name="quick_cn">
                <input type="hidden" name="csenote_date" id="cn_date"></p>
                <?php echo gen_mobile_datepicker(false); ?>
                <p><label>Case</label>
                    <select name="csenote_case_id" id="cn_case" style="width:230px;" >
                        <option value="NC">Non-Case Time</option>
                        <?php
                        $options = generate_active_cases_select($dbh,$_SESSION['login']);
                        echo $options;
                    ?>
                    </select>
                </p>
                <p class="quick_add_times">
                    <?php $selector = generate_time_selector(); echo $selector; ?>
                </p>
                <p>
                    <label>Description</label><br />
                    <textarea name="csenote_description" required></textarea>
                </p>
                <input type="hidden" name="query_type" value="add">
                <input type="hidden" name="csenote_user" value="<?php echo $_SESSION['login'];?>">
                <input type="hidden" name="csenote_date">
                <p id = "quick_add_cn">
                    <button class="btn btn-success"  id="quick_add_cn_submit">Add</button>
                </p>
            </form>
        </div>
        <div class="tab-pane" id="qaEvent">
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

        </div>
        <div class="tab-pane" id="qaContact">
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
        </div>
    </div>
</div>
</body>
</html>
