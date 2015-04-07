<?php
include_once APPPATH . 'modules/docs/controllers/docs_controller.php';
/**
* Controller class for venture prospections
*
*
*
* @copyright  2012 ARQABS
* @version $Id$
*/

class Prospection extends Docs_controller {
    
   public function __construct()
    {
        parent::__construct();
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Prospection_model','',TRUE);
        $this->load->model('Common_model','',TRUE);
        $this->load->helper('field');
    }
    
    /**
    * Index action
    *
    * Redirects to show action
    *
    * @param integer $type - type of prospection
    */
    public function index($type)
    {
        $url = 'prospection/show/' . $type;
        redirect($url, 'location'); 
    }
    
    
      /**
    * Show Action
    *
    * List all the prospection documents
    *
    * @param integer $type - type of prospection
    */
    public function show($type)
    {
        $data['venture_prospection_type_id'] = $type;        
        $header_data['active'] = 'prospection_' . $type;
        
        if($type == 2)
        {
            $data['query'] = $this->Prospection_model->get_prospection_documents($type);
            $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
            $this->load->view('prospection_document_view',$data);
            $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);
        }
        else
        {
            $data['query'] = $this->Prospection_model->get_all_prospections($type);
            $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
            $this->load->view('prospection_show_view',$data);
            $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);
        }
    }
    
    /**
    * Create Action
    *
    * Display view for insert
    * @param integer $type - type of prospection   *
    */
   public function create($type)
   {
        $data['venture_prospection_type_id'] = $type;
        $data['venture_actions'] = $this->Common_model->fill_action_combo();
        $data['error'] = array();
        
        
        $header_data['active'] = 'prospection_' . $type;
        
        $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
        $this->load->view('prospection_create_view',$data);
        $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);    
   }
   
   
   /**
    * Edit Action
    *
    * Display view for edit
    * @param integer $type - type of prospection   
    * @param integer $id - id for update   
    */
   public function edit($type, $id = NULL)
   {
        if($id == NULL)
        {
            $id = $this->uri->segment(4);    
        }
                
        $data['venture_prospection_type_id'] = $type;
        $data['venture_actions'] = $this->Common_model->fill_action_combo();
        $data['error'] = array();
        
        $header_data['active'] = 'prospection_' . $type;
        
        $data['prospection_object'] = $this->Prospection_model->Get($id);
        
        $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
        $this->load->view('prospection_create_view',$data);
        $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);    
        
   }
   
   /**
    * Edit Action
    *
    * Display view for edit document
    * @param integer $id - id for update   
    */
   public function edit_document($id = NULL)
   {
        if($id == NULL)
        {
            $id = $this->uri->segment(3);    
        }
                
        $data['error'] = array();
        
        $header_data['active'] = 'prospection_2';
        
        $data['prospection_object'] = $this->Prospection_model->get_document($id);
        
        $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
        $this->load->view('prospection_create_document_view',$data);
        $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);    
        
   }
   
   
   /**
    * Create document Action
    *
    * Display view for insert
    */
   public function create_document()
   {
        $data['error'] = array();
        
        $header_data['active'] = 'prospection_2';
                       
        $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
        $this->load->view('prospection_create_document_view',$data);
        $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);    
   }
   
   
    /**
    * Upload document Action
    *
    * Uploads document for prospection
    */
   public function upload_document()
   {
        $id = $this->input->post('id');
        
        
        $file_upload_path = './uploads/docs/prospection/Prospeccao/';
        
        $file_directory = realpath('.') . '\\uploads\\docs\\prospection\\Prospeccao\\' ;
        
        if(!is_dir($file_directory))
        {
           mkdir($file_directory, 0777, TRUE);
        }
        
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        
        $this->form_validation->set_rules('description', 'Descrição', 'required');
        $this->form_validation->set_rules('owner', 'Responsável', 'required');
        $this->form_validation->set_rules('action_date', 'Data', 'callback_action_date_check');
        
        if ($this->form_validation->run() == FALSE)
		{
        
            // Validation failed
            if($id != "X")
            {
                return $this->edit_document($id);
            } 
            else 
            {
                return $this->create_document();
            } 
		}
		else
		{
            
            $this->load->library('upload');
        
            if (!empty($_FILES['userfile']['name']))
            {
            
                $name = $_FILES['userfile']['name']; // get file name from form
                $fileNameParts   = explode(".", $name); // explode file name to two part
                $fileExtension   = end($fileNameParts); // give extension
                $fileExtension   = strtolower($fileExtension); // convert to lower case
                $final_file_name =  $this->logged_company->id . "_prospection_" . date('Ymd_H_i_s') . "."  .$fileExtension;  // new file name
                
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
                    
                    $header_data['active'] = 'prospection_2';
                       
                    $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
                    $this->load->view('prospection_create_document_view',$data);
                    $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data); 
                    return;
                }
            }
            
            if($id == 'X')
            {
                $this->Prospection_model->upload_prospection_document($file_upload_path,$final_file_name);
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
                
                $this->Prospection_model->update_prospection_document($id, $document_path);
            }

			redirect('prospection/show/2/success', 'location'); 
		}
        
   }
   
    /**
    * Insert Action
    *
    * Insert a new prospection register
    *
    */
   public function insert()
   {
   
        $id = $this->input->post('id');
   
        $venture_prospection_type_id = $this->input->post('venture_prospection_type_id');
        
        if($venture_prospection_type_id == 1)
        {
            $type_name = "Sensibilizacao";    
        }
        elseif($venture_prospection_type_id == 2)
        {
            $type_name = "Prospeccao";
        }
        else
        {
            $type_name = "Qualificacao";
        }
   
        
       
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');
        
                
        $this->form_validation->set_rules('id', 'Id', 'required');
        $this->form_validation->set_rules('description', 'Descrição', 'required');
        $this->form_validation->set_rules('owner', 'Responsável', 'required');
        $this->form_validation->set_rules('place', 'Local', 'required');
        $this->form_validation->set_rules('action_date', 'Data', 'callback_action_date_check');
        $this->form_validation->set_rules('attendees_number', 'Número de participantes', 'required|integer');
        $this->form_validation->set_rules('workload', 'Carga horária', 'required');
        $this->form_validation->set_rules('content_text', 'Texto sumarizado', 'required');
        
        if ($this->form_validation->run() == FALSE)
		{
           
           // Validation failed
            if($id != "X")
            {
                return $this->edit($this->input->post('venture_prospection_type_id'), $id);
            } 
            else 
            {
                return $this->create($this->input->post('venture_prospection_type_id'));
            }
           
		}
		else
		{
            
            $this->load->library('upload');
        
            if (!empty($_FILES['userfile']['name']))
            {
            
                $file_upload_path_docs = './uploads/docs/prospection/docs/';
              
                
                $file_directory = realpath('.') . '\\uploads\\docs\\prospection\\docs\\' ;
                
                
                if(!is_dir($file_directory))
                {
                    mkdir($file_directory, 0777, TRUE);
                }
                
            
                $name = $_FILES['userfile']['name']; // get file name from form
                $fileNameParts   = explode(".", $name); // explode file name to two part
                $fileExtension   = end($fileNameParts); // give extension
                $fileExtension   = strtolower($fileExtension); // convert to lower case
                $final_file_name_docs =  $this->logged_company->id . "_prospection-" . $this->input->post('venture_prospection_type_id') . "_" . date('Ymd_H_i_s') . "."  .$fileExtension;  // new file name
                
                $config['upload_path'] = $file_upload_path_docs;
                $config['allowed_types'] = 'doc|docx|xls|xlsx|pdf|ppt|pps|pptx|zip|rar|7z';
                $config['max_size']	= '12000';
                $config['file_name'] = $final_file_name_docs; //set file name
                
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
                    
                    $data['venture_prospection_type_id'] = $this->input->post('venture_prospection_type_id');
                    $data['venture_actions'] = $this->Common_model->fill_action_combo();
                    
                    $header_data['active'] = 'prospection_' . $this->input->post('venture_prospection_type_id');
                                
                    $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
                    $this->load->view('prospection_create_view',$data);
                    $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data); 
                    return;
                }
            }
            
            if (!empty($_FILES['userfile1']['name']))
            {
            
                $file_upload_path_photos = './uploads/docs/prospection/photos/';
                $file_directory1 = realpath('.') . '\\uploads\\docs\\prospection\\photos\\' ;
                
                if(!is_dir($file_directory1))
                {
                    mkdir($file_directory1, 0777, TRUE);
                }
                
                $name = $_FILES['userfile1']['name']; // get file name from form
                $fileNameParts   = explode(".", $name); // explode file name to two part
                $fileExtension   = end($fileNameParts); // give extension
                $fileExtension   = strtolower($fileExtension); // convert to lower case
                $final_file_name_photo =  $this->logged_company->id . "_prospection-" . $this->input->post('venture_prospection_type_id') . "_" . date('Ymd_H_i_s') . "."  .$fileExtension;  // new file name
                
                $config['upload_path'] = $file_upload_path_photos;
                $config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';
                $config['max_size']	= '12000';
                $config['file_name'] = $final_file_name_photo; //set file name
                
                // Initialize config for File 1
                $this->upload->initialize($config);
                
                // Upload file 1
                if ($this->upload->do_upload('userfile1'))
                {
                    $data = $this->upload->data();
                }
                else
                {
                    $data['error'] = array('error' => $this->upload->display_errors());
                    
                    $data['venture_prospection_type_id'] = $this->input->post('venture_prospection_type_id');
                    $data['venture_actions'] = $this->Common_model->fill_action_combo();
                    
                    $header_data['active'] = 'prospection_' . $this->input->post('venture_prospection_type_id');
                                
                    $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
                    $this->load->view('prospection_create_view',$data);
                    $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data); 
                    
                    return;
                }
            }
            
            if($id == 'X')
            {
                $this->Prospection_model->insert($file_upload_path_docs,$final_file_name_docs, $file_upload_path_photos,$final_file_name_photo);
            }
            else
            {
                if (!empty($final_file_name_docs))
                {
                     $document_path = $file_upload_path_docs . $final_file_name_docs;
                }
                elseif($this->input->post('document_path') != NULL)
                {
                    $document_path = $this->input->post('document_path');
                }
                else
                {
                    $document_path = '';
                }
                
                if (!empty($final_file_name_photo))
                {
                     $photo_path = $file_upload_path_photos . $final_file_name_photo;
                }
                elseif($this->input->post('photo_path') != NULL)
                {
                    $photo_path = $this->input->post('photo_path');
                }
                else
                {
                    $photo_path = '';
                }
               
                
                $this->Prospection_model->update($id, $document_path, $photo_path);
            }
            
            

			redirect('prospection/show/' . $this->input->post('venture_prospection_type_id') . '/success', 'location'); 
		}
       
        
   }
   
    /**
    * Delete Action
    *
    * Deletes a prospection register
    *
    */
   function delete($venture_prospection_type_id, $id){
		
		$this->Common_model->delete($id,'venture_prospection');
		
		redirect('prospection/show/' . $venture_prospection_type_id . '/success', 'location');
	}
    
    /**
    * Delete Action
    *
    * Deletes a prospection document register
    *
    */
    function delete_document($id){
        
		$this->Common_model->delete($id,'venture_prospection_document');
		
		redirect('prospection/show/2/success', 'location'); 
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

