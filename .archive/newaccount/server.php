<?php
/**
 * CAPTCHA image server
 * 
 */  
require_once ( './class.captcha_x.php');
$server = &new captcha_x ();
$server->handle_request ();
?>
