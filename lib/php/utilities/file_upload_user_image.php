<?php
session_start();
require('../auth/session_check.php');
require('../../../db.php');
require('resize.image.class.php');

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
        $filename = strtolower($pathinfo['filename']);

        if (isset($pathinfo['extension']))
        {$ext = strtolower($pathinfo['extension']);}
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


if (isset($_GET['preview'])) //user needs to review and crop image
    {
        //$allowed_file_types is set in the config file
        $allowedExtensions = unserialize(ALLOWED_FILE_TYPES);

        $sizeLimit = MAX_FILE_UPLOAD * 1024 * 1024;

        $uploader = new qqFileUploader($allowedExtensions, $sizeLimit);

        $result = $uploader->handleUpload(CC_PATH . '/uploads/');


        if (array_key_exists("error", $result))  //upload fails
            {echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);die;}
        else
        {
            $img = 'uploads/' . $result['file'] . '.' . $result['ext'];

            $img_path = CC_PATH . '/' . $img;

            //scale the picture if height is more than 400px
            $img_info = getimagesize($img_path);

            //The goal here is to get a preview image that is no more than 400px
            //in height and width.  God help us.
            if ($img_info[0] > 400 || $img_info[1] > 400)
            {
                $image = new Resize_Image;

                if ($img_info[0] > 400  && $img_info[1] > 400) //width or height
                    {
                        if ($img_info[0] > $img_info[1])
                        {
                            $image->new_width = 400;
                        }
                        elseif ($img_info[0] < $img_info[1])
                        {
                            $image->new_height = 400;
                        }
                        else
                        {
                            $image->new_width = 400;
                        }

                        $image->ratio = true;
                    }
                elseif ($img_info[0] > 400 && $img_info[1] < 400)
                    {
                        $image->new_width = 400;

                        $image->ratio = true;
                    }

                elseif ($img_info[0] < 400 && $img_info[1] > 400)
                    {
                        $image->new_height = 400;

                        $image->ratio = true;
                    }

                    $image->source_x = 0;

                    $image->source_y = 0;

                    $image->dest_x = 0;

                    $image->dest_y = 0;

                    $image->image_to_resize = $img_path;

                    $image->new_image_name = $result['file'] ;

                    $image->save_folder = '../../../uploads/';

                    $process = $image->resize();

                    if($process['result'] && $image->save_folder)
                        {
                            $new = 'uploads/' . $process['new_name'];

                            $return = array('success'=> true,'img'=> $new);

                            echo json_encode($return);
                        }
            }
            elseif ($img_info[0] < 128 || $img_info[1] < 128)
                {
                    $return = array('error' => 'This image is too small. The image must be at least 128 x 128 pixels');

                    echo json_encode($return);die;
                }
            else
                {
                    $return = array('success'=>true,'img' => $img);
                    echo json_encode($return);
                }

        }
    }
elseif (isset($_POST['cancel']))
    {
        foreach ($_POST['del'] as $value)
            {
                unlink("../../../uploads/$value");
            }
    }
else //user has cropped and submited the image
    {

        $image = new Resize_Image;

        $image->new_width = 128;

        $image->new_height = 128;

        $image->width = $_POST['w'];

        $image->height = $_POST['h'];

        $image->crop = true;

        $image->source_x = $_POST['x'];

        $image->source_y = $_POST['y'];

        $image->dest_x = 0;

        $image->dest_y = 0;

        $image->image_to_resize = CC_PATH . '/uploads/' . $_POST['img'];

        $image->ratio = false;

        $image->new_image_name = $_POST['id'];

        $image->save_folder = '../../../people/';

        $process = $image->resize();

        if($process['result'] && $image->save_folder)
            {

                foreach ($_POST['del'] as $value) {
                    unlink("../../../uploads/$value");
                }

                $new = 'people/' . $process['new_name'];

                //update db
                $q = $dbh->prepare("UPDATE cm_users SET picture_url = :url WHERE id = :id");

                $data = array('url' => $new, 'id' => $_POST['id']);

                $q->execute($data);

                //now make the thumbnail
                $thumb = new Resize_Image;

                $thumb->new_width = 32;

                $thumb->source_x = 0;

                $thumb->source_y = 0;

                $thumb->dest_x = 0;

                $thumb->dest_y = 0;

                $thumb->image_to_resize = CC_PATH . '/' . $new;

                $thumb->ratio = true;

                $thumb->new_image_name = 'tn_' . $_POST['id'] ;

                $thumb->save_folder = '../../../people/';

                $process = $thumb->resize();

                if($process['result'] && $thumb->save_folder)
                {

                    $return = array('success'=> true,'img'=> $new);

                    echo json_encode($return);

                }
            }

    }
