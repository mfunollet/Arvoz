<?php

/**
 * Step type Model file is the central point to interact with the entity 
 * Step_type_model
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
include_once APPPATH . 'modules/base/models/entity_model.php';
include_once 'step_type_object.php';

/**
 * Step_type_model class are responsible about the interact with the entity 
 * Step_type
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
Class Step_type_model extends Entity_model {

    /**
     * The contruct of class Step_type_model
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
        $this->table = 'step_type';
        //Contains the name of the view of the entity
        //$this->table_view = '';
        //Contains the name of the set entity object
        $this->data_type = 'step_type_object';
    }

    function insert($data)
    {
        isset($_POST['has_document_checkbox']) ? $data['has_document'] = 1 : $data['has_document'] = 0;
        return parent::insert($data);
    }

    function update_by_id($id, $data)
    {
        isset($_POST['has_document_checkbox']) ? $data['has_document'] = 1 : $data['has_document'] = 0;
        return parent::update_by_id($id, $data);
    }

}