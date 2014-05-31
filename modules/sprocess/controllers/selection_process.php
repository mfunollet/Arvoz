<?php

/**
 * Selection_process controller
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
include_once APPPATH . 'modules/base/controllers/crud_controller.php';

/**
 * Selection_process class
 *
 * Class to implements the actions to interact with the 
 * Selection_process_has_enterprise entity
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
class Selection_process extends CRUD_Controller {

    function __construct()
    {
        parent::__construct();
        $this->ctrl_name = 'selection_process';
        
        /*$this->ctrlr_name = $this->ctrl_name;
        $this->load->library('form_validation');
        $this->load->model('step_type', 'Step_type');*/
    }

    function _set_create_rules()
    {
        $this->form_validation->set_rules('name', lang('name'), 'required');
        $this->form_validation->set_rules('description', lang('description'), 'required');
        $this->form_validation->set_rules('start_date', lang('start_date'), 'required|callback_external_callbacks[selection_process_model,datetime_validation]');
        $this->form_validation->set_rules('userfile', lang('edict'), 'callback_external_callbacks[selection_process_model,userfile_validation]');
    }

    function _set_update_rules()
    {
        $this->form_validation->set_rules('name', lang('name'), 'required');
        $this->form_validation->set_rules('description', lang('description'), 'required');
        $this->form_validation->set_rules('start_date', lang('start_date'), 'required|callback_external_callbacks[selection_process_model,datetime_validation]');
        $this->form_validation->set_rules('userfile', lang('edict'), 'callback_external_callbacks[selection_process_model,userfile_validation,TRUE]');
    }

    function show_companies($selection_process_id)
    {
        //TODO
        $this->data['header']->title = sprintf(lang('new_selection_process'), lang($this->ctrlr_name));
        $this->data['companies'] = $this->selection_process_has_company->get_companies($selection_process_id);
        $this->_view('selection_process_show_companies');
    }

    function create($company_id)
    {
        $this->data['js_files'][] = 'datetime/date_setup.js';
        $this->data['header']->title = sprintf(lang('new_selection_process'), lang($this->ctrlr_name));

        $this->_set_create_rules();
        if ( $this->form_validation->run() == FALSE )
        {
            $this->data['company_id'] = $company_id;

            //step data
            $options = range(0, 99);
            $options[-1] = lang('days');
            unset($options[0]);
            ksort($options);
            $this->data['options'] = $options;
            $this->data['step_type'] = $this->Step_type->get_all();

            $this->data['reviewers'] = $this->Person->get_all_by_role($this->rel['person']['reviewer'], $company_id);
            $this->data['evaluators'] = $this->Person->get_all_by_role('evaluator', $company_id);

            $this->_view($this->ctrlr_name . '_create');
        }
        else
        {
            $data = $this->input->post(NULL, TRUE);
            $selection_process_id = $this->model->insert($data);

            if ( $selection_process_id === FALSE )
            {
                $this->msg_error($this->ctrlr_name . nbs() . lang('create_error'));
                redirect($this->ctrlr_name . '/create/' . $company_id, 'refresh');
            }
            else
            {
                $this->msg_ok($this->ctrlr_name . nbs() . lang('create_success'));
                redirect($this->ctrlr_name . '/view/' . $selection_process_id, 'refresh');
            }
        }
    }

    /**
     * Function that shows the edit form of an object and updates the info.     
     */
    public function edit($id)
    {
        $this->data['js_files'][] = 'datetime/date_setup.js';

        $item = $this->model->get($id);

        if ( $item )
        {
            if ( $item->can_edit() )
            {
                $this->data[$this->ctrlr_name] = $item;
                $this->data['l_sidebar'] = $this->_l_sidebar();
                $this->data['header']->title = sprintf(lang('edit_item_info'), $this->ctrlr_name);

                $this->_set_update_rules();

                if ( $this->form_validation->run() == FALSE )
                {
                    $this->data[$this->ctrlr_name] = $item;
                    $this->_view($this->ctrlr_name . '_update');
                }
                else
                {
                    $data = $this->input->post(NULL, TRUE);
                    $affected_rows = $this->model->update_by_id($id, $data);

                    if ( $affected_rows === FALSE )
                    {
                        $this->msg_error(lang('selection_process_update_error'));
                        redirect('selection_process/edit/' . $id, 'refresh');
                    }
                    else
                    {
                        $this->data[$this->ctrlr_name] = $this->model->get($id);
                        $this->msg_ok(lang('selection_process_updated_success'));
                        redirect($this->ctrlr_name . '/manager/' . $id, 'refresh');
                    }
                }
            }
            else
            {
                $this->msg_error(sprintf(lang('user_not_owns_item'), $this->ctrlr_name));
                redirect(plural($this->ctrlr_name));
            }
        }
        else
        {
            $this->msg_error(sprintf(lang('item_not_found'), $this->class_name));
            redirect(plural($this->ctrlr_name));
        }
    }

    function _set_extra_view_data($id)
    {
        $this->load->model('Step_model', 'Step');
        $this->data['css_files'][] = 'slickmap.css';

        $steps = $this->Step->get_all_by_selection_process($id);
        $this->data['steps'] = $steps;

        $duration_sum = 0;
        foreach ( $steps as $step ) :
            $duration_sum += $step->duration;
        endforeach;

        if ( $duration_sum == 0 )
        {
            $this->data['end_date'] = lang('dont_having_steps_in_this_selection_process');
        }
        else
        {
            $selection_process = $this->model->get($id);
            $this->data['end_date'] = brazilian_datetime(sum_date($selection_process->start_date, 0, 0, $duration_sum - 1));
        }

        parent::_set_extra_view_data($id);
    }

    function manager($id)
    {
        $item = $this->model->get($id);
        if ( $item )
        {
            if ( $item->can_view() )
            {
                if ( $item->can_edit() )
                {
                    $this->data['r_sidebar'] = $this->_r_sidebar();
                }
                $this->data[$this->ctrlr_name] = $item;

                $this->data['header']->title = $item->get_view_title();

                $this->load->model('Step_model', 'Step');
                $this->data['css_files'][] = 'slickmap.css';

                $steps = $this->Step->get_all_by_selection_process($id);
                $this->data['steps'] = $steps;

                $duration_sum = 0;
                foreach ( $steps as $step ) :
                    $duration_sum += $step->duration;
                endforeach;

                if ( $duration_sum == 0 )
                {
                    $this->data['end_date'] = lang('dont_having_steps_in_this_selection_process');
                }
                else
                {
                    $selection_process = $this->model->get($id);
                    $this->data['end_date'] = brazilian_datetime(sum_date($selection_process->start_date, 0, 0, $duration_sum - 1));
                }

                $this->_view($this->ctrlr_name . '_manager');
            }
            else
            {
                $this->msg_error(sprintf(lang('user_not_owns_item'), $this->ctrlr_name));
                redirect(plural($this->ctrlr_name));
            }
        }
        else
        {
            $this->msg_error(sprintf(lang('item_not_found'), $this->ctrlr_name));
            redirect(plural($this->ctrlr_name));
        }
    }

}