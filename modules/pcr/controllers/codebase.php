<?php

include_once(APPPATH . 'modules/base/controllers/base_controller.php');

class Codebase extends Base_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->authentication->is_signed_in())
        {
            $this->_add_menu_item('actions', '', array('people/login', 'Logar'));
            $this->_add_menu_item('actions', '', array('people/register', 'Registrar'));
            $this->_add_menu('top_right','actions');
        }
        
        $this->_add_menu_item('nav', 'Menu', array('people', lang('people')));
        $this->_add_menu_item('nav', 'Menu', array('companies', lang('companies')));        
    }

}
