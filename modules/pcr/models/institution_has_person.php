<?php

Class Institution_has_person extends Company_has_person {

    public $has_one = array('role', 'institution', 'person');
    var $table = 'institution_has_person';
    public $validation = array(
        'person' => array(
            'type' => 'autocomplete'
        )
    );

    function __construct($id = NULL) {
        parent::__construct($id);
    }

    //FIX: Fazer validação da inserção de role_id
}