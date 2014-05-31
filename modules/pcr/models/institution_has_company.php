<?php

Class Institution_has_company extends DataMapperExt {

    public $has_one = array('institution', 'company');
    var $table = 'institution_has_company';
    public $validation = array(
    );

    function __construct($id = NULL)
    {
        parent::__construct($id);
    }

    public function _saveExtra_incubate_company()
    {
        $c = new Company($this->CI->input->post('company'));
        $logged_institution = $this->CI->authentication->get_logged_company();


        $this->where_related($c);
        $this->where_related($logged_institution);
        $this->get();
        
        if ( !$this->exists() )
        {
            $this->status = STATUS_ACTIVE;
            if ( !isset($this->start_date) )
            {
                $this->start_date = date('Y-m-d');
            }
            $res = $this->save(array($c, $logged_institution));

            $this->_msg = my_lang('s_was_invited_to_be_incubated_at_s', array($c->name, $logged_institution->name));
        }
        else
        {
            $this->error_message('Company', lang('this_company_is_already_incubated'));
            $res = FALSE;
        }
        return $res;
    }

}