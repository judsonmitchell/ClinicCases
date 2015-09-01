<?php
//Functions that deal with formatting text

//Return a snippet
function snippet($number_of_words,$txt, $noescape = NULL)
{
	$parts = explode(' ',$txt);

	$snippet = array_slice($parts,0,$number_of_words);

	if (count($parts) > $number_of_words)
		{

			$snippet_string = implode(' ', $snippet) . "...";

		}
		else
		{

		$snippet_string = implode(' ', $snippet);

		}

    if ($noescape){
        return $snippet_string;
    } else {
        return htmlspecialchars($snippet_string,ENT_QUOTES,'UTF-8');
    }
}

//Function to deal with legacy text from cc6 where addslashes used to escape text, etc
function text_prepare($str)
{
	$str_a = stripslashes($str);
	$str_nl = preg_replace('#<br\s*/?>#i', "\n", $str_a);
	return $str_nl;
}


//I made some extremely bad decisions in dealing with documents and it's too late
//to fix them now.  So, this preserves the slashes in the document path after a
//rawurlencode has been called - makes rawurlencode mimic js's escape.  Geez.
function preserve_slashes($val){

    return str_replace('%2F','/',$val);

}
