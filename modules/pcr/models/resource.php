<?php

/**
 * Resource_model file is the point to interact with the entity Resource
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */

/**
 * Resource_model class are responsible about the interact with the entity 
 * Resource
 *
 * @copyright  2011 ARQABS
 * @version    Release: @package_version@
 */
Class Resource extends DataMapperExt {

    public $has_many = array(
        'role' => array(// in the code, we will refer to this relation by using the object name 'book'
            'class' => 'role', // This relationship is with the model class 'book'
            'other_field' => 'resource', // in the Book model, this defines the array key used to identify this relationship
            'join_self_as' => 'resource', // foreign key in the (relationship)table identifying this models table. The column name would be 'author_id'
            'join_other_as' => 'role', // foreign key in the (relationship)table identifying the other models table as defined by 'class'. The column name would be 'book_id'
            'join_table' => 'role_has_resource') // name of the join table that will link both Author and Book together
    );
    
    var $table = 'resource';

    public function __construct($id = NULL) 
    {
        parent::__construct($id);
    }

    /**
     * The contruct of class Resource_model
     *
     * In the construc of this class is necessary that you configure the name 
     * of the table and the data_type of your entity
     * 
     */
//    public function __construct()
//    {
//        parent::__construct();
//        //Contains the name of entity/table
//        $this->table = 'resource';
//        //Contains the name of the single entity object
//        $this->data_type = 'resource_object';
//    }
//
//    function get_controllers($fields, $groupby = NULL)
//    {
//        $this->db->select($fields);
//        if ( !empty($groupby) )
//            $this->db->group_by($groupby);
//        return $this->get_all();
//    }
//
//    /**
//     * Short description for the function
//     *
//     * Long description for the function (if any)...
//     *
//     * @param  array  $array  Description of array
//     * @param  string $string Description of string
//     * @return boolean
//     */
    function init_resource() {
        $this->trans_begin();
        //log_message("info", "Transação aberta: " . $this->router->class . "/" . $this->router->method . " Linha: " . __LINE__);

        $actual_resources = array();
        $persisted_resources = array();
        $persisted_resources = $this->convert_resources_array();
        $this->load->helper('directory');
        $map_modules = directory_map('./modules/', TRUE, TRUE);

        $key = array_search(".svn", $map_modules);
        if (isset($map_modules[$key]))
            unset($map_modules[$key]);

        foreach ($map_modules as $module) {
            $map_controllers_module = directory_map('./modules/' . $module . '/controllers/', TRUE, TRUE);

            foreach ($map_controllers_module as $controller) {
                list($file, $ext) = explode('.', $controller);

                if ($ext == 'php') {

                    $controllers_path = APPPATH . 'modules/' . $module . '/controllers/';

                    if ($file != 'crud_controller'
                            && $file != 'base_controller'
                            && $file != 'resource'
                            && $file != 'home') {
                        include($controllers_path . $controller);
                    }


                    $class_methods = get_class_methods(ucfirst($file));

                    debug($file, 'Arquivo ');
                    debug($class_methods, 'Metodos do Arquivo ');

                    foreach ($class_methods as $method) {
                        if (strpos($method, '_') !== 0
                                && strpos($method, 'msg') !== 0
                                && $method != 'g'
                                && $method != 'get_instance'
                                && $method != 'init_resource'
                                && $method != 'external_callbacks'
                                && $method != 'ajax_delete'
                                && $method != 'save'
                                && $method != 'get_city') {
                            $this->controller = $controller = strtolower($file);
                            $this->method = $method = strtolower($method);

                            $actual_resources[$controller][$method] = TRUE;

                            if (!isset($persisted_resources[$controller][$method])) {
                                //echo 'PARA NOSSA ALEGRIA3';
                                $this->save();
                                $this->clear();
                            }
                        }
                    }
                }
            }
        }
        die();

        $resources_to_delete = array_diff_key($persisted_resources, $actual_resources);
        foreach ($resources_to_delete as $resource) {
            foreach ($resource as $resource_id) {
                //$this->Role_has_resource_model->delete(array('resource_id' => $resource_id));                
                $this->where('id', $resource_id);
                $this->get();
                $this->delete();
                $this->clear();
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
            //log_message("info", "Transação commit: " . $this->router->class . "/" . $this->router->method . " Linha: " . __LINE__);
            return TRUE;
        }
    }

    function convert_resources_array() {
        $resources = $this->get();
        $new_array = array();

        foreach ($resources as $resource) {
            $new_array[$resource->controller][$resource->method] = $resource->id;
        }

        return $new_array;
    }

}