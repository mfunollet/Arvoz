<?php

/**
 * Step_type controller
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
include_once APPPATH . 'modules/base/controllers/crud_controller.php';

/**
 * Step_type class
 *
 * Class to implements the actions to interact with the Step_type entity
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
class Step_type extends CRUD_Controller {

    /**
     * The construct of this class to call the construct of the class 
     * CRUD_Controller and load any models, helpers, librarys and etc.
     *
     */
    function __construct()
    {
        parent::__construct();
    }

    function _set_create_rules()
    {
        $this->form_validation->set_rules('name', lang('name'), 'required');
        $this->form_validation->set_rules('has_document', lang('has_cument'), '');
        parent::_set_create_rules();
    }
    
    function _set_extra_edit_data()
    {
        $this->form_validation->set_rules('name', lang('name'), 'required');
        $this->form_validation->set_rules('has_document', lang('has_cument'), '');
        parent::_set_extra_edit_data();
    }
}