<?php

class Bomnegocio {

    var $html;

    function __construct($params = array()) {
        $this->CI = & get_instance();
        $this->terms_list['tipo'] = 'type';
        $this->terms_list['novo/usado'] = 'state';
        $this->terms_list['município'] = 'city';
        $this->terms_list['bairro'] = 'district';
        $this->terms_list['cep'] = 'zip_code';
        $this->terms_list['categoria'] = 'category';
        $this->terms_list['modelo'] = 'model';
        $this->terms_list['acessório'] = 'accessory';

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
            $data[0]['url'] = 'http://www.bomnegocio.com/brasil?ot=1&ott=1&q='.urlencode($q);
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


    public function read_product($_products = '') {
        if( is_string($_products) ){
            $products[0] = new stdClass();
            $products[0]->url = $_products;
        }else{
            $products = $_products;
        }
        /*$data[0]['url'] = 'http://rj.bomnegocio.com/rio-de-janeiro-e-regiao/audio-tv-video-e-fotografia/docking-station-sony-rdp-m7ip-iphone-4-4s-ipod-36314008';
        $data[0]['url'] = 'http://rj.bomnegocio.com/rio-de-janeiro-e-regiao/celulares/tela-de-iphone-5-preto-trocada-na-hora-centro-35374842';
        $data[0]['url'] = 'http://df.bomnegocio.com/distrito-federal-e-regiao/celulares/bateria-iphone-4-bateria-iphone-4s-129-99-instal-37765807';*/
        foreach($products as $k => $p){
            $data[$k]['url'] = $p->url;
            $data[$k]['opcoes']['CURLOPT_REFERER'] = 'http://google.com/';
        }
        $htmls = $this->CI->curl->get($data);

        foreach ($htmls as $k => $html) {
            $html = utf8_encode($html);
            $this->html = str_get_html($html);

            // Product was removed?
            //$data[$k]['opcoes']['CURLOPT_FOLLOWLOCATION'] = FALSE;
        }
        $htmls = $this->CI->curl->get($data);

        if(!is_array($htmls)){
            $htmls = array($htmls);
        }

        foreach ($htmls as $k => $html) {
            if(empty($html)){
                // se encontrar a mensagem "O anúncio não foi encontrado. Possíveis razões:" troca para vendido e pula
                $p = new ProductBomNegocio();
                $p->where('url', $data[$k]['url']);
                $p->get();
                if($p->exists()){
                    $p->status = 1;
                    $p->save();
                }
                continue;
            }
            $html = utf8_encode($html);
            $this->html = str_get_html($html);
            // echo $data[$k]['url'];
            // echo '<br>';
            // var_dump(is_null($this->html->find('.vi_adBN_not_found_section',0)));
            // echo "<textarea>";
            // echo $html;
            // echo "</textarea>";
            // // Product was removed?
            // if( ! is_null($this->html->find('h4.msg')) ){
            // }

            $image          = $this->html->find('img.image',0);
            $image          = (!is_null($image)) ? $image->src : '';
            

            $title          = $this->html->find('#ad_title',0)->innertext;
            $title          = utf8_decode(trim(substr($title,0,strpos($title, '<span class="price highlight">')-3)));

            $description    = trim($this->html->find('.description',0)->plaintext);

            $title          = $this->html->find('#ad_title',0);
            if(!is_null($title)){
                $title          = $title->innertext;
                $title          = utf8_decode(trim(substr($title,0,strpos($title, '<span class="price highlight">')-3)));
            }else{
                echo 'problema com: ';
                echo $data[$k]['url'];
            }

            $description    = $this->html->find('.description',0);
            $description    = (!is_null($description)) ? trim($description->plaintext):'';

            $price          = $this->html->find('.field_price span',0);
            $price          = (is_null($price)) ? 0 :  substr($price->plaintext,2);
            
            $date           = $this->html->find('div[class=adBN_header mb10px] p.text',0)->plaintext;
            $date           = trim(str_replace('Inserido em: ', '', $date));
            $date           = trim(substr($date, 0, -1));
            $day            = trim(substr($date, 0, strpos($date, ' ')));
            $month          = mes2numero(strtolower(trim(substr($date, strpos($date, ' '), strrpos($date, ' ')-1))));
            $hour           = trim(substr($date, strpos($date, ':')-2, 2));
            $minute         = trim(substr($date, strpos($date, ':')+1));
            $timestamp      = mktime($hour, $minute, 0, $month, $day, date('Y'));
            
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
                    $term = utf8_decode($term->plaintext);
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
                //$term = str_replace('í', 'i', $term);
                $product[$this->terms_list[$term]] = trim($item->find('.description',0)->plaintext);
            }

            $product['title']       = utf8_encode($title);
            $product['image']       = $image;
            $product['description'] = $description;
            $product['date']        = $date;
            $product['price']       = $price;
            $product['date']        = date('Y-m-d H:i:s O', $timestamp);
            $product['price']       = $price;
            $product['seller']       = $seller;
            $product['seller_phone'] = $seller_phone;
            $product['code']        = $code;

            // Lon Lat
            $this->CI->load->library('Gmap');
            $this->CI->gmap->GoogleMapAPI();
            $loc = $this->CI->gmap->getGeocode($product['zip_code'].' - '. $product['district'].' - '. $product['city']);
            $product['lat'] = $loc['lat'];
            $product['lon'] = $loc['lon'];


            // Save product
            $p = new ProductBomNegocio();
            $p->where('url', $data[$k]['url']);
            $p->get();
            foreach($product as $field => $value){
                $p->{$field} = $value;
            }

            $p->save();
        }
    }
    
    private function get_page_links() {
        foreach($this->html->find('ul.list_adsBN') as $ul){
            foreach ($ul->find('li.list_adsBN_item') as $li) {
                $link = $li->find('a',1);
                $links[] = (!is_null($link)) ? $link->href : '';
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