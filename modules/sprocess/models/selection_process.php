<?php

/**
 * P model file is the central point to interact with the entity 
 * Selection_process_model
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
include_once APPPATH . 'modules/base/models/entity_model.php';
include_once 'selection_process_object.php';

/**
 * Selection_process_model class are responsible about the interact with the entity Selection_process_model
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
Class Selection_process_model extends Entity_model {

        public $validation = array(
        'name' => array(
            'rules' => array('required', 'trim')
        ),
        'description' => array(
            'rules' => array('required', 'trim')
        ),
        'start_date' => array(
            'rules' => array('required', 'trim')
        ),
        'userfile' => array(
            'rules' => array('required', 'trim'),
            'type' => 'file'
        )
);
        //$this->form_validation->set_rules('start_date', lang('start_date'), 'required|callback_external_callbacks[selection_process_model,datetime_validation]');
        //$this->form_validation->set_rules('userfile', lang('edict'), 'callback_external_callbacks[selection_process_model,userfile_validation,TRUE]');
        
    /**
     * The contruct of class Selection_process_model
     *
     * In the construc of this class is necessary that you configure the name 
     * of the table and the data_type of your entity
     * 
     *
     * @param  array  $array  Description of array
     * @param  string $string Description of string
     * @return boolean
     */
    public function __construct()
    {
        parent::__construct();
        //Contains the name of entity/table
        $this->table = 'selection_process';
        //Contains the name of the view of the entity
        //$this->table_view = 'selection_processes_view';
        //Contains the name of the set entity object
        $this->data_type = 'Selection_process_object';
        $this->date_fields[] = 'start_date';
    }

    /**
     * Short description for the function
     *
     * Long description for the function (if any)...
     *
     * @param array $array Description of array
     * @param string $string Description of string
     * @return boolean
     */
    function insert($data)
    {
        $this->load->model('Step_model', 'Step');

        $this->db->trans_begin();
        log_message("info", "Transação aberta: " . $this->router->class . "/" . $this->router->method . " Linha: " . __LINE__);

        $id = FALSE;

        if ( !empty($_FILES['userfile']['name']) )
        {
            $file_name = $this->do_upload($data['company_id']);

            if ( ($file_name == FALSE ) )
            {
                $this->db->trans_rollback();
                log_message("info", "Transação rollback: " . $this->router->class . "/" . $this->router->method . " Linha: " . __LINE__);
                return FALSE;
            }
            else
            {
                $data['file_name'] = $file_name;
            }
        }

        //selection_process insert
        $id = parent::insert($data);
        
        //selection_process_has_company insert
        $data['selection_process_id'] = $id;

        //First Step data
        $data['name'] = $this->input->post('name_step');
        $data['description'] = $this->input->post('description_step');
        $step_number = $this->Step->get_last_step_number($id);
        $data['step_number'] = ( sizeof($step_number) != 0 ) ? ($step_number->step_number + 1) : 1;
        $step_id = $this->Step->insert($data);

        if ( $this->db->trans_status() === FALSE )
        {
            //trans error - Rollback
            $this->db->trans_rollback();
            log_message("info", "Transação rollback: " . $this->router->class . "/" . $this->router->method . " Linha: " . __LINE__);

            if ( file_exists($file_name) )
            {
                unlink(APPPATH . $file_name);
            }

            return FALSE;
        }
        else
        {
            //trans success - commit
            $this->db->trans_commit();
            log_message("info", "Transação commit: " . $this->router->class . "/" . $this->router->method . " Linha: " . __LINE__);
            return $id;
        }
    }

    function update_by_id($id, $data)
    {

        $selection_process = $this->model->get($id);

        if ( !empty($_FILES['userfile']['name']) )
        {
            $file_name = $this->do_upload($selection_process->company_id);

            if ( ($file_name == FALSE ) )
            {
                $this->db->trans_rollback();
                log_message("info", "Transação rollback: " . $this->router->class . "/" . $this->router->method . " Linha: " . __LINE__);

                $this->msg_error(lang('upload_selection_process_error'));
                redirect('selection_process/edit/' . $id, 'refresh');
                return;
            }
            else
            {
                $data['file_name'] = $file_name;
                $old_file_name = $selection_process->file_name;
            }
        }

        $affected_rows = parent::update_by_id($id, $data);

        if ( $affected_rows === FALSE )
        {
            if ( file_exists($file_name) )
            {
                unlink(APPPATH . $file_name);
            }
            return FALSE;
        }
        else
        {
            if ( isset($old_file_name) && !empty($old_file_name) )
            {
                if ( file_exists($old_file_name) )
                {
                    unlink(APPPATH . $old_file_name);
                }
            }
            return $affected_rows;
        }
    }

    function do_upload($folder_name)
    {
        //this variables needed setting in the _userfile_validation()
        //the format of allowed_types in this function is "pdf|doc|odt|zip"
        $config['upload_path'] = 'upload/' . $folder_name . '/edict/';
        $config['allowed_types'] = 'pdf|doc|xls|ppt|zip';
        $config['max_size'] = '20000';
        $this->load->library('upload', $config);

        if ( !is_dir($config['upload_path']) )
        {
            mkdir(APPPATH . '/upload/' . $folder_name . '/');
            mkdir(APPPATH . '/upload/' . $folder_name . '/edict/');
        }

        if ( !$this->upload->do_upload() )
        {
            $error = array('error' => $this->upload->display_errors());
            $msg = lang('document_upload_error');
            $this->msg_error($msg);
            return FALSE;
        }
        else
        {
            $nomeorig = $config["upload_path"] . $this->upload->file_name;
            $nomedestino = $config["upload_path"] . 'edital_' . $this->input->post('name') . '_' . date("Y-m-d_H:i:s") . $this->upload->file_ext;
            rename(APPPATH . $nomeorig, APPPATH . $nomedestino);
            $data = $this->upload->data();
            return $nomedestino;
        }
    }

    function userfile_validation($value, $field_names = NULL)
    {

        //this variables needed setting in the do_upload()
        //the format of $allowed_types in this function is ".pdf|.doc|.odt|.zip"
        $allowed_types = array(
            '.pdf' => 'pdf',
            '.doc' => 'doc',
            '.xls' => 'xls',
            '.ppt' => 'ppt',
            '.zip' => 'zip');

        //set the size of $max_size in bytes
        $max_size = '15728640'; //15megabytes

        $accept_empty = FALSE;
        if ( isset($field_names[0]) )
        {
            $accept_empty = $field_names[0];
        }

        if ( empty($_FILES['userfile']['name']) && $accept_empty != 'TRUE' )
        {
            $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . '%s' . nbs() . lang('is_necessary'));
            return FALSE;
        }

        if ( !empty($_FILES['userfile']['name']) )
        {
            $ext = strrchr($_FILES['userfile']['name'], '.');
            if ( !$allowed_types[$ext] )
            {
                $this->form_validation->set_message('external_callbacks', lang('the_field') . nbs() . '%s' . nbs() . lang('not_accept_type_file'));
                return FALSE;
            }
            elseif ( $_FILES['userfile']['size'] > $max_size )
            {
                $this->form_validation->set_message('external_callbacks', lang('the_file') . nbs() . $_FILES['userfile']['name'] . nbs() . lang('is_very_big'));
                print_r($_FILES['userfile']);
                return FALSE;
            }
            else
            {
                //erro desconhecido
                return TRUE;
            }
        }
        else
        {
            return TRUE;
        }
    }

}

class Selection_process_type {
    const INTERNAL = 1;
    const EXTERNAL = 0;
    
    static function get_selection_process_type($id)
    {
        switch ( $id )
        {
            case Selection_process_type::EXTERNAL:
                $active_type = lang('external');
                break;
            case Selection_process_type::INTERNAL:
                $active_type = lang('internal');
                break;
            default:
                $active_type = lang('error');
        }
        return $active_type;
    }

}

class Selection_process_active {
    const ACTIVE = 0;
    const INACTIVE = 1;
    
    static function get_selection_process_active($id)
    {
        switch ( $id )
        {
            case Selection_process_active::ACTIVE:
                $active_status = lang('active');
                break;
            case Selection_process_active::INACTIVE:
                $active_status = lang('inactive');
                break;
            default:
                $active_status = lang('error');
        }
        return $active_status;
    }

}