<?php
$data['xajax_js'] = $this->xajax->getJavascript(base_url()); 
//http://localhost/projetos/plataforma/xajax_js/xajax_core.js/xajax_js/xajax_core.js

$this->load->view('modulo/header',(isset($data))?$data:NULL);
if(!isset($ocultarMenu) ){
	$this->load->view('modulo/smenu',(isset($data['smenu']))?$data['smenu']:NULL);
}
if(isset($content)){$this->load->view($content,(isset($data))?$data:NULL);}
$this->load->view('modulo/footer',(isset($data))?$data:NULL);
?>
