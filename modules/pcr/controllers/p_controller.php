<?php

include_once APPPATH . 'modules/base/controllers/crud_controller.php';

class P_Controller extends CRUD_Controller {

    function __construct() {
        parent::__construct();
        if ($this->authentication->is_signed_in()) {
            $this->_add_menu_item('dashboard', '', array($this->ctrlr_name . '/notifications', lang('notifications')));
            $this->_add_menu_item('dashboard', '', array($this->ctrlr_name . '/dashboard', lang('dashboard')));
            $this->_add_menu_item('dashboard', '', array($this->ctrlr_name . '/edit', lang('edit')));
            $this->_add_menu_item('edit', lang('manage'), array($this->ctrlr_name . '/edit_image', lang('edit_image')));
            $this->_add_menu_item('edit', lang('manage'), array($this->ctrlr_name . '/edit_sites', lang('edit_sites')));
        }
        $this->_add_menu_item('view', '', array($this->ctrlr_name . '/contact', lang('contact')));
    }

    function edit_image() {
        $this->_add_menu('left', 'edit');
        $this->_set_title(lang('edit_image'));

        $this->element->filtered_fields = array('profile_image');

        $this->data['sidebars'][] = 'p_controller/p_controller_image_view';
        $this->view = 'p_controller/p_controller_form_view';
        parent::edit($this->element->id);
    }

    function profile() {
        if ($this->logged_p) {
            redirect($this->session->userdata('user_type') . '/view/' . $this->logged_p->id);
        }
        redirect();
    }

    function respond_invite($type = NULL, $respond_id = NULL, $accepted = NULL) {
        if (empty($respond_id)) {
            show_404();
        }
        if ($this->element->respond_invite($type, $respond_id, $accepted)) {
            $this->msg_ok(lang('create_success'));
            redirect($this->ctrlr_name . '/notifications');
        }
    }

    function notifications() {
        $this->data['sidebars'][] = 'p_controller/p_controller_image_view';
        $this->_add_menu('left', 'dashboard');
        $this->_set_title(lang('notifications'));

        $this->view = 'p_controller/p_controller_notifications_view';

        $this->element->get_notifications();
        parent::page();
    }

    function dashboard() {
        $this->_add_menu('left', 'dashboard');
        $this->_set_title(lang('dashboard'));

        $this->data['sidebars'][] = 'p_controller/p_controller_image_view';
        $this->view = 'p_controller/p_controller_dashboard_view';
        parent::page();
    }

    function view($id = NULL) {
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        if (!$id) {
            $this->msg_error(lang('s_does_not_exist'));
            redirect($this->ctrlr_name);
        }
        $singular = ucfirst(singular($this->ctrlr_name));
        $this->element = new $singular();

        $this->element->where('id', $id);
        $this->element->get();

        if (!$this->element->exists()) {
            $this->msg_error(lang('s_does_not_exist'));
            redirect($this->ctrlr_name);
        }

        $this->data['sidebars'][0] = array(
            'view' => 'p_controller/p_controller_image_sites_view',
            'data' => array('element' => $this->element)
        );

        $this->view = 'p_controller/p_controller_view_view';

        parent::view($id);
    }

    function contact($id = NULL) {
        $id = $this->input->post('id') ? $this->input->post('id') : $id;
        if (!$id) {
            $this->msg_error(lang('s_does_not_exist'));
            redirect($this->ctrlr_name);
        }

        $this->element->get_by_id($id);

        if (!$this->element->exists()) {
            $this->msg_error(lang('s_does_not_exist'));
            redirect($this->ctrlr_name);
        }

        $this->data['sidebars'][0] = array(
            'view' => 'p_controller/p_controller_image_sites_view',
            'data' => array('element' => $this->element)
        );

        $this->_add_menu('left', 'view', $id);
        $this->_set_title(lang('contact'));
        $this->view = 'p_controller/p_controller_contact_view';
        $this->data['data']['id'] = $id;

        $msg = $this->input->post('message');

        if ($msg) {
            $from = $this->logged_p;
            $recipients = $this->element->get_recipients();
            $subject = my_lang('contact_via_s', array($this->config->item('app_name')));
            $template = 'base/emails/contact_email';
            $sender_type = $from instanceof Person ? 'people' : 'companies';

            $i = 0;
            foreach ($recipients as $to) {
                $this->send_email(
                        $to->email1, array(
                    'email' => $from->email1,
                    'name' => $from->get_name(),
                        ), $subject, array(
                    'msg' => $msg,
                    'to' => $to,
                    'from' => $from,
                    'sender_type' => $sender_type,
                        ), $template
                );
                $i++;
            }
            $this->msg_ok(my_lang('email_sent_to_s_contacts', array($i)));
        }

        parent::page();
    }

    function edit_sites() {
        $this->_add_menu('left', 'edit');
        $this->_set_title(lang('edit_sites'));
        $this->data['sidebars'][] = 'p_controller/p_controller_image_view';
        $this->view = 'p_controller/p_controller_edit_sites_view';
        parent::page();
    }

    function edit($id = NULL) {
        $this->data['sidebars'][] = 'p_controller/p_controller_image_view';
        parent::edit($id);
    }

    function autocomplete() {
        if (!isAjax()) {
            exit;
        }
        $qtext = $this->input->post('qtext');
        $this->element->like($this->element->get_autocomplete_field(), $qtext);
        $this->element->get();
        
        $array = array();
        foreach ($this->element as $k => $element) {
            $array[$k]['value'] = $element->id;
            $array[$k]['name'] = $element->get_name();
            $array[$k]['profile_image'] = base_url($element->get_image(50,50));
        }
        $this->_set_json_output($array);
    }

}
