<?php

/**
 * URL Validation - To validation the URL
 *
 * @param string $url The url to validate
 * @return boolean
 */
function url_validation($url) {
    $validation = preg_match('/^(http|https|ftp):\/\/([\w]*)\.([\w]*)\.(com|net|org|biz|info|mobi|us|cc|bz|tv|ws|name|co|me)(\.[a-z]{1,3})?\z/i', $url) ||
            preg_match('/^([\w]*)\.([\w]*)\.(com|net|org|biz|info|mobi|us|cc|bz|tv|ws|name|co|me)(\.[a-z]{1,3})?\z/i', $url);
    if ($validation) {
        return TRUE;
    } else {
        $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . '%s' . nbs() . lang('not_have_valid_url'));
        return FALSE;
    }
}

/**
 * date or datetime Validation - To validation the date or datetime
 *
 * @param string $datetime The date or datetime to validate
 * @return boolean
 */
function datetime_validation($datetime) {
    if (!empty($datetime)) {
        if (is_datetime($datetime) == TRUE) {
            return TRUE;
        } else {
            $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . '%s' . nbs() . lang('not_have_valid_date'));
            return FALSE;
        }
    }
    return TRUE;
}

/**
 * phone required validation
 * 
 * PHONE[0] required and is numeric Validation - To validation the required 
 * input for one phone number
 *
 * @param array $phones The array of phone numbers to validate
 * @return boolean
 */
function phone_required_validation($phones) {
    if (isset($phones)) {

        for ($i = 0; $i < count($phones); $i++) {
            if (isset($phones[$i])) {
                if ((!is_numeric($phones[$i]))) {
                    $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . lang('phone' . $i) . nbs() . lang('accept_olnly_numbers_parentheses_hyphen'));
                    return FALSE;
                }
            }
        }

        if (isset($phones[0]) && is_numeric($phones[0])) {
            return TRUE;
        } else {
            $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . lang('phone1') . nbs() . lang('is_necessary'));
            return FALSE;
        }
    } else {
        $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . lang('phone1') . nbs() . lang('is_necessary'));
        return FALSE;
    }
}


/**
 * extension required validation
 * 
 * To transform the phone number to required if the extension are filled
 *
 * @param array $extensios The array of extension numbers to validate
 * @return boolean
 */
function extension_required_validation($extensions) {
    if (isset($extensions)) {
        $phones = $this->input->post('phone', TRUE);
        for ($i = 0; $i < count($extensions); $i++) {
            if (isset($extensions[$i])) {
                if (!isset($phones[$i])) {
                    $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . lang('phone' . $i) . nbs() . lang('accept_olnly_numbers_parentheses_hyphen'));
                    return FALSE;
                }
            }
        }
        return TRUE;
    } else {
        return TRUE;
    }
}
/*
function do_photo_upload($id) {
    //parametriza as preferências
    $config["upload_path"] = "upload/person_photos/";
    $config["allowed_types"] = "gif|jpg|png";
    $config["overwrite"] = TRUE;
    $this->load->library("upload", $config);
    //em caso de sucesso no upload

    if (!is_dir($config['upload_path'])) {
        mkdir(APPPATH . '/upload/' . $folder_name . '/');
        mkdir(APPPATH . '/upload/' . $folder_name . '/edict/');
    }

    $field_name = 'file_name_photo';
    if ($this->upload->do_upload($field_name)) {
        //renomeia a foto
        $nomeorig = $config["upload_path"] . $this->upload->file_name;
        $nomedestino = $config["upload_path"] . "person_" . $id . $this->upload->file_ext;
        rename($nomeorig, $nomedestino);

        //define opções de crop
        $config["image_library"] = "gd2";
        $config["source_image"] = $nomedestino;
        $config["width"] = 300;
        $config["height"] = 300;
        $this->load->library("image_lib", $config);
        $this->image_lib->crop();
        return $nomedestino;
    } else {
        $error = array('error' => $this->upload->display_errors());
        $msg = lang('document_upload_error');
        $this->msg_error($msg);
        return FALSE;
    }
}*/


function password_strength($pass) {
    $len = strlen($pass);
    $count = 0;
    //$array = array('`[a-z]`', '`[A-Z]`', '`[0-9]`', "`[!#_-]`");
    $array = array('`[a-zA-Z]`', '`[0-9]`');
    foreach ($array as $a) {
        if (preg_match($a, $pass)) {
            $count++;
        }
    }

    if ($len >= 8) {
        $count++;
    }

    if ($count == 3) {
        return TRUE;
    } else {
        return FALSE;
    }
}