<?php
@session_start();
require_once dirname(__FILE__) . '/../../../db.php';
require(CC_PATH . '/lib/php/auth/session_check.php');
include_once(CC_PATH . '/lib/php/utilities/names.php');
include_once(CC_PATH . '/lib/php/utilities/convert_times.php');
include_once(CC_PATH . '/lib/php/utilities/format_text.php');

if (isset($_REQUEST['id'])) {
    $case_id = $_REQUEST['id'];
}

if (isset($_REQUEST['container'])) {
    if ($_SESSION['mobile']){
        $container = preserve_slashes(rawurlencode($_REQUEST['container']));
    } else {
        $container = $_REQUEST['container'];
    }
}

if (isset($_REQUEST['path'])) {
    if ($_SESSION['mobile']){
        $path = preserve_slashes(rawurlencode($_REQUEST['path']));
    } else {
        $path = $_REQUEST['path'];
    }
}

if (isset($_REQUEST['update'])) {
    $update = $_REQUEST['update'];
}

if (isset($_REQUEST['search'])) {
    $search =  $_REQUEST['search'];
    $search_wildcard = "%" . $search . "%";
}

if (isset($_REQUEST['list_view']) || $_COOKIE['cc_doc_view'] == 'list') {
    $list_view = "yes";
}


//append the file type to each document array element.  Used to determine icon
function append_file_type(&$value,$key)
{
	if (stristr($value['local_file_name'], 'http://') || stristr($value['local_file_name'], 'https://') || stristr($value['local_file_name'], 'ftp://'))
	{
		$file_type = 'url';
	}
	else
	{
		$parts = explode('.', $value['local_file_name']);
		$file_type = strtolower(end($parts));
	}

	$value['type'] = $file_type;

}

//return appropriate icon for file type
function get_icon($type)
{

	if (in_array($type, array('doc','docx','odt','rtf','txt','wpd')))
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

	elseif (in_array($type, array('ccd')))
	{return "html/ico/text-richtext.png";}

	else {return "html/ico/other.png";}

}

//get document folders for this case and return array

if (isset($container)) //Indicates this is a sub-folder
{
	$sql = "SELECT * FROM cm_documents WHERE containing_folder LIKE :container AND local_file_name = '' AND case_id = :id";
}
else if (isset($search))
{
	$sql = "SELECT * FROM cm_documents WHERE folder LIKE :search  AND local_file_name='' AND case_id = :id";
}
else //Is in the root directory.  Empty local_file_name indicates that this is a folder, not a document
{
	$sql = "SELECT * FROM cm_documents WHERE folder != '' AND local_file_name='' AND containing_folder = '' AND case_id = :id";
}

$folder_query = $dbh->prepare($sql);

$folder_query->bindParam(':id',$case_id);

if (isset($container))
{
	$folder_query->bindParam(':container',$container);
}

if (isset($search))
{
	$folder_query->bindParam(':search',$search_wildcard);
}

$folder_query->execute();

$folders = $folder_query->fetchAll(PDO::FETCH_ASSOC);
//get all documents not inside a folder

if (isset($path))
{
	$sql = "SELECT * FROM cm_documents WHERE case_id = :id and local_file_name !='' and folder = :path";
}
else if (isset($search))
{
    $sql = "SELECT * FROM cm_documents where name like :search and case_id = :id";
}
else
{

	$sql = "SELECT * FROM cm_documents WHERE case_id = :id and folder = ''";
}

$documents_query = $dbh->prepare($sql);

$documents_query->bindParam(':id',$case_id);

if (isset($path)) {
    $documents_query->bindParam(':path',$path);
}

if (isset($search)) {
    $documents_query->bindParam(':search',$search_wildcard);
}

$documents_query->execute();

$documents = $documents_query->fetchAll(PDO::FETCH_ASSOC);

array_walk($documents, 'append_file_type');

if (isset($search) || isset($list_view)){
    include('../../../html/templates/interior/cases_documents_list.php');
} else if ( !$_SESSION['mobile']){
    include('../../../html/templates/interior/cases_documents.php');
}
