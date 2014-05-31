<?php

/*
 * error file control.
 */


if ( !defined('BASEPATH') )
    exit('No direct script access allowed');

include_once APPPATH . 'modules/base/controllers/base_controller.php';

class Error extends Base_Controller {

    function __construct()
    {
        parent::__construct();
        $this->ctrlr_name = 'home';
    }

    function access_denied()
    {
        echo 'acesso negado';
        //efetuar load view
    }

}