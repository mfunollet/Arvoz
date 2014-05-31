<?php

Class Company_type extends DataMapperExt {

    public $has_many = array('company');
    var $table = 'company_type';

    function __construct($id = NULL) {
        parent::__construct($id);
    }

    public function __toString() {
        return $this->name;
    }

    function get_htmlform_list($object, $field) {
        $this->get();
    }

}