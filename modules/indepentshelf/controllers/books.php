<?php

include_once(APPPATH . 'modules/base/controllers/crud_controller.php');

class Books extends CRUD_Controller {

    function __construct() {
        parent::__construct();
    }

    function index() {
        $logged_user = $this->authentication->get_logged_user();
        echo $logged_user->people->first_name;
        parent::page();
    }

    function _form($id = NULL) {
        $this->element->removed_fields = array('image');
        parent::_form($id);
    }

    function edit($id = NULL) {
        $this->redirect_url = site_url($this->ctrlr_name . '/edit/' . $id);
        parent::edit($id);
    }

    function show($page = 1) {
        if ($this->logged_user) {
            $this->element->where_related($this->logged_user);
        } else {
            show_404();
        }
        parent::show($page);
    }

    function showtop() {
        $this->element->order_by('rate');
        parent::show();
    }

    function search($keyword) {
        $this->element->like('title', $keyword);
        parent::show();
    }
    
    
}
