<?php

//include_once(APPPATH . 'modules/base/controllers/base_controller.php');

class Api extends CI_Controller {

  function Api() {
    parent::__construct();
  }

  function index() {
    echo anchor('crawlers', 'Crawlers');
    echo '<br>';
    echo anchor('api/search', 'search');
    echo '<br>';
    echo anchor('api/crawproducts', 'crawproducts');
    echo '<br>';
    echo anchor('api/apiGetProducts', 'apiGetProducts');
    echo '<br>';
    echo anchor('api/ads', 'ads');
    echo '<br>';
    echo anchor('api/view', 'view');
    echo '<br>';
  }

  function teste(){
    //$this->bomnegocio->read_product('http://df.bomnegocio.com/distrito-federal-e-regiao/celulares/bateria-iphone-4-bateria-iphone-4s-129-99-instal-37765807');
  }

  function search() {
    $p = new Productbomnegocio();
    $p->auto_craw();
  }

  function crawproducts() {
    $p = new Productbomnegocio();
    $p->craw_products();
  }

  function view() {
      $date =  date('Y-m-d H:i:s O', time(strtotime('yesterday')));
      $p = new Productbomnegocio();
      $p->where('price >', 200);
      //$p->like('title', '64');
      //$p->or_like('description', '64');
      $p->order_by('date', 'desc');
      //$p->where('date >', $date);
      $p->get();
      //$p->check_last_query();
      $data['products'] = $p;
      $this->load->view('view',$data);
  }

  function ads(){
    $this->bomnegocio->refreshAds();
  }

  function apiGetProducts() {
    $products = new Productbomnegocio();
    $products->get();
    header('Content-Type: application/json');
    foreach($products as $p){
      echo $p->to_json();
    }
  }

}

/* End of file site.php */
/* Location: ./application/controllers/site.php */




    //     $res = strpos($html, 'Nenhum an?ncio foi encontrado.');
    //     if($res){
    //         echo 'Nenhum anúncio foi encontrado.';
    //         exit;
    //     }
    //     $html = procurar('<ul class="list_adsBN">', $html, '!-- BEGIN ADSENSE/CRITEO -->');

    //     $itens = explode('<li class="list_adsBN_item">', $html);
    //     foreach ($itens as $a => $i) {
    //         $prod[$a]['link'] = procurar('href="', $i, '" title="');
    //         $prod[$a]['title'] = procurar('title="', $i, '">');

    // //            if ($a == 1) {
    // //                echo '<textarea>' .  $i. '</textarea><br>';
    // //            }
    //         if (strpos($i, 'sem foto')) {
    //             $prod[$a]['image'] = 'http://www.atacadaodasferramentas.com.br/fotos/thumbnails/190312-CHAVE_RODA_TIPO_L_135x0.jpg';
    //         } else {
    //             if (strpos($i, '<img class="image lazy" src="/img/transparent.png" data-original="')) {
    //                 $prod[$a]['image'] = procurar('<img class="image lazy" src="/img/transparent.png" data-original="', $i, '" alt="');
    //             } else {
    //                 $prod[$a]['image'] = procurar('image" src="', $i, '" alt="');
    //             }
    //         }
    //         $prod[$a]['desc'] = trim(preg_replace('/\s+/', ' ', procurar('<p class="text mb5px">', $i, '</p>', 2)));
    //         $prod[$a]['data'] = trim(preg_replace('/\s+/', ' ', procurar('<p class="text">', $i, '</p>')));
    //         $prod[$a]['hora'] = trim(preg_replace('/\s+/', ' ', procurar('<p class="text mb5px">', $i, '</p>', 4)));
    //         $prod[$a]['price'] = procurar('<p class="price"> R$ ', $i, '</p>');
    //     }
    //     if (empty($prod[0]['link'])) {
    //         unset($prod[0]);
    //     }
    //     debug($prod);

        // echo count($prod).' resultados<br />';
        // echo anchor($data[0]['url'],$data[0]['url']).'<br />';
        // foreach ($prod as $p) {
        //     if (empty($p['link'])) {
        //         continue;
        //     }
        //     echo '<div class="item">';
        //     echo anchor($p['link'], img($p['image']));
        //     echo '<br />';
        //     echo $p['desc'];
        //     echo '<br />';
        //     echo $p['price'];
        //     echo '<br />';
        //     echo $p['data'];
        //     echo '<br />';
        //     echo $p['hora'];
        //     echo '</div>';
        // }