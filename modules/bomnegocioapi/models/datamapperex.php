<?php
echo 'aaa';exit;
class DataMapperEx extends DataMapper {
    function __construct($id = NULL) {
        parent::__construct($id);
		$this->getModuleStatus();
    }
	
	function getModuleStatus(){
		$this->using_module = TRUE;
	}
	
	function getPerpage(){
		$CI =& get_instance();
		if(isset($this->per_page)){
			return $this->per_page;
		}
		return NULL;
	}
	
	function get_paged($search){
		$CI =& get_instance();
		$CI->load->library('pagination');

		$pagina_atual = (isset($search['pagina']) )?$search['pagina']:0;
		unset($search['pagina']);
		foreach($search as $key => $value){
			if(empty($value)){
				unset($search[$key]);
			}
		}
		$config['base_url'] = site_url($CI->uri->slash_segment(1).$CI->uri->assoc_to_uri($search).'/pagina');
		$config['total_rows'] = $this->countResult();
		$config['per_page'] = $this->getPerpage();
		$CI->pagination->initialize($config);
		
		$this->get($config['per_page'], $pagina_atual);
	}
	// --------------------------------------------------------------------
    /**
	 * Converte timestamp do mysql para unix time do php
	 */
//	function _timestamp2unix($mysql){
//        $this->{$mysql} = strtotime($this->{$mysql});
//    }

	function countResult(){
		// Clona objeto para fazer contagem
		$countn = $this->get_clone();
		return $countn->count();
	}
		
	/*
	 * Função auxiliar da extensão Array, serve para transformar um array de reação em um objeto
	 */
    function from_array_all($data, $fields = '', $save = FALSE){
		foreach($data as $subdata){
			$array[] = $this->from_array($subdata, $fields, $save);
			// Apaga a ID do objeto para evitar atualizar o registro anterior
			$this->id = NULL;
		}
		return $array;
	}

	function getFields(){
		$fields = array();
		foreach($this->fields as $field){	
				$fields[] = (substr($field, -3) == '_id') ? substr($field, 0, -3) : $field;
		}
		return $fields;
	}
	
	function setFields($relarray = NULL){
		if($_POST){
			$fields = array();
			$CI =& get_instance();
			foreach($this->fields as $field){
					if((substr($field, -3) != '_id')){
						$this->{$field} = $CI->input->post($field);
					}else{
						$field = substr($field, 0, -3); // Retira _id do final
						$rel[$field] = $this->_criarObjValido($field); // Cria o objeto de forma segura
						$rel[$field]->id = $CI->input->post($field); // Pega o valor e bota no id do objeto
						if(isset($relarray[$field]) && is_array($relarray[$field])){
							// array de atributo/valor Ex.: array('estado' => array('nome' => 'rio de janeiro'), 'assunto' => array('nome' => 'Critica'))
							foreach($relarray[$field] as $atributo => $valor){
								$rel[$field]->{$atributo} = $valor;
								//echo '$rel['.$field.']->{'.$atributo.'} = '.$valor.';'; // Debug
							}
						}
					}
			}
			return $this->save($rel);
		}
	}

	function _criarObjValido($className, $id = NULL){
		if(class_exists($className)){
			return new $className($id);
		}
		show_404('page');
	}

	static function autoload($class)
	{
		// Don't attempt to autoload CI_ or MY_ prefixed classes
		if (in_array(substr($class, 0, 3), array('CI_', 'MY_')))
		{
			return;
		}

		// Prepare class
		$class = strtolower($class);

		// Prepare path
		$path = APPPATH . 'modules';
		
		if(is_dir($path) ){
			if ($handle = opendir($path)){
				while (FALSE !== ($dir = readdir($handle))){
					// If dir does not contain a dot
					if (strpos($dir, '.') === FALSE){
						$modules[] = $dir;
					}
				}
			}
		}

		foreach($modules as $module){
			// Prepare path
			$path = APPPATH . 'modules/'.$module.'/models';
			
			// Verify if there is a models folder on Module folder
			if(is_dir($path) ){
				// Prepare file
				$file = $path . '/' . $class . EXT;
	
				// Check if file exists, require_once if it does
				if (file_exists($file))
				{
					require_once($file);
				}
				else
				{
					// Do a recursive search of the path for the class
					DataMapper::recursive_require_once($class, $path);
				}
			}
		}
	}


