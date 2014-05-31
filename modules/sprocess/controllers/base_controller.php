<?php

/**
 * Base controller Class to extends in a controller class
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */
if ( !defined('BASEPATH') )
    exit('No direct script access allowed');

class Base_Controller extends CI_Controller {

    /**
     * contains the name of the controller in lowercase
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
     * Contains the list of left sidebar items
     * @access protected
     * @var array
     */
    protected $l_sidebar = array();

    /**
     * Contains the list of right sidebar items
     * @access protected
     * @var array
     */
    protected $r_sidebar = array();

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
    function __construct()
    {
        parent::__construct();

        $this->output->enable_profiler(FALSE);
        //Set the default timezone configured in the codeigniter
        date_default_timezone_set($this->config->item('default_timezone'));

        //set default for ctrlr_name and class_name
        $class_name = get_class($this);
        $this->ctrlr_name = strtolower($class_name);
        $this->class_name = $class_name;

        //Set default for header and ctrl
        $this->data['header']->title = $class_name;
        $this->data['ctrlr'] = $this->ctrlr_name;

        //Initializate the variable $jsfile and $css_files for use in the views
        $this->data['js_files'] = array();
        $this->data['css_files'] = array();

        //Load the default javascript files
        $this->data['js_files'][] = 'jquery/jquery.validate.js';
        $this->data['js_files'][] = 'grid/adapt.min.js';
        $this->data['js_files'][] = 'grid/jquery.formalize.min.js';
        $this->data['js_files'][] = 'messages/message.js';
        $this->data['js_files'][] = 'confirm/confirm.js';

        //Load the default css files
        $this->data['css_files'][] = 'grid/reset.css';
        $this->data['css_files'][] = 'grid/text.css';
        $this->data['css_files'][] = 'grid/960.css';
        $this->data['css_files'][] = 'grid/formalize.css';

        //Load the language file
        $this->_load_language_file();
        
        //Load the define constants
        $this->config->load('base/types_relations');
        $this->rel = $this->config->item('rel');
    }

    /**
     * Fucntion to add items in the l_sidebar
     *
     * @param  string $action Name of the action for this menu
     * @param  string $label Label of the menu item
     */
    function _add_to_l_sidebar($action, $label)
    {
        $this->l_sidebar[$action] = $label;
    }

    /**
     * Fucntion to get the submenu
     *
     * @return array
     */
    function _l_sidebar()
    {
        return $this->l_sidebar;
    }

    /**
     * Fucntion to add items in the r_sidebar
     *
     * @param  string $action Name of the action for this menu
     * @param  string $label Label of the menu item
     */
    function _add_to_r_sidebar($action, $label)
    {
        $this->r_sidebar[$action] = $label;
    }

    /**
     * Fucntion to get the submenu
     *
     * @return array
     */
    function _r_sidebar()
    {
        return $this->r_sidebar;
    }

    /**
     * Function to show the view
     *
     * if you need load the default view for any action (for example change 
     * picture) put in the $sub_ctrl the name of the pattern view to load.
     *
     * @param  string $view name of the view
     * @param  string $sub_ctrl name of the default view to execute the pattern 
     * action
     */
    function _view($view, $sub_ctrl = NULL, $controller = NULL)
    {
        $this->data['ctrlr_name'] = $this->ctrlr_name;
        if ( isset($controller) )
        {
            $view = $this->load->view($controller . '/' . $view . '_view', $this->data, TRUE);
        }
        else
        {
            $view = $this->load->view($this->ctrlr_name . '/' . $view . '_view', $this->data, TRUE);
        }
        if ( !$view && $sub_ctrl )
        {
            $sub_class = strtolower(get_class($sub_ctrl));
            if ( isset($controller) )
            {
                $view = $this->load->view($controller . '/' . $view . '_view', $this->data, TRUE);
            }
            else
            {
                $view = $this->load->view($this->ctrlr_name . '/' . $view . '_view', $this->data, TRUE);
            }
        }
        echo $view;
    }

    /**
     * Function to load the laguage file
     *
     */
    function _load_language_file()
    {
        $this->load->helper('language');
        $user_lang = $this->session->userdata('user_lang');
        if ( !$user_lang )
        {
            $user_lang = 'pt-br';
            $this->session->set_userdata('user_lang', $user_lang);
        }
        $this->lang->load($user_lang, $user_lang);
    }

    /**
     * Set msg_ok for messages of confirmation to user
     *
     * @param  string $msg the message string
     */
    function msg_ok($msg)
    {
        $this->_set_message('message-success', $msg);
    }

    /**
     * Set msg_info for messages of information to user
     *
     * @param  string $msg the message string
     */
    function msg_info($msg)
    {
        $this->_set_message('message', $msg);
    }

    /**
     * Set msg_error for messages of error to user
     *
     * @param  string $msg the message string
     */
    function msg_error($msg)
    {
        $this->_set_message('message-error', $msg);
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
    function _set_message($type, $txt)
    {
        if ( isAjax() )
        {
            $return_arr['msg'] = '<p id="message" class="' . $type . '">' . $txt . '</p>';
            $return_arr['status'] = $type;

            echo json_encode($return_arr);
        }
        else
            $this->session->set_flashdata('message', '<p id="message" class="' . $type . '">' . $txt . '</p>');
    }

}
