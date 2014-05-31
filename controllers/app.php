<?php

include_once(APPPATH . 'modules/pcr/controllers/codebase.php');

class App extends Codebase {

    function __construct()
    {
        parent::__construct();
        if ( !$this->authentication->is_signed_in() )
        {
            $this->_add_menu_item('actions', '', array('app/register_institution', lang('register_institution')));
            $this->_add_menu('top_right', 'actions');
        }
        $this->_add_menu_item('nav', '', array('institutions', lang('institutions')));
        $this->_add_menu('top_left', 'nav');
        
        //debug($this->logged_p); exit();
    }

    function index()
    {
        if ( $this->authentication->is_signed_in() )
        {
            redirect($this->session->userdata('user_type') . '/dashboard');
        }
        redirect('people/login');
    }

    function register_institution()
    {
        $p = new Person();
        $p->filtered_fields = array('first_name', 'last_name', 'email1', 'email1_conf', 'password', 'password_conf', 'gender', 'birthday');

        $i = new Institution();
        $i->filtered_fields = array('name', 'cnpj', 'username', 'email1', 'email1_conf', 'phone1', 'foundation');

        if ( $_POST )
        {
            if ( !class_exists('DMZ_Json') )
            {
                $p->load_extension('json');
            }
            if ( !$this->session->userdata('p') )
            {
                $p->fillObject();
                $p->validate();
                if ( $p->valid )
                {
                    $this->session->set_userdata('p', $p->to_json($p->filtered_fields));
                    redirect($this->ctrlr_name . '/register_institution');
                }
            }
            else
            {
                if ( $p->from_json($this->session->userdata('p')) )
                {
                    $i->trans_begin();

                    // Salva Person
                    $p->status = STATUS_PENDING;
                    $p->skip_validation()->save();

                    $i->fillObject();

                    // Salva company e owner
                    $i->status = STATUS_PENDING;
                    $res = $i->save_owner($p);

                    // Salva associação e a Person como Administrator
                    $ihp = new Institution_has_person();
                    $r = new Role(COMPANY_ADMIN_ID);

                    $ihp->status = STATUS_PENDING;
                    $res = $res && $ihp->save(array($i, $p, $r));

                    if ( !$res || $i->trans_status() === FALSE )
                    {
                        $i->trans_rollback();
                        $this->msg_error(lang('save_error'));
                        redirect($this->ctrlr_name . '/register_institution');
                        //$i->error_message('transaction', 'The transaction failed to save (insert)');
                    }
                    else
                    {
                        $i->trans_commit();
                        $this->session->sess_destroy();
                        $this->msg_ok(lang('create_success'));
                        $this->send_email(
                                $this->config->item('admin_email'), $this->config->item('admin_email'), lang('new_institution_register'), array('p' => $p, 'i' => $i), 'arvoz/emails/new_institution_email'
                        );
                        redirect();
                    }
                }
                else
                {
                    $this->session->sess_destroy();
                    $this->msg_error(lang('create_error'));
                    redirect($this->ctrlr_name . '/register_institution');
                }
            }
        }
        if ( !$this->session->userdata('p') )
        {
            $this->element = $p;
        }
        else
        {
            $this->element = $i;
        }

        $this->data['data']['fields'] = $this->element->get_clean_fields();
        $this->data['data']['element'] = $this->element;
        parent::page();
    }

    function access_denied()
    {
        $this->msg_error(lang('you_have_to_be_logged_in'));
        redirect('people/login');
    }
}
