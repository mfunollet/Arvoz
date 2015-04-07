<?php
include_once APPPATH . 'modules/docs/models/docs_model.php';
/**
* Model for Management planning
*
* @copyright  2012 ARQABS
* @version $Id$
*/

class Management_planning_model extends Docs_model {
    
    function __construct()
    {
        parent::__construct();
    }

    /**
    * Get all planning registers
    *
    * @return array
    */
    function get_all_planning()
    {   
        $sql =  'SELECT  MP.id,
                    MP.planning_type_id,
                    PT.name as planning_type,
                    MP.entrepreneur,
                    MP.venture,
                    MP.version,
                    MP.approval_date,
                    MP.file_path,
                    MP.create_date,
                    MP.update_date
                From
                    management_planning MP
                    join planning_type PT on MP.planning_type_id = PT.id
                where MP.company_id = ?
                order by MP.update_date desc;';
                
         return $this->db->query($sql, array($this->logged_company->id))->result();
    }
    
    /**
    * Get all planning types to fill the combo
    *
    * @return array
    */
    function fill_planing_type_combo()
    {
        $query = $this->db->query('SELECT id,name FROM planning_type;');
        $dropdowns = $query->result();
        foreach($dropdowns as $dropdown)
        {
            $dropDownList[$dropdown->id] = $dropdown->name;
        }
    
        $finalDropDown = $dropDownList;
        return $finalDropDown;
    }
    
     /**
    * Gets a specific planning
    *
    *
    * @param integer $id primary key
    */
    function get($id = NULL)
    {
        $this->db->select('*');
        $this->db->from('management_planning');
        // Check if we're getting one row or all records
        if($id != NULL){
            // Getting only ONE row
            $this->db->where('id', $id);
            $this->db->limit('1');
            $query = $this->db->get();
            if( $query->num_rows() == 1 ){
            // One row, match!
            return $query->row();        
            } else {
            // None
            return false;
            }
        } else {
            // Get all
            $query = $this->db->get();
            if($query->num_rows() > 0){
            // Got some rows, return as assoc array
            return $query->result();
            } else {
            // No rows
            return false;
            }
        }
        
    }
    
    /**
    * Inserts a new planning
    *
    *
    * @param string $file_upload_path path to upload file
    * @param string $final_file_name file name
    */
    function insert($file_upload_path,$final_file_name)
    {
        $date = trim($this->input->post('approval_date'));
        if (strstr($date, "/")){
            $aux2 = explode ("/", $date);
            $datai2 = $aux2[2] . "-". $aux2[1] . "-" . $aux2[0];
        }
    
        $object = new Management_planning_object;
        
        $object->company_id = $this->logged_company->id;
        $object->planning_type_id = $this->input->post('planning_type_id');
        $object->entrepreneur = $this->input->post('entrepreneur');
        $object->venture = $this->input->post('venture');
        $object->version = $this->input->post('version');
        $object->approval_date = $datai2;
        $object->file_path =  ltrim($file_upload_path . $final_file_name, ".");
        $object->create_date = date('Y-m-d H:i:s');
        $object->update_date = date('Y-m-d H:i:s');
        
	    return $this->db->insert('management_planning', $object);
    }
    
    /**
    * Updates a planning
    *
    * @param int $id id
    * @param string $file_upload_path path to upload file
    */
    function update($id, $file_upload_path)
    {
        $date = trim($this->input->post('approval_date'));
        if (strstr($date, "/")){
            $aux2 = explode ("/", $date);
            $datai2 = $aux2[2] . "-". $aux2[1] . "-" . $aux2[0];
        }
    
        $object = new Management_planning_object;
        
        $object->company_id = $this->logged_company->id;
        $object->planning_type_id = $this->input->post('planning_type_id');
        $object->entrepreneur = $this->input->post('entrepreneur');
        $object->venture = $this->input->post('venture');
        $object->version = $this->input->post('version');
        $object->approval_date = $datai2;
        $object->file_path =  ltrim($file_upload_path, ".");
        $object->update_date = date('Y-m-d H:i:s');
        $object->create_date = $this->input->post('create_date');
        
	    $this->db->where('id', $id);
	    $this->db->update('management_planning', $object);
        
        // Return
        if($result)
        {
            return $id;
        } 
        else 
        {
            return false;
        }
    }
    
}

/**
* Management planning entity
*
*
* @copyright  2012 ARQABS
* @version $Id$
*/
class Management_planning_object
{
    /**
    * Company id
    * @access public
    * @var integer
    */
    public $company_id;
    /**
    * Planning type id
    * @access public
    * @var integer
    */
    public $planning_type_id;
    /**
    * venture 
    * @access public
    * @var string
    */
    public $venture;
    /**
    * entrepreneur 
    * @access public
    * @var string
    */
    public $entrepreneur;
    /**
    * Version
    * @access public
    * @var decimal
    */
    public $version;
    /**
    * Approval Date
    * @access public
    * @var datetime
    */
    public $approval_date;
    /**
    * File phisycal path for document
    * @access public
    * @var string
    */
    public $file_path;
    /**
    * Creation date
    * @access public
    * @var datetime
    */
    public $create_date;
    /**
    * Update date
    * @access public
    * @var datetime
    */
    public $update_date;  
}

?>