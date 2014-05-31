<?php

include_once(APPPATH . 'modules/base/controllers/base_controller.php');

class Panelinha extends Base_Controller {

    function Panelinha() {
        parent::__construct();
        $this->load->library('powerdt/curl');
    }

    function Vaga() {
        $c = new Vaga();
        $c->email = 'thomas.groch@gmail.com';
        //$c->logar();
        $c->obterVagas(68);
    }

    function VagaDetalhes() {
        $c = new Vaga();
        $c->email = 'thomas.groch@gmail.com';
        $c->obterDetalhes();
    }

}

/* End of file site.php */
/* Location: ./application/controllers/site.php */
