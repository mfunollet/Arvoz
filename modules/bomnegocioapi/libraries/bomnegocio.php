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
        $data[0]['url'] = 'http://rj.bomnegocio.com/rio-de-janeiro-e-regiao/celulares/tela-de-iphone-5-preto-trocada-na-hora-centro-35374842';
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://google.com/';
        $html = $this->CI->curl->get($data);
        $this->html = str_get_html($html);

        $description    = $this->html->find('.description',0)->plaintext;
        $price          = $this->html->find('.field_price span',0)->plaintext;
        
        $date           = $this->html->find('div[class=adBN_header mb10px] p.text',0)->plaintext;
        $date           = trim(str_replace('Inserido em: ', '', $date));
        $date           = substr($date, 0, -1);
        
        $seller         = $this->html->find('li[class=item owner] p.text',0)->plaintext;
        $seller_phone   = 'http://bomnegocio.com/'.trim($this->html->find('img.number',0)->src);
        
        $code           = $this->html->find('ul[class=list list_id last] p.description',0)->plaintext;

        // Details from product
        foreach($this->html->find('div[class=ad_details_section] li.item') as $item) {
            $term = $item->find('.term',0)->plaintext;
            $term = substr($term, 0, -1);
            $info[$term] = trim($item->find('.description',0)->plaintext);
        }

        // Location
        foreach($this->html->find('ul[class=list location] li') as $item) {
            $term = $item->find('.term',0)->plaintext;
            $term = substr($term, 0, -1);
            $info[$term] = trim($item->find('.description',0)->plaintext);
        }

        
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