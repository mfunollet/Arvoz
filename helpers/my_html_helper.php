<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * CodeIgniter HTML Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/html_helper.html
 */
// ------------------------------------------------------------------------

/**
 * Image
 *
 * Generates an <img /> element
 *
 * @access	public
 * @param	mixed
 * @return	string
 */

//    function img($src = '', $index_page = FALSE) {
//        if (empty($src)) {
//            return '';
//        }
//        if (!is_array($src)) {
//            $src = array('src' => $src);
//        }
//
//        // If there is no alt attribute defined, set it to an empty string
//        if (!isset($src['alt'])) {
//            $src['alt'] = '';
//        }
//
//        $img = '<img';
//
//        foreach ($src as $k => $v) {
//
//            if ($k == 'src' AND strpos($v, '://') === FALSE) {
//                $CI = & get_instance();
//
//                if ($index_page === TRUE) {
//                    $img .= ' src="' . $CI->config->site_url($v) . '"';
//                } else {
//                    $img .= ' src="' . $CI->config->slash_item('base_url') . $v . '"';
//                }
//            } else {
//                $img .= " $k=\"$v\"";
//            }
//        }
//
//        $img .= '/>';
//
//        return $img;
//    }





/* End of file html_helper.php */
/* Location: ./system/helpers/html_helper.php */