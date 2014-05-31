<?php

Class Person_login extends DataMapperExt {

    public $has_one = array('person');
    var $table = 'person_login';

    public function __construct($id = NULL) {
        parent::__construct($id);
    }

}