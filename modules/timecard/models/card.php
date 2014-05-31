<?php

Class Card extends DataMapperExt {

    var $table = 'cards';
    public $validation = array(
        'start_date' => array(
            'rules' => array('trim', 'required')
        ),
        'end_date' => array(
            'rules' => array('trim', 'required')
        )
    );

    function __construct($id = NULL) {
        parent::__construct($id);
    }

}