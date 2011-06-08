<?php
// SETS SYSTEM-WIDE CONFIG PREFERNENCES

//Information to access your mysql database
$CC_dbhost = "localhost";
$CC_dbusername = "cc7";
$CC_dbpasswd = "cc7";
$CC_database_name = "cc_dev_live_copy";

//Email address for the adminstrator who will deal with student questions
$CC_admin_email = "jmitchel@loyno.edu";

//Url where your Cliniccases installation will be run, e.g. http://www.yourservername.com/yourdirectory/ .  Don't forget trailing slash!
$CC_base_url = "http://localhost/cc7/";

//Your domain , e.g. http://www.yourserver.com
$CC_domain = "http://localhost";

//Default email for ClinicCases notifications, e.g. no-reply@yourserver.co
$CC_default_email = "";

//Your Program Name, e.g. "Loyola Law Clinic"
$CC_program_name = "ClinicCases 7 Development";

//Optional: Your School Color (used for program name); rgb or hex value.
$CC_school_color = "#A52A2A";


//Error reporting.

ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);

?>
