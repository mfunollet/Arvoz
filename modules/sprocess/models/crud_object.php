<?php
/**
 * Base file to implement a model for a line of an entity.
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
include_once 'data_object.php';

class Crud_object extends Data_object {

    function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Function that implements the logic to see if a user can view this data.
     * 
     * @return boolean Returns if a user can view this data.
     */
    function can_view()
    {
        return TRUE;
    }
    
    /**
     * Function that implements the logic to see if a user can create this data.
     * 
     * @return boolean Returns if a user can create this data.
     */
    function can_create()
    {
        return TRUE;
    }
    
    /**
     * Function that implements the logic to see if a user can edit this data.
     * 
     * @return boolean Returns if a user can edit this data.
     */
    function can_edit()
    {
        return TRUE;
    }
    
    /**
     * Function that implements the logic to see if a user can delete this data.
     * 
     * @return boolean Returns if a user can delete this data.
     */
    function can_delete()
    {
        return TRUE;
    }

}
?>


