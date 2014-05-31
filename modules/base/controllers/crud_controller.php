<?php

/**
 * Crud_controller Class to extends in a Base_Controller class
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 * CRUD
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include_once(APPPATH . "controllers/app.php");

abstract class CRUD_Controller extends APP {

    var $element;
    var $redirect_url = NULL;

    function __construct() {

        parent::__construct();

        if (empty($this->element)) {
            $singular = ucfirst(singular($this->ctrlr_name));
            $this->element = new $singular();
        }
        if ($this->authentication->is_signed_in()) {
            $this->_add_menu_item('edit', lang('manage'), array($this->ctrlr_name . '/edit', lang('edit_profile')));
        }
        $this->_add_menu_item('view', '', array($this->ctrlr_name . '/view', lang('home')));
    }

    public function index() {
        redirect($this->ctrlr_name . '/show');
    }

    function create() {
        if (!$this->element->can_create()) {
            $this->msg_error(lang('no_permission'));
            redirect($this->ctrlr_name);
        }
        $this->_set_title(lang('create_' . singular($this->ctrlr_name)));
        $this->_form();
    }

    function edit($id = NULL) {
        if (empty($id)) {
            show_404();
        }
        $this->element->where('id', $id);
        $this->element->get();

        if (!$this->element->exists() || !$this->element->can_edit()) {
            $this->msg_error(lang('no_permission'));
            redirect($this->ctrlr_name);
        }
        $this->_set_title(lang('edit'));
        $this->_form($id);
    }

    function _form($id = NULL) {
        $this->data['data']['fields'] = $this->element->get_clean_fields();
        $this->view = (empty($this->view)) ? $this->ctrlr_name . '/' . $this->ctrlr_name . '_form_view' : $this->view;

        if ($_POST || $_FILES) {
            $this->element->fillObject();
            $this->element->trans_begin();

            // Se o metodo _saveExtra existir no controller que estende o CRUD, salva o objeto por ele, caso contrario salva normalmente
            if (method_exists($this->element, '_saveExtra_' . $this->action)) {
                $res = $this->element->{'_saveExtra_' . $this->action}($id);
            } elseif (method_exists($this->element, '_saveExtra')) {
                $res = $this->element->_saveExtra($id);
            } else {
                $res = $this->element->save();
            }
            if (!$res || $this->element->trans_status() === FALSE) {
                $this->element->trans_rollback();
                $errors = '';
                foreach ($this->element->error->all as $k => $err) 
                {
                    $errors .= $err;
                }
                $this->msg_error($errors);
            } else {
                $this->element->trans_commit();
                $this->msg_ok((empty($this->element->_msg) ? lang('save_success') : $this->element->_msg));
                if (!isAjax()) {
                    $redirect = ($this->redirect_url) ? $this->redirect_url : $this->ctrlr_name . '/' . $this->action . '/';
                    redirect($redirect);
                }
            }
        }
        parent::page();
    }

    function show($page = 1) {
        $this->element->get_paged($page, $this->config->item('max_num_items_on_page'));
        $this->layout = (empty($this->layout)) ? 'base/content_layout' : $this->layout;

        if (!$this->element->can_view()) {
            $this->msg_error(lang('no_permission'));
            redirect($this->ctrlr_name);
        }

        $this->load->library('pagination');

        $confpage['use_page_numbers'] = TRUE;
        $confpage['total_rows'] = $this->element->paged->total_rows;
        $confpage['per_page'] = $this->element->paged->page_size;
        $confpage['base_url'] = site_url($this->ctrlr_name . '/' . $this->action . ((isset($this->viewing_id)) ? '/' . $this->viewing_id : ''));
        $confpage['uri_segment'] = 4;

        $this->pagination->initialize($confpage);

        $this->data['data']['pagination'] = $this->pagination->create_links();
        parent::page();
    }

    function view($id = NULL) {
        $this->element->get_by_id($id);

        if (!$this->element->exists() || !$this->element->can_view()) {
            $this->msg_error(lang('no_permission'));
            redirect($this->ctrlr_name);
        }

        $this->_set_title($this->element->__toString());
        $this->_add_menu('left', 'view', $id);
        parent::page();
    }

    function delete($id = NULL) {
        if (!isAjax() || empty($id)) {
            show_404();
        }
        $this->element->where('id', $id);
        $this->element->get();

        if (!$this->element->exists() || !$this->element->can_delete()) {
            $this->msg_error(lang('no_permission'));
        }

        if ($this->element->delete()) {
            $this->set_message(lang('delete_success'));
        }
    }

}
