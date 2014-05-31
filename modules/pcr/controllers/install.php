<?php

include_once(APPPATH . 'modules/base/controllers/base_controller.php');

class Install extends Base_Controller {

    function __construct() {
        parent::__construct();
         $this->_add_menu_item('sidebar_left', 'Menu', array('install/chmodtest', 'Chmod Test'));
        $this->_add_menu_item('sidebar_left', 'Menu', array('install/chmodtest/TRUE', 'Executar Chmod Test'));
        $this->_add_menu('left', 'sidebar_left');
    }

    public function index() {
        parent::page();
    }

    function chmodtest($do = FALSE) {
        $html = '';
        $models = array('Company', 'Institution', 'Person');

        foreach ($models as $model) {
            $obj = new $model;
            $class_vars = get_class_vars(get_class($obj));
            foreach ($class_vars as $var => $value) {
                if ($pos = strrpos($var, '_source')) {
                    // Verifica se é um arquivo vindo da file_manager
                    $field_name = substr($var, 0, $pos);
                    $path[get_class($obj)][$var] = substr($obj->{$field_name . '_source'}['upload_path'], 1);
                } elseif ($var == 'thumbnails') {
                    // Verifica se é um arquivo vindo da photo_manager
                    foreach ($obj->thumbnails as $thumb_array) {
                        $chaves = array_keys($path[get_class($obj)]);
                        $primeiro = $chaves[0];
                        $diretory = $path[get_class($obj)][$primeiro];
                        $path[get_class($obj)][$var.'_'.$thumb_array['width']] = $diretory . $thumb_array['width'] . 'x' . $thumb_array['height'] . '/';
                    }
                }
            }
        }

        //chmod('/uploads/', 0777);
        foreach ($path as $model => $dir) {
            $html .= '<h3>' . $model . '</h3>';
            foreach ($dir as $var => $pasta) {
                $pasta = substr(APPPATH, 0, -1) . $pasta;
                if($do){
                    chmod($pasta, 0777);
                }
                $html .= '<br /><b>' . $var . '</b><br />';
                $html .= $pasta . ' esta com com chmod: <b>' . substr(sprintf('%o', fileperms($pasta)), -4) . '</b><br />';
            }
            $html .= '<br />';
        }
        $this->data['data']['html'] = $html;
        parent::page();
    }

}
