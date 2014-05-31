<?php

/**
 * Helper to assist in debugging
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
function debug($param, $name = NULL)
{
    if ( is_array($param) || is_object($param) )
    {
        echo '<pre>';
        echo $name.': ';
        print_r($param);
        echo '</pre>';
    }
    else
    {
        echo $name.': ';
        var_dump($param);
        echo '<br />';
    }
}