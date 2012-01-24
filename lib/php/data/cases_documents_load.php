<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
include('../utilities/names.php');
include('../utilities/convert_times.php');

$id = $_POST['id'];
//$id = '1525';
//$update = 'y';
if (isset($_POST['container']))
{$container = $_POST['container'];}

if (isset($_POST['path']))
{$path = $_POST['path'];}

if (isset($_POST['update']))
{$update = $_POST['update'];}
//$path = "Inventory";
//append the file type to each document array element.  Used to determine icon
function append_file_type(&$value,$key)
{
	if (stristr($value['url'], 'http://') || stristr($value['url'], 'https://') || stristr($value['url'], 'ftp://'))
	{
		$file_type = 'url';
	}
	else
	{
		$parts = explode('.', $value['url']);
		$file_type = strtolower(end($parts));
	}

	$value['type'] = $file_type;

}

//return appropriate icon for file type
function get_icon($type)
{

	if (in_array($type, array('doc','docx','odt','rtf','txt')))
	{return "html/ico/doc.png";}

	if (in_array($type, array('xls','ods','csv')))
	{return "html/ico/spreadsheet.png";}

	elseif (in_array($type, array('mp3','wav','ogg','aif','aiff')))
	{return "html/ico/audio.png";}

	elseif (in_array($type, array('pdf')))
	{return "html/ico/pdf.png";}

	elseif (in_array($type, array('mpeg','avi','mp4','mpg','mov','qt','ovg','webm','ogv','flv')))
	{return "html/ico/video.png";}

	elseif (in_array($type, array('bmp','jpg','jpeg','gif','png','svg','tif','tiff')))
	{return "html/ico/image.png";}

	elseif (in_array($type, array('zip','tar','gz','bz')))
	{return "html/ico/zip.png";}

	elseif (in_array($type, array('url')))
	{return "html/ico/url.png";}

	else {return "html/ico/other.png";}

}

// function remove_extension($doc)
// {
// 	$file = substr($doc, 0,strrpos($doc,'.'));
//     return $file;
// }

//get document folders for this case and return array

if (isset($container))
{
	$sql = "SELECT * FROM cm_documents WHERE containing_folder LIKE :container AND case_id = :id";
}
else
{
	$sql = "SELECT DISTINCT folder FROM cm_documents WHERE folder != '' AND url='' AND containing_folder = '' AND case_id = :id";
}

$folder_query = $dbh->prepare($sql);

$folder_query->bindParam(':id',$id);

if (isset($container))
{
	$container_mod = trim($container);
	$folder_query->bindParam(':container',$container_mod);
}

$folder_query->execute();

$folders = $folder_query->fetchAll(PDO::FETCH_ASSOC);

//print_r($folders);

//get all documents not inside a folder

if (isset($path))
{
	$sql = "SELECT * FROM cm_documents WHERE case_id = :id and url !='' and folder = :path";
}
else
{
	$sql = "SELECT * FROM cm_documents WHERE case_id = :id and folder = ''";
}

$documents_query = $dbh->prepare($sql);

$documents_query->bindParam(':id',$id);

if (isset($path))
{$documents_query->bindParam(':path',$path);}

$documents_query->execute();

$documents = $documents_query->fetchAll(PDO::FETCH_ASSOC);

array_walk($documents, 'append_file_type');

//print_r($documents);die;

include('../../../html/templates/interior/cases_documents.php');
