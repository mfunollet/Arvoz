<?php
include_once APPPATH . 'modules/docs/models/docs_model.php';
/**
* Model for process documents manager
*
* @copyright  2012 ARQABS
* @version $Id$
*/
class Process_document_model extends Docs_model {

    var $CI;
    var $logged_company = NULL;

    function __construct() {
        parent::__construct();
        $this->CI = & get_instance();
        $this->logged_company = $this->CI->authentication->get_logged_company();
    }

    /**
    * Get all key process documents
    *
    * @return array
    */
    function get_all_documents()
    {   
       $query = $this->db->query('SELECT  ml.id maturity_level_id,  
                                    ml.name maturity_level,
                                    kp.id key_process_id, 
                                    kp.name key_process, 
                                    pd.id process_document_id,
                                    pd.key_practice_id,
                                    kpa.name as key_practice,
                                    pd.version,
                                    pd.approval_date,
                                    pd.approver,
                                    pd.file_path,
                                    pd.create_date,
                                    (select GROUP_CONCAT(concat( CONVERT(kpml.key_practice_id USING utf8), "|", kpa2.name)) from key_practice_has_maturity_level kpml 
                                        left join key_practice kpa2 on kpml.key_practice_id = kpa2.id
                                        where kpml.maturity_level_id = ml.id) key_practice_has_maturity_level 
                                    FROM maturity_level ml join key_process kp on ml.id = kp.maturity_level_id
                                    left join process_document pd on kp.id = pd.key_process_id
                                    AND pd.company_id =  ' . $this->logged_company->id .
                ' left join key_practice kpa on pd.key_practice_id = kpa.id
                                    order by ml.id, kp.id, pd.create_date desc;');
                            
        return $query->result();
    }
    
    /**
    * Get key process documents history
    *
    * @return array
    */
    function get_documents_by_key_process_id($key_process_id)
    {
        
        $sql = 'SELECT 
                pd.id process_document_id,
                pd.company_id,
                pd.key_process_id,
                kp.name as key_process,
                ml.name as maturity_level,
                pd.key_practice_id,
                kpa.name as key_practice,
                pd.version,
                pd.approval_date,
                pd.approver,
                pd.file_path,
                pd.create_date,
                pd.update_date
            FROM process_document pd
            Inner join key_process kp on pd.key_process_id = kp.id
            inner join maturity_level ml on kp.maturity_level_id = ml.id
            inner join key_practice kpa on pd.key_practice_id = kpa.id
            where pd.company_id = ? and pd.key_process_id = ?
            order by pd.create_date desc;';



        return $this->db->query($sql, array($this->logged_company->id, $key_process_id))->result();
    }

    /**
     * Get audit data for process document
     *
     * @return array
     */
    function get_audit_data_by_key_process_id($key_process_id) {

        $sql = 'SELECT 
                pd.process_document_id,
                pd.company_id,
                pd.key_process_id,
                kp.name as key_process,
                ml.name as maturity_level,
                pd.key_practice_id,
                kpa.name as key_practice,
                pd.version,
                pd.approval_date,
                pd.approver,
                pd.file_path,
                pd.create_date,
                pd.action
            FROM process_document_audit pd
            Inner join key_process kp on pd.key_process_id = kp.id
            inner join maturity_level ml on kp.maturity_level_id = ml.id
            inner join key_practice kpa on pd.key_practice_id = kpa.id
            where pd.company_id = ? and pd.key_process_id = ?
            order by pd.create_date desc;';

        return $this->db->query($sql, array($this->logged_company->id, $key_process_id))->result();
    }
    
    /**
    * Inserts a new document file and data
    *
    *
    * @param string $file_upload_path path to upload file
    * @param string $final_file_name file name
    */
    function insert_document($file_upload_path,$final_file_name)
    {
    
        $approval_date = trim($this->input->post('approval_date'));
        if (strstr($approval_date, "/")){
            $aux2 = explode ("/", $approval_date);
            $datai2 = $aux2[2] . "-". $aux2[1] . "-" . $aux2[0];
        }
    
        $object = new Process_document_object;

        $object->company_id = $this->logged_company->id;
        $object->key_process_id = $this->input->post('key_process_id');
        $object->key_practice_id = $this->input->post('key_practice_id');
        $object->version = $this->input->post('version');
        $object->approval_date = $datai2;
        $object->approver = $this->input->post('approver');
        $object->file_path =  ltrim($file_upload_path . $final_file_name, ".");
        $object->create_date = date('Y-m-d H:i:s');
        
	    return $this->db->insert('process_document', $object);
    }
    
    /**
    * Updates a document data
    *
    *
    * @param integer $id key_process_id
    *
    */
    function update_document($id)
    {
        
        $approval_date = trim($this->input->post('approval_date'));
        if (strstr($approval_date, "/")){
            $aux2 = explode ("/", $approval_date);
            $datai3 = $aux2[2] . "-". $aux2[1] . "-" . $aux2[0];
        }
     
        $object = new Process_document_object;

        $object->company_id = $this->logged_company->id;
        $object->key_process_id = $this->input->post('key_process_id');
        $object->key_practice_id = $this->input->post('key_practice_id');
        $object->version = $this->input->post('version');
        $object->approval_date = $datai3;
        $object->approver = $this->input->post('approver');
        $object->file_path =  $this->input->post('file_path');
        $object->create_date = $this->input->post('create_date');
        $object->update_date = date('Y-m-d H:i:s');
        
        $this->db->update('process_document', $object, array('id' => $id));
    }
    
}

/**
* Process document entity
*
*
* @copyright  2012 ARQABS
* @version $Id$
*/
class Process_document_object
{
    /**
    * Company id
    * @access public
    * @var integer
    */
    public $company_id;
    /**
    * Key process id
    * @access public
    * @var integer
    */
    public $key_process_id;
    /**
    * Key practice id
    * @access public
    * @var integer
    */
    public $key_practice_id;
    /**
    * Version for file
    * @access public
    * @var double
    */
    public $version;
    /**
    * Approval date
    * @access public
    * @var datetime
    */
    public $approval_date;
    /**
    * Name of the approver
    * @access public
    * @var string
    */
    public $approver;
    /**
    * File phisycal path
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