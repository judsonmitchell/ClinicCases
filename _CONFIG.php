<?php
// SETS SYSTEM-WIDE CONFIG PREFERNENCES

//Information to access your database
define("CC_DBHOST","localhost");
define("CC_DBUSERNAME","");
define("CC_DBPASSWD", "");
define("CC_DATABASE_NAME","");

//Define a salt for passwords.
//You can use php's uniqid function to generate this, e.g from the command line:
//php -r 'echo uniqid();'
define("CC_SALT","");

//Full path to ClinicCases on your server, e.g. "/var/www/clinicases"
define("CC_PATH","");

//Path of directory which will store uploaded documents.  For security purposes, this directory should not be out of the webroot.  Ensure that the directory has is writeable.  E.g "/var/cc_docs" or "/home/you/private/cc_docs"
define("CC_DOC_PATH","");

//Url where your Cliniccases installation will be run, e.g.
//http://www.yourservername.com/yourdirectory/cliniccases/ .  Don't forget trailing slash!
define("CC_BASE_URL","");

//Your domain , e.g. http://www.yourserver.com
define("CC_DOMAIN","");

//Email address for the adminstrator who will deal with user questions
define("CC_ADMIN_EMAIL","");

//Default email for ClinicCases notifications, e.g. no-reply@yourserver.co
define("CC_DEFAULT_EMAIL","");

//Your Program Name, e.g. "Loyola Law Clinic"
define("CC_PROGRAM_NAME","");

//Minimum timekeeping unit.  Default is 5 minutes.  Some prefer 6
define("CC_TIME_UNIT","5");

//Maximum file upload size in MB.  Note that the php.ini settings post_max_size and upload_max_filesize
//should be set to at least this value.  Default 10MB.
define("MAX_FILE_UPLOAD","10");

//Optional: Your School Color (used for program name); rgb or hex value.
define("CC_SCHOOL_COLOR","");

//Allowed file types for upload
define("ALLOWED_FILE_TYPES", serialize(array('doc','docx','odt','rtf','txt','wpd','xls','ods','csv','mp3','wav','ogg','aif','aiff','mpeg','avi','mp4','mpg','mov','qt','ovg','webm','ogv','flv','bmp','jpg','jpeg','gif','png','svg','tif','tiff','zip','tar','gz','bz','pdf')));

//Define constants for sending email notifications
define("CC_EMAIL_FROM","no-reply@YOURSERVER.com");  //e.g. no-reply@yourserver.com
define("CC_EMAIL_HEADERS","From: " . CC_EMAIL_FROM . "\n" . "Reply-To: " . CC_EMAIL_FROM . "\n" . "X-Mailer: PHP/" . phpversion());
define("CC_EMAIL_FOOTER","Please log on ClinicCases at " . CC_BASE_URL . " to view the entire message");

//Define case number mask
	//Possible values:
	//YYYY or YY for four digit or two digit year
	//ClinicType (derived from cm_clinic_type table) or CaseType (derived from cm_case_types table)
	//Your mask must have at least a year value and Number, seperated by dash
define("CC_CASE_NUMBER_MASK","YYYY-Number-ClinicType");

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
