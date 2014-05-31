<?php

class P_model extends DataMapperExt {

    function __construct($id = NULL) {
        parent::__construct($id);
    }

    function get_image($width = FALSE, $height = FALSE, $field = 'profile_image') {
        $path = $this->{$field . '_source'}['upload_path'];
        $file_raw = substr($this->{$field}, 0, strrpos($this->{$field}, '.'));
        $file_ext = substr($this->{$field}, strrpos($this->{$field}, '.'));

        // Caso não tenha passado largura ou altura retorna a imagem original
        if (!$width && !$height) {
            // Retorna imagem original
            return $path . $file_raw . $file_ext;
        }

        // Seta caminho para thumbnail
        $thumbnail = $path . $file_raw . '_' . $width . 'x' . $height . $file_ext;

        // Verifica se a imagem existe
        if (!file_exists($thumbnail)) {
            // Caso não exista gera minatura pedida
            $this->CI->load->library('photo_manager');
            $this->CI->photo_manager->initialize($this, $field);

            $source['path'] = $path;
            $source['file_raw'] = $file_raw;
            $source['file_ext'] = $file_ext;
            
            $config['width'] = $width;
            $config['height'] = $height;
            
            $thumbnail = $this->CI->photo_manager->create_thumbnail($source, $config);
        }
        $thumbnail = substr($thumbnail, 2);
        
        // Retorna thumbnail gerada ou ja existente
        return $thumbnail;
    }

    function get_name() {
        return $this->__toString();
    }

    function respond_invite($type, $id, $accepted) {
        $logged_user = $this->CI->authentication->get_logged_user();
        $aux_model = ucfirst($type);

        $element = new $aux_model();

        $element->where('id', $id);
        $element->where_related($logged_user);
        $element->get_pending();

        if ($element->exists()) {
            if ($accepted) { // Aceitando
                $element->start_date = date($element->timestamp_format);
                $element->status = STATUS_ACTIVE;
                $res = $element->save();
            } else { // Recusando
                $res = $element->delete();
            }
            return $res;
        }
    }
    
    function get_recipients()
    {        
        return $this;
    }
    
    function get_autocomplete_field(){
        return 'name';
    }

}