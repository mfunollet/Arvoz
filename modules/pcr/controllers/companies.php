<?php

include_once APPPATH . 'modules/pcr/controllers/p_controller.php';

class Companies extends P_Controller {

    /**
     * The construct of this class to call the construct of the class 
     * CRUD_Controller and load any models, helpers, librarys and etc.
     *
     */
    function __construct() {
        parent::__construct();

        if ($this->authentication->is_incorporated()) {
            $this->_add_menu_item(array('edit', 'dashboard'), lang('colaborators'), array($this->ctrlr_name . '/invite', lang('add_colaborator')));
            $this->_add_menu_item(array('edit', 'dashboard'), lang('colaborators'), array($this->ctrlr_name . '/my_colaborators', lang('colaborators')));
            $this->_add_menu_item(array('edit', 'dashboard'), lang('colaborators'), array($this->ctrlr_name . '/past_colaborators', lang('past_colaborators')));
        }
        $this->_add_menu_item('view', '', array($this->ctrlr_name . '/colaborators', lang('colaborators')));
    }

    function create() {
        $this->element->filtered_fields = array('username', 'name', 'email1', 'phone1', 'foundation');
        parent::create();
    }

    function edit() {
        if ($this->authentication->is_incorporated()) {
            $this->_add_menu('left', 'edit');
            $this->element = $this->logged_company;
            $this->element->removed_fields = array('profile_image');
            parent::edit($this->element->id);
        } else {
            $this->msg_error(lang('nao tem empresa incorporada'));
            redirect($this->ctrlr_name);
        }
    }

    //mostra todos colaboradores da empresa
    //isto é, todos ativos excetuando os admins
    function colaborators($id = NULL, $page = 1) {
        if (!$id) {
            $this->msg_error(lang('company_does_not_exist'));
            redirect($this->ctrlr_name);
        }

        unset($this->element);
        $singular = ucfirst(singular($this->ctrlr_name));
        $aux_model = $singular . '_has_person';
        $this->element = new $aux_model();

        $c = new $singular();
        $c->where('id', $id);
        $c->get();
        $this->viewing_id = $id;

        if (!$c->exists()) {
            $this->msg_error(lang('company_does_not_exist'));
            redirect($this->ctrlr_name);
        }

        $this->_add_menu('left', 'view', $id);
        $this->_set_title(lang('colaborators'));

        $this->data['sidebars'][0] = array(
            'view' => 'p_controller/p_controller_image_sites_view',
            'data' => array('element' => $c)
        );

        $this->element->where_related($c);
        $this->element->where('status', STATUS_ACTIVE);
        $this->element->where('end_date IS NULL');

        $this->view = 'companies/companies_colaborators_view';


        parent::show($page);
    }

    function my_colaborators($page = 1) {
        $this->_add_menu('left', 'edit');
        $this->_set_title(lang('colaborators'));
        $this->data['sidebars'][] = 'p_controller/p_controller_image_view';

        unset($this->element);
        $singular = ucfirst(singular($this->ctrlr_name));
        $aux_model = $singular . '_has_person';
        $this->element = new $aux_model();

        $this->element->where_related($this->logged_company);
        $this->element->where('end_date IS NULL');

        $this->_add_menu('left', 'edit');
        $this->view = 'companies/companies_my_colaborators_view';

        parent::show($page);
    }

    function invite($id = NULL) {
        $this->_add_menu('left', 'edit');

        // FIX: E se o owner convidar o owner?
        unset($this->element);
        $singular = ucfirst(singular($this->ctrlr_name));
        $aux_model = $singular . '_has_person';
        $this->element = new $aux_model();
        $this->element->filtered_fields = array('person', 'role', 'job', 'start_date');

        if (isset($id)) {
            $this->element->filtered_fields = array('role', 'job', 'start_date', 'end_date');
            if (!isAjax())
                $this->redirect_url = $this->ctrlr_name . '/my_colaborators';
            else
                $this->redirect_url = $this->ctrlr_name . '/invite/' . $id;
            $this->_set_title(lang('edit_colaborator'));
            parent::edit($id);
        } else {
            $this->_set_title(lang('add_colaborator'));
            parent::create();
        }
    }

    function invite_delete($id = NULL) {
        unset($this->element);
        $singular = ucfirst(singular($this->ctrlr_name));
        $aux_model = $singular . '_has_person';
        $this->element = new $aux_model();
        parent::delete($id);
    }

    function past_colaborators() {
        $this->_set_title(lang('past_colaborators'));

        unset($this->element);
        $singular = ucfirst(singular($this->ctrlr_name));
        $aux_model = $singular . '_has_person';
        $this->element = new $aux_model();
        $r = new Role(COMPANY_ADMIN_ID);

        $this->element->where_related($this->logged_company);
        $this->element->where_not_in_related($r);
        $this->element->where('end_date IS NOT NULL');

        $this->_add_menu('left', 'edit');
        $this->data['sidebars'][] = 'p_controller/p_controller_image_view';

        $this->view = 'companies/companies_past_colaborators_view';

        parent::show();
    }

    //mostra somente os admins da empresa
//    function admins()
//    {
//        $this->_set_title(lang('admins'));
//
//        unset($this->element);
//        $singular = ucfirst(singular($this->ctrlr_name));
//        $aux_model = $singular . '_has_person';
//        $this->element = new $aux_model();
//
//        $r = new Role(COMPANY_ADMIN_ID);
//        $this->element->where_related($this->logged_company);
//        $this->element->where_related($r);
//        $this->element->where('end_date IS NULL');
//
//        $this->_add_menu('left', 'edit');
//        $this->view = 'companies/companies_colaborators_view';
//        $this->data['sidebars'][] = 'p_controller/p_controller_image_view';
//        parent::show();
//    }

    function remove_user($p_id = NULL, $role_id = NULL) {
        // Impede remoção do owner
        // Impede acessar esse método sem ter uma empresa incorporada
        if ($this->authentication->is_incorporated && $this->logged_company->owner->id == $p_id) {
            $this->msg_error(lang('Não é possível remover o dono.'));
            show_404();
        }

        $p = new Person($p_id);
        $r = new Role($role_id);

        $chp = new Company_has_person();
        $chp->where_related($p);
        $chp->where_related($this->logged_company);
        $chp->where_related($r);
        $chp->where('end_date IS NULL');
        $chp->get();

        if ($chp->exists()) {
            $chp->end_date = date($p->timestamp_format);

            if ($chp->save()) {
                $this->msg_ok(lang('colaborator_fired'));
                redirect();
            }
        }
        show_404();
    }

    function edit_image() {
        $this->element = $this->logged_company;
        parent::edit_image();
    }

    function show($page = 1) {
        $this->view = (empty($this->view)) ? 'companies/companies_show_view' : $this->view;
        parent::show($page);
    }

}

