<?php

class Bomnegocio {

    var $html;

    function __construct($params = array()) {
        $this->CI = & get_instance();
        $this->terms_list['tipo'] = 'type';
        $this->terms_list['novo/usado'] = 'state';
        $this->terms_list['tipo'] = 'type';
        $this->terms_list[utf8_decode('município')] = 'city';
        $this->terms_list['bairro'] = 'district';
        $this->terms_list['cep'] = 'zip_code';
        $this->terms_list['categoria'] = 'category';

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

    public function search($q = '', $url = ''){
        if(empty($url)){
            $data[0]['url'] = 'http://www.bomnegocio.com/rio_de_janeiro/rio_de_janeiro_e_regiao/centro?sp=1&q='.urlencode($q);
        }else{
            $data[0]['url'] = $url;
        }
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://google.com/';
        $html = $this->CI->curl->get($data);
        $this->html = str_get_html($html);

        $links = $this->get_page_links();
       /* $next_link = $this->_next_page();
        
        if(is_null($next_link)){
            return $links;
        }*/

        return $links;
        //return array_merge($links, $this->search($q, $next_link));
    }

    public function read_product($url = '') {
        /*$data[0]['url'] = 'http://rj.bomnegocio.com/rio-de-janeiro-e-regiao/audio-tv-video-e-fotografia/docking-station-sony-rdp-m7ip-iphone-4-4s-ipod-36314008';
        $data[0]['url'] = 'http://rj.bomnegocio.com/rio-de-janeiro-e-regiao/celulares/tela-de-iphone-5-preto-trocada-na-hora-centro-35374842';
        $data[0]['url'] = 'http://df.bomnegocio.com/distrito-federal-e-regiao/celulares/bateria-iphone-4-bateria-iphone-4s-129-99-instal-37765807';*/
        
        $data[0]['url'] = $url;
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://google.com/';
        $html = $this->CI->curl->get($data);
        $this->html = str_get_html($html);

        // Product was removed?



        $title          = $this->html->find('#ad_title',0)->innertext;
        $title          = trim(substr($title,0,strpos($title, '<span class="price highlight">')-3));

        $description    = $this->html->find('.description',0)->plaintext;
        $price          = $this->html->find('.field_price span',0);
        $price          = (is_null($price)) ? 0 :  $price->plaintext;
        
        $date           = $this->html->find('div[class=adBN_header mb10px] p.text',0)->plaintext;
        $date           = trim(str_replace('Inserido em: ', '', $date));
        $date           = substr($date, 0, -1);
        
        $seller         = $this->html->find('li[class=item owner] p.text',0)->plaintext;
        $seller_phone   = $this->html->find('img.number',0);
        if(!is_null($seller_phone)){
            $seller_phone   = 'http://bomnegocio.com/'.trim($seller_phone->src);
        }
        
        $code           = $this->html->find('ul[class=list list_id last] p.description',0)->plaintext;

        // Details from product
        foreach($this->html->find('div[class=ad_details_section] li.item') as $item) {
            $term = $item->find('.term',0);
            if(!is_null($term)){
                $term = $term->plaintext;
                $term = substr($term, 0, -1);
                $term = strtolower($term);
                $product[$this->terms_list[$term]] = trim($item->find('.description',0)->plaintext);
            }
        }

        // Location
        foreach($this->html->find('ul[class=list location] li') as $item) {
            $term = $item->find('.term',0)->plaintext;
            $term = substr($term, 0, -1);
            $term = strtolower($term);
            $term = str_replace('í', 'i', $term);
            $product[$this->terms_list[$term]] = trim($item->find('.description',0)->plaintext);
        }

        $product['title']       = utf8_encode($title);
        $product['description'] = $description;
        $product['date']        = $date;
        $product['price']       = $price;
        $product['date']        = $date;
        $product['price']       = $price;
        $product['seller']       = $seller;
        $product['seller_phone'] = $seller_phone;

        return $product;
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
        $link = $this->html->find('link[rel=next]',0);
        if(is_null($link)){
            return null;
        }
        return $link->href;
    }

}