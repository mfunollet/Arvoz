<?php

include_once(APPPATH . 'modules/base/controllers/base_controller.php');

class Docs_controller extends Base_Controller {

    public function __construct() {
        parent::__construct();
        //Load the default css files
        $this->data['css_files'][] = 'docs/css/arvoz.css';

        //Load the default javascript files
        $this->data['js_files'][] = 'jquery/jquery.numeric.js';
        $this->data['js_files'][] = 'docs/js/arvoz.util.js';

        $this->_add_menu_item('nav', 'Processos', array('process', 'Documentos'));
        $this->_add_menu_item('nav', 'Empreendimentos', array('prospection/index/1', 'Sensibilização'));
        $this->_add_menu_item('nav', 'Empreendimentos', array('prospection/index/2', 'Prospecção'));
        $this->_add_menu_item('nav', 'Empreendedores', array('qualification', 'Qualificação'));
        $this->_add_menu_item('nav', 'Planejamento', array('planning', 'Gestão'));
        $this->_add_menu('left', 'nav');
    }

}

?>