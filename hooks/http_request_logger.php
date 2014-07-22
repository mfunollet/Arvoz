<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Http_request_logger {

    public $CI;

    public function log_all() {
    	// if($_SERVER['REMOTE_ADDR'] == '186.229.80.7'){
     //    	log_message('info', 'Reserva');
     //    	exit;
    	// }
        $this->CI = & get_instance();
        log_message('info', 'GET --> ' . var_export($this->CI->input->get(null), true));
        log_message('info', 'POST --> ' . var_export($this->CI->input->post(null), true));                
        log_message('info', '$_SERVER -->' . var_export($_SERVER, true));
    }

}
?>