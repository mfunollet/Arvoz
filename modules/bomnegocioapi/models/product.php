<?php

class Product extends DataMapperExt {

    var $table = 'crawler_product';
    var $products_details = array();

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


    ## Craw Products Details begin ##
    function crawProductsDetails() {
        // Log SQL
        log_message('info', 'SQL: '.$this->check_last_query(FALSE, TRUE));

        // Get terms translations from config
        $this->CI->config->load('terms_list', TRUE);

        $this->CI->load->library('Gmap');
        $this->CI->gmap->GoogleMapAPI();
        $i=0;
        foreach($this as $p){
            $data[$i]['url'] = $p->url;
            $i++;
        }

        log_message('info', '['.$this->crawler->keyword.'] Carregando '.$i.' do bomnegocio');
        $this->CI->benchmark->mark('code_start');
        
        $htmls = $this->CI->curl->get($data);
        if(!is_array($htmls)){
            $htmls = array($htmls);
        }
        
        $this->CI->benchmark->mark('code_end');
        $time = $this->CI->benchmark->elapsed_time('code_start', 'code_end');
        log_message('info', 'Levou '.$time.'segs para acessar '.$i.' produtos');


        log_message('info', 'Obtidos '.count($htmls).' detalhes de produtos.');

        foreach($this as $k => $p) {
            $html = $htmls[$k];
            if(!isset($html[$k])){
                log_message('info', 'Request Error:#'.$k);
                continue;
            }
            if(empty($html) OR (strpos($html, "O anúncio não foi encontrado. Possíveis razões:")) ){
                // Se encontrar esta mensagem, troca para vendido e pula
                if($p->exists()){
                    $status = (empty($html)) ? 1 : 2;
                    $p->status = $status;
                    $p->save();
                }
                continue;
            }
            $this->products_details[] =  $this->extractProductsDetails(str_get_html($html), $p->url);
        }
        log_message('info', 'Memory usage: '.convert(memory_get_usage(true)) );
    }

    function extractProductsDetails($html, $url) {
        // http://img.bomnegocio.com/images/44/4444898106.jpg
        // http://img.bomnegocio.com/thumbs/44/4444898106.jpg
        $images = array();

        ## Imagem principal
        $image          = $html->find('img.image',0);
        $images[]       = (!is_null($image)) ? $image->src : '';

        ## Imagens opcionais
        $op_image          = $html->find('div.box_image a.link[target=_blank]');
        foreach($op_image as $op_img){
            $images[]          = (!is_null($op_img)) ? $op_img->href : '';            
        }
        $product['images']       = json_encode($images);

        ## Descrição
        $description    = $html->find('.description',0);
        $description    = (!is_null($description)) ? trim($description->plaintext):'';

        ## Título
        $title          = $html->find('#ad_title',0);
        if(!is_null($title)){
            $title          = $title->innertext;
            $title          = trim(substr($title,0,strpos($title, '<span class="price highlight">')-3));
        }else{
            log_message('info', '['.$data[$k]['url'].'] nao possui titulo');
            $title = '';
        }

        ## Preço
        $price          = $html->find('.field_price span',0);
        $price          = (!is_null($price)) ? trim(str_replace(".","", substr($price->plaintext,2))) : 0;
        
        ## Data do anúncio
        $date           = $html->find('div[class=adBN_header mb10px] p.text',0);
        if(!is_null($date)){
            $date           = $date->plaintext;
            $date           = trim(str_replace('Inserido em: ', '', $date));
            $date           = trim(substr($date, 0, -1));

            $day            = trim(substr($date, 0, strpos($date, ' ')));
            $month          = mes2numero(strtolower(trim(substr($date, strpos($date, ' '), strrpos($date, ' ')-1))));
            $hour           = trim(substr($date, strpos($date, ':')-2, 2));
            $minute         = trim(substr($date, strpos($date, ':')+1));
            $timestamp      = mktime($hour, $minute, 0, $month, $day, date('Y'));
        }

        ## Nome do vendedor
        $seller         = $html->find('li[class=item owner] p.text',0);
        $seller         = (!is_null($seller)) ? trim($seller->plaintext) : '';

        ## Telefone do vendedor
        $seller_phone   = $html->find('img.number',0);
        $seller_phone   = (!is_null($image)) ? $image->src : '';

        ## Code
        $code           = $html->find('ul[class=list list_id last] p.description',0)->plaintext;

        $terms_list = $this->CI->config->item('terms_list');    

        ## Primeira Area de atributos, todos são opcionais
        foreach($html->find('div[class=ad_details_section] li.item') as $item) {
            $term = $item->find('.term',0);
            if(!is_null($term)){
                $term = $term->plaintext;
                $term = substr($term, 0, -1); // remove o ":" do final
                $term = strtolower($term);
                if(isset($terms_list[$term])){
                    $product[$terms_list[$term]] = trim($item->find('.description',0)->plaintext);
                }else{
                    log_message('info', 'Termo sem tradução associado!'. $term);
                }
            }
        }

        ## Segunda Area de atributos relacionados a localização, todos são opcionais
        foreach($html->find('ul[class=list location] li') as $item) {
            $term = $item->find('.term',0)->plaintext;
            $term = substr($term, 0, -1); // remove o ":" do final
            $term = strtolower($term);
            if(isset($terms_list[$term])){
                $product[$terms_list[$term]] = trim($item->find('.description',0)->plaintext);
            }else{
                log_message('info', 'Termo sem tradução associado!'. $term);
            }
        }

        $product['url']         = $url;
        $product['title']       = $title;
        $product['description'] = $description;
        $product['date']        = date('Y-m-d H:i:s O', $timestamp);
        $product['price']       = $price;
        $product['seller']       = $seller;
        $product['seller_phone'] = $seller_phone;
        $product['code']        = $code;

        ## Lon Lat
        if(!empty($product['zip_code']) and
            !empty($product['district']) and
            !empty($product['city'])
            ){
            $addr = $product['zip_code'].' - '. $product['district'].' - '. $product['city'];
            $loc = $this->CI->gmap->getGeocode(utf8_encode($addr));
            $product['lat'] = $loc['lat'];
            $product['lon'] = $loc['lon'];                
        }

        //log_message('info', 'Dados extraidos com sucesso do produto code='.$product['code']);

        return $product;
    }

    function saveProductsDetails(){
        $this->CI->benchmark->mark('code_start');
        log_message('info', '['.$this->crawler->keyword.'] Salvando detalhes dos produtos do banco');

        foreach($this as $k => $p) {
            if($p->exists()){
                foreach($this->products_details[$k] as $field => $value){
                    if(empty($field)) {
                        log_message('info', 'empty -> '. $field);
                    }else{
                        $p->{$field} = $value;
                    }
                    $p->save();
                }
            }
        }

        $this->CI->benchmark->mark('code_end');
        $time = $this->CI->benchmark->elapsed_time('code_start', 'code_end');

        log_message('info', 'Detalhes dos produtos salvos. Total de '.$k.'. Em '.$time.' segs');
    }

    ## Craw Products Details end ##

}

?>