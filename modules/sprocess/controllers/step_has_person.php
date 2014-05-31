<?php

/**
 * Step_has_person controller
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
include_once APPPATH . 'modules/base/controllers/crud_controller.php';

/**
 * Step_has_person class
 *
 * Class to implements the actions to interact with the Step_has_person entity
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
class Step_has_person extends CRUD_Controller {

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