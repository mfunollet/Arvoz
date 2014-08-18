<?php

/**
 * Function to print the reference of the script url in the view
 *
 * @param  array  $js_files  contains a list of filenames JS
 */
function a2_javascript($js_files) {

    foreach ($js_files as $js) {
    	if(substr($js, 0, 3) == 'htt'){
	        echo '<script src="' . $js . '" type="text/javascript"></script>';
    	}else{
        	echo '<script src="' . base_url() . 'cjs/' . $js . '" type="text/javascript"></script>';
    	}
    }
}

/**
 * Function to print the reference of the css url in the view
 *
 * @param  array  $js_files  contains a list of filenames CSS
 */
function a2_css($css_files) {

    foreach ($css_files as $css) {
        echo link_tag('cjs/' . $css);
    }
}
