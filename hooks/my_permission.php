<?php

/**
 * File to implements the second level authentication in the system by action
 * control, based in an array action using the type [role][controller][action]
 *
 * @copyright  2011 ARQABS
 * @version    $Id$
 */

/**
 * This class has responsible about the second level atuthetication
 *
 * @copyright  2011 ARQABS
 */
class MY_permission extends CI_Hooks {

    var $CI;

    function __construct()
    {
        $this->CI = &get_instance();
    }

    /**
     * The check_permission function is executed every time an action is
     * requested.
     *
     * @param  array  $params  any parameters configured in the hooks
     * @return boolean
     */
    public function check_permission($params)
    {

        //array of permissions [role][controller][action]
        //require_once('permissions.php');
        //grab a reference to the controller
        //Load models to work in this hook
        $this->CI->load->model('pcr/Role_model', 'Role_model');
        $this->CI->load->model('pcr/Role_has_resource_model', 'Role_has_resource_model');
        $this->CI->load->model('pcr/P_and_p_model', 'P_and_p_model');

        //load session
        $this->CI->load->library('session');
        //load authentication
        $this->CI->load->library('pcr/authentication');

        ///load language
        $this->CI->load->helper('language');
        $user_lang = $this->CI->session->userdata('user_lang');
        if ( !$user_lang )
        {
            $user_lang = 'pt-br';
            $this->CI->session->set_userdata('user_lang', $user_lang);
        }
        $this->CI->lang->load($user_lang, $user_lang);

        //getting the class(controller) and the method
        $baseURL = $GLOBALS['CFG']->config['base_url'];
        $routing = & load_class('Router');
        $class = $routing->fetch_class();
        $method = $routing->fetch_method();

        //urls that do not need to login and has permission
        if ( ($class == 'auth' && $method == 'login') ||
                ($class == 'auth' && $method == 'logout') ||
                ($class == 'access_error' && $method == 'access_denied') ||
                ($class == 'person' && $method == 'create') ||
                ($class == 'person' && $method == 'create_success') )
            return TRUE;

        //authetication
        if ( $this->CI->authentication->is_signed_in() === FALSE )
        {
            //to access previous url before login
            $this->CI->session->set_userdata('previous_url', uri_string());
            $formatted_msg = '<p id="message" class="message-error">' . lang('you_need_logon_for_access_this_area') . '</p>';
            $this->CI->session->set_flashdata('message', $formatted_msg);
            redirect('auth/login', 'refresh');
        }

        $permissions = $this->format_permission_array($this->CI->Role_has_resource_model->get_all());
        $permission_list = array();

        $roles = $this->CI->Role_model->get_all();
        foreach ( $roles as $role )
        {
            if ( isset($permissions[$role->id][$class][$method]) )
            {
                if ( ($permissions[$role->id][$class][$method]) == TRUE )
                {
                    $permission_list[] = $role->id;
                }
            }
        }

        $person_permissions = $this->format_roles_permission_array($this->CI->P_and_p_model->get_where(array('p_id' => $this->CI->session->userdata('p_id'))));

        if ( in_array('1', $person_permissions) )
            return TRUE;

        foreach ( $permission_list as $line )
        {
            if ( in_array($line, $person_permissions) )
                return TRUE;
        }

        redirect('/access_error/access_denied/', 'refresh');
    }

    function format_permission_array($data)
    {
        $new_array = array();
        foreach ( $data as $line )
        {
            $new_array[$line->role_id][$line->controller][$line->method] = $line->permission;
        }
        return $new_array;
    }

    function format_roles_permission_array($data)
    {
        $new_array = array();
        foreach ( $data as $line )
        {
            $new_array[] = $line->role_id;
        }
        $new_array = array_unique($new_array);
        return $new_array;
    }

    function check()
    {

        //getting the class(controller) and the method
        $baseURL = $GLOBALS['CFG']->config['base_url'];
        $routing = & load_class('Router');
        $class = $routing->fetch_class();
        $method = $routing->fetch_method();
        $url = $_SERVER["REQUEST_URI"];

        $url = explode("/", $url);

        $institution_only = array(
            'process',
            'process/index'
        );

        $allowed = array(
            'people/index',
            'people/show',
            'people/companies',
            'people/view',
            'people/register',
            'people/login',
            'companies/index',
            'companies/show',
            'companies/view',
            'institutions/index',
            'institutions/show',
            'institutions/view',
            'app/register_institution',
            'app/access_denied',
            'cards',
            'cards/index',
            'cards/create',
            'cards/edit',
            'cards/show',
            'cards/view'
        );

//        debug($this->CI->uri->rsegment(1) . '/' . $this->CI->uri->rsegment(2));
//
//        debug(get_class($this->CI->authentication->get_logged_company()));
//        die();



        if ( (count($url) < 4 || empty($url[3])) && $url[2] != 'process' )
        {
            return TRUE;
        }


        if ( $this->CI->uri->rsegment(1) == 'process' )
        {
            if ( $this->CI->authentication->is_incorporated() &&
                    get_class($this->CI->authentication->get_logged_company()) == 'Institution' )
            {
                return TRUE;
            }
            else
            {
                redirect('app/access_denied');
            }
        }

        if ( $this->CI->authentication->is_signed_in() )
        {
            return TRUE;
        }
        elseif ( in_array($this->CI->uri->rsegment(1) . '/' . $this->CI->uri->rsegment(2), $allowed) )
        {
            return TRUE;
        }

        redirect('app/access_denied');
    }

}
