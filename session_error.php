<?php
if (!$_SESSION)
{
echo <<<WARN
<div style="position:absolute;z-index:1000;width:300px;height:300px;left:300px;top:2px;background-color:white;color:red;border:3px ridge #bbf;"><div style="width:100%;height:40px;background-color:red;color:white;font-weight:bold;">Alert!</div><br><br><p>Sorry, but your session has expired due to two hours of inactivity. </span></p><br><p> Please <a href="index.php?login_error=3" target="_top" alt="Log in again" title="Log in again">log in again</a> to get back to work.</p></div>
WARN;
die;

}









?>
