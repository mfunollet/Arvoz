<?php

/**
 * Event controller
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
include_once APPPATH . 'modules/base/controllers/crud_controller.php';

/**
 * Event class
 *
 * Class to implements the actions to interact with the Event entity
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
class Event extends CRUD_Controller {

    /**
     * The construct of this class to call the construct of the class 
     * CRUD_Controller and load any models, helpers, librarys and etc.
     *
     */
    function __construct()
    {
        parent::__construct();
    }

}