<?php
include_once(APPPATH . 'models/datamapperex.php');

Class Crawler extends DataMapperExt {

    var $table = 'crawler';

    public $validation = array(
        'keyword' => array(
            'rules' => array('required', 'trim', 'unique')
        )/*,
        'url' => array(
            'rules' => array('required', 'trim', 'prep_url')
        )*/
    );

    function __construct($id = NULL) {
        parent::__construct($id);
    }
    
    // function _saveExtra($id = NULL) {
    //     $rel = NULL;
    //     $logged_company = $this->CI->authentication->get_logged_company();
    //     $logged_user = $this->CI->authentication->get_logged_user();

    //     if ($logged_company) {
    //         $rel = $logged_company;
    //     } elseif ($logged_user) {
    //         $rel = $logged_user;
    //     } else {
    //         return FALSE;
    //     }
    //     return $this->save($rel);
    // }

}
