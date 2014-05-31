<?php

class Institutions extends CI_Controller {

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

    function amigos() {
        $i = new Institution();

        $i->where('id', 30)->get();
//        $i->name = 'Name';
//        $i->cnpj = 'Amor';
//        $i->type = 'Amor';
//        $i->p_id = 2;
////        $i->save();

        $c_meta = new C_meta();

        $c_meta->social_reason = 'asdasd';
        $c_meta->entity_manager_link = '2asdasd';
        $c_meta->manage_incubator = '3asdasd';
        $c_meta->time_dedication = '4asdasd';
        $c_meta->social_bylaws = '5asdasd';
        $c_meta->internal_regulation = '6asdasd';

        $c_meta->memorial_descriptive = '7asdasd';
        $c_meta->description_service_offered = '8asdasd';

        $c_meta->institution_material = '9asdasd';
        //$c_meta->company_id = $i->id;
        $c_meta->save($i);

        $i->clear();

        $i->where('id', 6)->get();

        $c_meta->save($i);
    }

    function createupdate($id = null) {
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

        echo $i->render_form($fields);
    }

}
