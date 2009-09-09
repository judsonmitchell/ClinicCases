<?php
/*
 *      create_private_urls.php
 *
 *      Copyright 2007 - 2009 Judson Mitchell <judsonmitchell <at> gmail.com>
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


include_once '../db.php';
include '../classes/url_key_generator.php';
//Update existing ClinicCases 3 db with private urls
//You must add private_key field to cm_users before runninng this script



$get_rows = mysql_query("SELECT `username`,`private_key` FROM `cm_users`");
	while ($r = mysql_fetch_array($get_rows))
	{

		$key = alphanumericPass();
		$insert_key = mysql_query("UPDATE `cm_users` SET `private_key` = '$key' WHERE `username` = '$r[username]'");


	}

	if (mysql_errno($connection))
		{echo "There was a problem.  Make sure you have updated the cm_users table with the private_key field.  Mysql says: " . mysql_error($connection);}
		else
		{
	echo "Users db updated with private keys.";
	}
