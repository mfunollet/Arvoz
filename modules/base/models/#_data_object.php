<?php

/**
 * Base file to implement a model for a line of an entity.
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */

/**
 * Class to represent a table row as an object
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
class Data_object {

    function __construct()
    {
        //parent::__construct();
    }

    function get_view_title()
    {
        $class = get_class($this);
        return substr($class, 0, strrpos($class, '_object'));
    }

}