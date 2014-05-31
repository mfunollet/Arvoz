<?php

include_once APPPATH . 'modules/pcr/models/p_model.php';

Class Company extends P_model {

    public $has_many = array(
        'people' => array(// in the code, we will refer to this relation by using the object name 'book'
            'class' => 'person', // This relationship is with the model class 'book'
            'other_field' => 'companies', // in the Book model, this defines the array key used to identify this relationship
            'join_self_as' => 'company', // foreign key in the (relationship)table identifying this models table. The column name would be 'author_id'
            'join_other_as' => 'person', // foreign key in the (relationship)table identifying the other models table as defined by 'class'. The column name would be 'book_id'
            'join_table' => 'company_has_person') // name of the join table that will link both Author and Book together
        , 'institutions' => array(// in the code, we will refer to this relation by using the object name 'book'
            'class' => 'institution', // This relationship is with the model class 'book'
            'other_field' => 'companies', // in the Book model, this defines the array key used to identify this relationship
            'join_self_as' => 'company', // foreign key in the (relationship)table identifying this models table. The column name would be 'author_id'
            'join_other_as' => 'institution', // foreign key in the (relationship)table identifying the other models table as defined by 'class'. The column name would be 'book_id'
            'join_table' => 'institution_has_company') // name of the join table that will link both Author and Book together
        , 'site'
        , 'company_has_person'
        , 'institution_has_company'
    );
    public $has_one = array(
        'owner' => array(// in the code, we will refer to this relation by using the object name 'book'
            'class' => 'person', // This relationship is with the model class 'book'
            'other_field' => 'owned', // foreign key in the (relationship)table identifying the other models table as defined by 'class'. The column name would be 'book_id'
        ),
        'company_type'
    );
    var $table = 'company_view';
    var $removed_fields = array();
    var $profile_image_source = array('upload_path' => './uploads/company/profile_image/',
        'allowed_types' => 'jpg|jpeg|gif|png',
        'overwrite' => TRUE,
        'max_size' => '2048'
    );
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
            'rules' => array('trim', 'min_length' => 3, 'max_length' => 40, 'cnpj_validation'),
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
        )
    );

    /**
     * The contruct of class Company_model
     *
     * In the construc of this class is necessary that you configure the name
     * of the table and the data_type of your entity
     *
     *
     * @param  array  $array  Description of array
     * @param  string $string Description of string
     * @return boolean
     */
    function __construct($id = NULL)
    {
        parent::__construct($id);
    }

    public function __toString()
    {
        return $this->name;
    }

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
      }
     */

    function _cnpj_validation($field, $param)
    {
        $this->{$field} = str_pad(preg_replace('/[^0-9]/', '', $this->{$field}), 14, '0', STR_PAD_LEFT);
        $this->{$field} = str_pad(preg_replace('/[^0-9]/', '', $this->{$field}), 11, '0', STR_PAD_LEFT);
        //TODO CPNJ VALIDATION
        if ( !is_numeric($this->{$field}) && count($this->{$field}) != 14 )
        {
            return FALSE;
        }
        else
        {
            /* Verifica se todos os números digitados são iguais, caso sejam, faz o mesmo que na condição anterior */
            if ( ($this->{$field} == '11111111111111') ||
                    ($this->{$field} == '22222222222222') ||
                    ($this->{$field} == '33333333333333') ||
                    ($this->{$field} == '44444444444444') ||
                    ($this->{$field} == '55555555555555') ||
                    ($this->{$field} == '66666666666666') ||
                    ($this->{$field} == '77777777777777') ||
                    ($this->{$field} == '88888888888888') ||
                    ($this->{$field} == '99999999999999') ||
                    ($this->{$field} == '00000000000000') )
            {
                return FALSE;
            }
            else
            {
                if ( strlen($this->{$field}) > 14 )
                    $this->{$field} = substr($this->{$field}, 1);

                $sum1 = 0;
                $sum2 = 0;
                $sum3 = 0;
                $calc1 = 5;
                $calc2 = 6;

                for ( $i = 0; $i <= 12; $i++ )
                {
                    $calc1 = $calc1 < 2 ? 9 : $calc1;
                    $calc2 = $calc2 < 2 ? 9 : $calc2;

                    if ( $i <= 11 )
                    {
                        $sum1 += $this->{$field}[$i] * $calc1;
                    }
                    $sum2 += $this->{$field}[$i] * $calc2;
                    $sum3 += $this->{$field}[$i];
                    $calc1--;
                    $calc2--;
                }

                $sum1 %= 11;
                $sum2 %= 11;

                return ($sum3 && $this->{$field}[12] == ($sum1 < 2 ? 0 : 11 - $sum1) && $this->{$field}[13] == ($sum2 < 2 ? 0 : 11 - $sum2)) ? TRUE : FALSE;
            }
        }
    }

    function _saveExtra($id = NULL)
    {
        $p = $this->CI->authentication->get_logged_user();
        $this->company_type_id = COMPANY_TYPE_COMPANY;

        // Só salva a owner se estiver criando
        if ( $id != NULL )
        {
            $p = NULL;
        }
        // Salva company e owner
        $res = $this->save_owner($p);

        // Salva usuario logado como administrador
        if ( !$id )
        {
            $chp = new Company_has_person();
            $chp->job = 'Fundador';
            $chp->start_date = $this->foundation;
            $r = new Role(COMPANY_ADMIN_ID);
            $res = $res && $chp->save(array($this, $p, $r));
        }
        $this->CI->load->library('photo_manager');
        $this->CI->photo_manager->initialize($this, 'profile_image');
        $res = $res && $this->CI->photo_manager->upload_main($id);
        $res = $res && $this->save();

        $this->_msg = lang('company_created');

        return $res;
    }

    function get_notifications($limit = NULL, $offset = NULL) {
        $ihp = new Institution_has_company();
        $logged_company = $this->CI->authentication->get_logged_company();

        $ihp->where_related($logged_company);
        $ihp->where('end_date IS NULL');
        $ihp->get_pending();

        $this->notifications = array();
        foreach ($ihp as $item) {
            $this->notifications[$item->id]['name'] = $item->institution->name;
            $this->notifications[$item->id]['role'] = $item->role->name;
            $this->notifications[$item->id]['type'] = strtolower(get_class($item));
        }
    }
    
    function get_recipients()
    {     
        $r = new Role();
        $r->get_by_id(COMPANY_ADMIN_ID);
        
        $chp = new Company_has_person();
        $chp->where_related($r);
        $chp->where_related($this);
        $chp->get_active();       
        
        $recipients = array();
        $recipients[] = $this;
        
        foreach ($chp as $item) {
            $recipients[] = $item->person;
        }

        return $recipients;
    }

}
