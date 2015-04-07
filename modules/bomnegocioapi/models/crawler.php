<?php
include_once(APPPATH . 'models/datamapperex.php');

Class Crawler extends DataMapperExt {

    var $table = 'crawler';
    var $links = array();

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

    function getUrl(){
        if(!empty($this->region) ){
            $url = $this->region.'?q='.urlencode($this->keyword).'&o='.$this->_cur_page;
        }else{
            $url = 'http://www.bomnegocio.com/brasil?q='.urlencode($this->keyword).'&o='.$this->_cur_page;
        }
        return $url;
    }

    function getSearchResults(){ // A
        $i=0;
        $this->html = array();
        while ($this->_cur_page <= $this->_last_page) {
            // Prepara url
            $url = $this->getUrl();

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
