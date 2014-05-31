<?php
include_once APPPATH . 'modules/docs/models/docs_model.php';

/**
* Common model to be reused between multiple controllers
*
* @copyright  2012 ARQABS
* @version $Id$
*/

class Common_model extends Docs_model {
    
    function __construct()
    {
        parent::__construct();
    }

    /**
    * Get all  actions to fill the combo
    *
    * @return array
    */
    function fill_action_combo()
    {
        $query = $this->db->query('SELECT id,name FROM venture_action;');
        $dropdowns = $query->result();
        foreach($dropdowns as $dropdown)
        {
            $dropDownList[$dropdown->id] = $dropdown->name;
        }
    
        $finalDropDown = $dropDownList;
        return $finalDropDown;
    }
    
     /**
    * Get all  qualification shafts to fill the combo
    *
    * @return array
    */
    function fill_qualification_shaft_combo()
    {
        $query = $this->db->query('SELECT id,name FROM qualification_shaft;');
        $dropdowns = $query->result();
        foreach($dropdowns as $dropdown)
        {
            $dropDownList[$dropdown->id] = $dropdown->name;
        }
    
        $finalDropDown = $dropDownList;
        return $finalDropDown;
    }
    
    /**
    * Deletes an id from any database identity
    *
    * @param integer id
    * @param string entity
    */
	function delete($id, $entity){
		$this->db->where('id', $id);
		$this->db->delete($entity);
	}
}

?>