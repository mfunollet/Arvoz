<?php

class Docs_model extends CI_Model {

    var $CI;
    var $logged_company = NULL;

    function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->logged_company = $this->CI->authentication->get_logged_company();
    }

}
?>