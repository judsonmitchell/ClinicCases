<?php
/*
------------------------------------------------------------------------------------
Credits: Bit Repository

Source URL: http://www.bitrepository.com/web-programming/php/resizing-an-image.html
------------------------------------------------------------------------------------
*/
class Resize_Image {

var $image_to_resize;
var $new_width;
var $new_height;
var $ratio;
var $new_image_name;
var $save_folder;
//Mitchell adds
var $crop;
var $source_x;
var $source_y;
var $dest_x;
var $dest_y;

function resize()
{
if(!file_exists($this->image_to_resize))
{
  exit("File ".$this->image_to_resize." does not exist.");
}

$info = GetImageSize($this->image_to_resize);

if(empty($info))
{
  exit("The file ".$this->image_to_resize." doesn't seem to be an image.");
}

if ($this->crop)
	{
		$width = $this->width;
		$height = $this->height;
	}
	else
	{
		$width = $info[0];
		$height = $info[1];
	}

$mime = $info['mime'];

/* Keep Aspect Ratio? */

if($this->ratio)
{
$thumb = ($this->new_width < $width && $this->new_height < $height) ? true : false; // Thumbnail
$bigger_image = ($this->new_width > $width || $this->new_height > $height) ? true : false; // Bigger Image

	if($thumb)
	{
	    if($this->new_width >= $this->new_height)
		{
		$x = ($width / $this->new_width);

		$this->new_height = ($height / $x);
		}
		else if($this->new_height >= $this->new_width)
		{
		$x = ($height / $this->new_height);

		$this->new_width = ($width / $x);
		}
	}
	else if($bigger_image)
	{
	    if($this->new_width >= $width)
		{
        $x = ($this->new_width / $width);

		$this->new_height = ($height * $x);
		}
		else if($this->new_height >= $height)
		{
		$x = ($this->new_height / $height);

		$this->new_width = ($width * $x);
		}
	}
}

// What sort of image?

$type = substr(strrchr($mime, '/'), 1);

switch ($type)
{
case 'jpeg':
    $image_create_func = 'ImageCreateFromJPEG';
    $image_save_func = 'ImageJPEG';
	$new_image_ext = 'jpg';
    break;

case 'png':
    $image_create_func = 'ImageCreateFromPNG';
    $image_save_func = 'ImagePNG';
	$new_image_ext = 'png';
    break;

case 'bmp':
    $image_create_func = 'ImageCreateFromBMP';
    $image_save_func = 'ImageBMP';
	$new_image_ext = 'bmp';
    break;

case 'gif':
    $image_create_func = 'ImageCreateFromGIF';
    $image_save_func = 'ImageGIF';
	$new_image_ext = 'gif';
    break;

default:
	$image_create_func = 'ImageCreateFromJPEG';
    $image_save_func = 'ImageJPEG';
	$new_image_ext = 'jpg';
}

	// New Image
	$image_c = ImageCreateTrueColor($this->new_width, $this->new_height);

	$new_image = $image_create_func($this->image_to_resize);

	ImageCopyResampled($image_c, $new_image, $this->dest_x, $this->dest_y, $this->source_x, $this->source_y, $this->new_width, $this->new_height, $width, $height);

        if($this->save_folder)
		{
	       if($this->new_image_name)
	       {
	       $new_name = $this->new_image_name.'.'.$new_image_ext;
	       }
	       else
	       {
	       $new_name = $this->new_thumb_name(basename($this->image_to_resize)).'_resized.'.$new_image_ext;
	       }

		$save_path = $this->save_folder.$new_name;
		}
		else
		{
		/* Show the image without saving it to a folder */
		   header("Content-Type: ".$mime);

	       $image_save_func($image_c);

		   $save_path = '';
		}

	    $process = $image_save_func($image_c, $save_path);

		return array('result' => $process, 'new_file_path' => $save_path,'new_name' =>$new_name);

	}

	function new_thumb_name($filename)
	{
	$string = trim($filename);
	$string = strtolower($string);
	$string = trim(ereg_replace("[^ A-Za-z0-9_]", " ", $string));
	$string = ereg_replace("[ \t\n\r]+", "_", $string);
	$string = str_replace(" ", '_', $string);
	$string = ereg_replace("[ _]+", "_", $string);

	return $string;
	}
}
?>