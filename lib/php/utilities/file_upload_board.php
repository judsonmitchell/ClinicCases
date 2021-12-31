<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');

$username = $_SESSION['login'];

if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];
}


/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize()){
            return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader {
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = array(), $sizeLimit = 10485760){
        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;

        $this->checkServerSettings();

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false;
        }
    }

    private function checkServerSettings(){
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
        }
    }

    private function toBytes($str){
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;
        }
        return $val;
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $replaceOldFile = FALSE){
        if (!is_writable($uploadDirectory)){
            return array('error' => "Server error. Upload directory isn't writable.");
        }

        if (!$this->file){
            return array('error' => 'No files were uploaded.');
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return array('error' => 'File is empty');
        }

        if ($size > $this->sizeLimit) {
            return array('error' => 'File exceeds the mamxiumum file size of ' . MAX_FILE_UPLOAD . 'M');
        }

        $pathinfo = pathinfo($this->file->getName());
        $filename = $pathinfo['filename'];

        if (isset($pathinfo['extension']))
        {$ext = $pathinfo['extension'];}
        else
        {$ext = '';}

        if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array('error' => 'This file type is not permitted.  Ask your administrator to add this file type.');
        }

        if(!$replaceOldFile){
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }

        if ($this->file->save($uploadDirectory . $filename . '.' . $ext)){
            return array('success'=>true,'file'=>$filename,'ext'=>$ext);
        } else {
            return array('error'=> 'Could not save uploaded file.' .
                'The upload was cancelled, or server error encountered');
        }

    }
}

//$allowed_file_types is set in the config file
$allowedExtensions = unserialize(ALLOWED_FILE_TYPES);

$sizeLimit = MAX_FILE_UPLOAD * 1024 * 1024;

$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);

$result = $uploader->handleUpload('../../../uploads/');

 if (array_key_exists("error", $result))  //upload fails
     {echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);die;}
 else
 {
    $upload_doc_query = $dbh->prepare("INSERT INTO cm_board_attachments (id, name, local_file_name, extension, username, post_id, time_added) VALUES (NULL, :name, '', :extension, :user, :post_id, CURRENT_TIMESTAMP);");

    $users_file_name = $result['file'] . "." . $result['ext'];

    $data = array('name' => $users_file_name, 'extension' => strtolower($result["ext"]), 'user' => $username, 'post_id' => $post_id);

    $upload_doc_query->execute($data);

    $error = $upload_doc_query->errorInfo();

    if ($error[1])
        {
            $result = array('error'=>$error[2]);
            echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
            die;
        };

    $doc_id = $dbh->lastInsertId();

    //now update the local_file_name field with the id and the extension

    $local_file_name = 'board_' . $doc_id . "." . $result['ext'];

    $update_name = $dbh->prepare("UPDATE cm_board_attachments SET local_file_name = '$local_file_name' WHERE id = '$doc_id'");

    $update_name->execute();

    if (!is_writable(CC_DOC_PATH))
        {
            $return = array('error' => 'Error: Documents directory is not writable');
            echo htmlspecialchars(json_encode($return), ENT_NOQUOTES);
        }
        else

        {
            rename(CC_PATH . "/uploads/" . $result['file'] . "." . $result['ext'], CC_DOC_PATH . "/" .  $local_file_name);
            $return = array('success'=>true);
            echo htmlspecialchars(json_encode($return), ENT_NOQUOTES);
        }

 }
