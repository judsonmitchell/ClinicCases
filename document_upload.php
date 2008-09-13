<?php
include 'db.php';
session_start();

if(!$_SESSION["login"] ) {
echo "You are not logged in.";
die;}



function size_hum_read($size){
/*
Returns a human readable size
*/
  $i=0;
  $iec = array("B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB");
  while (($size/1024)>1) {
   $size=$size/1024;
   $i++;
  }
  return substr($size,0,strpos($size,'.')+4).$iec[$i];
}



//  Usage : size_hum_read(filesize($file));
if($_POST['Submit']){
$case_id = $_POST['case_id'];
$folder = $_POST['folder'];
if (isset($folder))
{
$chosen_folder = $folder;
}
else
{$chosen_folder = '';}

//If the Submitbutton was pressed do:
//Jady puts in a jpg check



if ($_FILES['docfile']['size'] < "10485760")
{ 
$now = date("YmdHis"); 
$unixtime = time(); 
$newfilename = $_FILES['docfile']['name'];
$filename_alias = "$unixtime" . "-" . $_FILES['docfile']['name'];
$old="docs/".$_FILES['docfile']['name'];
$new="docs/$filename_alias";
copy ($_FILES['docfile']['tmp_name'], "docs/" . $_FILES['docfile']['name']) 
    or die ("Could not copy"); 

rename($old,$new);
$thesize=size_hum_read(filesize($new));

$result=MYSQL_QUERY("INSERT INTO `cm_documents` (`id` ,`name` ,`url`,`folder`,`username` , `case_id`,`date_modified`)VALUES (NULL , '$newfilename', '$new','$chosen_folder','$_SESSION[login]','$case_id',NULL)");
 
 echo "
 

 Document Uploaded.<br><a href=\"upload_form.php?id=$case_id\">Upload Another</a>"; 

}
else {
            echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"cmstyle.css\">
<body style=\"background-color:white;\";><br><br>";
            echo "File size is too large. (".$_FILES['docfile']['name'].")<br />5Mb max.";

  echo"<a href=\"#\" onClick=\"history.back(-1)\">Go Back<a/>" ;
        } 
        }


else
echo "
<html>
<head><body>
$id
<form name=\"form1\" method=\"post\" action=\"image_upload.php\" enctype=\"multipart/form-data\">
<input type=\"file\" name=\"docfile\">
<br>
<input type='text' name='id' value='enter id'>
<input type=\"submit\" name=\"Submit\" value=\"Submit\"> </form></body></html>
";
?>
