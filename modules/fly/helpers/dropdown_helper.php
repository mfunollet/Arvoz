<?php

/**
 * Helper to assist in creating dropdowns
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
if ( !defined('BASEPATH') )
    exit('No direct script access allowed');

/**
 * Function to print their values ​​for analysis
 *
 * @param  mixed  $param  data for print
 */
function array_to_dropdown($array, $key = 'id', $value = 'name')
{
    $new_array = array();

    foreach ( $array as $line )
    {
        $new_array[$line->$key] = $line->$value;
    }

    return $new_array;
}