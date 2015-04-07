<?php
include_once APPPATH . 'modules/docs/models/docs_model.php';
/**
* Model for entrepreneur qualification
*
* @copyright  2012 ARQABS
* @version $Id$
*/

class Entrepreneur_qualification_model extends Docs_model {
    
    function __construct()
    {
        parent::__construct();
    }

    /**
    * Get all qualification documents
    *
    * @return array
    */
    function get_all_qualification()
    {   
        $sql =     'SELECT 
                    EQ.id,
                    EQ.entrepreneur_key_practice_id,
                    A.name as venture_action,
                    KP.id as entrepreneur_key_practice_id,
                    KP.name as entrepreneur_key_practice,
                    EQ.description,
                    EQ.action_date,
                    EQ.owner,
                    EQ.place,
                    EQ.attendees_number,
                    EQ.document_path,
                    EQ.photo_path,
                    EQ.create_date,
                    EQ.update_date,
                    EQ.workload,
                    EQ.content_text
                from entrepreneur_qualification EQ
                join venture_action A on EQ.venture_action_id = A.id
                join entrepreneur_key_practice KP on EQ.entrepreneur_key_practice_id = KP.id
                where EQ.company_id = ? 
                order by EQ.update_date desc;';
                            
         return $this->db->query($sql, array($this->logged_company->id))->result();
    }
    
     /**
    * Gets a specific qualification
    *
    *
    * @param integer $id primary key
    */
    function get($id = NULL)
    {
        $this->db->select('*');
        $this->db->from('entrepreneur_qualification');
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
    * Get all entrepreneur key practices to fill the combo
    *
    * @return array
    */
    function fill_key_practice_combo()
    {
        $query = $this->db->query('SELECT id,name FROM entrepreneur_key_practice;');
        $dropdowns = $query->result();
        foreach($dropdowns as $dropdown)
        {
            $dropDownList[$dropdown->id] = $dropdown->name;
        }
    
        $finalDropDown = $dropDownList;
        return $finalDropDown;
    }
    
    /**
    * Inserts a new qualification
    *
    *
    * @param string $file_upload_path path to upload file
    * @param string $final_file_name file name
    * @param string $file_upload_path_photo path to upload photo file
    * @param string $final_file_name_photo photo file name
    */
    function insert($file_upload_path,$final_file_name,$file_upload_path_photo,$final_file_name_photo)
    {
    
        $date = trim($this->input->post('action_date'));
        if (strstr($date, "/")){
            $aux2 = explode ("/", $date);
            $datai2 = $aux2[2] . "-". $aux2[1] . "-" . $aux2[0];
        }
    
        $object = new Entrepreneur_qualification_object;
        
        $object->company_id = $this->logged_company->id;
        $object->entrepreneur_key_practice_id = $this->input->post('entrepreneur_key_practice_id');
        $object->venture_action_id = $this->input->post('venture_action_id');
        $object->description = $this->input->post('description');
        $object->action_date = $datai2;
        $object->owner = $this->input->post('owner');
        $object->place = $this->input->post('place');
        $object->attendees_number = $this->input->post('attendees_number');
        $object->document_path =  ltrim($file_upload_path . $final_file_name, ".");
        $object->photo_path =  ltrim($file_upload_path_photo . $final_file_name_photo, ".");
        $object->create_date = date('Y-m-d H:i:s');
        $object->update_date = date('Y-m-d H:i:s');
        $object->workload = $this->input->post('workload');
        $object->content_text = $this->input->post('content_text');
        
	    return $this->db->insert('entrepreneur_qualification', $object);
    }
    
     /**
    * Updates a qualification
    *
    * @param int $id Primary key
    * @param string $file_upload_path path to upload document file
    * @param string $file_upload_path_photo path to upload photo file
    */
    function update($id, $file_upload_path, $file_upload_path_photo)
    {
    
        $date = trim($this->input->post('action_date'));
        if (strstr($date, "/")){
            $aux2 = explode ("/", $date);
            $datai2 = $aux2[2] . "-". $aux2[1] . "-" . $aux2[0];
        }
    
        $object = new Entrepreneur_qualification_object;
        
        $object->company_id = $this->logged_company->id;
        $object->entrepreneur_key_practice_id = $this->input->post('entrepreneur_key_practice_id');
        $object->venture_action_id = $this->input->post('venture_action_id');
        $object->description = $this->input->post('description');
        $object->action_date = $datai2;
        $object->owner = $this->input->post('owner');
        $object->place = $this->input->post('place');
        $object->attendees_number = $this->input->post('attendees_number');
        $object->document_path =  ltrim($file_upload_path, ".");
        $object->photo_path =  ltrim($file_upload_path_photo, ".");
        $object->update_date = date('Y-m-d H:i:s');
        $object->create_date = $this->input->post('create_date');
        $object->workload = $this->input->post('workload');
        $object->content_text = $this->input->post('content_text');
        
        $this->db->where('id', $id);
	    $this->db->update('entrepreneur_qualification', $object);
        
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
* Entrepreneur qualification entity
*
*
* @copyright  2012 ARQABS
* @version $Id$
*/
class Entrepreneur_qualification_object
{
    /**
    * Company id
    * @access public
    * @var integer
    */
    public $company_id;
    /**
    * Entrepreneur key practice id
    * @access public
    * @var integer
    */
    public $entrepreneur_key_practice_id;
    /**
    * Venture prospection action id
    * @access public
    * @var integer
    */
    public $venture_action_id;
    /**
    * Description
    * @access public
    * @var string
    */
    public $description;
    /**
    * Date
    * @access public
    * @var datetime
    */
    public $action_date;
    /**
    * Name of the owner
    * @access public
    * @var string
    */
    public $owner;
    /**
    * Place
    * @access public
    * @var string
    */
    public $place;
    /**
    * Number of atendees
    * @access public
    * @var integer
    */
    public $attendees_number;
    /**
    * File phisycal path for document
    * @access public
    * @var string
    */
    public $document_path;
    /**
    * File phisycal path for photo
    * @access public
    * @var string
    */
    public $photo_path;
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
    /**
    * Workload
    * @access public
    * @var int
    */
    public $workload;
    /**
    * Summarized content
    * @access public
    * @var int
    */
    public $content_text;
}

?>