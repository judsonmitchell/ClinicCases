<?php
//function to return the correct url for the user thumbnail

function thumbify($url)
	{
			$split = explode('/', $url);
			$thumbnail = $split[0] 	. "/tn_" . $split[1];
			return $thumbnail;
	}
