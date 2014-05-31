<?php

/**
 * Selection process has company model file is the central point to interact with the entity 
 * Selection_process_has_company_model
 *
 * @copyright  2012 ARQABS
 * @version    $Id$
 */
include_once APPPATH . 'modules/base/models/entity_model.php';
include_once 'selection_process_has_company_object.php';
include_once APPPATH . 'modules/pcr/models/company_model.php';

/**
 * Selection_process_has_company_model class are responsible about the interact with the entity Selection_process_model
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
Class Selection_process_has_company_model extends Entity_model {

    /**
     * The contruct of class Selection_process_has_company_model
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
        $this->table = 'selection_process_has_company';
        //Contains the name of the set entity object
        $this->data_type = 'Selection_process_has_company_object';
    }

    function get_companies($selection_process_id)
    {
        $sql = <<< END_SQL
        SELECT
        C.id AS company_id,
        C.p_id AS p_id,
        C.name,
        C.cnpj,
        P.username,
        P.primary_email,
        P.birthday,
        P.image,
        P.additional_information,
        P.create_time,
        P.update_time
        FROM selection_process_has_company AS SPHC JOIN company AS C
        ON SPHC.company_id = C.id
        JOIN p AS P
        ON C.p_id = P.id
        WHERE SPHC.selection_process_id = ?;
END_SQL;
        $query = $this->db->query($sql, array($selection_process_id));
        return $query->result('Company_object');
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
    function get_all_by_selection_process($selection_process_id, $company_id)
    {
        $companies = $this->model->get_where(array('selection_process_id' => $selection_process_id, 'company_id' => $company_id));

        for ( $i = 0; $i < count($companies); $i++ )
        {
            $companies[$i]->company = $this->Company->get($companies[$i]->company_id);
            if ( !empty($companies[$i]->step_disqualification) )
                $companies[$i]->step = $this->Step->get($companies[$i]->step_disqualification);
        }
        return $companies;
    }

}