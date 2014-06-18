<?php

//include_once(APPPATH . 'modules/base/controllers/base_controller.php');

class Api extends CI_Controller {

  function Api() {
    parent::__construct();
  }

  function index() {
    echo 'pedido';

    $this->bomnegocio->search();
  }

}

/* End of file site.php */
/* Location: ./application/controllers/site.php */
