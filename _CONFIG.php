<?php
// SETS SYSTEM-WIDE CONFIG PREFERNENCES

//Information to access your database
define("CC_DBHOST","localhost");
define("CC_DBUSERNAME","");
define("CC_DBPASSWD", "");
define("CC_DATABASE_NAME","");

//If sqlite, define path to db
//define("CC_SQLITE_PATH","my/database/path/database.db");

//Email address for the adminstrator who will deal with student questions
define("CC_ADMIN_EMAIL","");

//Url where your Cliniccases installation will be run, e.g. http://www.yourservername.com/yourdirectory/ .  Don't forget trailing slash!
define("CC_BASE_URL","");

//Your domain , e.g. http://www.yourserver.com
define("CC_DOMAIN","");

//Default email for ClinicCases notifications, e.g. no-reply@yourserver.co
define("CC_DEFAULT_EMAIL","");

//Your Program Name, e.g. "Loyola Law Clinic"
define("CC_PROGRAM_NAME","");

//Minimum timekeeping unit.  Default is 5 minutes.  Some prefer 6
define("CC_TIME_UNIT","5");

//Optional: Your School Color (used for program name); rgb or hex value.
define("CC_SCHOOL_COLOR","");

//Magic Quotes:  CC expects magic quotes to be turned off.  This fixes that if they are not.

if ( in_array( strtolower( ini_get( 'magic_quotes_gpc' ) ), array( '1', 'on' ) ) )
{
    $_POST = array_map( 'stripslashes', $_POST );
    $_GET = array_map( 'stripslashes', $_GET );
    $_COOKIE = array_map( 'stripslashes', $_COOKIE );
}

//Error reporting.

ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);

?>
