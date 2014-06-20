<?php

class Bomnegocio {

    var $html;

    function __construct($params = array()) {
        $this->CI = & get_instance();
        //parent::__construct();
    }

    public function refreshAds(){
        $data[0]['url'] = 'http://www.bomnegocio.com/sold_ads_new_images.json?_=1403180725887';
        $json = $this->CI->curl->get($data);
        $data = json_decode($json);
        foreach($data->images as $img){
            $imgs[] = $img->url;
        }
    }

    public function search($q = ''){
        $data[0]['url'] = 'http://www.bomnegocio.com/rio_de_janeiro/rio_de_janeiro_e_regiao/centro?sp=1&q=iphone'.urlencode($q);
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://google.com/';
        $html = $this->CI->curl->get($data);
        $this->html = str_get_html($html);

        $links = $this->get_page_links();
        $next_link = $this->_next_page();
        print_r($links);
        print_r($next_link);
    }

    public function read_product($url = '') {
        $data[0]['url'] = 'http://rj.bomnegocio.com/rio-de-janeiro-e-regiao/audio-tv-video-e-fotografia/docking-station-sony-rdp-m7ip-iphone-4-4s-ipod-36314008';
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://google.com/';
        $html = $this->CI->curl->get($data);
        $this->html = str_get_html($html);

        echo $this->html->find('.description',0)->plaintext;
    }
    
    private function get_page_links() {
        foreach($this->html->find('ul.list_adsBN') as $ul){
            foreach ($ul->find('li.list_adsBN_item') as $li) {
                $links[] = $li->find('a',1)->href;
            }
        }
        return $links;
    }

    private function _next_page() {
        return $this->html->find('link[rel=next]',0)->href;
    }

}