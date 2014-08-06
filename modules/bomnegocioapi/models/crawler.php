<?php
include_once(APPPATH . 'models/datamapperex.php');

Class Crawler extends DataMapperExt {

    var $table = 'crawler';
    var $links = array();
    var $products_details = array();

    var $html = array();
    var $_cur_page = 1;
    var $_last_page = 1;

    public $has_many = array(
        'product' => array(
            'class' => 'product',
        )
    );

    public $validation = array(
        'keyword' => array(
            'rules' => array('required', 'trim', 'unique')
        ),
        'region' => array(
            'rules' => array('trim')
        ),
        /*,
        'url' => array(
            'rules' => array('required', 'trim', 'prep_url')
        )*/
    );

    function __construct($id = NULL) {
        parent::__construct($id);
    }

//     private function _next_page() {
//         $link = $this->html->find('link[rel=next]',0);
//         if(is_null($link)){
//             return null;
//         }
//         return $link->href;
//     }

//     public function obtemPaginaDeAnuncios($url){
//         log_message('info', 'Rastreando: '.$url);
//         log_message('info', 'Usando: '.convert(memory_get_usage(true)) );
//         $links = array();

//         // Prepara o request curl
//         $data[0]['url'] = $url;
//         $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://google.com/';
//         $html = $this->CI->curl->get($data);
//         $this->html = str_get_html($html);
//         $i = 0;
//         // Obtem links do resultado da busca
//         foreach ($this->html->find('li.list_adsBN_item') as $li) {
//             // Obtem link da linha
//             $link = $li->find('a',1);
//             $link = ( !is_null($link) ) ? $link->href : '';
//             $links[] = $link;
//         }

//         // Verifica se existe mais paginas
//         $next_link = $this->_next_page();

//         // Se houver link para proxima página executa chamada recursiva
//         if($next_link != NULL){
//             unset($html);
//             unset($this->html);
//             log_message('info', 'Rastreando proxima pagina');
//             return array_merge($links, $this->obtemPaginaDeAnuncios($next_link));
//         }
//         return $links;
//         log_message('info', 'FIM?');
// //        return $links;
//     }

//     public function obteranuncios(){
//         foreach($this->all as $c){
//             log_message('info', 'Obtendo urls da busca = '.$c->keyword);
//             $a = $this->obtemPaginaDeAnuncios('http://www.bomnegocio.com/brasil?ot=1&ott=1&q='.urlencode($c->keyword));
//             //print_r($a);
//             log_message('info', 'Usando fim: '.convert(memory_get_usage(true)) );
//             exit;
    
//             // Verifica se o link ja está indexado
//             $p = new Product();
//             $p->where('url', $link);
//             $p->get();
            
//             // Se não estiver indexado adiciona
//             if(!$p->exists()){
//                 // Salva link associado ao crawler
//                 $p->url = $link;
//                 $p->save($c);
//                 log_message('info', 'Anuncio novo =  '.$c->keyword.'->'.$p->id);
//             }
            
//         }

//     }


    ## Craw Products begin ##

    function getSearchResults(){ // A
        $i=0;
        $this->html = array();
        while ($this->_cur_page <= $this->_last_page) {
            // Prepara url
            $url = 'http://www.bomnegocio.com/brasil?ot=1&ott=1&q='.urlencode($this->keyword).'&o='.$this->_cur_page;
            log_message('info', 'Rastreando pag '.$this->_cur_page.'/'.$this->_last_page.'  '.$url);

            // Prepara o request curl
            $data[$i]['url'] = $url;
            $data[$i]['opcoes']['CURLOPT_REFERER'] = 'http://google.com/';
            $i++;
            $this->_cur_page++;
        }

        $htmls = $this->CI->curl->get($data);
        
        if(!is_array($htmls)){
            $htmls = array($htmls);
        }

        foreach ($htmls as $html) {
            $this->html[] = str_get_html($html);
        }
    }

    function extractLinks(){ // B
        // Obtem links do resultado da busca
        log_message('info', 'Extraindo links de '.count($this->html). ' buscas');
        foreach ($this->html as $html) {
            foreach ($html->find('li.list_adsBN_item') as $li) {
                // Obtem link da linha
                $link = $li->find('a',1);
                $link = ( !is_null($link) ) ? $link->href : '';
                $this->links[] = $link;
            }
        }

        // Verifica se existe mais paginas
        $_last_page = $this->_last_page();

        $page_str = '['.$this->_cur_page.'/'.$this->_last_page.'] ';

        // Se houver mais páginas
        if($_last_page > 0){
            log_message('info', $page_str.'Obtendo buscas em lote');
            $this->getSearchResults();
            $this->extractLinks(); 
            return;
        }
        log_message('info', $page_str.'Busca concluida');
        log_message('info', 'Memory usage: '.convert(memory_get_usage(true)) );
    }

    function _last_page(){
        $_last_page = 0;
        end($this->html); 
        $html = $this->html[key($this->html)];
        $link = $html->find('li[class=item last] a',0);
        if( ! is_null($link)){
            $_last_page = intval(procurar('o=', $link->href, '&'));
        }
        log_message('info', 'Last page:'.$_last_page);
        $this->_last_page = $_last_page;
        return $_last_page;
    }

    function saveProducts(){
        $this->CI->benchmark->mark('code_start');
        $i=0;
        foreach($this->links as $link){
            $p = new Product();
            $p->where('url', $link);
            $p->get();

            // Se não estiver indexado adiciona
            if(!$p->exists()){
                // Salva link associado ao crawler
                $p->url = $link;
                $p->save($this);
                log_message('info', '['.$this->keyword.'] Produto novo adicionado product_id = '.$p->id);
                $i++;
            }
        }
        $this->CI->benchmark->mark('code_end');
        $time = $this->CI->benchmark->elapsed_time('code_start', 'code_end');

        log_message('info', '['.$this->keyword.'] '.$i.' produtos novos adicionados. Em '.$time.' segs');
    }
    ## Craw Products end ##




    ## Craw Products begin ##

    function crawProductsDetails() {
        $this->CI->load->library('Gmap');
        $this->CI->gmap->GoogleMapAPI();
        $i=0;
        foreach($this->product as $p){
            $data[$i]['url'] = $p->url;
            $data[$i]['opcoes']['CURLOPT_REFERER'] = 'http://google.com/';
            $i++;
        }
        $htmls = $this->CI->curl->get($data);


        if(!is_array($htmls)){
            //utf8_encode(
            $htmls = array($htmls);
        }
        log_message('info', '['.$this->keyword.'] Obtidos '.count($htmls).' páginas de produtos.');

        foreach($htmls as $k => $html) {
            if(empty($html)){
                // se encontrar a mensagem "O anúncio não foi encontrado. Possíveis razões:" troca para vendido e pula
                $p = new Product();
                $p->where('url', $data[$k]['url']);
                $p->get();
                if($p->exists()){
                    $p->status = 1;
                    $p->save();
                }
                continue;
            }
            $html = str_get_html($html);

            $image          = $html->find('img.image',0);
            $image          = (!is_null($image)) ? $image->src : '';
            

            $title          = $html->find('#ad_title',0)->innertext;
            $title          = trim(substr($title,0,strpos($title, '<span class="price highlight">')-3));

            $description    = trim($html->find('.description',0)->plaintext);

            $title          = $html->find('#ad_title',0);
            if(!is_null($title)){
                $title          = $title->innertext;
                $title          = trim(substr($title,0,strpos($title, '<span class="price highlight">')-3));
            }else{
                log_message('info', '['.$data[$k]['url'].'] nao possui titulo');
                $title = '';
            }

            $description    = $html->find('.description',0);
            $description    = (!is_null($description)) ? trim($description->plaintext):'';

            $price          = $html->find('.field_price span',0);
            $price          = (is_null($price)) ? 0 :  substr($price->plaintext,2);
            
            $date           = $html->find('div[class=adBN_header mb10px] p.text',0)->plaintext;
            $date           = trim(str_replace('Inserido em: ', '', $date));
            $date           = trim(substr($date, 0, -1));
            $day            = trim(substr($date, 0, strpos($date, ' ')));
            $month          = mes2numero(strtolower(trim(substr($date, strpos($date, ' '), strrpos($date, ' ')-1))));
            $hour           = trim(substr($date, strpos($date, ':')-2, 2));
            $minute         = trim(substr($date, strpos($date, ':')+1));
            $timestamp      = mktime($hour, $minute, 0, $month, $day, date('Y'));
            
            $seller         = $html->find('li[class=item owner] p.text',0)->plaintext;
            $seller_phone   = $html->find('img.number',0);
            if(!is_null($seller_phone)){
                $seller_phone   = 'http://bomnegocio.com/'.trim($seller_phone->src);
            }
            
            $code           = $html->find('ul[class=list list_id last] p.description',0)->plaintext;

            // Get terms translations from config
            $terms_list = $this->CI->config->item('terms_list');

            // Details from product
            foreach($html->find('div[class=ad_details_section] li.item') as $item) {
                $term = $item->find('.term',0);
                if(!is_null($term)){
                    $term = $term->plaintext;
                    $term = substr($term, 0, -1);
                    $term = strtolower($term);
                    $product[$terms_list[$term]] = trim($item->find('.description',0)->plaintext);
                }
            }

            // Location
            foreach($html->find('ul[class=list location] li') as $item) {
                $term = $item->find('.term',0)->plaintext;
                $term = substr($term, 0, -1);
                $term = strtolower($term);
                //$term = str_replace('í', 'i', $term);
                $product[$terms_list[$term]] = trim($item->find('.description',0)->plaintext);
            }

            $product['url']         = $data[$k]['url'];
            $product['title']       = $title;
            $product['image']       = $image;
            $product['description'] = $description;
            $product['date']        = $date;
            $product['price']       = $price;
            $product['date']        = date('Y-m-d H:i:s O', $timestamp);
            $product['price']       = $price;
            $product['seller']       = $seller;
            $product['seller_phone'] = $seller_phone;
            $product['code']        = $code;

            if(!empty($product['zip_code']) and
                !empty($product['district']) and
                !empty($product['city'])
                ){
                // Lon Lat
                $loc = $this->CI->gmap->getGeocode($product['zip_code'].' - '. $product['district'].' - '. $product['city']);
                $product['lat'] = $loc['lat'];
                $product['lon'] = $loc['lon'];                
            }

            log_message('info', 'Dados extraidos code='.$product['code']);
            $this->products_details[] = $product;
        }
    }

    function saveProductsDetails(){
        $this->CI->benchmark->mark('code_start');
        $i=0;
        foreach($this->products_details as $product){
            $p = new Product();
            $p->where('url', $product['url']);
            foreach($product as $field => $value){
                if(empty($field)) {
                    log_message('info', 'empty -> '. $field);
                }else{
                    $p->{$field} = $value;
                }
            }
            $p->save();
            log_message('info', '['.$this->keyword.'] Detalhes do produto adicionado em product_id = '.$p->id);
            $i++;
        }
        $this->CI->benchmark->mark('code_end');
        $time = $this->CI->benchmark->elapsed_time('code_start', 'code_end');

        log_message('info', '['.$this->keyword.'] '.$i.' detalhes de produtos adicionados. Em '.$time.' segs');
    }


    ## Craw Products end ##

    
    // function _saveExtra($id = NULL) {
    //     $rel = NULL;
    //     $logged_company = $this->CI->authentication->get_logged_company();
    //     $logged_user = $this->CI->authentication->get_logged_user();

    //     if ($logged_company) {
    //         $rel = $logged_company;
    //     } elseif ($logged_user) {
    //         $rel = $logged_user;
    //     } else {
    //         return FALSE;
    //     }
    //     return $this->save($rel);
    // }

}
