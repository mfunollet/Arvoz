<?php

include_once APPPATH . 'modules/pcr/models/p_model.php';

Class Person extends P_model {

    public $has_many = array(
        'company' => array(// in the code, we will refer to this relation by using the object name 'book'
            'class' => 'company', // This relationship is with the model class 'book'
            'other_field' => 'person', // in the Book model, this defines the array key used to identify this relationship
            'join_self_as' => 'person', // foreign key in the (relationship)table identifying this models table. The column name would be 'author_id'
            'join_other_as' => 'company', // foreign key in the (relationship)table identifying the other models table as defined by 'class'. The column name would be 'book_id'
            'join_table' => 'company_has_person') // name of the join table that will link both Author and Book together
        , 'institution' => array(// in the code, we will refer to this relation by using the object name 'book'
            'class' => 'institution', // This relationship is with the model class 'book'
            'other_field' => 'person', // in the Book model, this defines the array key used to identify this relationship
            'join_self_as' => 'person', // foreign key in the (relationship)table identifying this models table. The column name would be 'author_id'
            'join_other_as' => 'institution', // foreign key in the (relationship)table identifying the other models table as defined by 'class'. The column name would be 'book_id'
            'join_table' => 'institution_has_person') // name of the join table that will link both Author and Book together
        , 'site'
        , 'company_has_person'
        , 'institution_has_person'
        , 'person_login'
        , 'owned_institution' => array(// in the code, we will refer to this relation by using the object name 'book'
            'class' => 'institution', // This relationship is with the model class 'book'
            'other_field' => 'owner' // in the Book model, this defines the array key used to identify this relationship
        )
        , 'owned' => array(// in the code, we will refer to this relation by using the object name 'book'
            'class' => 'company', // This relationship is with the model class 'book'
            'other_field' => 'owner' // in the Book model, this defines the array key used to identify this relationship
        )
    );
    var $table = 'person_view';
    var $roles = array();
    var $profile_image_source = array('upload_path' => './uploads/person/profile_image/',
        'allowed_types' => 'jpg|jpeg|gif|png',
        'overwrite' => TRUE,
        'max_size' => '2048'
    );
    // 50x50 e 180x180
    var $thumbnails_profile_image = array(
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
        'first_name' => array(
            'rules' => array('required', 'trim'),
        ),
        'last_name' => array(
            'rules' => array('required', 'trim')
        ),
        'gender' => array(
            'rules' => array('required'),
            'type' => 'dropdown',
            'values' => array('M' => 'M', 'F' => 'F')
        ),
        'cpf' => array(
            'rules' => array('cpf_validation', 'unique'),
        ),
        'username' => array(
            'rules' => array('trim', 'min_length' => 2, 'max_length' => 50, 'unique')
        ),
        'password_conf' => array(
            'rules' => array('required', 'matches' => 'password', 'hash_password'),
            'type' => 'password'
        ),
        'password' => array(
            'rules' => array('required', 'min_length' => 6, 'max_length' => 40, 'hash_password'),
            'type' => 'password'
        ),
        'new_password' => array(
            'rules' => array('min_length' => 6, 'max_length' => 40, 'hash_password'),
            'type' => 'password'
        ),
        'new_password_conf' => array(
            'rules' => array('matches' => 'new_password', 'hash_password'),
            'type' => 'password'
        ),
        'email1_conf' => array(
            'rules' => array('required', 'matches' => 'email1')
        ),
        'email1' => array(
            'rules' => array('trim', 'required', 'valid_email', 'unique')
        ),
        'email2' => array(
            'rules' => array('trim', 'valid_email')
        ),
        'email3' => array(
            'rules' => array('trim', 'valid_email')
        ),
        'phone1' => array(
            'rules' => array('trim')
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
        'profile_image' => array(
            //'rules' => array('file_required'),
            'type' => 'file'
        ),
        'birthday' => array(
            'rules' => array('trim', 'required')
        ),
        'type' => array(
            'rules' => array('trim')
        ),
        'remember' => array(
            'type' => 'checkbox'
        )
    );

    function __construct($id = NULL) {
        parent::__construct($id);
    }

    public function __toString() {
        return $this->first_name . ' ' . $this->last_name;
    }

    function get_roles($company) {
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

    function _cpf_validation($field, $param = '') {
        $this->{$field} = str_pad(preg_replace('/[^0-9]/', '', $this->{$field}), 11, '0', STR_PAD_LEFT);
        // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
        if (strlen($this->{$field}) != 11 ||
                $this->{$field} == '00000000000' ||
                $this->{$field} == '11111111111' ||
                $this->{$field} == '22222222222' ||
                $this->{$field} == '33333333333' ||
                $this->{$field} == '44444444444' ||
                $this->{$field} == '55555555555' ||
                $this->{$field} == '66666666666' ||
                $this->{$field} == '77777777777' ||
                $this->{$field} == '88888888888' ||
                $this->{$field} == '99999999999') {
            return FALSE;
        } else { // Calcula os números para verificar se o CPF é verdadeiro
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $this->{$field}{$c} * (($t + 1) - $c);
                }

                $d = ((10 * $d) % 11) % 10;

                if ($this->{$field}{$c} != $d) {
                    return FALSE;
                }
            }
            return TRUE;
        }
    }

    function login() {
        $this->_hash_password('password');

        $p = new Person();
        $p->where('password', $this->password);
        $p->where('email1', $this->email1);
        $p->get();

        if ($p->exists()) {
            $session_data = array(
                'person_id' => $p->id
            );

            $pl = new Person_login();
            $pl->ip_address = $_SERVER['REMOTE_ADDR'];
            $pl->save($p);

            $this->CI->session->set_userdata($session_data);

            if ($p->remember) {
                //TODO persistir cookie caso remember esteja marcado
                //$this->load->helper('cookie');
                //$this->set_cookie();
                //$this->remember_user($user->id);
            }

            return TRUE;
        }
        //$this->CI->msg_error(lang('login_nok'));
        $this->error_message('email1', lang('login_nok'));
        return FALSE;
    }

    function _hash_password($field = NULL, $param = NULL) {
        $salt = $this->config->item('encryption_key');

        $this->{$field} = sha1($this->{$field} . $salt);
    }

    public function _saveExtra() {
        $default = './images/person_default_profile_image.png';

        $res = $this->save();
        $this->profile_image = $this->id . '_profile_image.png';
        $res = $this->save();

        $res = $res && copy($default, './uploads/person/profile_image/' . $this->profile_image);
        return $res;
    }

    public function _saveExtra_edit_password() {
        $this->_hash_password('new_password_conf');

        if ($this->validate() && $this->password == $this->db_password) {
            // new pass ja confere se é unique
            $this->password = $this->new_password;
            $res = $this->save();
        } else {
            if ($this->password != $this->db_password) {
                $this->error_message('password', 'A senha nao confere');
            }
            $res = FALSE;
        }
        return $res;
    }

    public function _saveExtra_edit_image() {
        $this->CI->load->library('photo_manager');

        $this->CI->photo_manager->initialize($this, 'profile_image');
        $res = $this->CI->photo_manager->upload_main(TRUE);
        $res = $res && $this->save();
        return $res;
    }

    function get_htmlform_list($object, $field) {
        // Remove usuario logado da lista
        $logged_user = $this->CI->authentication->get_logged_user();
        $this->where('id !=', $logged_user->id);
        $this->get();
    }

    function get_notifications($limit = NULL, $offset = NULL) {
        $ihp = new Institution_has_person();
        $chp = new Company_has_person();
        $logged_user = $this->CI->authentication->get_logged_user();

        $ihp->where_related($logged_user);
        $ihp->where('end_date IS NULL');
        $ihp->get_pending();

        $chp->where_related($logged_user);
        $chp->where('end_date IS NULL');
        $chp->get_pending();

        $this->notifications = array();
        foreach ($ihp as $item) {
            $this->notifications[$item->id]['name'] = $item->institution->name;
            $this->notifications[$item->id]['role'] = $item->role->name;
            $this->notifications[$item->id]['type'] = strtolower(get_class($item));
        }
        foreach ($chp as $item) {
            $this->notifications[$item->id]['name'] = $item->company->name;
            $this->notifications[$item->id]['role'] = $item->role->name;
            $this->notifications[$item->id]['type'] = strtolower(get_class($item));
        }
    }

    function get_autocomplete_field() {
        return 'first_name';
    }

}