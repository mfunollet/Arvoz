<?php

class DataMapperExt extends DataMapper {

    var $filtered_fields = array();
    var $removed_fields = array();
    var $CI;
    /* Variavel de mensagem usada no _form()*/
    var $_msg = NULL;

    function __construct($id = NULL) {
        $this->CI = & get_instance();
        /*
          $classe = get_class($this) . '_link';
          $link = new $classe;
          $this->has_many = array_merge($this->has_many, $link->has_many);
         */
        parent::__construct($id);
    }

    //FIX: NUNCA MAIS MEXE!
   function get_clean_fields() {
       if (empty($this->filtered_fields)) {
           $fields = $this->CI->db->list_fields($this->table);
           foreach ($fields as $k => $v) {
               //FIX: trocar create_time para $this->create_field do $conf do datamapper
               if ($v == 'id' || substr($v, -3) == '_id' || $v == 'create_time' || $v == 'update_time' || $v == 'status' || in_array($v, $this->removed_fields)) {
                   unset($fields[$k]);
               }
           }

           $this->filtered_fields = $fields;
       }

       // FIX: verificar se $this->filtered_fields é um array
       //se for array, descobre qual chave
       // Senão toma como $this->filtered_fields

       foreach ($this->validation as $k => $v) {
           if (!in_array($k, $this->filtered_fields) && !isset($this->{$k})) {
               unset($this->validation[$k]['rules']);
           }
       }

       return $this->filtered_fields;
   }

    function fillObject($remove = array(), $post = NULL) {
        $post = (empty($post)) ? $this->CI->input->post() : $post;
        $fields = $this->get_clean_fields();

        if (empty($post)) {
            return;
        }
        foreach ($post as $k1 => $v1) {
            if (!in_array($k1, $fields)) {
                unset($post[$k1]);
            }
        }

        if (!class_exists('DMZ_Array')) {
            $this->load_extension('array');
        }
        $this->from_array($post, $fields);

        return $post;
    }

    function can_create() {
        return TRUE;
    }

    function can_edit() {
        return TRUE;
    }

    function can_delete() {
        return TRUE;
    }

    function can_view() {
        return TRUE;
    }

    function delete_by_status() {
        $this->status = STATUS_DELETED;
        return $this->save();
    }

    function get_pending($limit = NULL, $offset = NULL) {
        $this->where('status', STATUS_PENDING);
        parent::get($limit, $offset);
    }

    function get_active($limit = NULL, $offset = NULL) {
        $this->where('status', STATUS_ACTIVE);
        parent::get($limit, $offset);
    }

    function get_deteted($limit = NULL, $offset = NULL) {
        $this->where('status', STATUS_DELETED);
        parent::get($limit, $offset);
    }

    function get_image($width = FALSE, $height = FALSE, $field = 'profile_image') {
        if (empty($this->{$field})) {
            return FALSE;
        }
        $path = substr($this->{$field . '_source'}['upload_path'], 1);

        if (!$width && !$height) {
            // Retorna imagem oringinal
            return $path . $this->{$field};
        }
        // Retorna thumbnails
        $folder = $width . 'x' . $height . '/';
        return $path . $folder . $this->{$field};
    }

}
