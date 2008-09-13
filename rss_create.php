<?php
include 'db.php';
include 'get_name.php';
include 'date_format_with_time.php';
function createRSS()
{
$get_journals = mysql_query("SELECT * FROM `cm_journals` ORDER BY `date_added` ASC");
$body="<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
<rss version=\"2.0\"> 
<channel> 
	<title>Student Journals - ClinicCases</title> 
	<link>http://www.cliniccases.com</link> 
	<description>Notification of new student journal entries on ClinicCases</description> 
	<copyright>(c) 2007, threepipeproblem.com, All rights reserved.</copyright> 
";
while($r = mysql_fetch_array($get_journals)) { 
list($fname,$lname) = getNameAsVar($r[username]);
list($date_correct) = formatDateAsVar($r[date_added]);
$body .="
			<item>
				<title>$fname $lname - $date_correct</title>
				
		<link>http://www.cliniccases.com</link> 
				<description> <![CDATA[ $fname $lname submitted a journal on $date_correct. ]]> </description>				
				
				<pubDate>$r[date_added]</pubDate>
			</item>";
}


$body .="
</channel>
</rss>";
$path="journal_rss.xml";
	$filenum=fopen($path,"w");
	fwrite($filenum,$body);
	fclose($filenum);
	
/* End the RSS Feed */

}

createRSS();
























?>
