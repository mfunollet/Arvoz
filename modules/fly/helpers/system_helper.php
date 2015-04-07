<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
function toupper($str){
	$str = utf8_encode(strtoupper(utf8_decode($str)));
	return $str;
}
function tolower($str){
	$str = utf8_encode(strtolower(utf8_decode($str)));
	return $str;
}
if(!function_exists('mes2numero')){
	function mes2numero($nome = FALSE) {
		if(!$nome){
			return FALSE;
		}
		$nome = strtolower($nome);
		$data = array(0				=> 0,
					'janeiro'		=> 1,
					'fevereiro'		=> 2,
					'marco'			=> 3,
					'abril'			=> 4,
					'maio'			=> 5,
					'junho'			=> 6,
					'julho'			=> 7,
					'agosto'		=> 8,
					'setembro'		=> 9,
					'outubro'		=> 10,
					'novembro'		=> 11,
					'dezembro'		=> 12
					);
		return (isset($data[$nome]) ) ? $data[$nome] : FALSE;
	}
}

// ------------------------------------------------------------------------
if(!function_exists('getMeses')){
	function getMeses() {
		$data = array(0				=> 'Todos',
					'janeiro'		=> 'Janeiro',
					'fevereiro'		=> 'Fevereiro',
					'marco'			=> 'Mar&ccedil;o',
					'abril'			=> 'Abril',
					'maio'			=> 'Maio',
					'junho'			=> 'Junho',
					'julho'			=> 'Julho',
					'agosto'		=> 'Agosto',
					'setembro'		=> 'Setembro',
					'outubro'		=> 'Outubro',
					'novembro'		=> 'Novembro',
					'dezembro'		=> 'Dezembro'
					);
		return $data;
	}
}

// ------------------------------------------------------------------------
if(!function_exists('array_keys_exists')){
	function array_keys_exists($array = array(), $keys = array()) {
		if(empty($array)){
			return FALSE;
		}
		foreach($keys as $k) {
			if(isset($array[$k])) {
				$array_valid[$k] = $array[$k];
			}
		}
		return (isset($array_valid) && !empty($array_valid)) ? $array_valid : FALSE;
	}
}

// ------------------------------------------------------------------------
if(!function_exists('procurar')){
	function procurar($antes,$pag = '',$depois=FALSE,$numero=1){
		// Se n„o encontrar $antes retorna FALSE  || $pag == ''
		if(strpos($pag, $antes) === FALSE){
			return FALSE;
		}
		for($vez=0; $vez<$numero; $vez++){
			$pos = strpos($pag, $antes)+strlen($antes);
			$pag = substr($pag,$pos);
		}
		// Procurar/recortar o $depois caso ele exista
		$pos = strpos($pag, $depois);
		if($pos !== FALSE){
			$rec_numero = substr($pag,0,$pos);
		}else{
			$rec_numero = $pag;
		}
		return $rec_numero;
	}
}


if ( ! function_exists('limparNumero'))
{
	function limparNumero($numero){
		$numero = str_replace(',','',$numero);
		$numero = str_replace('.','',$numero);
		return $numero;
	}
}


if ( !function_exists('encriptarSenha') && !function_exists('rotateHEX') ){
	function encriptarSenha($password){
	  $palavraSecreta = ".„q6VYo(v* Uq◊8l&>’;-ª65’'b?p;“u;i„C=U:5?…¸B*v.P";
	  //encrypt the password, rotate characters by length of original password
	  $len = strlen($password);
	  $password = md5($password);
	  $password = rotateHEX($password,$len);
	  return md5($palavraSecreta.$password);
	}
	
	function rotateHEX($string, $n){
		//for more security, randomize this string
		$chars="abcdef1234567890";
		$str="";
		for ($i=0;$i<strlen($string);$i++)	{
			$pos = strpos($chars,$string[$i]);
			$pos += $n;
			if ($pos>=strlen($chars))
				$pos = $pos % strlen($chars);
				$str.=$chars[$pos];
			}
		return $str;
	}
}


if ( !function_exists('formatarData') ){
	function formatarData($time){
		return date('H:i:s  d/m/Y',$time); 
		/*
		$data = explode(" ", $conf_data);
		$data = mktime ($data[0], $data[1], $data[2], $data[3], $data[4], $data[5]);
		*/
	}
}


/*
* array2select
* Formats a given array for use by select boxes in CI. Example below:
*  - form_dropdown('name', array2dropdown($array_of_values_from_db, 'id', 'name', '- Select -'))
*  - Would return something like:
*    <select name="name">
*      <option value="">- Select -</option>
*      <option value="id1">Name 1</option>
*      <option value="id2">Name 2</option>
*    </select>
*
* @param array $array
* @param string $value_field
* @param string $test_field
* @param string $first Not required
*
* @return array
* @author Kirk Bushell
* @url http://www.kirkbushell.com
*/
if ( !function_exists('array2dropdown') ){
	function array2dropdown($array, $value_field = 'id', $text_field = 'titulo', $first = null){
	     if (!is_array($array)) {
		 	if(!is_object($array)){
				throw new Exception('Array expected for 1st argument, '.gettype($array).' received.');
			}
	     }
		
	     $return = array();
	     if (!is_null($first)) {
	         $return[] = $first;
	     }
		if(is_object($array)){
			 foreach ($array as $row) {
				 $return[$row->$value_field] = $row->$text_field;
			 }
		}elseif(is_array($array) ){
			 foreach ($array as $row) {
				 if (is_object($row)) {
					 $return[$row->$value_field] = $row->$text_field;
				 } else {
					 $return[$row[$value_field]] = $row[$text_field];
				 }
			 }
		}
	     return $return;
	}
}


if ( !function_exists('toTimeStamp') ){
	function toTimeStamp($unix=0){
		return date('Y-m-d H:i:s', $unix);
	}
}

if ( !function_exists('resumir') ){
	function resumir($texto = '', $max = 0, $prefix = '', $sufix = '...', $tirarHtml = TRUE){
		if(strlen($texto) < $max){ return $texto; }
		$CI =& get_instance();
		$txt = trim(substr(html_entity_decode(($tirarHtml)?strip_tags($texto):$texto, ENT_QUOTES, $CI->config->item('charset')), 0, $max));
		if($pos = strpos(strrev($txt), ' ')){
			return $prefix.trim(substr($txt, 0, -$pos )).$sufix;
		}else{
			return $prefix.trim(substr($txt, 0, $max )).$sufix;
		}
	}
}

if ( !function_exists('strtolower_utf8') ){
	function strtolower_utf8($texto){
		//Letras min˙sculas com acentos;
		$texto = strtr($texto, "¬¿¡ƒ√ »…ÀŒÕÃœ‘’“”÷€Ÿ⁄‹«", "‚‡·‰„ÍËÈÎÓÌÏÔÙıÚÛˆ˚˘˙¸Á");
		return rawurlencode($texto);
	}
}
/* End of file system_helper.php */
/* Location: ./system/system_helper.php */
?>