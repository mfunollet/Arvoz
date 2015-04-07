<?php

include_once(APPPATH . 'modules/base/controllers/base_controller.php');

class Danboor extends Base_Controller {

    function Danboor() {
        parent::__construct();
        $this->load->library('powerdt/curl');
    }

    function index() {
        if (isset($_POST['tag'])) {
            $limit = (isset($_POST['limit'])) ? $_POST['limit'] : 500;

            //$data[0]['url'] = 'http://danbooru.donmai.us/user/authenticate';
            $data[0]['post']['commit'] = 'Login';
            $data[0]['post']['url'] = '';
            $data[0]['post']['user[name]'] = 'Alface';
            $data[0]['post']['user[password]'] = 'LIMELAM1';
            $data[0]['cookie'] = 'danboor';
            //$data[0]['cookie_modo'] = 'criar';

            //$html = $this->curl->get($data);


            $data[0]['url'] = "http://danbooru.donmai.us/post/index.xml?limit=" . $limit . "&tags=" . $_POST['tag'];
            $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.google.com.br/';

            $html = $this->curl->get($data);
            echo $html;
            $xml = simplexml_load_string($html);

            foreach ($xml->post as $post) {
                echo anchor($post['file_url'],$post['file_url']);
                echo '<br />';
            }
        } else {
            echo form_open('danboor') . 'tags:' . form_input('tag') .
            '<br />' . 'limite:' . form_input('limit', 500) . '<br />' . form_submit('Enviar', 'Enviar') . form_close();
        }
    }
}

/* End of file site.php */
/* Location: ./application/controllers/site.php */
