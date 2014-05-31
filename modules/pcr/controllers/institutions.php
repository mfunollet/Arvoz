<?php

include_once APPPATH . 'modules/pcr/controllers/companies.php';

class Institutions extends Companies {

    /**
     * The construct of this class to call the construct of the class 
     * CRUD_Controller and load any models, helpers, librarys and etc.
     *
     */
    function __construct() {
        parent::__construct();

        if ($this->authentication->is_incorporated()) {
            $this->_add_menu_item('edit', lang('incubated'), array('institutions/my_companies', lang('incubated_companies')));
            $this->_add_menu_item('edit', lang('incubated'), array('institutions/incubate_company', lang('incubate_company')));                      
            $this->_add_menu_item('dashboard', lang('modules'), array('process', lang('docs_management')));
            $this->_add_menu_item('dashboard', lang('incubated'), array('institutions/my_companies', lang('incubated_companies')));
        }
         $this->_add_menu_item('view', '', array('institutions/companies', lang('incubated_companies')));
        
    }

    function edit() {
        $this->element = $this->logged_company;
        $this->element->filtered_fields = array(lang('institution_data') => 'section', 'name', 'cnpj', 'username',
            'email1', 'email2', 'email3', 'phone1', 'phone2', 'phone3', 'foundation',
            'entity_manager_link',
            'manager', 'manager_work_period',
            'attach1_custom' => 'custom',
            'attach2_custom' => 'custom',
            'attach3_custom' => 'custom',
            'regulation_custom' => 'custom',
            'social_law_custom' => 'custom',
            lang('institution_info') => 'section',
            'website',
            'services_description',
            'institutional_material_custom' => 'custom',
            'logo_custom' => 'custom',
            'descriptive_memorial_custom' => 'custom'
            );
        parent::edit($this->element->id);
    }

    function edit_file($field_upload = NULL) {
        $this->field_upload = $field_upload;
        $this->element = $this->logged_company;
        $this->element->field_upload = $this->field_upload;
        $this->element->filtered_fields = array($this->field_upload);

        $this->view = 'institutions/institutions_form_edit_file_view';
        parent::edit($this->element->id);
    }
    
    function companies($id = NULL)
    {
        $this->_add_menu('left', 'view');
        $this->_set_title(lang('incubated_companies'));
        
        $institution = new Institution();
        $institution->where('id', $id);
        $institution->get();
        
        if( ! $institution->exists() )
        {
            $this->msg_error(lang('company_does_not_exist'));
            redirect($this->ctrlr_name);
        }
        
        $this->element = new Institution_has_company();
            
        $this->element->where_related($institution);     
        $this->element->where('end_date IS NULL');
        $this->view = 'institutions/institutions_companies_view';
        
        $this->data['sidebars'][0] = array(
            'view' => 'p_controller/p_controller_image_sites_view',
            'data' => array('element' => $institution)
        );        
        
        parent::show();
    }
    
    function my_companies($page = 1)
    {
        $this->_add_menu('left', 'edit');
        $this->_set_title(lang('incubated_companies'));
        $this->data['sidebars'][] = 'p_controller/p_controller_image_view';
        
        $this->element = new Institution_has_company();
        $this->element->where_related($this->logged_company);     
        $this->element->where('end_date IS NULL');
        
        $this->view = 'institutions/institutions_companies_view';
        
        
        parent::show($page);        
    }
    
    function incubate_company()
    {              
        $this->_set_title( lang('invite_company') );
        $this->element = new Institution_has_company();
        $this->element->filtered_fields = array('company', 'start_date');
        CRUD_Controller::create();
    }

}

