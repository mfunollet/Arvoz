<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function get_color($percent, $reverse = FALSE) {
    if($reverse){
        $percent = 100-$percent;
    }
    if ($percent > 0 && $percent < 20) {
        return 'info';
    }elseif ($percent > 20 && $percent < 40) {
        return 'success';
    }elseif ($percent > 40 && $percent < 60) {
        return 'warning';
    }elseif ($percent > 60 && $percent < 100) {
        return 'danger';
    }
}

function tirar_array($var, $chave = NULL) {
    if (is_array($var)) {
        $exit = '';
        foreach ($var as $key => $value) {
            $exit .= tirar_array($value, ($chave) ? '[' . $chave . ']' : '' . '[' . $key . ']');
        }
        return $exit;
    } else {

        return '$var' . $chave . ' = ' . $var . '
';
    }
}

function debugtxt($var = false, $reescrever = TRUE) {
    $conteudo = file_get_contents('C:\\debug.txt');
    $fp = fopen('C:\\debug.txt', "w+");
    $var = tirar_array($var, NULL);
    $gravar = ($reescrever) ? $var : $conteudo . $var;
    fwrite($fp, $gravar, strlen($gravar));
    fclose($fp);
    clearstatcache();
}

// ------------------------------------------------------------------------
if (!function_exists('validaFormato')) {

    function post_check($str) {
        if ($this->session->userdata('nivel') > 0) {
            return TRUE;
        } else {
            if ($this->session->userdata('post')) {
                if (date('i') > ($this->session->userdata('post') + 1)) {
                    return TRUE;
                } else {
                    $this->validation->set_message('post_check', 'Voc&ecirc; deve esperar um pouco para enviar mais um formul&aacute;rio');
                    return true;
                }
            } else {// Nao postou a 5 minutos
                return TRUE;
            }
        }
    }

}

// ------------------------------------------------------------------------
if (!function_exists('validaFormato')) {

    // Retorna verdadeiro somente se o final for.jpg ou .jpeg
    function validaFormato($str) {
        if ((strtoupper(substr($str, -5)) == '.JPEG') || (strtoupper(substr($str, -4)) == '.JPG')) {
            return true;
        }
        $this->validation->set_message('formato', 'O a %s deve ser de formato JPEG ou JPG.');
        return false;
    }

}

if (!function_exists('fazerTimeStamp')) {

    // Retorna FALSE se n�o for dd/MM/yyyy, se for dd/MM/yyyy retorna timestamp com Horas, Minutos, Segundos zerado
    function fazerTimeStamp($str) {
        if (strlen($str) == 10) {
            $dia = substr($str, 0, 2);
            $mes = substr($str, 3, 2);
            $ano = substr($str, 6);
            return date('Y-m-d H:i:s O', mktime(0, 0, 0, $mes, $dia, $ano));
        }
        return FALSE;
    }

}

if (!function_exists('input_links')) {

    // Retorna um input js
    function input_links($object, $field, $value, $options) {
        $coluna = 'url';
        $html = '<input type="button" value="Adicionar ' . $field . '" onclick="addField(\'' . $field . '_area\',\'' . $field . '\',0,\'' . $coluna . '\');" />';
        $html .= '	<ol id="' . $field . '_area">';

        $i = 0;
        if ($object->{$field}->count() > 1) { // Campos com ID
            foreach ($object->{$field}->all as $obj) {
                $i++;
                $html .= '	<li><input type="text" name="' . $field . '[' . $obj->id . '][' . $coluna . ']"
								id="' . $field . '[' . $obj->id . '][' . $coluna . ']"
								value="' . $obj->{$coluna} . '" /> ' . anchor($obj->{$coluna}, $obj->nome, array('target' => '_blank')) . '</li>';
            }
        } else { // Campos sem ID
            if (isset($object->{$field}->id)) {
                $id = $object->{$field}->id;
                $nameid = $field . '[' . $id . '][' . $coluna . ']';
            } else {
                $id = time() + rand(1, 200);
                $nameid = $field . '[novos][' . $id . '][' . $coluna . ']';
            }
            $html .= '	<li><input type="text" name="' . $nameid . '"
								id="' . $nameid . '"
								value="' . $object->{$field}->{$coluna} . '" /></li>';
        }
        $html .= '	</ol>';
        return $html;
    }

}

