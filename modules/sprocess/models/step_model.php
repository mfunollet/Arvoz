<?php

/**
 * Step Model file is the central point to interact with the entity Step_model
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
include_once APPPATH . 'modules/base/models/entity_model.php';
include_once 'step_object.php';

/**
 * Step_model class are responsible about the interact with the entity Step
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
Class Step_model extends Entity_model {

    /**
     * The contruct of class Step_model
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
        $this->table = 'step';
        //Contains the name of the view of the entity
        //$this->table_view = '';
        //Contains the name of the set entity object
        $this->data_type = 'step_object';

        $this->load->model('Activity_model', 'Activity');
        $this->load->model('Step_type_model', 'Step_type');
        $this->load->model('pcr/Person_model', 'Person');
    }

    function insert($data)
    {
        $step_number = $this->get_last_step_number($data['selection_process_id']);
        $data['step_number'] = ( sizeof($step_number) != 0 ) ? ($step_number->step_number + 1) : 1;
        return parent::insert($data);
    }

    function get($step_id)
    {
        $step = parent::get($step_id);

        if ( $step )
        {
            $step->activities = $this->Activity->get_all_by_step($step_id);
            $step->step_type = $this->Step_type->get($step->step_type_id);
            $step->evaluator = $this->Person->get($step->evaluator_id);
        }
        return $step;
    }

    function get_last_step_number($selection_process_id)
    {
        $sql = <<< FIM
            SELECT MAX(S.step_number) AS step_number
            FROM step AS S
            where S.selection_process_id = ?;
FIM;
        $query = $this->db->query($sql, array($selection_process_id));
        return $query->row();
    }

    function get_duration_sum_filtered_by_step_number($selection_process_id, $step_number)
    {
        $sql = <<< FIM
            SELECT SUM(S.duration) AS duration_sum
            FROM step AS S
            where S.selection_process_id = ?
            and S.step_number < ?;
FIM;
        $query = $this->db->query($sql, array($selection_process_id, $step_number));
        return $query->row();
    }

    function get_all_by_selection_process($selection_process_id)
    {
        $sql = <<< FIM
            SELECT
            SP.name AS selection_process_name,
            S.*
            FROM step AS S JOIN selection_process AS SP
            ON S.selection_process_id = SP.id
            where S.selection_process_id = ?
            ORDER BY S.step_number ASC, S.id ASC;
FIM;
        $query = $this->db->query($sql, array($selection_process_id));
        $steps = $query->result('Step_object');

        for ( $i = 0; $i < count($steps); $i++ )
        {
            $steps[$i]->activities = $this->Activity->get_all_by_step($steps[$i]->id);
            $steps[$i]->step_type = $this->Step_type->get($steps[$i]->step_type_id);
            $steps[$i]->evaluator = $this->Person->get($steps[$i]->evaluator_id);
        }
        return $steps;
    }

}

class Step_status {
    const WAITING = 0;
    const PROGRESS = 1;
    const DELAYED = 2;
    const COMPLETED = 3;

    static function get_step_status($id)
    {
        switch ( $id )
        {
            case Step_status::WAITING:
                $status = lang('waiting');
                break;
            case Step_status::PROGRESS:
                $status = lang('progress');
                break;
            case Step_status::DELAYED:
                $status = lang('delayed');
                break;
            case Step_status::COMPLETED:
                $status = lang('completed');
                break;
            default:
                $status = lang('error');
        }
        return $status;
    }

}