<?php

/**
 * Base controller Class to extends in a controller class
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Base_Controller extends CI_Controller {

    /**
     * contains the name of the controller in lowercase on plural
     * @access public
     * @var string
     */
    public $ctrlr_name;

    /**
     * contains the name of the controller in uppercase
     * @access public
     * @var string
     */
    public $class_name;

    /**
     * Contains any data to pass to view
     * @access protected
     * @var array
     */
    protected $data;

    /**
     * contains the DMZ object from the logged user
     * @access protected
     * @var Person
     */
    protected $logged_user;

    /**
     * contains the DMZ object from the logged company
     * @access protected
     * @var Person
     */
    protected $logged_company;

    /**
     * contains the default layout to be used
     * @access public
     * @var string
     */
    public $layout = 'base/sidebar_content_layout';

    /**
     * contains the DMZ object from the all Company_has_person each the logged user may use as
     * @access protected
     * @var Company_has_person
     */
    protected $show_use_as_company = NULL;
    protected $show_use_as_institution = NULL;
    protected $menu = array();
    protected $json_output = array();

    /**
     * The construct of Base_controller
     *
     * Definitions of what is done in this constructor:
     * Set the default timezone configured in the codeigniter
     *
     * Initializate the variable $jsfile and $css_files for use in the views
     *
     * Load the language file
     *
     */
    function __construct() {
        parent::__construct();
        $this->data['data'] = array();

        $this->logged_user = $this->authentication->get_logged_user();
        $this->data['data']['logged_user'] = $this->logged_user;

        $this->logged_company = $this->authentication->get_logged_company();
        $this->data['data']['logged_company'] = $this->logged_company;

        $this->logged_p = $this->authentication->get_logged_p();
        $this->data['data']['logged_p'] = $this->logged_p;

        //Set the default timezone configured in the codeigniter
        date_default_timezone_set($this->config->item('default_timezone'));

        //set default for ctrlr_name and class_name
        $class_name = get_class($this);
        $this->ctrlr_name = strtolower($class_name);
        $this->class_name = $class_name;


        //Set default for header and ctrl
        $this->_set_title($class_name);
        $this->data['ctrlr'] = $this->ctrlr_name;

        //Initializate the variable $jsfile and $css_files for use in the views
        $this->data['js_files'] = array();
        $this->data['css_files'] = array();

        //Load the default css files
        //$this->data['css_files'][] = 'jquery-ui/jquery-ui-1.8.18.custom.css';
        $this->data['css_files'][] = 'bootstrap/css/bootstrap.css';
        $this->data['css_files'][] = 'bootstrap/css/bootstrap-responsive.css';
        $this->data['css_files'][] = 'dialog2/css/jquery.dialog2.css';

        //Load the default javascript files
        $this->data['js_files'][] = 'bootstrap/js/bootstrap.js';
        $this->data['js_files'][] = 'ajax-form/jquery.form.js';
        $this->data['js_files'][] = 'dialog2/js/jquery.dialog2.js';
        $this->data['js_files'][] = 'dialog2/js/jquery.dialog2.helpers.js';
        $this->data['js_files'][] = 'datetime/date_setup.js';
        $this->data['js_files'][] = 'autoload.js';

        $this->data['js_files'][] = 'jquery/jquery.validate.js';
        $this->data['js_files'][] = 'confirm/confirm.js';
        $this->data['js_files'][] = 'messages/message.js';
        $this->data['js_files'][] = 'mask/jquery.maskedinput-1.3.min.js';

        //Load the language file
        $this->_load_language_file();

        //Load the use as companies
        $this->_load_use_as_companies();

        //Load the use as institutions
        $this->_load_use_as_institutions();

        //Verifica se a função que chamou esta função é 'show', se não for chama a view com o nome dela
        //$this->router = & load_class('Router');
        $class = $this->router->fetch_class();
        $action = $this->router->fetch_method();
        $this->action = $action;

        if (!isAjax()) {
            //$this->output->enable_profiler(true);
        } else {
            $this->output->set_content_type('application/json');
        }

        if($this->config->item('enable_hooks')){
            $log = 'Uri='.$this->uri->uri_string().'  ';
            $log .= 'rUri='.$this->uri->ruri_string().'  ';
            $log .= 'Controller='.$this->ctrlr_name.'  ';
            $log .= 'Class='.$this->class_name.'  ';
            $log .= 'Action='.$this->action.'  ';
            log_message('info', $log);
        }

    }

    function _load_use_as_companies() {
        if ($this->logged_user) {
            $show_use_as_company = new Company_has_person();

            $r = new Role(COMPANY_ADMIN_ID);
            $show_use_as_company->where_related($this->logged_user);
            $show_use_as_company->where_related($r);
            $show_use_as_company->where('status', STATUS_ACTIVE);
            $show_use_as_company->where('end_date IS NULL');
            $show_use_as_company->get();

            $this->data['data']['show_use_as_company'] = $show_use_as_company;
            $this->show_use_as_company = $this->data['data']['show_use_as_company'];
        }
    }

    function _set_title($title, $screen_title = NULL) {
        //$this->data['header']->title = $title;
        //$this->data['header']->screen_title = $screen_title;
    }

    function _load_use_as_institutions() {
        if ($this->logged_user) {
            $show_use_as_institution = new Institution_has_person();

            $r = new Role(COMPANY_ADMIN_ID);
            $show_use_as_institution->where_related($this->logged_user);
            $show_use_as_institution->where_related($r);
            $show_use_as_institution->where('status', STATUS_ACTIVE);
            $show_use_as_institution->where('end_date IS NULL');
            $show_use_as_institution->get();


            $this->data['data']['show_use_as_institution'] = $show_use_as_institution;
            $this->show_use_as_institution = $this->data['data']['show_use_as_institution'];
        }
    }

    function index() {
        $this->page();
    }

    function page() {
        if (!empty($this->data['content'])) {
            echo 'Use $this->view em vez de $this->data[\'content\'] em ' . $this->data['content'];
        }

        // Possibilita usar $this->view para setar a view manualmente
        if (empty($this->view)) {
            // Padrão controller_action_view.php
            $view = ($this->config->item('view_style')) ? $this->ctrlr_name . '/' . $this->ctrlr_name . '_' . $this->action . '_view' : $view;
        } else {
            $view = $this->view;
        }
        $this->data['content'] = $view;

        // Possibilita usar $this->layout para setar a layout manualmente
        $this->layout = (empty($this->layout)) ? 'base/content_layout' : $this->layout;

        if (isset($this->element)) {
            $this->data['data']['element'] = $this->element;
        }
        $this->data['layout'] = $this->layout;

        $this->data['data']['pagination'] = (!empty($this->data['data']['pagination'])) ? $this->data['data']['pagination'] : '';

        if (isAjax() and !$this->force_noajax_view) {
            $output = $this->load->view($this->layout, $this->data, TRUE);
            $return_arr['content'] = $output;
            $this->_set_json_output($return_arr);
        } else {
            $this->load->view($this->layout, $this->data);
        }
    }

    function _output($output) {
        if (isAjax() and !isset($this->no_ajax))
            echo json_encode($this->json_output);
        else
            echo $output;
    }

    /**
     * Function to load the laguage file
     */
    function _load_language_file() {
        $this->load->helper('language');
        $user_lang = $this->session->userdata('user_lang');
        if (!$user_lang) {
            $user_lang = 'pt-br';
            $this->session->set_userdata('user_lang', $user_lang);
        }
        $this->lang->load($user_lang, $user_lang);
    }

    function _add_menu_item($local = 'menu', $title = '', $link = array(), $id = NULL) {
        $action = ($this->action == 'index') ? '' : '/' . $this->action;
        if (is_array($local)) {
            foreach ($local as $name) {
                $l['uri'] = isset($link[0]) ? $link[0] . (($id) ? '/' . $id : '') : NULL;
                $l['title'] = isset($link[1]) ? $link[1] : NULL;
                $l['attributes'] = isset($link[2]) ? $link[2] : NULL;
                $l['active'] = isset($link[0]) ? (($link[0] == $this->ctrlr_name . '/' . $action) ? TRUE : FALSE) : NULL;
                $this->menu[$name][$title][] = $l;
            }
        } else {
            $l['uri'] = isset($link[0]) ? $link[0] . (($id) ? '/' . $id : '') : NULL;
            $l['title'] = isset($link[1]) ? $link[1] : NULL;
            $l['attributes'] = isset($link[2]) ? $link[2] : NULL;
            $l['active'] = isset($link[0]) ? (($link[0] == $this->ctrlr_name . $action) ? TRUE : FALSE) : NULL;
            $this->menu[$local][$title][] = $l;
        }
    }

    function &_menu($name) {
        if (isset($this->menu[$name])) {
            return $this->menu[$name];
        }
        return NULL;
    }

    function _add_menu($widget, $local, $id = NULL) {
        $menu = &$this->_menu($local);
        if ($menu) {
            if ($id) {
                // Loop entre locais
                foreach ($menu as $k1 => $l) {
                    // Loob entre links
                    foreach ($l as $k2 => $link) {
                        $menu[$k1][$k2]['uri'] .= '/' . $id;
                    }
                }
            }
            $this->data[$widget . '_menu'] = $menu;
        }
    }

    /**
     * Set msg_ok for messages of confirmation to user
     *
     * @param  string $msg the message string
     */
    function msg_ok($msg) {
        $this->_set_message('success', $msg);
    }

    /**
     * Set msg_info for messages of information to user
     *
     * @param  string $msg the message string
     */
    function msg_info($msg) {
        $this->_set_message('warning', $msg);
    }

    /**
     * Set msg_error for messages of error to user
     *
     * @param  string $msg the message string
     */
    function msg_error($msg) {
        $this->_set_message('error', $msg);
    }

    /**
     * Set message to user see in the view
     *
     * if not is ajax requisition, user the session module for set_flashdata
     *
     * @param  string $type type of message
     * @param  string $txt message
     * @return string
     */
    function _set_message($type, $txt) {
        $msg = '<div class="alert fade in alert-' . $type . '"><a class="close" data-dismiss="alert" href="#">&times;</a>' . $txt . '</div>';
        if (isAjax()) {
            //$return_arr['msg'] = $msg;
            $return_arr['status'] = $type;

            $this->json_output['msg'][] = $msg;
            $this->_set_json_output($return_arr);
        } else {
            $this->session->set_flashdata('message', $msg);
            $this->data['message'] = $msg;
        }
    }

    function _set_json_output($data) {
        if (!is_array($data)) {
            $data = array('content' => $data);
        }
        $this->json_output = array_merge($this->json_output, $data);
    }

    function send_email($to, $from, $subject, $data, $template, $to_admin = TRUE) {
        if ($this->config->item('devel'))
            $this->load->library('email', array(
                'protocol' => 'smtp',
                'smtp_host' => '10.0.0.100',
                'mailtype' => 'html'
            ));
        else
            $this->load->library('email');

        $this->email->clear();

        $message = $this->load->view($template, $data, true);

        $config['mailtype'] = 'html';
        $this->email->initialize($config);

        if ($from == 'admin' || !is_array($from)) {
            $from['email'] = $this->config->item('admin_email');
            $from['email'] = $this->config->item('app_name');
        }

        if ($to_admin)
            $this->email->bcc($this->config->item('admin_email'));

        $this->email->set_newline("\r\n");
        $this->email->from($from['email'], $from['name']);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        // TODO: test if email not sent
        @$this->email->send();
    }

}
