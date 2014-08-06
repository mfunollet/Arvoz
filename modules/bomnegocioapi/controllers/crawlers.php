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
    $date = date('Y-m-d H:i:s O', strtotime('-30 minutes'));
    $this->element->where_related_product('url IS NOT NULL');
    // 2014-08-06 16:07:19
    // now - 30 min
    $this->element->where_related_product('update_time < ',$date);
    // $this->element->where('url IS NOT NULL');
    // update_time < 10 min
    // where related products are NOT with status = 1, because it has already sould out
    $this->element->get();
    $this->element->product->get();
    $this->element->crawProductsDetails();
    $this->element->saveProductsDetails();
  }


}