function input_CKtextarea($object, $field, $value, $options) {
    $html = '';
    $html .= '
		<script type="text/javascript" src="' . base_url() . 'inc/3party/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" src="' . base_url() . 'inc/3party/ckfinder/ckfinder.js"></script>';
    $js = (isset($object->validation[$field]['js'])) ? $object->validation[$field]['js'] : '';
    $html .= '<textarea cols="50" id="' . $field . '" name="' . $field . '" rows="20" ' . $js . '>' . $value . '</textarea>';
    $html .= '<script type="text/javascript">
				CKEDITOR.replace( \'' . $field . '\',
						{
							filebrowserBrowseUrl : \'' . base_url() . 'inc/3party/ckfinder/ckfinder.html\',
							filebrowserImageBrowseUrl : \'' . base_url() . 'inc/3party/ckfinder/ckfinder.html?Type=images\',
							filebrowserFlashBrowseUrl : \'' . base_url() . 'inc/3party/ckfinder/ckfinder.html?Type=Flash\',
							filebrowserUploadUrl : \'' . base_url() . 'inc/3party/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files\',
							filebrowserImageUploadUrl : \'' . base_url() . 'inc/3party/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images\',
							filebrowserFlashUploadUrl : \'' . base_url() . 'inc/3party/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash\'
						}
				);
				</script>';


    return $html;
}

if (!function_exists('input_fotos')) {

    function input_fotos($object, $field, $value, $options) {
        return '<applet name="postlet" code="Main.class" archive="' . base_url() . '/inc/3party/java/postlet.jar" width="305" height="150" mayscript>
				<param name = "maxthreads"		value = "5" />
				<param name = "language"		value = "" />
				<param name = "type"			value = "application/x-java-applet;version=1.3.1" />
				<param name = "destination"		value = "' . base_url() . '/index.php/adm/java3" />
				<param name = "backgroundcolour" value = "16777215" />
				<param name = "tableheaderbackgroundcolour" value = "14079989" />
				<param name = "tableheadercolour" value = "0" />
				<param name = "warnmessage" value = "false" />
				<param name = "autoupload" value = "false" />
				<param name = "helpbutton" value = "false" />
				<param name = "fileextensions" value = "Image Files,jpg,gif,jpeg" />
				<param name = "endpage" value = "[*** ENDPAGE URL***]" />
				<param name = "helppage" value = "http://www.postlet.com/help/?thisIsTheDefaultAnyway" />
			</applet>';
    }

}

if (!function_exists('input_calendario')) {

    function input_calendario($object, $field, $value, $options) {
        $value = ($value) ? date('d/m/Y', strtotime($value)) : '';
        $html = '';
        $html .= '<input type="text" name="' . $field . '" value="' . $value . '" size="25" onclick="cal.select(document.forms[\'form\'].' . $field . ',\'' . $field . '\',\'dd/MM/yyyy\'); return false;">
	  <a href="#" onclick="cal.select(document.forms[\'form\'].' . $field . ',\'' . $field . '\',\'dd/MM/yyyy\'); return false;" name="' . $field . 'link" id="' . $field . '">Escolher</a>';
        return $html;
    }

}

if (!function_exists('input_JStext')) {

    function input_JStext($object, $field, $value, $options) {
        $html = '';
        $js = (isset($object->validation[$field]['js'])) ? $object->validation[$field]['js'] : '';
        $html .= '<input type="text" name="' . $field . '" value="' . $value . '" size="45" id="' . $field . '" ' . $js . ' />';
        return $html;
    }

}


/* End of file app_helper.php */
/* Location: ./system/app_helper.php */
?>
