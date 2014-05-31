<?php

include_once APPPATH . 'modules/pcr/models/p_model.php';

Class Book extends P_model {

    //public $has_one = array('person');
    public $has_many = array(
        'people' => array(// in the code, we will refer to this relation by using the object name 'book'
            'class' => 'person', // This relationship is with the model class 'book'
            'other_field' => 'book', // in the Book model, this defines the array key used to identify this relationship
            //'join_self_as' => 'book', // foreign key in the (relationship)table identifying this models table. The column name would be 'author_id'
            'join_other_as' => 'person', // foreign key in the (relationship)table identifying the other models table as defined by 'class'. The column name would be 'book_id'
            'join_table' => 'person_has_book') // name of the join table that will link both Author and Book together
        );
    var $table = 'book';
    var $roles = array();
    public $validation = array(
        'title' => array(
            'rules' => array('required', 'trim')
        ),
        'autor' => array(
            'rules' => array('required', 'trim')
        ),
        'type' => array(
            'rules' => array('required', 'trim')
        ),
        'price' => array(
            'rules' => array('required', 'trim')
        ),
        'desc' => array(
            'rules' => array('required', 'trim')
        ),
        'capa_image' => array(
            'type' => 'file'
        ),
        'pdf' => array(
            'rules' => array('required', 'trim')
        ),
        'tags' => array(
            'rules' => array('required', 'trim')
        ),
        'pdf' => array(
            'rules' => array('required', 'trim')
        )
    );
    var $capa_image_source = array('upload_path' => './uploads/book/capa_image/',
        'allowed_types' => 'jpg|jpeg|gif|png',
        'overwrite' => TRUE,
        'max_size' => '2048'
    );
    // 50x50 e 180x180
    var $thumbnails_capa_image = array(
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

    function __construct($id = NULL) {
        parent::__construct($id);
        $this->load->helper('favicon');
    }

    public function get_favicon() {
        return 'http://www.google.com/s2/favicons?domain=' . get_domain_name($this->url);
    }

    function _saveExtra($id = NULL) {
        $rel = NULL;
        //$logged_company = $this->CI->authentication->get_logged_company();
        $logged_user = $this->CI->authentication->get_logged_user();
        if ($logged_user) {
            $rel = $logged_user;
        }
        $this->CI->load->library('photo_manager');

        $this->CI->photo_manager->initialize($this, 'capa_image');
        $res = $this->CI->photo_manager->upload_main(TRUE);
        $res = $res && $this->save();
        return $res;
    }

}
