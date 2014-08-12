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
            $url = 'http://www.bomnegocio.com/brasil?q='.urlencode($this->keyword).'&o='.$this->_cur_page;

            // Prepara o request curl
            $data[$i]['url'] = $url;
            $data[$i]['opcoes']['CURLOPT_REFERER'] = 'http://google.com/';
            $i++;
            $this->_cur_page++;
        }
        //var_dump($data);

        log_message('info', 'Rastreando total de: '.$i.' paginas');
        $this->CI->benchmark->mark('code_start');
        
        $htmls = $this->CI->curl->get($data);

        $this->CI->benchmark->mark('code_end');
        $time = $this->CI->benchmark->elapsed_time('code_start', 'code_end');
        log_message('info', 'Levou '.$time.'segs para acessar '.$i.' paginas');

        if(!is_array($htmls)){
            $htmls = array($htmls);
        }

        foreach ($htmls as $html) {
            //var_dump($html);
            $this->html[] = str_get_html($html);
        }
    }

    function extractLinks(){ // B
        // Obtem links do resultado da busca
        log_message('info', 'Extraindo links de '.count($this->html). ' paginas');
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
        log_message('info', 'paginas='.$_last_page);

        $page_str = '['.$this->_cur_page.'/'.$this->_last_page.'] ';

        // Se houver mais páginas
        if($_last_page > 0/* && $this->_cur_page < $_last_page*/){
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
        //var_dump($link);
        if( ! is_null($link)){
            $_last_page = intval(procurar('o=', $link->href, '&'));
        }
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




    ## Craw Products Details begin ##

    function crawProductsDetails() {
        // Get terms translations from config
        $this->CI->config->load('terms_list', TRUE);

        $this->CI->load->library('Gmap');
        $this->CI->gmap->GoogleMapAPI();
        $i=0;
        foreach($this->product as $p){
            $data[$i]['url'] = $p->url;
            $data[$i]['opcoes']['CURLOPT_REFERER'] = 'http://google.com/';
            $i++;
        }
        // unset($data);
        // $data[0]['url'] = 'http://sp.bomnegocio.com/sao-paulo-e-regiao/videogames/jogos-de-game-boy-pokemon-silver-e-outros-38492973';
        // $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://google.com/';
        log_message('info', 'Acessando '.$i.' produtos');
        $this->CI->benchmark->mark('code_start');
        
        $htmls = $this->CI->curl->get($data);
        
        $this->CI->benchmark->mark('code_end');
        $time = $this->CI->benchmark->elapsed_time('code_start', 'code_end');
        log_message('info', 'Levou '.$time.'segs para acessar '.$i.' produtos');


        if(!is_array($htmls)){
            $htmls = array($htmls);
        }
        log_message('info', '['.$this->keyword.'] Obtidos '.count($htmls).' detalhes de produtos.');

        foreach($htmls as $k => $html) {
            if(empty($html) OR (strpos($html, "O anúncio não foi encontrado. Possíveis razões:")) ){
                // Se encontrar esta mensagem, troca para vendido e pula
                $p = new Product();
                $p->where('url', $data[$k]['url']);
                $p->get();
                if($p->exists()){
                    $status = (empty($html)) ? 1 : 2;
                    $p->status = $status;
                    $p->save();
                }
                continue;
            }
            $this->products_details[] =  $this->extractProductsDetails(str_get_html($html), $data[$k]['url']);
            log_message('info', 'Memory usage: '.convert(memory_get_usage(true)) );
        }
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
        $price          = (!is_null($price)) ? trim(substr($price->plaintext,2)) : 0;
        
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

        log_message('info', 'Dados extraidos com sucesso do produto code='.$product['code']);

        return $product;
    }

    function saveProductsDetails(){
        $this->CI->benchmark->mark('code_start');
        $i=0;
        log_message('info', '['.$this->keyword.'] Salvando detalhes dos produtos');
        foreach($this->products_details as $product){
            $p = new Product();
            $p->where('url', $product['url']);
            $p->get();
            foreach($product as $field => $value){
                if(empty($field)) {
                    log_message('info', 'empty -> '. $field);
                }else{
                    $p->{$field} = $value;
                }
            }
            $p->save();
            $i++;
        }
        $this->CI->benchmark->mark('code_end');
        $time = $this->CI->benchmark->elapsed_time('code_start', 'code_end');

        log_message('info', '['.$this->keyword.'] Detalhes dos produtos salvos. Total de '.$i.'. Em '.$time.' segs');
    }


    ## Craw Products Details end ##

    
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
