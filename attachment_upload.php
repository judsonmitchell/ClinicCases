<?php
session_start();
if(!$_SESSION["login"] ) {
echo "You are not logged in.";
die;}

include 'db.php';

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

//Set the maximum allowed file size, in bytes.  Suggested change:  also poll php.ini memory_limit, post_max_size , and upload_max_filesize using parse_ini_file() to get true upload limit
$allowed_size = "20971520";

//  Usage : size_hum_read(filesize($file));
if($_POST){
        $post_id = $_POST['post_id'];






                if ($_FILES['new_post']['size'] < $allowed_size)
                {
                //$now = date("YmdHis");
                //$unixtime = time();
                //$newfilename = $_FILES['new_post']['name'];
                $rand = rand();
                $filename = $_FILES['new_post']['name'];
                $newfilename= "docs/" . $_FILES['new_post']['name'];

                	//here we test if the file name already exists; if so, rename
                	if (file_exists($newfilename))
						{
							die("Error: A file with the same name aleady exists on the system.  Please rename your file.");


						}



				//$new= "docs/" . $old;
                copy ($_FILES['new_post']['tmp_name'], $newfilename)
                    or die ("Could not copy");

                //rename($old,$new);
                $thesize=size_hum_read(filesize($new));

                $result=MYSQL_QUERY("UPDATE `cm_board` SET `attachment` = CONCAT_WS('|',attachment,'$filename') WHERE `id` = '$post_id' LIMIT 1");

                 echo "Attachment Uploaded";

                }
                    else
                            {
                            echo "Error : The file size, " .  size_hum_read($_FILES['new_post']['size']) . ", is too large. " . size_hum_read($allowed_size) . " is max.";
                            }



                }

                else
{echo "Sorry there was an error."; }
?>
