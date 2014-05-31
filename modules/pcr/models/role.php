<?php

Class Role extends DataMapperExt {

    public $has_many = array('company_has_person', 'institution_has_person',
        'resource' => array(// in the code, we will refer to this relation by using the object name 'book'
            'class' => 'resource', // This relationship is with the model class 'book'
            'other_field' => 'role', // in the Book model, this defines the array key used to identify this relationship
            'join_self_as' => 'role', // foreign key in the (relationship)table identifying this models table. The column name would be 'author_id'
            'join_other_as' => 'resource', // foreign key in the (relationship)table identifying the other models table as defined by 'class'. The column name would be 'book_id'
            'join_table' => 'role_has_resource'));
    var $table = 'role';

    function __construct($id = NULL) {
        parent::__construct($id);
    }

    public function __toString() {
        return $this->name;
    }

    function get_htmlform_list($object, $field) {
        // Remove owner Role da listagem se não for owner
        $logged_company = $this->CI->authentication->get_logged_company();
        $logged_user = $this->CI->authentication->get_logged_user();
        if ($logged_company->owner->id != $logged_user->id) {
            $this->where('id !=', COMPANY_ADMIN_ID);
        }
        $this->get();
    }

}