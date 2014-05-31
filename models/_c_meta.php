<?php

Class C_meta extends DataMapper {

    var $table = 'c_meta';
    
    public $has_one = array('institution' => array(			// in the code, we will refer to this relation by using the object name 'book'
            'class' => 'institution',			// This relationship is with the model class 'book'
            'other_field' => 'company',		// foreign key in the (relationship)table identifying this models table. The column name would be 'author_id'
            'join_other_as' => 'company')	// name of the join table that will link both Author and Book together
    );
    
   
    /*
    public $has_many = array(
        // bugs created by this user
        'created_bug' => array(
            'class' => 'bug',
            'other_field' => 'creator'
        ),
        // bugs edited by this user
        'edited_bug' => array(
            'class' => 'bug',
            'other_field' => 'editor'
        ),
        // bugs assigned to this user
        'bug'
    );
    */
    public $validation = array();

    function __construct($id = NULL) {
        parent::__construct($id);
    }


}