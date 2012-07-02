<!DOCTYPE html>

<head>
	<title>Install ClinicCases 7</title>
	<link rel="stylesheet" href="../html/css/cm.css" type="text/css" />
	<link rel="stylesheet" href="../html/css/cm_tabs.css" type="text/css" />
	<link rel="stylesheet" href="install.css" type="text/css" />
	<link rel="stylesheet" href="../lib/jqueryui/css/custom-theme/jquery-ui-1.8.9.custom.css" type="text/css" />
	<link type="text/css" href="../html/css/fff.icon.core.css" rel="stylesheet"/>
	<link type="text/css" href="../html/css/fff.icon.icons.css" rel="stylesheet"/>
	<link rel="shortcut icon" type="image/x-icon" href="../html/images/favicon.ico" />

	<script src="../lib/jqueryui/js/jquery-1.4.4.min.js" type="text/javascript"></script>
	<script src="../lib/jqueryui/js/jquery-ui-1.8.9.custom.min.js" type="text/javascript"></script>
	<script src="install.js" type="text/javascript"></script>
	<script src="../html/js/notifyUser.js" type="text/javascript"></script>



</head>

<body>

	<div id="notifications"></div>

	<div id = "nav_container">

		<div id='tabs'>

			<ul>

				<li class="current"><a href="#"><span>Install</span></a></li>

			</ul>

		</div>

		<div id="menus">

			<img src="../html/images/logo_small4.png">

			<a class="menu" target="_new" href="http://cliniccases.com/help">Help</a>

		</div>

	</div>

	<div id="content">

		<h1>Welcome to ClinicCases 7!</h1>

		<br /><br />

		<p>Thanks for trying out ClinicCases.  Let's get started with the install.</p>

		<?php
			//check php version number
			//check ability to change file permissions
			//check is_writeable on people, uploads
			//add form to do config values
			//run sql

		if (strnatcmp(phpversion(),'5.2') >= 0)
	    {
	        echo "<p class='good'>Your php version is " . phpversion() . ". Good.</p>";
	    }
	    else
	    {
	        die("<p class='config_error'>Your php version is " . phpversion() . ".  ClinicCases requires at least php 5.2.  Sorry, but you will have to upgrade php before proceeding with the install.</p>");
	    }

	    //Change directory permissions
	    if (!is_writable('../people'))
	    {
		    $people = @chmod('../people/', 0777);
		    if ($people === false)
		    {
		    	die("<p class='config_error'>I was unable to change the permissions on the 'people' directory.  This is usually because the ClinicCases files you just added are not owned by the same user that owns the server (on Apache, usually 'www-data'). The command to fix this might be something like:</p> <pre>cd /YOURPATH/cliniccases</pre><pre>sudo chown -R www-data:www-data .*</pre><p class='config_error'>Please fix this to proceed.</p>");
		    }
		}

	    if (!is_writable('../uploads'))
	    {
		    $uploads = @chmod('../uploads/', 0777);
		    if ($uploads === false)
		    {
		    	die("<p class='config_error'>I was unable to change the permissions on the 'uploads' directory. This is usually because the ClinicCases files you just added are not owned by the same user that owns the server (on Apache, usually 'www-data'). The command to fix this might be something like:</p><pre>cd /YOURPATH/cliniccases</pre> <pre>sudo chown -R www-data:www-data .*</pre><p class='config_error'>Please fix this to proceed.</p> ");
		    }
		}

		echo "<p class='good'>People and Uploads directories are writable.  Good.</p>";

		if (!is_writable('../_CONFIG.php'))
	    {
		    $uploads = @chmod('../CONFIG.php', 0777);
		    if ($uploads === false)
		    {
		    	die("<p class='config_error'>I was unable to change the permissions on the _CONFIG.php file.  Please do this manually (chmod 777 _CONFIG.php).");

		    	//must be returned to 664 at end of script
		    }
		}

		echo "<p class='good'>The config file is writable.  Good.</p>";

		?>

		<p>Please provide the configuration information (hover your mouse over each field for more information).  All fields are required.</p>

		<p id="error_display"></p>

		<form name="config_form">

			<p><label>Host</label><input type="text" name="db_host" value="localhost" title="The address of your mysql server.  Usually localhost"></p>

			<p><label>Database Username</label><input type="text" name="db_user" value="" title="Your database username"></p>

			<p><label>Database Password</label><input type="text" name="db_pass" value="" title="Your database password"></p>

			<p><label>Database Name</label><input type="text" name="db_name" value="" title="The name of the database you are using for ClinicCases"></p>

			<p><label>Password Salt</label><input type="text" name="salt" value="<?php echo uniqid();?>" title="Salt used to secure passwords. The default value should be fine."></p>

			<p><label>Path to ClinicCases</label><input type="text" name="cc_path" value="/var/www/cliniccases" title="The full path to ClinicCases on your server"></p>

			<p><label>Path to Documents</label><input type="text" name="doc_path" value="/var/cc_docs" title="You must create a directory outside of the web root to store uploaded documents in.  This directory must be writable."></p>

			<p><label>Base URL</label><input type="text" name="base_url" value="https://yourserver.com/cliniccases/" title="The url to your installation of ClinicCases.  Do not forget the trailing slash!"></p>

			<p><label>Domain</label><input type="text" name="domain" value="https://yourserver.com" title="The domain of your web server.  No trailing slash"></p>

			<p><label>Admin Email</label><input type="text" name="admin_email" value="" title="The email address of the person who will answer questions about ClinicCases on a day-to-day basis.  Usually your clinic's administrator"></p>

			<p><label>Default Email</label><input type="text" name="default_email" value="no-reply@yourserver.com" title="The address that system emails will come from"></p>

			<p><label>Your Program Name</label><input type="text" name="program_name" value="Your Law Clinic's Name" title="The name of your clinic or organization"></p>

			<p><label>Your School Color</label><input type="text" name="school_color" value="#FFFFFF" title="An rgb or hex value"></p>

			<p><label>Timekeeping Unit</label>
				<select name="t_unit" title="The unit of time used for timekeeping on cases.  5 is the default">
					<option value="5" selected=selected>5</option>
					<option value="6">6</option>
				</select>

			</p>
			<p><label>Max File Upload (MB)</label><input type="text" name="max_upload" value="10" title="The maximum file size (in MB) that can be uploaded by users to your server. Note that the php.ini settings post_max_size and upload_max_filesize should be set to at least this value."></p>

			<p><label>Case Number Mask</label><input type="text" name="mask" value="YYYY-Number" title="Default case number format. Possible values: YYYY or YY for four digit or two digit year;ClinicType (derived from cm_clinic_type table) or CaseType (derived from cm_case_types table);Number or NumberInfinite - Number resets to one at the beginning of each year, NumberInifinite does not.Your mask must have at least a year value and Number/NumberInfinite, seperated by dash"></p>

			<button class="config_submit">Submit</button>

		</form>

		<div id="upshot">

		</div>


	</div>

</body>

</html>
