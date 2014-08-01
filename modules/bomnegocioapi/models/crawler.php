<?php
include_once(APPPATH . 'models/datamapperex.php');

Class Crawler extends DataMapperExt {

    var $table = 'crawler';

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

    private function _next_page() {
        $link = $this->html->find('link[rel=next]',0);
        if(is_null($link)){
            return null;
        }
        return $link->href;
    }

    public function obtemPaginaDeAnuncios($url){
        $links = array();
        // Prepara o request curl
        $data[0]['url'] = $url;
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://google.com/';
        $html = $this->CI->curl->get($data);
        $this->html = str_get_html($html);

        // Obtem links do resultado da busca
        foreach($this->html->find('ul.list_adsBN') as $ul){
            foreach ($ul->find('li.list_adsBN_item') as $li) {
                // Obtem link da linha
                $link = $li->find('a',1);
                $link = (!is_null($link)) ? $link->href : '';
            }
        }

        // Verifica se existe mais paginas
        $next_link = $this->_next_page();

        // Se houver link para proxima página executa chamada recursiva
        if($next_link != NULL){
            log_message('info', 'Rastreando proxima pagina');
            return array_merge($links, $this->obtemPaginaDeAnuncios($next_link));
        }
        log_message('info', 'Rastreamento completo');
        return $links;
    }

    public function obteranuncios(){
        foreach($this->all as $c){
            log_message('info', 'Obtendo urls da busca = '.$c->keyword);
            $this->obtemPaginaDeAnuncios('http://www.bomnegocio.com/brasil?ot=1&ott=1&q='.urlencode($c->keyword));
    
            // Verifica se o link ja está indexado
            $p = new Product();
            $p->where('url', $link);
            $p->get();
            
            // Se não estiver indexado adiciona
            if(!$p->exists()){
                // Salva link associado ao crawler
                $p->url = $link;
                $p->save($c);
                log_message('info', 'Anuncio novo =  '.$c->keyword.'->'.$p->id);
            }
            
        }

    }
    
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
