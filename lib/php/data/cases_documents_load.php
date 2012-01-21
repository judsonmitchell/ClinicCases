<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');

$id = $_POST['id'];
//$id ='1175';

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

	elseif (in_array($type, array('mpeg','avi','mp4','mpg','mov','qt','ovg')))
	{return "html/ico/video.png";}

	elseif (in_array($type, array('bmp','jpg','jpeg','gif','png','svg','tif','tiff')))
	{return "html/ico/image.png";}

	elseif (in_array($type, array('zip','tar','gz','bz')))
	{return "html/ico/zip.png";}

	else {return "html/ico/other.png";}

}

//get document folders for this case and return array

$folder_query = $dbh->prepare("SELECT DISTINCT folder FROM cm_documents WHERE folder != '' AND case_id = :id");

$folder_query->bindParam(':id',$id);

$folder_query->execute();

$folders = $folder_query->fetchAll(PDO::FETCH_ASSOC);

//print_r($folders);

//get all documents not inside a folder

$documents_query = $dbh->prepare("SELECT * FROM cm_documents WHERE case_id = :id and folder = ''");

$documents_query->bindParam(':id',$id);

$documents_query->execute();

$documents = $documents_query->fetchAll(PDO::FETCH_ASSOC);

//append the file type to each document array element.  Used to determine icon
function append_file_type(&$value,$key)
{
	$parts = explode('.', $value['url']);
	$file_type = end($parts);
	$value['type'] = $file_type;
}

array_walk($documents, 'append_file_type');

include('../../../html/templates/interior/cases_documents.php');
