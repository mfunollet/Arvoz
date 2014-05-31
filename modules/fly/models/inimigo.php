<?php
class Inimigo extends DataMapper {
	var $table = 'inimigos';

	var $validation = array(
		'criado' => array(
        'get_rules' => array('strtotime')
		)
	);

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}


}
?>