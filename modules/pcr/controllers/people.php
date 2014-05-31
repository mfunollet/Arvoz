<?php

include_once APPPATH . 'modules/pcr/controllers/p_controller.php';

class People extends P_Controller {

    /**
     * The construct of this class to call the construct of the class 
     * CRUD_Controller and load any models, helpers, librarys and etc.
     *
     */
    function __construct() {
        parent::__construct();

        if ($this->authentication->is_signed_in()) {
            $this->_add_menu_item('edit', lang('manage'), array($this->ctrlr_name.'/edit_password', lang('edit_password')));
            $this->_add_menu_item('dashboard', '', array('people/companies', lang('where_i_work')));
            $this->_add_menu_item('dashboard', '', array('people/past_companies', lang('where_i_worked')));
            $this->_add_menu_item('dashboard', '', array('companies/create', lang('create_company')));
        }

        if (!empty($this->show_use_as_company)) {
            if ($this->show_use_as_company->exists()) {
                foreach ($this->show_use_as_company as $chp) {
                    $this->_add_menu_item('dashboard', lang('companies_that_iam_admin'), array('companies/view/' . $chp->company->id, $chp->company->name));
                }
            }
        }
        if (!empty($this->show_use_as_institution)) {
            if ($this->show_use_as_institution->exists()) {
                foreach ($this->show_use_as_institution as $ihp) {
                    $this->_add_menu_item('dashboard', lang('companies_that_iam_admin'), array('institutions/view/' . $ihp->institution->id, $ihp->institution->name));
                }
            }
        }        
    }


    public function login() {
        if ($this->logged_user) {
            redirect('/');
        }
        $this->_set_title(lang('login'));
        $this->element = new Person();

        $fields = array('email1', 'password', 'remember');
        if ($_POST) {
            $this->element->from_array($this->input->post(), $fields);
            //debug($this->authentication->login($this->element->password));
            if ($this->authentication->login($this->element)) {
                redirect('/');
            }
        }

        $this->data['data']['fields'] = $fields;
        parent::page();
    }

    function register() 
    {

        $this->element->filtered_fields = array('first_name', 'last_name', 'email1', 'email1_conf', 'password', 'password_conf', 'gender', 'birthday');

        $this->_set_title(lang('register'));
        $this->redirect_url = 'people/login';

        $this->data['data']['fields'] = $this->element->get_clean_fields();
        parent::create();
    }

    function forgotten_password() {
        $p = new Person();
        $fields = $p->filtered_fields = array('email1');

        if ($_POST) {
            $p->fillObject();
            $p->get_where(array('email1' => $p->email1));
            if ($p->exists()) {
                $this->authentication->send_forgotten_password_email($p);
                redirect();
            }
        }
        $this->_set_title(lang('forgotten'));

        $this->data['data']['fields'] = $fields;
        $this->element = $p;
        parent::page();
    }

    public function leave_company($chp_id = NULL) {
        $chp = new Company_has_person();
        $chp->where_related($this->logged_user);
        $chp->where('id', $chp_id);
        $chp->where('end_date IS NULL');
        $chp->where('start_date IS NOT NULL');
        $chp->get();

        if ($chp->exists()) {
            $chp->end_date = date($chp->timestamp_format);
            $chp->save();
            $this->msg_ok(lang('demission_done'));
        }
        redirect('people/companies');
    }

    function use_as_company($company_id = NULL) {
        $c = new Company($company_id);
        $this->authentication->use_as_company($c);
        redirect('/');
    }

    function use_as_institution($institution_id = NULL) {
        $i = new Institution($institution_id);
        $this->authentication->use_as_institution($i);
        redirect('/');        
    }

    function logout() {
        $this->authentication->logout();
        redirect();
    }

    function company_logout() {
        $this->authentication->company_logout();
        redirect('/');
    }

    function edit() {
        $this->_set_title(lang('edit_profile'));
        $this->_add_menu('left', 'edit');

        $this->element = $this->logged_user;
        $this->element->removed_fields = array('activation_code', 'forgotten_password_code', 'remember_code', 'email1', 'status', 'is_superadmin', 'profile_image', 'password');
        unset($this->element->validation['password']['rules'][0]);

        parent::edit($this->element->id);
    }

    function edit_password() {
        $this->_add_menu('left', 'edit');
        $this->_set_title(lang('edit_password'));

        $this->element = $this->logged_user;
        $this->element->filtered_fields = array('password', 'new_password', 'new_password_conf');
        $this->element->validation['new_password']['rules'][] = 'required';
        $this->element->validation['new_password_conf']['rules'][] = 'required';
        $this->element->db_password = $this->element->password;

        parent::edit($this->element->id);
    }

    function edit_image() {
        $this->element = $this->logged_user;
        parent::edit_image();
    }

    function companies($page = 1) {
        $this->_add_menu('left', 'dashboard');
        $this->_set_title(lang('where_i_work'));

        unset($this->element);

        $this->element = new Company_has_person();
        $this->element->where_related($this->logged_user);
        $this->element->where('end_date IS NULL');
        $this->element->where('status', STATUS_ACTIVE);
        
        $this->data['sidebars'][] = 'p_controller/p_controller_image_view';

        parent::show($page);
    }
    
    function past_companies($page = 1) {
        $this->_add_menu('left', 'dashboard');
        $this->_set_title(lang('where_i_worked'));

        unset($this->element);

        $this->element = new Company_has_person();
        $this->element->where_related($this->logged_user);
        $this->element->where('end_date IS NOT NULL');
        
        $this->data['sidebars'][] = 'p_controller/p_controller_image_view';

        parent::show($page);
    }

}
