<?php

Class Crawler extends DataMapperExt {

    var $table = 'crawler';

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
