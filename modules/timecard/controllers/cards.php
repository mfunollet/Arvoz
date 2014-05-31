<?php

include_once(APPPATH . 'modules/base/controllers/crud_controller.php');

class Cards extends CRUD_Controller {

    function __construct() {
        parent::__construct();
    }
/*
    function index() {
        redirect('sites/show');
    }

    function _form($id = NULL) {
        $this->element->removed_fields = array('image');
        parent::_form($id);
    }

    function edit($id = NULL) {
        $this->redirect_url = site_url($this->ctrlr_name.'/edit/'.$id);
        parent::edit($id);
    }

    function show($page = 1)
    {
        if ( $this->authentication->is_incorporated() ) {
            $this->element->where_related($this->logged_company);
            $this->element->where('person_id IS NULL');
        } elseif ($this->logged_user) {
            $this->element->where_related($this->logged_user);
            $this->element->where('company_id IS NULL');
        } else {
            show_404();
        }
        parent::show($page);
    }*/

}
