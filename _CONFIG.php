<?php
// SETS SYSTEM-WIDE CONFIG PREFERNENCES

//Information to access your database
define("CC_DBHOST","");
define("CC_DBUSERNAME","");
define("CC_DBPASSWD", "");
define("CC_DATABASE_NAME","");

//If sqlite, define path to db
//define("CC_SQLITE_PATH","my/database/path/database.db");

//Full path to ClinicCases on your server, e.g. "/var/www/clinicases"
define("CC_PATH","");

//Path of directory which will store uploaded documents.  For security purposes, this directory should not be out of the webroot.  Ensure that the directory has is writeable.  E.g "/var/cc_docs" or "/home/you/private/cc_docs"
define("CC_DOC_PATH","");

//Url where your Cliniccases installation will be run, e.g. http://www.yourservername.com/yourdirectory/ .  Don't forget trailing slash!
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
