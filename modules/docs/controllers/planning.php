<?php
include_once APPPATH . 'modules/docs/controllers/docs_controller.php';
/**
* Controller class for Management Planning
*
*
*
* @copyright  2012 ARQABS
* @version $Id$
*/

class Planning extends Docs_controller {
    
   public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Management_planning_model','',TRUE);
        $this->load->helper('field');
        $this->load->model('Common_model','',TRUE);
    }
    
    /**
    * Index action
    *
    * Redirects to show action
    *
    */
    public function index()
    {
        $url = 'planning/show';
        redirect($url, 'location'); 
    }
    
    /**
    * Show Action
    *
    * List all the planning registers
    *
    */
    public function show()
    {
        
        $header_data['active'] = 'planning';
        
        $data['query'] = $this->Management_planning_model->get_all_planning();
        $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
        $this->load->view('planning_show_view',$data);
        $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);
    }
    
    /**
    * Create Action
    *
    * Display view for insert
    *
    */
    public function create()
    {
    
        $data['planning_types'] = $this->Management_planning_model->fill_planing_type_combo();
        $data['error'] = array();
        
        $header_data['active'] = 'planning';
                       
        $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
        $this->load->view('planning_create_view',$data);
        $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);    
    }
    
    /**
    * Edit Action
    *
    * Display view for edit
    * @param integer $id - id for update   
    */
   public function edit($id = NULL)
   {
        if($id == NULL)
        {
            $id = $this->uri->segment(3);    
        }
                
        $data['planning_types'] = $this->Management_planning_model->fill_planing_type_combo();
        $data['error'] = array();
        
        $header_data['active'] = 'planning';
        
        $data['planning_object'] = $this->Management_planning_model->get($id);
        
        $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
        $this->load->view('planning_create_view',$data);
        $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);
        
   }
    
    /**
    * Insert Action
    *
    * Insert a new planning register
    *
    */
    public function insert()
    {
    
        $id = $this->input->post('id');
   
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('entrepreneur', 'Empreendedor', 'required');
        $this->form_validation->set_rules('venture', 'Empreendimento', 'required');
        $this->form_validation->set_rules('version', 'Versão', 'required|numeric');
        $this->form_validation->set_rules('approval_date', 'Data', 'callback_action_date_check');
        
        if ($this->form_validation->run() == FALSE)
		{
			// Validation failed
            if($id != "X")
            {
                return $this->edit($id);
            } 
            else 
            {
                return $this->create();
            }    
		}
		else
		{
        
            if (!empty($_FILES['userfile']['name']))
            {
            
                $file_upload_path = './uploads/docs/management_planning/';
            
                $file_directory = realpath('.') . '\\uploads\\docs\\management_planning\\';
                
                if(!is_dir($file_directory))
                {
                    mkdir($file_directory, 0777, TRUE);
                }
            
                $this->load->library('upload');
            
                $name = $_FILES['userfile']['name']; // get file name from form
                $fileNameParts   = explode(".", $name); // explode file name to two part
                $fileExtension   = end($fileNameParts); // give extension
                $fileExtension   = strtolower($fileExtension); // convert to lower case
                $final_file_name =  $this->logged_company->id . "_management_planning_" . date('Ymd_H_i_s') . "."  .$fileExtension;  // new file name
                
                $config['upload_path'] = $file_upload_path;
                $config['allowed_types'] = 'doc|docx|xls|xlsx|pdf|ppt|pps|pptx|zip|rar|7z';
                $config['max_size']	= '12000';
                $config['file_name'] = $final_file_name; //set file name
                
                // Initialize config for File 1
                $this->upload->initialize($config);
                
                
                
                // Upload file 1
                if ($this->upload->do_upload('userfile'))
                {
                    $data = $this->upload->data();
                }
                else
                {
                    $data['error'] = array('error' => $this->upload->display_errors());
                    
                    $data['planning_types'] = $this->Management_planning_model->fill_planing_type_combo();
                    
                    $header_data['active'] = 'planning';
                                
                    $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
                    $this->load->view('planning_create_view',$data);
                    $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);    
                        
                    return;
                }
            }    
            if($id == 'X')
            {
                $this->Management_planning_model->insert($file_upload_path,$final_file_name);
            }
            else
            {
                if (!empty($final_file_name))
                {
                     $document_path = $file_upload_path . $final_file_name;
                }
                elseif($this->input->post('file_path') != NULL)
                {
                    $document_path = $this->input->post('file_path');
                }
                else
                {
                    $document_path = '';
                }
                
                $this->Management_planning_model->update($id, $document_path);
            }

			redirect('planning/show/success', 'location'); 
		}
       
        
    }
    
    /**
    * Delete Action
    *
    * Deletes a planning register
    *
    */
    function delete($id){
        
		$this->Common_model->delete($id,'management_planning');
		
		redirect('planning/show/success', 'location'); 
	}
   
    /**
    * Acion date check
    *
    * Method to check if the action date is valid
    *
    */
    public function action_date_check($str){
        
        if(empty($str))
        {
            $this->form_validation->set_message('action_date_check', 'O campo %s é obrigatório.');
            return FALSE;
        }
        
        if (!strstr($str, "/")){
            $this->form_validation->set_message('action_date_check', 'Data inválida');
            return FALSE;
        }
        $data = explode("/","$str"); 
        $d = $data[0];
        $m = $data[1];
        $y = $data[2];
    
        // check if the date is valid
        // 1 = true 
        // 0 = false 
        $res = checkdate($m,$d,$y);
        if ($res == 1)
        {
            return TRUE;
        } 
        else 
        {
            $this->form_validation->set_message('action_date_check', 'Data inválida');
            return FALSE;
        }
    }
    
}


?>