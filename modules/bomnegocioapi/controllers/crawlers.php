<?php

include_once(APPPATH . 'modules/base/controllers/crud_controller.php');

class Crawlers extends CRUD_Controller {
// http://ba.bomnegocio.com/sul-da-bahia/animais-e-acessorios/cachorros/yorkshire
  var $force_noajax = FALSE;
  var $force_noajax_view = FALSE;
  public $layout = 'base/simple_layout';

  function Crawlers() {
    //$this->data['js_files'][] = 'jquery/jquery.validate.js';
    parent::__construct();
  }

  function index(){
    redirect($this->ctrlr_name . '/show');
  }

  function show(){
    $this->element->get();
    parent::show();
  }

  function create(){
    parent::create();
  }

  function delete($id){
  	parent::delete($id);
  }

  function getproducts() {
    $this->element->get();
    foreach ($this->element as $search) {
      log_message('info', 'Iniciando Loop:'.$search->keyword);
      $search->getSearchResults();
      $search->extractLinks();
      $search->saveProducts();      
    }
  }

}