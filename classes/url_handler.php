<?php
function safe_url($url)

{
if (substr($url,0,4) == 'http')
{return $url;}
else
{
$split = explode("/",$url);
$clean = addslashes($split[1]);
$encode = rawurlencode($clean);
$safe = $split[0] . "/" . $encode;
return $safe;
}




}



?>
