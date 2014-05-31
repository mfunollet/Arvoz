<?php

/**
 * Role_has_resource_model file is the point to interact with the entity 
 * Role_has_resource
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */

/**
 * Role_has_resource_model class are responsible about the interact with the 
 * entity Role_has_resource
 *
 * @copyright  2011 ARQABS
 * @version    Release: @package_version@
 */
Class Role_has_resource extends DataMapperExt {

    /**
     * The contruct of class Role_has_resource_model
     *
     * In the construc of this class is necessary that you configure the name 
     * of the table and the data_type of your entity
     * 
     */
    public function __construct() {
        parent::__construct();
        //Contains the name of entity/table
        $this->table = 'role_has_resource';
        //Contains the name of entity/view
        $this->table_view = 'role_has_resources_view';
        //Contains the name of the single entity object
        $this->data_type = 'role_has_resource_object';
    }

    function save_permissions() {
        $this->db->trans_begin();
        log_message("info", "Transação aberta: " . $this->router->class . "/" . $this->router->method . " Linha: " . __LINE__);

        $resource_associated_roles = $this->model->get_where(array('role_id' => $this->input->post('role_id')));

        $permission_has_role = array();
        foreach ($resource_associated_roles as $resource_associated_role) {
            $permission_has_role[] = $resource_associated_role->resource_id;
        }

        $count_resource_checkbox = isset($_POST['resource_checkbox']) ? count($_POST['resource_checkbox']) : 0;
        $count_resource_permissions = count($permission_has_role);
        $count = max($count_resource_checkbox, $count_resource_permissions);

        for ($i = 0; $i < $count; $i++) {
            if (isset($_POST['resource_checkbox'][$i])) {
                if (!in_array($_POST['resource_checkbox'][$i], $permission_has_role)) {
                    $this->model->insert(array('resource_id' => $_POST['resource_checkbox'][$i], 'role_id' => $this->input->post('role_id')));
                }
            }

            if (isset($permission_has_role[$i])) {
                if (!in_array($permission_has_role[$i], $_POST['resource_checkbox'])) {
                    $this->model->delete(array('resource_id' => $permission_has_role[$i], 'role_id' => $this->input->post('role_id')));
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            //trans error - Rollback
            $this->db->trans_rollback();
            log_message("info", "Transação rollback: " . $this->router->class . "/" . $this->router->method . " Linha: " . __LINE__);
            return FALSE;
        } else {
            //trans success - commit
            $this->db->trans_commit();
            log_message("info", "Transação commit: " . $this->router->class . "/" . $this->router->method . " Linha: " . __LINE__);
            return TRUE;
        }
    }

}