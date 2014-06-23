<?php

include_once(APPPATH . 'modules/base/controllers/crud_controller.php');

class Crawlers extends CRUD_Controller {

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

}