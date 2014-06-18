<?php
$data['xajax_js'] = $this->xajax->getJavascript(base_url()); 
//http://localhost/projetos/plataforma/xajax_js/xajax_core.js/xajax_js/xajax_core.js
if(isset($data) ){
	$this->load->view('moduloadm/header',$data);
	if(!isset($ocultarMenu) ){
		$this->load->view('moduloadm/menu',$data);
	}else{
		$this->load->view('moduloadm/ocultarmenu',$data);
	}
	$this->load->view($content,$data);
	$this->load->view('moduloadm/footer',$data);
}else{
	$this->load->view('moduloadm/header');
	if(!isset($ocultarMenu) ){
		$this->load->view('moduloadm/menu');
	}else{
		$this->load->view('moduloadm/ocultarmenu');
	}
	$this->load->view($content);
	$this->load->view('moduloadm/footer');
}
?>
