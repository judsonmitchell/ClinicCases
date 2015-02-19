<?php
// SETS SYSTEM-WIDE CONFIG PREFERNENCES

//Information to access your database
define("CC_DBHOST","%db_host%");
define("CC_DBUSERNAME","%db_user%");
define("CC_DBPASSWD", "%db_pass%");
define("CC_DATABASE_NAME","%db_name%");

//Define a salt for passwords.
//You can use php's uniqid function to generate this, e.g from the command line:
//php -r 'echo uniqid();'
define("CC_SALT","%salt%");

//Full path to ClinicCases on your server, e.g. "/var/www/clinicases"
define("CC_PATH","%cc_path%");

//Path of directory which will store uploaded documents.  For security purposes,
//this directory should be out of the webroot.  Ensure that the directory
//is writable.  E.g "/var/cc_docs" or "/home/you/private/cc_docs"
define("CC_DOC_PATH","%doc_path%");

//Url where your ClinicCases installation will be run,
//e.g. http://www.yourservername.com/yourdirectory/ .  Don't forget trailing slash!
define("CC_BASE_URL","%base_url%");

//Your domain , e.g. http://www.yourserver.com
define("CC_DOMAIN","%domain%");

//Email address for the Administrator who will deal with user questions
define("CC_ADMIN_EMAIL","%admin_email%");

//Your Program Name, e.g. "Loyola Law Clinic"
define("CC_PROGRAM_NAME","%program_name%");

//Minimum timekeeping unit.  Default is 5 minutes.  Some prefer 6
define("CC_TIME_UNIT","%t_unit%");

//Maximum file upload size in MB.  Note that the php.ini settings post_max_size
//and upload_max_filesize should be set to at least this value.  Default 10MB.
define("MAX_FILE_UPLOAD","%max_upload%");

//Optional: Your School Color (used for program name); rgb or hex value.
define("CC_SCHOOL_COLOR","%school_color%");

//Allowed file types for upload
define("ALLOWED_FILE_TYPES", serialize(array('doc','docx','odt','rtf','txt','wpd','xls','ods','csv','mp3','wav','ogg','aif','aiff','mpeg','avi','mp4','mpg','mov','qt','ovg','webm','ogv','flv','bmp','jpg','jpeg','gif','png','svg','tif','tiff','zip','tar','gz','bz','pdf')));

//Define constants for sending email notifications
define("CC_EMAIL_FROM","%default_email%");  //e.g. no-reply@yourserver.com

define("CC_EMAIL_HEADERS","From: " . CC_EMAIL_FROM . "\n" . "Reply-To: " . CC_EMAIL_FROM . "\n" . "X-Mailer: PHP/" . phpversion());

define("CC_EMAIL_FOOTER","Please log on ClinicCases at " . CC_BASE_URL . " to view the entire message");

//Define case number mask
        //Possible values:
        //YYYY or YY for four digit or two digit year
        //ClinicType (derived from cm_clinic_type table) or CaseType (derived from cm_case_types table)
        //Number or NumberInfinite - Number resets to one at the beginning of each year; NumberInifinite
        //does not.
        //
        //Your mask must have at least a year value and Number/NumberInfinite, seperated by dash
        //Default YYYY-Number
define("CC_CASE_NUMBER_MASK","%mask%");

//ReCaptcha is used to prevent spam from the newaccounts/index.php form.  It strongly
//recommended, but not required, to use this feature.  If you need ReCaptcha keys,
//please get them at http://recaptcha.net
define("RECAPTCHA_PUBLIC_KEY","%recaptcha_public%");
define("RECAPTCHA_PRIVATE_KEY","%recaptcha_private%");

//Nothing further needs to be changed.
include('version.php');
define("CC_VERSION",$version);

//Magic Quotes:  CC expects magic quotes to be turned off.  This fixes that if they are not.

if ( in_array( strtolower( ini_get( 'magic_quotes_gpc' ) ), array( '1', 'on' ) ) )
{
    $_POST = array_map( 'stripslashes', $_POST );
    $_GET = array_map( 'stripslashes', $_GET );
    $_COOKIE = array_map( 'stripslashes', $_COOKIE );
}

//Error reporting.

//ini_set("error_reporting", "true");
//error_reporting(E_ALL|E_STRCT);
error_reporting(0);
