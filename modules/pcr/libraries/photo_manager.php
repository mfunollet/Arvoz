<?php

class Photo_Manager {

    var $field = 'image';
    var $obj = NULL;

    function __construct() {
        $this->CI = & get_instance();
    }

    function initialize($obj, $field = NULL) {
        $this->obj = $obj;
        if (isset($field)) {
            $this->field = $field;
        }
        $this->CI->load->library('image_lib');
    }

    function create_thumbnails() {
        if ($this->obj == NULL) {
            return FALSE;
        }
        $res = TRUE;

        foreach ($this->obj->{'thumbnails_' . $this->field} as $config) {
            $source['path'] = $this->obj->{$this->field . '_source'}['upload_path'];
            $source['file_raw'] = $this->file_name;
            $source['file_ext'] = $this->file_ext;

            $res = $res && $this->create_thumbnail($source, $config, TRUE);
        }
        return $res;
    }

    function create_thumbnail($source = array(), $config = array(), $test = FALSE) {
        $res = TRUE;
        
        $config['width'] = !isset($config['width']) ? 150 : $config['width'];
        $config['height'] = !isset($config['height']) ? 150 : $config['height'];
        
        $file_name_thumb = $source['file_raw'] . '_' . $config['width'] . 'x' . $config['height'] . $source['file_ext'];

        $default_config['image_library'] = 'gd2';
        $default_config['maintain_ratio'] = FALSE;
        $default_config['master_dim'] = 'width';
        $default_config['width'] = 150;
        $default_config['height'] = 150;
        $default_config['quality'] = 100;
        $default_config['source_image'] = $source['path'] . $source['file_raw'] . $source['file_ext'];
        $default_config['new_image'] = $source['path'] . $file_name_thumb;

        $config = array_merge($default_config, $config);

        $res = $res && $this->CI->image_lib->initialize($config);

        // Adicionar erros da biblioteca de Imagem no objeto
        if (!$this->CI->image_lib->resize()) {
            $this->obj->error_message($this->obj->regra, $this->CI->image_lib->display_errors());
        }
        // Se $test for TRUE retorna o sucesso da geração da miniatura, caso TRUE retorna o caminho 
        return ($test) ? $res : $config['new_image'];
    }

    function delete_link() {
        if ($this->obj == NULL) {
            return FALSE;
        }
        if ($this->delete_photo()) {
            $this->obj->{$this->field} = NULL;
            $this->obj->save();
        }
    }

    function delete_photo() {
        if ($this->obj == NULL) {
            return FALSE;
        }
        $res = TRUE;
        if (!empty($this->obj->{$this->field})) {
            // Apaga Foto original
            $original = $this->obj->{$this->field . '_source'}['upload_path'] . $this->obj->{$this->field};
            $ext = substr($original, strrpos($original, '.'));
            foreach (glob(substr($original, 0, strrpos($original, '.')) . '*' . $ext) as $filename) {
                $res = $res && unlink($filename);
            }
            /*
              if (is_file($original)) {
              $res = unlink($original);
              }

              // Apaga thumbnails
              foreach ($this->obj->{'thumbnails_' . $this->field} as $config) {
              $miniatura = $this->obj->{$this->field . '_source'}['upload_path'] . $config['width'] . 'x' . $config['height'] . '/' . $this->obj->{$this->field};
              if (is_file($miniatura)) {
              $res = $res && unlink($miniatura);
              }
              }

             */
        }

        // Retorna TRUE se a foto original e as thumbnails foram apagadas ou verificado a inexistencia das mesmas não acontece
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
        //debug($this->obj->validation);
        if (array_key_exists($this->field, $this->obj->validation)) {
            if (isset($this->obj->validation[$this->field]['rules'])
                    && in_array('file_required', $this->obj->validation[$this->field]['rules'])) {
                // é required
                if (!$this->CI->form_validation->file_required($this->field)) {
                    // Não enviou arquivo
                    return FALSE;
                }
            } else {
                // não é required
                if (!$this->CI->form_validation->file_required($this->field)) {
                    // Não enviou arquivo
                    return TRUE;
                }
            }
        }

        if (!$edit) {
            // Caso esteja adicionando
            // Arquivo foi preenchodo
            // :Envia
            return $this->upload($edit);
            // Arquivo não foi preenchido
            // :Ignora o envio
        } else {
            // Caso esteja Editando
            ///if($this->obj->usarfoto){
            // usarfoto foi marcado
            // Arquivo foi preenchodo
            // :Apaga foto anterior se houver
            $this->delete_link();

            // :Envia foto nova
            return $this->upload($edit);
            // Arquivo n�o foi preenchido
            // :Mantem foto anterior
            ///}else{
            // userfoto n�o foi marcado
            // :Apaga foto anterior se houver
            ///	$this->delete_link();
            // :Ignora o envio
            ///}
        }
    }

    function upload($edit = FALSE) {
        if ($this->obj == NULL) {
            return FALSE;
        }
        $this->CI->load->library('upload');
        $this->file_name = $this->obj->id . '_' . $this->field;
        $this->obj->{$this->field . '_source'}['file_name'] = $this->file_name;

        $this->CI->upload->initialize($this->obj->{$this->field . '_source'});
        $res = $this->CI->upload->do_upload($this->field);

        // Verifica se o upload foi feito com sucesso
        if ($res) {
            // Se foi feito o upload com sucesso
            $upload = $this->CI->upload->data();
            $this->obj->{$this->field} = $upload['file_name'];
            $this->raw_name = $upload['raw_name'];
            $this->file_ext = $upload['file_ext'];

            // Cria thumbnails
            if (isset($this->obj->{'thumbnails_' . $this->field})) {
                $res = $res && $this->create_thumbnails();
            }
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

        // Retorna FALSE se a foto original OU as thumbnails n�o foram gravadas com sucesso
        // Rollback da foto original e todas thumbnails caso tenha acontecido algum erro de grava��o parcial.
        $this->delete_photo();
        return FALSE;
    }

}