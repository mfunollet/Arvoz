<?php

class ProductBomNegocio extends DataMapperExt {

    var $table = 'products_bomnegocio';
    
    function __construct($id = NULL) {
        //register_shutdown_function(array(&$this, 'MyDestructor'));
        parent::__construct($id);
    }

    function MyDestructor() {
        echo 'Script executed with success' . PHP_EOL;
        echo $this->email;
    }

    function search() {
        $this->bomnegocio->read_product();
    }

    public function craw_products() {
        //$this->where('url != NULL');
        $this->get();
        foreach ($this as $p) {
            $product = $this->CI->bomnegocio->read_product($p->url);
            if($product){
                foreach($product as $field => $value){
                    $p->{$field} = $value;
                }
                $p->save();
            }else{
                $p->delete();
            }
        }

    }

    public function craw_by_keywords($keywords = array()) {
        foreach ($keywords as $key) {
            $links = $this->CI->bomnegocio->search($key);
            foreach ($links as $link) {
                $p = new ProductBomNegocio();
                $p->where('url', $link);
                $p->get();
                if(!$p->exists()){
                    $p->searched_keyword = $key;
                    $p->url = $link;
                    $p->save();
                }
            }
        }

    }

}

?>