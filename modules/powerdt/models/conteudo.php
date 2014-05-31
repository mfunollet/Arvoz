<?php
class Conteudo extends DataMapperEx {
	
	var $table = 'conteudos';
	var $has_one = array("usuario");	
	
	var $tipo = 1;

	var $validation = array(
		'titulo' => array(
			'label' => 'T&iacute;tulo',
			'rules' => array('required', 'trim', 'unique', 'min_length' => 3, 'max_length' => 50),
		),
		'html' => array(
			'label' => 'Conteudo',
			'rules' => array('required', 'trim'),
			'type' => 'CKtextarea'
		),
		'usuario' => array(
			'label' => 'Usuario',
			'rules' => array('required'),
			'type' => 'dropdown'
		)
	);
	
	function __construct($id = NULL)
	{
		parent::__construct($id);
	}
	
	
	function pegarLista(){
		$this->get();
		$data = array();

		foreach($this->all as $conteudo){
				$data[] = array('nome'	=> resumir($conteudo->titulo, 60),
								'url'	=> 'partido/'.$conteudo->id, $conteudo->titulo
							);
		}
		return $data;
	}

}

/* End of file group.php */
/* Location: ./application/models/group.php */
