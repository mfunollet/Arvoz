<?php

include_once APPPATH . 'modules/base/controllers/base_controller.php';

class Incubator extends Base_Controller {

    /**
     * The construct of this class to call the construct of the class 
     * CRUD_Controller and load any models, helpers, librarys and etc.
     *
     */
    function __construct() {
        parent::__construct();
        $this->output->enable_profiler(TRUE);
    }

    function index() {
        echo 'show';
    }

    function company_form($id = null) {
        $i = new Institution();
        if ($id) {
            $i->where('id', $id);
            $i->get();
        }

        if ($_POST) {
            $post = $i->fillObject();

            if ($i->save()) {
                echo 'ok ' . anchor('institutions/createupdate/' . $i->id, $i->id);
            } else {
                echo 'nao salvei';
            }
        }
        $fields = $i->get_clean_fields();

        $this->data['data']['url'] = $this->config->item('admin') . '/' . __FUNCTION__ . (empty($i->id)) ? '' : '/' . $i->id;
        $this->data['content'] = strtolower(__CLASS__) . '/' . __FUNCTION__;
        $this->data['data']['i'] = $i;
        $this->data['data']['fields'] = $fields;
        $this->load->view('layoutadm', $this->data);
    }

}
