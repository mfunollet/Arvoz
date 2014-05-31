<?php

/**
 * Activity controller
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
include_once APPPATH . 'modules/base/controllers/crud_controller.php';

/**
 * Activity class
 *
 * Class to implements the actions to interact with the Activity entity
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
class Activity extends CRUD_Controller {

    /**
     * The construct of this class to call the construct of the class 
     * CRUD_Controller and load any models, helpers, librarys and etc.
     *
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('Selection_process_model', 'Selection_process');
        $this->load->model('Step_model', 'Step');
        $this->load->model('pcr/Person_model', 'Person');
    }

    function _set_create_rules()
    {
        $this->form_validation->set_rules('name', lang('name'), 'required');
        $this->form_validation->set_rules('description', lang('description'), 'required');
        $this->form_validation->set_rules('responsible_id', lang('select_the_responsible'), 'required');
    }
    
    function _set_update_rules()
    {
        $this->form_validation->set_rules('name', lang('name'), 'required');
        $this->form_validation->set_rules('description', lang('description'), 'required');
        $this->form_validation->set_rules('responsible_id', lang('select_the_responsible'), 'required');
    }

    function create($step_id)
    {
        $this->data['header']->title = sprintf(lang('new_ativity'), $this->ctrlr_name);

        $this->_set_create_rules();
        if ( $this->form_validation->run() == FALSE )
        {
            $selection_process_id = $this->Step->get($step_id)->selection_process_id;
            $company_id = $this->Selection_process->get($selection_process_id)->company_id;
            $roles = array(
                $this->rel['person']['reviewer'],
                $this->rel['person']['evaluator'],
                $this->rel['person']['user']
            );

            $people_with_roles = $this->Person->get_all_by_roles($roles, $company_id);

            $people = array();
            $people[NULL] = lang('select_the_responsible');
            foreach ( $people_with_roles as $line )
            {
                $people[$line->pp_id] = ucfirst($line->role_name) . ' - ' . $line->get_full_name() . ' (' . $line->username . ')';
            }

            $this->data['people'] = $people;
            $this->data['step_id'] = $step_id;
            $this->_view($this->ctrlr_name . '_create');
        }
        else
        {
            if ( ($this->model->insert($this->input->post(NULL, TRUE))) > 0 )
            {
                //controller show
                $this->msg_ok($this->ctrlr_name . nbs() . lang('create_success'));
                redirect('step/view/' . $step_id, 'refresh');
            }
            else
            {
                // Erro na inserção
                $this->msg_ok(lang('create_error') . nbs() . lang('the') . nbs() . lang($this->ctrlr_name));
                redirect($this->ctrlr_name . '/create/' . $step_id, 'refresh');
            }
        }
    }

    public function _set_extra_edit_data($id)
    {
        $step_id = $this->model->get($id)->step_id;
        $selection_process_id = $this->Step->get($step_id)->selection_process_id;
        $company_id = $this->Selection_process->get($selection_process_id)->company_id;

        $roles = array(
            $this->rel['person']['reviewer'],
            $this->rel['person']['evaluator'],
            $this->rel['person']['user']
        );
        $people_with_roles = $this->Person->get_all_by_roles($roles, $company_id);

        $people = array();
        $people[NULL] = lang('select_the_responsible');
        foreach ( $people_with_roles as $line )
        {
            $people[$line->pp_id] = ucfirst($line->role_name) . ' - ' . $line->get_full_name() . ' (' . $line->username . ')';
        }

        $this->data['people'] = $people;
        $this->data['step_id'] = $step_id;
        
        $this->url_return = 'step/view/'.$step_id;
    }
    
    /**
     * Change activity status
     *
     * Function to change the status of activity
     *
     * @param  integer  $id  Id of Step
     */
    function change_status($id)
    {
        $item = $this->model->get($id);
        if ( $item->can_edit() )
        {
            $data = $this->input->post(NULL, TRUE);
            if ( $this->model->update_by_id($id, $data) )
            {
                $this->msg_ok(lang('status_changed'));
                redirect('step/view/' . $item->step_id, 'refresh');
            }
            else
            {
                $this->msg_error(lang('status_not_changed'));
                redirect('step/view/' . $item->step_id, 'refresh');
            }
        }
        else
        {
            $this->msg_error(sprintf(lang('you_dont_have_permission_to_edit_this_item'), $this->ctrlr_name));
            redirect('access_error/access_denied','refresh');
        }
    }

}