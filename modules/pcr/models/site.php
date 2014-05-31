<?php

Class Site extends DataMapperExt {

    public $has_one = array('person', 'company', 'institution');
    var $table = 'site';
    var $roles = array();
    public $validation = array(
        'name' => array(
            'rules' => array('required', 'trim')
        ),
        'url' => array(
            'rules' => array('required', 'trim', 'prep_url')
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
        $logged_company = $this->CI->authentication->get_logged_company();
        $logged_user = $this->CI->authentication->get_logged_user();

        if ($logged_company) {
            $rel = $logged_company;
        } elseif ($logged_user) {
            $rel = $logged_user;
        } else {
            return FALSE;
        }
        return $this->save($rel);
    }

}
