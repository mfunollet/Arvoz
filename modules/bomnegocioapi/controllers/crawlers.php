<?php

include_once(APPPATH . 'modules/base/controllers/crud_controller.php');

class Crawlers extends CRUD_Controller {
// http://ba.bomnegocio.com/sul-da-bahia/animais-e-acessorios/cachorros/yorkshire
  var $force_noajax = FALSE;
  var $force_noajax_view = FALSE;

  function Crawlers() {
    parent::__construct();
  }

  function index(){
    parent::index();
  }

  function create(){
    parent::create();
  }

  function delete($id){
  	parent::delete($id);
  }

  function obteranuncios() {
    $this->benchmark->mark('code_start');
    $this->element->get();
    $this->element->getSearchResults();
    $this->element->extractLinks();
    print_r($this->links);
    $this->benchmark->mark('code_end');
    echo $this->benchmark->elapsed_time('code_start', 'code_end');
    exit;
    $this->element->obteranuncios();
  }


}