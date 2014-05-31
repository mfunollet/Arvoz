<?php

Class Company_has_person extends DataMapperExt {

    public $has_one = array('role', 'company', 'person');
    var $table = 'company_has_person';
    
    public $validation = array(
        'person' => array(
            'type' => 'autocomplete'
        )
    );

    function __construct($id = NULL) {
        parent::__construct($id);
    }

    public function _saveExtra_invite() {
        $r = new Role($this->CI->input->post('role'));
        $p = new Person($this->CI->input->post('person'));
        $logged_company = $this->CI->authentication->get_logged_company();

        $aux_model = get_class($this);
        $obj = new $aux_model();
        $obj->where_related($r);
        $obj->where_related($logged_company);
        $obj->where('end_date IS NULL');


        if ($this->id) {
            $obj->where('id !=', $this->id);
            $obj->where_related($this->person);
            $obj->get();
            if (!$obj->exists()) {
                $res = $this->save(array($r, $logged_company));
                //$this->_msg = my_lang('notification_created', array($p->first_name, $r->name, $logged_company->name));
            } else {
                $this->error_message('Role', my_lang('notification_already_in_this_role', array($this->person->first_name, $r->name, $logged_company->name)));
                $res = FALSE;
            }
        } else {

            $obj->where_related($p);
            $obj->get_pending();

            //FIX: Mensagens de sucesso e erro e redirect ficam aqui ou no CRUD_controller?
            if (!$obj->exists()) {
                // Salva notificação como não lida
                $this->status = STATUS_PENDING;
                if (!isset($this->start_date)) {
                    $this->start_date = date('Y-m-d');
                }
                $res = $this->save(array($r, $p, $logged_company));
                $this->_msg = my_lang('notification_created', array($p->first_name, $r->name, $logged_company->name));
            } else {
                $this->error_message('Role', my_lang('notification_already_in_this_role', array($p->first_name, $r->name, $logged_company->name)));
                $res = FALSE;
            }
        }
        return $res;
    }

    //FIX: Fazer validação da inserção de role_id
}