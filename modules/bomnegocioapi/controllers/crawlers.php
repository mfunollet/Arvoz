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

  function getproducts() {
    $this->element->get();
    $this->element->getSearchResults();
    $this->element->extractLinks();
    $this->element->saveProducts();
  }

  function getproductsdetails() {
    $this->element->where_related_product('url IS NOT NULL');
    // $this->element->where('url IS NOT NULL');
    // update_time < 10 min
    // where related products are NOT with status = 1, because it has already sould out
    $this->element->get();
    $this->element->product->get(5);
    $this->element->crawProductsDetails();
    $this->element->saveProductsDetails();
  }


}