	/*
	 * Sobreecreve required para ter a opção de associar com algum outro campo do formulario
	 */
/*	function _special_rule($field, $params){
		$valid = ... // validate the field
		if( ! $valid)
		{
			$result = 'For your account, you can have no more than ' . $useraccount->max_widgets . ' widgets at a time.';
			return $result; 
		}
	}
*/
/*    function file_required($campo=NULL,$quantidadeMinima=0){
		$i=0;
        foreach($_FILES as $file){
			if($file['error'] != 4 ){
				// contador de campos válidos
				$i++;
			}
		}
		
		// Se atinjiu a quantidade minima de campos validos então retorna TRUE
		if($i>$quantidadeMinima){
			return TRUE;
		}else{
			$this->set_message('file_required','Uploading a file for %s is required.');
			return FALSE;
		}
    }
*/

	function pegarNomes(){
		$data = array();
		$array = array();
		$data[0] = 'Todos';
		$e = new Estado();
		$e->get();
		foreach($e as $estado){
			$array[strtolower($estado->nome)] = $estado->nome;
		}
		$data = $data + $array;
		return $data;
	}

	function pegarMeses($mes = NULL){
		$data = array('Todos', 'Janeiro', 'Fevereiro', 'Mar&ccedil;o', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro');
		if($mes === NULL){
			return $data;
		}else{
			return $data[$mes];
		}
	}

	function pegarAnos($tipo = 'criado'){
		$class = get_class($this);
		$obj = new $class();
		$obj->group_by('YEAR(criado)')->get();
		$data = array();
		$data[] = 'Todos';
		foreach($obj->all as $noticia){
			$data[date('Y',strtotime($noticia->criado) )] = date('Y',strtotime($noticia->criado) );
		}
		return $data;
	}

	function pegarDiaMes($tipo = 'criado'){
		$data = '';
		if($tipo == 'criado'){
			$data = $this->{$this->created_field};
		}elseif($tipo == 'modificado'){
			$data = $this->{$this->updated_field};
		}
		$dia = date('j',strtotime($data) );
		$mes = date('n',strtotime($data) );
		$meses = $this->pegarMeses();
		return $dia.' de '.$meses[$mes];
	}

	function pegarAno($tipo = 'criado'){
		$data = '';
		if($tipo == 'criado'){
			$data = $this->{$this->created_field};
		}elseif($tipo == 'modificado'){
			$data = $this->{$this->updated_field};
		}
		return date('Y',strtotime($data) );
	}

	function pegarMesAno($tipo = 'criado'){
		if($tipo == 'criado'){
			$data = $this->{$this->created_field};
		}elseif($tipo == 'modificado'){
			$data = $this->{$this->updated_field};
		}
		$nome = $this->pegarMeses(date('n',strtotime($data) ) );
		return $nome.' de '.date('Y',strtotime($data) );
	}

	function pegarDiaMesAno($tipo = 'criado'){
		if($tipo == 'criado'){
			$data = $this->{$this->created_field};
		}elseif($tipo == 'modificado'){
			$data = $this->{$this->updated_field};
		}
		$mes = $this->pegarMeses(date('n',strtotime($data) ) );
		$dia = date('d',strtotime($data) );
		return  $dia.' de '.$mes.' de '.date('Y',strtotime($data) );
	}

	function pegarTempoDiaMesAno($tipo = 'criado'){
		if($tipo == 'criado'){
			$data = $this->{$this->created_field};
		}elseif($tipo == 'modificado'){
			$data = $this->{$this->updated_field};
		}
		$tempo = date('G:i:s',strtotime($data) );
		$mes = $this->pegarMeses(date('n',strtotime($data) ) );
		$dia = date('d',strtotime($data) );
		return  $tempo.' '.$dia.' de '.$mes.' de '.date('Y',strtotime($data) );
	}

}

/* End of file datamapperex.php */
/* Location: ./application/models/datamapperex.php */
