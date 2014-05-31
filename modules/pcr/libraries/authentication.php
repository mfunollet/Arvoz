<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Authentication {

    var $CI;

    /**
     * Constructor
     */
    function __construct() {
        // Obtain a reference to the ci super object
        $this->CI = & get_instance();
    }

    // --------------------------------------------------------------------

    /**
     * Check user signin status
     *
     * @access public
     * @return bool
     */
    function is_signed_in() {
        return $this->CI->session->userdata('person_id') ? TRUE : FALSE;
    }

    function is_admin() {
        if (!$this->is_signed_in()) {
            return FALSE;
        }
        return $this->get_logged_user()->is_superadmin;
    }

    function is_incorporated() {
        return ($this->CI->session->userdata('company_id') != NULL) ||
                ($this->CI->session->userdata('institution_id') != NULL);
    }

    function get_logged_p() {
        if ($this->is_incorporated())
            return $this->get_logged_company();
        elseif ($this->is_signed_in())
            return $this->get_logged_user();
        return NULL;
    }

    function get_logged_user() {
        if (!$this->is_signed_in()) {
            return NULL;
        } else {
            $p = new Person();

            $p->where('id', $this->CI->session->userdata('person_id'))->get();

            return $p;
        }
    }

    function get_logged_company() {

        if (!$this->is_signed_in()) {
            return NULL;
        } else {

            if ($this->CI->session->userdata('company_id') ||
                    $this->CI->session->userdata('institution_id')) {
                $c = new Company();

                $c->where('id', $this->CI->session->userdata('company_id'))->get();

                if ($c->exists()) {
                    return $c;
                } else {
                    $c = new Institution();
                    $c->where('id', $this->CI->session->userdata('institution_id'))->get();

                    if ($c->exists()) {
                        return $c;
                    }
                }
            }
            return NULL;
        }
    }

    function login($user) {
        if ($user->login()) {

            $this->CI->session->set_userdata('user_type', 'people');
            return TRUE;
        }

        return FALSE;
    }

    function logout() {
        $this->CI->session->unset_userdata('person_id');
        $this->CI->session->unset_userdata('user_type');
        $this->CI->session->sess_destroy();
    }

    function use_as_company($company) {
        $user = $this->get_logged_user();
        $chp = new Company_has_person();
        $role = new Role(COMPANY_ADMIN_ID);

        $chp->where_related($role);
        $chp->where_related($user);
        $chp->where('end_date IS NULL');
        $chp->where_related($company);
        $chp->get_active();

        if ($chp->exists()) {
            if ($this->is_incorporated() && $this->CI->session->userdata('institution_id')) {
                $this->CI->session->unset_userdata('institution_id');
                $this->CI->session->unset_userdata('user_type');
            }
            $this->CI->session->set_userdata('company_id', $company->id);
            $this->CI->session->set_userdata('user_type', 'companies');
            return TRUE;
        }
        return FALSE;
    }

    function use_as_institution($institution) {
        $user = $this->get_logged_user();
        $ihp = new Institution_has_person();
        $role = new Role(COMPANY_ADMIN_ID);

        $ihp->where_related($role);
        $ihp->where_related($user);
        $ihp->where('end_date IS NULL');
        $ihp->where_related($institution);
        $ihp->get_active();

        if ($ihp->exists()) {
            if ($this->is_incorporated() && $this->CI->session->userdata('company_id')) {
                $this->CI->session->unset_userdata('company_id');
                $this->CI->session->unset_userdata('user_type');
            }
            $this->CI->session->set_userdata('institution_id', $institution->id);
            $this->CI->session->set_userdata('user_type', 'institutions');
            return TRUE;
        }
        return FALSE;
    }

    function company_logout() {
        $this->CI->session->unset_userdata('company_id');
        $this->CI->session->unset_userdata('institution_id');
        $this->CI->session->set_userdata('user_type', 'people');
        return TRUE;
    }

    function institution_logout() {
        $this->CI->session->unset_userdata('company_id');
        $this->CI->session->unset_userdata('institution_id');
        $this->CI->session->set_userdata('user_type', 'people');
        return TRUE;
    }

    public function send_forgotten_password_email($p) {
        $p->forgotten_password_code = sha1(microtime() . $p->email1);

        $p->save();

        $data = array(
            'email1' => $p->email1,
            'forgotten_password_code' => $p->forgotten_password_code
        );

        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => '10.0.0.100',
            'mailtype' => 'html'
        );

        $this->CI->load->library('email', $config);

        $message = $this->CI->load->view('email/forgotten_password', $data, true);
        $this->CI->email->clear();
        $this->CI->email->set_newline("\r\n");
        $this->CI->email->from($this->ci->config->item('admin_email'), $this->CI->config->item('site_title'));
        $this->CI->email->from('admin@codebase.com', 'Codebase Admin');


        $this->CI->email->to($p->email1);
        $this->CI->email->subject('Codebase' . ' - Forgotten Password Verification');
        $this->CI->email->message($message);

        if ($this->CI->email->send()) {
            echo $this->CI->email->print_debugger();
            return TRUE;
        } else {
            echo $this->CI->email->print_debugger();
            return FALSE;
        }
    }

}

/* End of file Authentication.php */
/* Location: ./application/modules/account/libraries/Authentication.php */