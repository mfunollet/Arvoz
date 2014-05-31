<?php

/**
 * Step controller
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
include_once APPPATH . 'modules/base/controllers/crud_controller.php';

/**
 * Step class
 *
 * Class to implements the actions to interact with the Step entity
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
class Step extends CRUD_Controller {

    /**
     * The construct of this class to call the construct of the class 
     * CRUD_Controller and load any models, helpers, librarys and etc.
     *
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('Selection_process_model', 'Selection_process');
    }

    function reorder_step($selection_process_id)
    {
        $selection_process = $this->Selection_process->get($selection_process_id);
        $this->data['header']->title = lang('reorder_step_for_slection_process') . nbs() . $selection_process->name;
        $this->data['selection_process'] = $selection_process;
        $steps = $this->model->get_all_by_selection_process($selection_process_id);
        $this->data['steps'] = $steps;
        $this->_view($this->ctrlr_name . '_reorder_step');
    }

    function _set_create_rules()
    {
        $this->form_validation->set_rules('name', lang('name'), 'required');
        $this->form_validation->set_rules('description', lang('description'), 'required');
        $this->form_validation->set_rules('duration', lang('duration'), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('step_type_id', lang('step_type'), 'required');
        $this->form_validation->set_rules('evaluator_id', lang('evaluator'), 'required');
    }

    public function _set_update_rules()
    {
        $this->form_validation->set_rules('name', lang('name'), 'required');
        $this->form_validation->set_rules('description', lang('description'), 'required');
        $this->form_validation->set_rules('duration', lang('duration'), 'required|is_natural_no_zero');
        $this->form_validation->set_rules('step_type_id', lang('step_type'), 'required');
        $this->form_validation->set_rules('evaluator_id', lang('evaluator'), 'required');
    }

    function create($selection_process_id)
    {
        $this->data['header']->title = sprintf(lang('new_step'), $this->ctrlr_name);

        $this->_set_create_rules();
        if ( $this->form_validation->run() == FALSE )
        {
            $company_id = $this->Selection_process->get($selection_process_id)->company_id;
            $options = range(0, 99);
            $options[-1] = lang('days');
            unset($options[0]);
            ksort($options);
            $this->data['options'] = $options;
            $this->data['step_type'] = $this->Step_type->get_all();
            //$this->data['reviewers'] = $this->Person->get_all_by_role($this->rel['person']['reviewer'], $company_id);
            $this->data['evaluators'] = $this->Person->get_all_by_role('evaluator', $company_id);
            $this->data['selection_process_id'] = $selection_process_id;

            $this->_view($this->ctrlr_name . '_create');
        }
        else
        {
            if ( ($this->model->insert($this->input->post(NULL, TRUE))) > 0 )
            {
                //controller show
                $this->msg_ok($this->ctrlr_name . nbs() . lang('create_success'));
                redirect('selection_process/manager/' . $selection_process_id, 'refresh');
            }
            else
            {
                // Erro na inserção
                $this->msg_ok(lang('create_error') . nbs() . lang('the') . nbs() . lang($this->ctrlr_name));
                redirect($this->ctrlr_name . '/create/' . $selection_process_id, 'refresh');
            }
        }
    }

    public function _set_extra_edit_data($id = NULL)
    {
        $step = $this->model->get($id);
        $selection_process_id = $step->selection_process_id;
        $selection_process = $this->Selection_process->get($selection_process_id);
        $company_id = $selection_process->company_id;

        $options = range(0, 99);
        $options[-1] = lang('days');
        unset($options[0]);
        ksort($options);

        $this->data['options'] = $options;
        $this->data['step_type'] = $this->Step_type->get_all();
        $this->data['selection_process_id'] = $selection_process_id;
        $this->data['evaluators'] = $this->Person->get_all_by_role('evaluator', $company_id);
        $this->data['step_id'] = $step->id;
        
        $this->url_return = 'selection_process/manager/' . $selection_process_id;

        parent::_set_extra_edit_data($id);
    }

    /**
     * Function that loads the view to be presented
     */
    function view($id)
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


                $duration_sum = $this->model->get_duration_sum_filtered_by_step_number($item->selection_process_id, $item->step_number);
                $this->data['duration_sum'] = !empty($duration_sum) ? $duration_sum->duration_sum : 0;
                $this->data['selection_process'] = $this->Selection_process->get($item->selection_process_id);


                $this->data['step_status'] = array(
                    Step_status::WAITING => Step_status::get_step_status(Step_status::WAITING),
                    Step_status::PROGRESS => Step_status::get_step_status(Step_status::PROGRESS),
                    Step_status::DELAYED => Step_status::get_step_status(Step_status::DELAYED),
                    Step_status::COMPLETED => Step_status::get_step_status(Step_status::COMPLETED)
                );

                $this->data['activity_status'] = array(
                    Activity_status::WAITING => Activity_status::get_activity_status(Activity_status::WAITING),
                    Activity_status::PROGRESS => Activity_status::get_activity_status(Activity_status::PROGRESS),
                    Activity_status::COMPLETED => Activity_status::get_activity_status(Activity_status::COMPLETED)
                );
                //$this->data['activity'] = $this->Activity_model->get_all_by_step($id);


                $this->_view($this->ctrlr_name);
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

    /**
     * Change step status
     *
     * Function to change the status of step
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
                redirect($this->ctrlr_name . '/view/' . $id, 'refresh');
            }
            else
            {
                $this->msg_error(lang('status_not_changed'));
                redirect($this->ctrlr_name . '/view/' . $id, 'refresh');
            }
        }
        else
        {
            $this->msg_error(sprintf(lang('you_dont_have_permission_to_edit_this_item'), $this->ctrlr_name));
            redirect('access_error/access_denied','refresh');
        }
    }

    function reorder()
    {
        if ( isAjax() )
        {
            $new_order = $this->input->post('new_order');
            $new_order = explode('&', $new_order);
            if ( count($new_order) > 0 )
            {
                foreach ( $new_order as $position => $id )
                {
                    $id = str_replace(array("step[]="), "", $id);
                    $position++;
                    $this->model->update_by_id($id, array('step_number' => $position));
                    $ret['new_order'][] = $position;
                }
                echo json_encode($ret);
            }
        }
        else
        {
            show_404();
        }
    }

}