<?php

/**
 * Selection_process_has_enterprise controller
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
include_once APPPATH . 'modules/base/controllers/crud_controller.php';

/**
 * Selection_process_has_enterprise class
 *
 * Class to implements the actions to interact with the 
 * Selection_process_has_enterprise entity
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
class Selection_process_has_company extends CRUD_Controller {

    /**
     * The construct of this class to call the construct of the class 
     * CRUD_Controller and load any models, helpers, librarys and etc.
     *
     */
    function __construct()
    {
        parent::__construct();
        $this->load->model('Step_model','Step');
        $this->load->model('Selection_process_model','Selection_process');
        $this->load->model('pcr/Role_model','Role');
        $this->load->model('pcr/P_and_p_model','P_and_p');
        $this->load->model('pcr/Company_model','Company');
    }

    function participate($selection_process_id)
    {
        if ( $this->session->userdata('company_id') )
        {
            if ( !$this->model->exists(array(
                        'selection_process_id' => $selection_process_id,
                        'company_id' => $this->session->userdata('company_id'))) )
            {
                if ( $this->model->insert(array(
                            'selection_process_id' => $selection_process_id,
                            'company_id' => $this->session->userdata('company_id'))) )
                {
                    $this->msg_ok(lang('you_are_participating_on_selection_process'));
                    $url = '/selection_process/show_detail/' . $selection_process_id;
                    redirect($url, 'refresh');
                }
                else
                {
                    $this->msg_error(lang('participate_selection_process_error'));
                    $url = '/selection_process/show_detail/' . $selection_process_id;
                    redirect($url, 'refresh');
                }
            }
            else
            {
                $this->msg_error(lang('you_has_participate_on_selection_process_error'));
                $url = '/selection_process/show_detail/' . $selection_process_id;
                redirect($url, 'refresh');
            }
        }
        else
        {
            $this->msg_info(lang('select_company_for_use_as'));
            $url = '/company/my_companies/';
            redirect($url, 'refresh');
        }
    }

    /**
     * Short description for the function
     *
     * Long description for the function (if any)...
     *
     * @param  array  $array  Description of array
     * @param  string $string Description of string
     * @return boolean
     */
    function classify_companies($step_id)
    {
        $step = $this->Step->get($step_id);
        $selection_process_id = $step->selection_process_id;
        // exists if user use "use_as" the company
        $company_id = $this->session->userdata('company_id');
        
        //if ( $this->is_admin($selection_process_id) )
        
        //TODO perguntar se a etapa está completada
        
        if ( $company_id && $this->_is_admin($selection_process_id) )
        {
            $selection_process = $this->Selection_process->get($selection_process_id);
            $this->data['companies'] = $this->model->get_all_by_selection_process($selection_process_id, $company_id);
            $this->data['selection_process'] = $selection_process;
            $this->data['step_id'] = $step_id;
            $this->_view($this->ctrlr_name . '_classify_companies');
        }
        else
        {
            $this->msg_info(lang('you_dont_have_permission_to_access_this_area'));
            redirect('/access_error/access_denied/', 'refresh');
        }
    }

    function _is_admin($selection_process_id)
    {
        //Incompatible use_as and the selection process access
        $selection_process = $this->Selection_process->get($selection_process_id);
        $company_id = $this->session->userdata('company_id');
        if ( $selection_process->company_id != $company_id )
            return FALSE;

        $role_id = $this->Role->get_where(array('name' => 'administrator'))->id;
        $role_id_admin = $this->Role->get_where(array('name' => 'superadministrator'))->id;
        $person_p_id = $this->session->userdata('p_id');
        $company_p_id1 = $this->session->userdata('company_p_id');

        //superadministrator
        if ( $this->P_and_p->exists(array('p_id' => $person_p_id, 'role_id' => $role_id_admin)) )
            return TRUE;

        //administrator
        if ( $this->P_and_p->exists(array('p_id' => $person_p_id, 'p_id1' => $company_p_id1, 'role_id' => $role_id)) )
            return TRUE;

        return FALSE;
    }

}