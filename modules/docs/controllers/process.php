<?php

include_once APPPATH . 'modules/docs/controllers/docs_controller.php';

/**
 * Controller class for process document management
 *
 *
 *
 * @copyright  2012 ARQABS
 * @version $Id$
 */
class Process extends Docs_controller {

    public function __construct() {
        parent::__construct();
        //Initialize company_id session
        $this->load->model('Process_document_model', '', TRUE);
    }

    /**
     * Index action
     *
     * Redirects to show action
     *
     */
    public function index() {
        redirect('process/show', 'location');
    }

    /**
     * Show Action
     *
     * List all the key process documents within their maturity levels
     *
     */
    public function show() {

        $data['query'] = $this->Process_document_model->get_all_documents();
        $data['error'] = array();

        $header_data['active'] = 'process_document';

        $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
        $this->load->view('process_document_show_view', $data);
        $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);
    }

    /**
     * History Action
     *
     * List all the files uploaded for a given key process
     *
     * @param  integer $key_process_id key process id selected
     * 
     */
    public function history($key_process_id) {
        $data['query'] = $this->Process_document_model->get_all_documents();
        $data['error'] = array();

        $data['history_documents'] = $this->Process_document_model->get_audit_data_by_key_process_id($key_process_id);


        $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
        $this->load->view('process_document_show_view', $data);
        $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);
    }

    /**
     * Insert Action
     *
     * Insert a new process document
     *
     */
    public function insert() {

        $file_upload_path = './uploads/docs/process_document/';
        $file_directory = $file_upload_path;

        $name = $_FILES['userfile']['name']; // get file name from form
        $fileNameParts = explode(".", $name); // explode file name to two part
        $fileExtension = end($fileNameParts); // give extension
        $fileExtension = strtolower($fileExtension); // convert to lower case
        $final_file_name = $this->logged_company->id . "_process-" . $this->input->post('key_process_id') . "_" . $this->input->post('version') . "_" . date('Ymd_H_i_s') . "." . $fileExtension;  // new file name
//$file_directory = realpath('.') . '\\uploads\\docs\\' . $this->logged_company->id . '\\process_document\\';

        if (!is_dir($file_directory)) {
            mkdir($file_directory, 0777, TRUE);
        }


        $config['upload_path'] = $file_upload_path;
        $config['allowed_types'] = 'doc|docx|xls|xlsx|pdf|ppt|pps|pptx|zip|rar|7z';
        $config['max_size'] = '12000';
        $config['file_name'] = $final_file_name; //set file name

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            $data['error'] = array('error' => $this->upload->display_errors());

            $data['query'] = $this->Process_document_model->get_all_documents();

            $header_data['active'] = 'process_document';
            $this->data['header_data'] = $header_data['active'];

            $this->load->view('base/sidebar_content_layout/sidebar_content_header_view', $this->data);
            $this->load->view('process_document_show_view', $data);
            $this->load->view('base/sidebar_content_layout/sidebar_content_footer_view', $this->data);
        } else {
            $this->Process_document_model->insert_document($file_upload_path, $final_file_name);

            redirect('process/show/success', 'location');
        }
    }

    /**
     * Update Action
     *
     * Update the information for a specific key process
     *
     * @param  integer $id selected key_process_id for update
     * 
     */
    public function update($id) {

        $this->Process_document_model->update_document($id);

        redirect('process/show/success', 'location');
    }

}

?>