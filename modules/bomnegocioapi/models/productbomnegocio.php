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

    public function auto_craw(){
        $craws = new Crawler();
        $craws->get();
        foreach($craws as $c){
            $keys[] = $c->keyword;
        }
        $this->craw_by_keywords($keys);
    }

    public function craw_products() {
        //$this->where('url != NULL');
        // update_time < 10 min
        $this->get();
        if($this->count() > 0){
            $this->CI->bomnegocio->read_product($this);
        }else{
            echo 'nenhum produto registrado';
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