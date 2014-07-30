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

    public function search($q = '', $url = ''){
        if(empty($url)){
            $data[0]['url'] = 'http://www.bomnegocio.com/brasil?ot=1&ott=1&q='.urlencode($q);
        }else{
            $data[0]['url'] = $url;
        }
        log_message('info', 'searching for keyword: '.$data[0]['url']);

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