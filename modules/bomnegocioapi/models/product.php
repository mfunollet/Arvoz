<?php

class Product extends DataMapperExt {

    var $table = 'crawler_product';
    
    public $has_one = array(
        'crawler' => array(
            'class' => 'crawler',
        )
    );

    var $html;

    function __construct($id = NULL) {
        //register_shutdown_function(array(&$this, 'MyDestructor'));
        parent::__construct($id);
    }

    function MyDestructor() {
        echo 'Script executed with success' . PHP_EOL;
        echo $this->email;
    }


    public function refreshAds(){
        $data[0]['url'] = 'http://www.bomnegocio.com/sold_ads_new_images.json?_=1403180725887';
        $json = $this->CI->curl->get($data);
        $data = json_decode($json);
        foreach($data->images as $img){
            $imgs[] = $img->url;
        }
    }
}

?>