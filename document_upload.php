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

//Set the maximum allowed file size, in bytes
$allowed_size = "20971520";

//  Usage : size_hum_read(filesize($file));
if($_POST){
        $case_id = $_POST['case_id'];
        $folder = $_POST['folder'];
        if (isset($folder))
        {
        $chosen_folder = $folder;
        }
        else
        {$chosen_folder = '';}





                if ($_FILES['docfile']['size'] < $allowed_size)
                {
					shell_exec('chmod 777 docs');
					
                $now = date("YmdHis");
                $unixtime = time();
                $newfilename = $_FILES['docfile']['name'];
                $filename_alias = "$unixtime" . "-" . $_FILES['docfile']['name'];
                $old="docs/".$_FILES['docfile']['name'];
                $new= "docs/$filename_alias";
                copy ($_FILES['docfile']['tmp_name'], "docs/" . $_FILES['docfile']['name'])
                    or die ("Could not copy");

                rename($old,$new);
                $thesize=size_hum_read(filesize($new));

                $result=MYSQL_QUERY("INSERT INTO `cm_documents` (`id` ,`name` ,`url`,`folder`,`username` , `case_id`,`date_modified`)VALUES (NULL , '$newfilename','$new','$chosen_folder','$_SESSION[login]','$case_id',NULL)");
                 if (!connection)
  {
  shell_exec('chmod 644 docs');
  die(mysql_error());
  }
				 shell_exec('chmod 644 docs');
                 echo "Document Uploaded";

                }
                    else
                            {
                            echo "Error : The file size, " .  size_hum_read($_FILES['docfile']['size']) . ", is too large. " . size_hum_read($allowed_size) . " is max.";
                            }



                }

                else
{echo "Sorry there was an error."; }
?>
