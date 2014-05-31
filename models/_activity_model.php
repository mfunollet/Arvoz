<?php

/**
 * Activity Model file is the central point to interact with the entity 
 * Activity_model
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
include_once APPPATH . 'modules/base/models/entity_model.php';
include_once 'activity_object.php';

/**
 * Activity_model class are responsible about the interact with the entity 
 * Activity
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
Class Activity_model extends Entity_model {

    /**
     * The contruct of class Activity_model
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
        $this->table = 'activity';
        //Contains the name of the view of the entity
        //$this->table_view = '';
        //Contains the name of the set entity object
        $this->data_type = 'Activity_object';
        $this->load->model('pcr/Person_model', 'Person');
    }

    function get_all_by_step($step_id)
    {
        $sql = <<< FIM
            SELECT
            A.*
            FROM activity AS A JOIN step AS S
            ON A.step_id = S.id
            JOIN p_and_ps_view AS PP
            ON A.responsible_id = PP.id
            where A.step_id = ?;
FIM;

        $query = $this->db->query($sql, array($step_id));
        $activities = $query->result('Activity_object');

        for ( $i = 0; $i < count($activities); $i++ )
        {
            $p_and_p = $this->P_and_p->get($activities[$i]->responsible_id);
            $activities[$i]->responsible = $this->Person->get_where_unique(array('p_id' => $p_and_p->p_id));
            $activities[$i]->responsible_meta_data = $p_and_p;
        }
        return $activities;
    }
    
    function get($id)
    {
        $activity = parent::get($id);
        $p_and_p = $this->P_and_p->get($activity->responsible_id);
        $activity->responsible = $this->Person->get_where_unique(array('p_id' => $p_and_p->p_id));
        $activity->responsible_meta_data = $p_and_p;
        return $activity;
    }

}

class Activity_status {
    const WAITING = 0;
    const PROGRESS = 1;
    const COMPLETED = 2;

    static function get_activity_status($id)
    {
        switch ( $id )
        {
            case Activity_status::WAITING:
                $status = lang('waiting');
                break;
            case Activity_status::PROGRESS:
                $status = lang('progress');
                break;
            case Activity_status::COMPLETED:
                $status = lang('completed');
                break;
            default:
                $status = lang('error');
        }
        return $status;
    }

}