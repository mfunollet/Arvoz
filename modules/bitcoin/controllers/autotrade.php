<?php

//include_once(APPPATH . 'modules/base/controllers/base_controller.php');

class Autotrade extends CI_Controller {

    function Bitcoin() {
        parent::__construct();
    }

  //   function index() {
  //       echo 'ok2';
  //       /*$data[0]['url'] = 'https://www.mercadobitcoin.com.br/api/ticker/';
  //       //$data[0]['post']['DEBUG'] = true;


  //       $html = $this->curl->get($data);
  //       echo $html;*/


		// // $ch = curl_init('https://www.mercadobitcoin.com.br/api/ticker/');
  // //       curl_setopt_array($ch, array(
  // //       CURLOPT_SSL_VERIFYPEER => false,
  // //       CURLOPT_CONNECTTIMEOUT => 10, 
  // //       CURLOPT_RETURNTRANSFER => 1, 
  // //       CURLOPT_TIMEOUT => 60
  // //   ));

  // //       //if(!empty($opts))
  // //       //    curl_setopt_array($ch, $opts);

  // //       $return["body"] = json_decode(curl_exec($ch));
  // //       $return["httpCode"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);

  // //       curl_close($ch);

  // //       var_dump($return);

  //   }

    function testea(){
        $tapi = new Tapi();
        $tapi->getInfo();
    }

}

/* End of file site.php */
/* Location: ./application/controllers/site.php */
