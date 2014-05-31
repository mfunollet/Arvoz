<?php

include_once APPPATH . 'modules/pcr/models/company.php';

Class Institution extends Company {

    public $has_many = array(
        'people' => array(// in the code, we will refer to this relation by using the object name 'book'
            'class' => 'person', // This relationship is with the model class 'book'
            'other_field' => 'institutions', // in the Book model, this defines the array key used to identify this relationship
            'join_self_as' => 'institution', // foreign key in the (relationship)table identifying this models table. The column name would be 'author_id'
            'join_other_as' => 'person', // foreign key in the (relationship)table identifying the other models table as defined by 'class'. The column name would be 'book_id'
            'join_table' => 'institution_has_person') // name of the join table that will link both Author and Book together
        , 'site'
        , 'institution_has_person'
        , 'institution_has_company'
        , 'companies' => array(// in the code, we will refer to this relation by using the object name 'book'
            'class' => 'company', // This relationship is with the model class 'book'
            'other_field' => 'institutions', // in the Book model, this defines the array key used to identify this relationship
            'join_self_as' => 'institution', // foreign key in the (relationship)table identifying this models table. The column name would be 'author_id'
            'join_other_as' => 'company', // foreign key in the (relationship)table identifying the other models table as defined by 'class'. The column name would be 'book_id'
            'join_table' => 'institution_has_company') // name of the join table that will link both Author and Book together
    );
    public $has_one = array(
        'owner' => array(// in the code, we will refer to this relation by using the object name 'book'
            'class' => 'person', // This relationship is with the model class 'book'
            'other_field' => 'owned_institution', // foreign key in the (relationship)table identifying the other models table as defined by 'class'. The column name would be 'book_id'
        ),
        'company_type'
    );
    var $table = 'institution_view';
    var $removed_fields = array();
    var $profile_image_source = array('upload_path' => './uploads/institution/profile_image/',
        'allowed_types' => 'jpg|jpeg|gif|png',
        'overwrite' => TRUE,
        'max_size' => '2048'
    );
    var $attach1_source = array('upload_path' => './uploads/institution/attach1/');
    var $attach2_source = array('upload_path' => './uploads/institution/attach2/');
    var $attach3_source = array('upload_path' => './uploads/institution/attach3/');
    var $regulation_source = array('upload_path' => './uploads/institution/regulation/');
    var $institutional_material_source = array('upload_path' => './uploads/institution/institutional_material/');
    var $logo_source = array('upload_path' => './uploads/institution/logo/');
    var $social_law_source = array('upload_path' => './uploads/institution/social_law/');
    var $descriptive_memorial_source = array('upload_path' => './uploads/institution/descriptive_memorial/');
    var $services_description_source = array('upload_path' => './uploads/institution/services_description/');
    // 50x50 e 180x180
    var $thumbnails = array(
        array('image_library' => 'gd2',
            'maintain_ratio' => FALSE,
            'master_dim' => 'width',
            'width' => 50,
            'height' => 50,
            'quality' => 80
        ),
        array('image_library' => 'gd2',
            'maintain_ratio' => FALSE,
            'master_dim' => 'width',
            'width' => 180,
            'height' => 180,
            'quality' => 80
        )
    );
    public $validation = array(
        'username' => array(
            'rules' => array('required', 'trim', 'min_length' => 2, 'max_length' => 50, 'unique', 'url_title')
        ),
        'name' => array(
            'rules' => array('required', 'trim')
        ),
        'cnpj' => array(
            'rules' => array('trim', 'min_length' => 3, 'max_length' => 40 , 'cnpj_validation'),
        ),
        'email1_conf' => array(
            'rules' => array('matches' => 'email1')
        ),
        'email1' => array(
            'rules' => array('required', 'trim', 'valid_email', 'unique')
        ),
        'email2' => array(
            'rules' => array('trim', 'valid_email')
        ),
        'email3' => array(
            'rules' => array('trim', 'valid_email')
        ),
        'phone1' => array(
            'rules' => array('required', 'trim')
        ),
        'phone2' => array(
            'rules' => array('trim')
        ),
        'phone3' => array(
            'rules' => array('trim')
        ),
        'site1' => array(
            'rules' => array('trim', 'prep_url')
        ),
        'site2' => array(
            'rules' => array('trim', 'prep_url')
        ),
        'site3' => array(
            'rules' => array('trim', 'prep_url')
        ),
        'foundation' => array(
            'rules' => array('trim')
        ),
        'profile_image' => array(
            'type' => 'file'
        ),
        'type' => array(
            'rules' => array('trim')
        ),
        'attach1' => array(
            'rules' => array('trim'),
            'type' => 'file',
            'custom' => 'institutions/edit_file/attach1'
        ),
        'attach2' => array(
            'rules' => array('trim'),
            'type' => 'file',
            'custom' => 'institutions/edit_file/attach2'
        ),
        'attach3' => array(
            'rules' => array('trim'),
            'type' => 'file',
            'custom' => 'institutions/edit_file/attach3'
        ),
        'regulation' => array(
            'rules' => array('trim'),
            'type' => 'file',
            'custom' => 'institutions/edit_file/regulation'
        ),
        'website' => array(
            'rules' => array('trim', 'prep_url')
        ),
        'institutional_material' => array(
            'rules' => array('trim'),
            'type' => 'file',
            'custom' => 'institutions/edit_file/institutional_material'
        ),
        'logo' => array(
            'rules' => array('trim'),
            'type' => 'file',
            'custom' => 'institutions/edit_file/logo'
        ),
        'social_law' => array(
            'rules' => array('trim'),
            'type' => 'file',
            'custom' => 'institutions/edit_file/social_law'
        ),
        'manager' => array(
            'rules' => array('trim')
        ),
        'manager_work_period' => array(
            'rules' => array('trim')
        ),
        'descriptive_memorial' => array(
            'rules' => array('trim'),
            'type' => 'file',
            'custom' => 'institutions/edit_file/descriptive_memorial'
        ),
        'services_description' => array(
            'rules' => array('trim'),
            'type' => 'textarea',
            'custom' => 'institutions/edit_file/services_description'
        )
    );

    /*
      function get_role($role) {
      $chp = new Company_has_person();
      $chp->where('company_id', $company->id);
      $chp->where('person_id', $this->id);
      $chp->get();

      foreach ($chp as $chp_row) {
      $role = new Role();
      $role->where('id', $chp_row->role_id);
      $role->get();
      $this->roles[] = $role;
      }
      } */

    function get_file($field = NULL, $link = FALSE) {
        if (empty($this->{$field})) {
            return FALSE;
        }
        $path = substr($this->{$field . '_source'}['upload_path'], 1);
        return ($link) ? anchor($path . $this->{$field}, $link) : $path . $this->{$field};
    }

    function _saveExtra($id = NULL) {
        $p = $this->CI->authentication->get_logged_user();
        $res = TRUE;

        // Só salva a owner se estiver criando
        if ($id != NULL) {
            $p = NULL;
        }

        // Salva usuario logado como administrador
        if (!$id) {
            $chp = new Company_has_person();
            $r = new Role(COMPANY_ADMIN_ID);

            $res = $res && $chp->save(array($this, $p, $r));
        }
        $this->CI->load->library('photo_manager');
        $this->CI->photo_manager->initialize($this, 'profile_image');
        $res = $res && $this->CI->photo_manager->upload_main($id);

        // Salva company e owner
        $res = $res && $this->save_owner($p);

        return $res;
    }

    function _saveExtra_edit_file($id = NULL) {
        $this->CI->load->library('file_manager');
        $res = TRUE;
        $this->CI->file_manager->initialize($this, $this->field_upload);
        $res = $res && $this->CI->file_manager->upload_main($id);

        if ($res) {
            return $this->save();
        }
        return false;
    }

}