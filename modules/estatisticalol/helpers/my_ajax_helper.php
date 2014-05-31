<?php 
/**
 * Helper for Ajax work
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */

/**
 * Indicate if the requisition is Ajax
 * 
 * @return boolean
 */
function isAjax()
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
} 