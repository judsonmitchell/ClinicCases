<?php
/*
 *      message_star.php
 *      
 *      Copyright 2009 Judson Mitchell <judson@back-computer>
 *      
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *      
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *      
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */

session_start();
if (!$_SESSION)
{die('Error: You are not logged in');}
include 'db.php';


$check_star =  mysql_query("SELECT `id`,`starred` FROM `cm_messages` WHERE `id` = '$_POST[id]' LIMIT 1");
$b = mysql_fetch_object($check_star);

	if (stristr($b->starred, $_SESSION[login]))
		{
			//$get_current_starred = mysql_query("SELECT `id`,`starred` FROM `cm_messages` WHERE `id` = '$_POST[id]' LIMIT 1");
			//$a = mysql_fetch_object($get_current_star);
			$current_starred = $b->starred;
			$new_starred = str_replace($_SESSION[login] . ",","",$current_starred);
			$set_new_starred = mysql_query("UPDATE `cm_messages` SET `starred` = '$new_starred' WHERE `id` = '$_POST[id]'");
			echo "<img src='images/not_starred.png' border='0'>";
	
		}

			else
					

		{
			//$get_current_star = mysql_query("SELECT `id`,`starred` FROM `cm_messages` WHERE `id` = '$_POST[id]' LIMIT 1");
			//$a = mysql_fetch_object($get_current_star);
			$current_starred = $b->starred;
			$new_starred = $b->starred . "$_SESSION[login],";
			$set_new_archive = mysql_query("UPDATE `cm_messages` SET `starred` = '$new_starred' WHERE `id` = '$_POST[id]'");
			echo "<img src='images/starred.png' border='0'>";
		}








?>
