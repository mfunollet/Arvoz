<?php

include_once APPPATH . 'modules/docs/controllers/docs_controller.php';

/**
 * Controller class for entrepreneur qualification
 *
 *
 *
 * @copyright  2012 ARQABS
 * @version $Id$
 */
class Qualification extends Docs_controller {

    public function __construct() {
        parent::__construct();
        //Initialize company_id session
        //$this->output->enable_profiler(TRUE);
        $this->load->model('Entrepreneur_qualification_model', '', TRUE);
        $this->load->model('Common_model', '', TRUE);
        $this->load->helper('field');
    }

    /**
     * Index action
     *
     * Redirects to show action
     *
     */
    public function index() {
        $url = 'qualification/show';
        redirect($url, 'location');
    }

    /**
     * Show Action
     *
     * List all the entrepreneur qualification documents
     *
     */
    public function show() {
        $header_data['active'] = 'qualification';

        $data['query'] = $this->Entrepreneur_qualification_model->get_all_qualification();
        $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
        $this->load->view('qualification_show_view', $data);
        $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);
    }

    /**
     * Create Action
     *
     * Display view for insert
     *
     */
    public function create() {

        $data['venture_actions'] = $this->Common_model->fill_action_combo();
        $data['key_practices'] = $this->Entrepreneur_qualification_model->fill_key_practice_combo();
        $data['error'] = array();

        $header_data['active'] = 'qualification';

        $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
        $this->load->view('qualification_create_view', $data);
        $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);
    }

    /**
     * Edit Action
     *
     * Display view for edit
     * @param integer $id - id for update   
     */
    public function edit($id = NULL) {
        if ($id == NULL) {
            $id = $this->uri->segment(3);
        }

        $data['venture_actions'] = $this->Common_model->fill_action_combo();
        $data['key_practices'] = $this->Entrepreneur_qualification_model->fill_key_practice_combo();
        $data['error'] = array();

        $header_data['active'] = 'qualification';

        $data['qualification_object'] = $this->Entrepreneur_qualification_model->get($id);

        $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
        $this->load->view('qualification_create_view', $data);
        $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);
    }

    /**
     * Insert Action
     *
     * Insert a new qualification register
     *
     */
    public function insert() {

        $id = $this->input->post('id');

        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('description', 'Descrição', 'required');
        $this->form_validation->set_rules('owner', 'Responsável', 'required');
        $this->form_validation->set_rules('place', 'Local', 'required');
        $this->form_validation->set_rules('action_date', 'Data', 'callback_action_date_check');
        $this->form_validation->set_rules('attendees_number', 'Número de participantes', 'required|integer');
        $this->form_validation->set_rules('workload', 'Carga horária', 'required|integer');
        $this->form_validation->set_rules('content_text', 'Conteúdo sumarizado', 'required');

        if ($this->form_validation->run() == FALSE) {
            // Validation failed
            if ($id != "X") {
                return $this->edit($id);
            } else {
                return $this->create();
            }
        } else {

            $this->load->library('upload');

            if (!empty($_FILES['userfile']['name'])) {

                $file_upload_path_docs = './uploads/docs/entrepreneur_qualification/docs/';

                $file_directory = realpath('.') . '\\uploads\\docs\\entrepreneur_qualification\\docs\\';

                if (!is_dir($file_directory)) {
                    mkdir($file_directory, 0777, TRUE);
                }

                $name = $_FILES['userfile']['name']; // get file name from form
                $fileNameParts = explode(".", $name); // explode file name to two part
                $fileExtension = end($fileNameParts); // give extension
                $fileExtension = strtolower($fileExtension); // convert to lower case
                $final_file_name_docs = $this->logged_company->id . "_entrepreneur_qualification_" . date('Ymd_H_i_s') . "." . $fileExtension;  // new file name

                $config['upload_path'] = $file_upload_path_docs;
                $config['allowed_types'] = 'doc|docx|xls|xlsx|pdf|ppt|pps|pptx|zip|rar|7z';
                $config['max_size'] = '12000';
                $config['file_name'] = $final_file_name_docs; //set file name
                // Initialize config for File 1
                $this->upload->initialize($config);

                // Upload file 1
                if ($this->upload->do_upload('userfile')) {
                    $data = $this->upload->data();
                } else {
                    $data['error'] = array('error' => $this->upload->display_errors());

                    $data['venture_actions'] = $this->Common_model->fill_action_combo();
                    $data['key_practices'] = $this->Entrepreneur_qualification_model->fill_key_practice_combo();

                    $header_data['active'] = 'qualification';

                    $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
                    $this->load->view('qualification_create_view', $data);
                    $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);
                    return;
                }
            }

            if (!empty($_FILES['userfile1']['name'])) {

                $file_upload_path_photos = './uploads/docs/entrepreneur_qualification/photos/';


                $file_directory1 = realpath('.') . '\\uploads\\docs\\entrepreneur_qualification\\photos\\';


                if (!is_dir($file_directory1)) {
                    mkdir($file_directory1, 0777, TRUE);
                }

                $name = $_FILES['userfile1']['name']; // get file name from form
                $fileNameParts = explode(".", $name); // explode file name to two part
                $fileExtension = end($fileNameParts); // give extension
                $fileExtension = strtolower($fileExtension); // convert to lower case
                $final_file_name_photo = $this->logged_company->id . "_entrepreneur_qualification_" . date('Ymd_H_i_s') . "." . $fileExtension;  // new file name

                $config['upload_path'] = $file_upload_path_photos;
                $config['allowed_types'] = 'jpg|jpeg|gif|png|bmp';
                $config['max_size'] = '12000';
                $config['file_name'] = $final_file_name_photo; //set file name
                // Initialize config for File 1
                $this->upload->initialize($config);

                // Upload file 1
                if ($this->upload->do_upload('userfile1')) {
                    $data = $this->upload->data();
                } else {
                    $data['error'] = array('error' => $this->upload->display_errors());

                    $data['venture_actions'] = $this->Common_model->fill_action_combo();
                    $data['key_practices'] = $this->Entrepreneur_qualification_model->fill_key_practice_combo();

                    $header_data['active'] = 'qualification';

                    $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
                    $this->load->view('qualification_create_view', $data);
                    $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);

                    return;
                }
            }

            if ($id == 'X') {
                $this->Entrepreneur_qualification_model->insert($file_upload_path_docs, $final_file_name_docs, $file_upload_path_photos, $final_file_name_photo);
            } else {
                if (!empty($final_file_name_docs)) {
                    $document_path = $file_upload_path_docs . $final_file_name_docs;
                } elseif ($this->input->post('document_path') != NULL) {
                    $document_path = $this->input->post('document_path');
                } else {
                    $document_path = '';
                }

                if (!empty($final_file_name_photo)) {
                    $photo_path = $file_upload_path_photos . $final_file_name_photo;
                } elseif ($this->input->post('photo_path') != NULL) {
                    $photo_path = $this->input->post('photo_path');
                } else {
                    $photo_path = '';
                }


                $this->Entrepreneur_qualification_model->update($id, $document_path, $photo_path);
            }

            redirect('qualification/show/success', 'location');
        }
    }

    /**
     * Delete Action
     *
     * Deletes a qualification register
     *
     */
    function delete($id) {

        $this->Common_model->delete($id, 'entrepreneur_qualification');

        redirect('qualification/show/success', 'location');
    }

    /**
     * Acion date check
     *
     * Method to check if the action date is valid
     *
     */
    public function action_date_check($str) {

        if (empty($str)) {
            $this->form_validation->set_message('action_date_check', 'O campo %s é obrigatório.');
            return FALSE;
        }

        if (!strstr($str, "/")) {
            $this->form_validation->set_message('action_date_check', 'Data inválida');
            return FALSE;
        }
        $data = explode("/", "$str");
        $d = $data[0];
        $m = $data[1];
        $y = $data[2];

        // check if the date is valid
        // 1 = true 
        // 0 = false 
        $res = checkdate($m, $d, $y);
        if ($res == 1) {
            return TRUE;
        } else {
            $this->form_validation->set_message('action_date_check', 'Data inválida');
            return FALSE;
        }
    }

}

?>