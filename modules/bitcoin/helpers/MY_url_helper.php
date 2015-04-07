<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// ------------------------------------------------------------------------

/**
* Create URL Title
*
* Takes a "title" string as input and creates a
* human-friendly URL string with either a dash
* or an underscore as the word separator.
*
* @access    public
* @param    string    the string
* @param    string    the separator: dash, or underscore
* @return    string
*/
if ( ! function_exists('url_title'))
{
	function url_title($str, $separator = 'dash', $lowercase = FALSE)
	{
		if ($separator == 'dash')
		{
			$search		= '_';
			$replace	= '-';
		}
		else
		{
			$search		= '-';
			$replace	= '_';
		}
		$pt_br = 'À-ÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr';
		$permitted_uri_chars = 'a-z 0-9~%.:_-'.$pt_br;

		$trans = array(
						'&\#\d+?;'				=> '',
						'&\S+?;'				=> '',
						'\s+'					=> $replace,
				'[^'.$permitted_uri_chars.']'	=> '',
				        '\.'					=> $replace, // dot turn - or _
				        $search					=> $replace, // replace space for  - or _
						$replace.'+'			=> $replace,
						$replace.'$'			=> $replace,
						'^'.$replace			=> $replace,
						'\.+$'					=> ''
					  );

		$str = strip_tags($str);

		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		if ($lowercase === TRUE)
		{
			$str = tolower($str);
		}
		
		return trim(stripslashes($str));
	}
}

if ( ! function_exists('remove_dash'))
{
	function remove_dash($str)
	{
		$search		= '-';
		$replace	= ' ';

		$trans = array(
				        $search					=> $replace, // replace space for  - or _
					  );

		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#i", $val, $str);
		}

		return $str;
	}
}

/* End of file url_helper.php */
/* Location: ./system/url_helper.php */
?>
