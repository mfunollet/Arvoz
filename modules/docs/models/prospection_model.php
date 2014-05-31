<?php
include_once APPPATH . 'modules/docs/models/docs_model.php';
/**
* Model for venture prospection
*
* @copyright  2012 ARQABS
* @version $Id$
*/

class Prospection_model extends Docs_model {
    
    function __construct()
    {
        parent::__construct();
    }
    
    
    /**
    * Get all prospection documents
    *
    * @return array
    */
    function get_all_prospections($type)
    {   
        $sql =     'SELECT 
                    VP.id,
                    VP.venture_prospection_type_id,
                    A.name as prospection_action,
                    VP.description,
                    VP.action_date,
                    VP.owner,
                    VP.place,
                    VP.attendees_number,
                    VP.document_path,
                    VP.photo_path,
                    VP.create_date,
                    VP.update_date,
                    VP.workload,
                    VP.content_text
                from venture_prospection VP 
                join venture_action A on VP.venture_action_id = A.id
                where VP.company_id = ? and VP.venture_prospection_type_id = ?
                order by VP.update_date desc;';
                            
         return $this->db->query($sql, array($this->logged_company->id, $type))->result();
    }
    
    /**
    * Get prospection document
    *
    * @return row
    */
    function get_prospection_documents($type)
    {
        return $this->db->query("SELECT id, venture_prospection_type_id, description, action_date, owner, file_path, create_date FROM venture_prospection_document where company_id = ? and venture_prospection_type_id = ? order by id desc;",
        array($this->logged_company->id, $type))->result();
    }
    
    /**
    * Inserts a new prospection
    *
    *
    * @param string $file_upload_path path to upload file
    * @param string $final_file_name file name
    */
    function insert($file_upload_path,$final_file_name,$file_upload_path_photo,$final_file_name_photo)
    {
    
        $date = trim($this->input->post('action_date'));
        if (strstr($date, "/")){
            $aux2 = explode ("/", $date);
            $datai2 = $aux2[2] . "-". $aux2[1] . "-" . $aux2[0];
        }
    
        $object = new Venture_prospection_object;
        
        $object->company_id = $this->logged_company->id;
        $object->venture_prospection_type_id = $this->input->post('venture_prospection_type_id');
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
        
	    return $this->db->insert('venture_prospection', $object);
    }
    
    
    /**
    * Gets a specific prospection
    *
    *
    * @param integer $id primary key
    */
    function Get($id = NULL)
    {
        $this->db->select('*');
        $this->db->from('venture_prospection');
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
    
    
    function get_document($id = NULL)
    {
        $this->db->select('*');
        $this->db->from('venture_prospection_document');
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
    * Updates a prospection
    *
    *
    * @param int $id primary key
    * @param string $final_file_name file name
    */
    function update($id, $document_path, $photo_path)
    {
        $date = trim($this->input->post('action_date'));
        if (strstr($date, "/")){
            $aux2 = explode ("/", $date);
            $datai2 = $aux2[2] . "-". $aux2[1] . "-" . $aux2[0];
        }
    
        $object = new Venture_prospection_object;
        
        $object->company_id = $this->logged_company->id;
        $object->venture_prospection_type_id = $this->input->post('venture_prospection_type_id');
        $object->venture_action_id = $this->input->post('venture_action_id');
        $object->description = $this->input->post('description');
        $object->action_date = $datai2;
        $object->owner = $this->input->post('owner');
        $object->place = $this->input->post('place');
        $object->attendees_number = $this->input->post('attendees_number');
        $object->document_path =  ltrim($document_path, ".");
        $object->photo_path =  ltrim($photo_path, ".");
        $object->update_date = date('Y-m-d H:i:s');
        $object->create_date = $this->input->post('create_date');
        $object->workload = $this->input->post('workload');
        $object->content_text = $this->input->post('content_text');
        
        $this->db->where('id', $id);
	    $this->db->update('venture_prospection', $object);
        
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
    
    /**
    * Updates a prospection document
    *
    *
    * @param string $file_upload_path path to upload file
    * @param string $final_file_name file name
    */
    public function update_prospection_document($id, $document_path)
    {
        $date = trim($this->input->post('action_date'));
        if (strstr($date, "/")){
            $aux2 = explode ("/", $date);
            $datai2 = $aux2[2] . "-". $aux2[1] . "-" . $aux2[0];
        }
    
        $object = new Venture_prospection_document_object;
        
        $object->company_id = $this->logged_company->id;
        $object->venture_prospection_type_id = 2;
        $object->description = $this->input->post('description');
        $object->action_date = $datai2;
        $object->owner = $this->input->post('owner');
        $object->file_path =  ltrim($document_path, ".");
        $object->update_date = date('Y-m-d H:i:s');
        $object->create_date = $this->input->post('create_date');
        
        $this->db->where('id', $id);
	    $this->db->update('venture_prospection_document', $object);
        
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
    
    
     /**
    * Inserts a new document for prospection
    *
    *
    * @param string $file_upload_path path to upload file
    * @param string $final_file_name file name
    */
    function upload_prospection_document($file_upload_path,$final_file_name)
    {
        
        $date = trim($this->input->post('action_date'));
        if (strstr($date, "/")){
            $aux2 = explode ("/", $date);
            $datai2 = $aux2[2] . "-". $aux2[1] . "-" . $aux2[0];
        }
        
        $object = new Venture_prospection_document_object;
        
        $object->company_id = $this->logged_company->id;
        $object->venture_prospection_type_id = 2;
        $object->description = $this->input->post('description');
        $object->action_date = $datai2;
        $object->owner = $this->input->post('owner');
        $object->file_path =  ltrim($file_upload_path . $final_file_name, ".");
        $object->create_date = date('Y-m-d H:i:s');
        
	    return $this->db->insert('venture_prospection_document', $object);
    }

}

/**
* Venture prospection entity
*
*
* @copyright  2012 ARQABS
* @version $Id$
*/
class Venture_prospection_object
{
    /**
    * Company id
    * @access public
    * @var integer
    */
    public $company_id;
    /**
    * Venture prospection type id
    * @access public
    * @var integer
    */
    public $venture_prospection_type_id;
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
    * workload
    * @access public
    * @var int
    */
    public $workload;
     /**
    * Summarized content
    * @access public
    * @var string
    */
    public $content_text;
}

/**
* Venture prospection document entity
*
*
* @copyright  2012 ARQABS
* @version $Id$
*/
class Venture_prospection_document_object
{
    /**
    * Company id
    * @access public
    * @var integer
    */
    public $company_id;
    /**
    * Venture prospection type id
    * @access public
    * @var integer
    */
    public $venture_prospection_type_id;
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
}

?>