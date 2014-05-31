<?php

class File_Manager {

    var $field = 'file';
    var $obj = NULL;

    function __construct() {
        $this->CI = & get_instance();
    }

    function initialize($obj, $field = NULL) {
        $this->obj = $obj;
        if (isset($field)) {
            $this->field = $field;
        }
    }

    function delete_link() {
        if ($this->obj == NULL) {
            return FALSE;
        }
        if ($this->delete_file()) {
            $this->obj->{$this->field} = NULL;
            $this->obj->save();
        }
    }

    function delete_file() {
        if ($this->obj == NULL) {
            return FALSE;
        }
        $res = TRUE;
        if (!empty($this->obj->{$this->field})) {
            // Apaga File
            $original = $this->obj->{$this->field . '_source'}['upload_path'] . $this->obj->{$this->field};
            if (is_file($original)) {
                $res = unlink($original);
            }
        }
        return $res;
    }

    function delete_all_link($fields = array()) {
        if ($this->obj == NULL) {
            return FALSE;
        }
        $fields = (empty($fields)) ? array($this->field) : $fields;
        foreach ($this->obj as $o) {
            foreach ($fields as $field) {
                $this->initialize($o, $field);
                $this->delete_link();
            }
        }
    }

    function upload_main($edit = FALSE) {
        if ($this->obj == NULL) {
            return FALSE;
        }

        $this->CI->load->library('form_validation');

        $this->delete_link();
        return $this->upload($edit);
    }

    function upload($edit = FALSE) {
        if ($this->obj == NULL) {
            return FALSE;
        }
        $this->CI->load->library('upload');
        $config = $this->obj->{$this->field . '_source'};
        $config = array_merge($config, array('allowed_types' => 'doc|pdf|txt|docx|rtf|jpg|zip|rar|7z|jpg|gif'));

        $this->CI->upload->initialize($config);
        $res = $this->CI->upload->do_upload($this->field);

        // Verifica se o upload foi feito com sucesso
        if ($res) {
            // Se foi feito o upload com sucesso
            $upload = $this->CI->upload->data();
            $this->obj->{$this->field} = $upload['file_name'];
        } else {
            // Se não foi feito o upload com sucesso
            // Adicionar erros da biblioteca de Upload no objeto
            foreach ($this->CI->upload->error_msg as $uploadErro) {
                $this->obj->error_message(__FUNCTION__, $uploadErro);
            }
            //echo $this->CI->upload->display_errors();
            $res = FALSE;
        }

        // Retorna TRUE se a foto original e as thumbnails foram gravadas com sucesso
        if ($res) {
            return TRUE;
        }

        // Retorna FALSE se o arquvio nao foi gravado com sucesso
        // Rollback do arquivo caso tenha acontecido algum erro de gravação parcial.
        $this->delete_file();
        return FALSE;
    }

}