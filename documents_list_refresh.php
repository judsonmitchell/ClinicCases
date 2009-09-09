<?php
session_start();
if (!$_SESSION)
{die('Error: You are not logged in');}

include 'db.php';
include_once './classes/format_dates_and_times.class.php';
include_once './classes/url_handler.php';


$id = $_GET[id];
$folder = $_GET[folder];
$get_docs= mysql_query("SELECT * FROM `cm_documents` WHERE `case_id` = '$id' AND `folder` = '$folder' AND `name` != '' ORDER BY `date_modified` DESC");
while ($line = mysql_fetch_array($get_docs, MYSQL_ASSOC)) {
    $i=0;
    foreach ($line as $col_value) {
        $field=mysql_field_name($get_docs,$i);
        $d[$field] = $col_value;
        $i++;


    }
    
    $prefix = substr($d[url], 0, 4);
    if ( $prefix == 'http')
    {
    	$icon = "images/url.png";
	}
		else
		{$icon = "images/doc.png";}
	
	$clean_url = safe_url($d[url]);
	
ECHO <<<SINGLEDOCS
<div id="$d[id]" style="width:350px;height:40px;overflow:hide;clear:both;"><img src="$icon" border="0" onLoad="new Draggable('$d[id]');this.style.cursor='move';" onMouseOver="Effect.Appear('doc_info_$d[id]');" onMouseOut="Effect.Fade('doc_info_$d[id]');"><a href="$clean_url" target="_new">$d[name]</a>

<div id="doc_info_$d[id]" style="position:absolute;right:0px;top:30px;width:250px;height:100px;display:none;background-color:rgb(255, 255, 204);border:1px solid black;font-size:10pt;z-index:10000;overflow:hidden;">Name: <b>$d[name]</b><br>Uploaded:<b> 
SINGLEDOCS;


formatDate($d[date_modified]);
ECHO <<<SINGLEDOCS
</b><br>By: <b>$d[username]</b></div> </div>
SINGLEDOCS;

}



?>
