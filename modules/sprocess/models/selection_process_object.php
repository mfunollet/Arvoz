<?php

/**
 * Base file to implement a model for a line of an entity.
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
include_once APPPATH . 'modules/base/models/crud_object.php';

class Selection_process_object extends Crud_object {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Function that implements the logic to see if a user can view this data.
     * 
     * @return boolean Returns if a user can view this data.
     */
    function can_view()
    {
        return TRUE;
    }

    /**
     * Function that implements the logic to see if a user can create this data.
     * 
     * @return boolean Returns if a user can create this data.
     */
    function can_create()
    {
        return TRUE;
    }

    /**
     * Function that implements the logic to see if a user can edit this data.
     * 
     * @return boolean Returns if a user can edit this data.
     */
    function can_edit()
    {
//        $this->load->model('Role_model', 'Role');
//        $this->load->model('P_and_p_model', 'P_and_p');
//        
//        $role_id = $this->Role->get_where(array('name' => 'administrator'))->id;
//        $role_id_admin = $this->Role->get_where(array('name' => 'superadministrator'))->id;
//        $person_p_id = $this->session->userdata('p_id');
//        $company_p_id1 = $this->session->userdata('company_p_id');
//
//        $selection_process = $this->Selection_process->get($selection_process_id);
//        $company_id = $this->session->userdata('company_id');
//        if ( $selection_process->company_id != $company_id )
//            return FALSE;

        return TRUE;
    }

    /**
     * Function that implements the logic to see if a user can delete this data.
     * 
     * @return boolean Returns if a user can delete this data.
     */
    function can_delete()
    {
        return TRUE;
    }

}