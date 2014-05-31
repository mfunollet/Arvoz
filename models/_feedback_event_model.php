<?php

/**
 * Feedback_event Model file is the central point to interact with the entity 
 * Feedback_event_model
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
include_once APPPATH . 'modules/base/models/entity_model.php';
include_once 'feedback_event_object.php';

/**
 * feedback_event class are responsible about the interact with the entity 
 * feedback event
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
Class Feedback_event_model extends Entity_model {

    /**
     * The contruct of class Feedback_event_model
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
        $this->table = 'feedback_event';
        //Contains the name of the view of the entity
        //$this->table_view = '';
        //Contains the name of the set entity object
        $this->data_type = 'Feedback_event_object';
    }

}