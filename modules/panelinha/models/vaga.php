<?php
class Vaga extends DataMapperEx {

	var $table = 'vagas';
	var $tmpDir	= '.\\htdocs\\projetos\\dt\\tmp\\';

	// Numero de páginas que cada request vai fazer por vez
	var $saltos = 40;
	// a cada X socketadas ele vai conferir o nivel
	var $confere = 4;

	function __construct($id = NULL)
	{
		parent::__construct($id);
	}

	/*	Retorna nome do cookie usado */
	function getCookieName(){
		return substr($this->email, 0, strpos($this->email, '@'));
	}

	/*	Loga no site */
	function logar(){
		//$relogin = false;
		$CI =& get_instance();
		$data[0]['url'] = 'http://www.e-panelinha.com.br/pro/loading.asp';
		$data[0]['post']['pwdSenha'] = 'LIMELAM1';
		$data[0]['post']['txtBusca'] = 'Busque oportunidades no e-Panelinha. Ex. Analista+Sistemas';
		$data[0]['post']['txtEmail'] = $this->email;
		$data[0]['post']['x'] = rand(1,111);
		$data[0]['post']['y'] = rand(1,28);
		$data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.e-panelinha.com.br/';
		$data[0]['cookie'] = $this->getCookieName();
		$data[0]['cookie_modo'] = 'criar';

		$CI->curl->get($data);
	}

	/*	Logout no site	*/
	function logout(){
		unlink($tmpDir.$this->getCookieName());
	}

	function obterVagasPrimeira(){
		$CI =& get_instance();
		$data[0]['url'] = 'http://www.e-panelinha.com.br/pro/buscaOportunidade.asp?intPagina=1&intMudPag=S';
		$data[0]['post']['chkUf'] = 1;
		$data[0]['post']['hidUF'] = 'RJ,';
		$data[0]['post']['txtEmail'] = $this->email;
		$data[0]['post']['txtDatFim'] = '27/05/2012';
		$data[0]['post']['txtDatIni'] = '20/05/2010';
		$data[0]['post']['txtPalavra'] = 'php';
		$data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.e-panelinha.com.br/pro/login.asp';

		$data[0]['cookie'] = $this->getCookieName();
		$html = $CI->curl->get($data);
		if(strpos($html, 'Nenhuma oportunidade foi encontrada com o c') > 0){
			echo 'erro';
			exit;
		}

		$this->varreVagaSimples($html);
		echo $html;
		return procurar('gina 1 de ', $html, '</p>');
	}

	function obterVagas($pag){
		//$pag = $this->obterVagasPrimeira();
		//echo $pag;exit;
		$CI =& get_instance();
		for($i=1; $i <= $pag; $i++){
			$data[($i-1)]['url'] = 'http://www.e-panelinha.com.br/pro/buscaOportunidade.asp?intPagina='.$i.'&intMudPag=S';
			$data[($i-1)]['post']['chkUf'] = 1;
			$data[$i]['post']['hidUF'] = 'RJ,';
			$data[($i-1)]['post']['txtEmail'] = $this->email;
			$data[($i-1)]['post']['txtDatFim'] = date('d/m/Y',time());
			$data[($i-1)]['post']['txtDatIni'] = date('d/m/Y', strtotime("-1 month"));
			//$data[($i-1)]['post']['txtPalavra'] = '';
			//$data[($i-1)]['opcoes']['CURLOPT_REFERER'] = 'http://www.e-panelinha.com.br/pro/login.asp';

			$data[($i-1)]['cookie'] = $this->getCookieName();
		}
		$htmls = $CI->curl->get($data);

		foreach($htmls as $pagina){
			$this->varreVagaSimples($pagina);
		}

	}

	function varreVagaSimples($html){
		// Varrer String
		$linhas = explode('<tr>', procurar('<table', $html, '<td colspan="5">&nbsp;</td>') );
		unset($linhas[0]);
		unset($linhas[1]);
		unset($linhas[2]);

		foreach($linhas as $linha){
			$v = new Vaga();
			$cod = trim(procurar(';">', $linha, '</font>'));
			if(!empty($cod)){
				$v->where('cod',$cod);
				$v->get();

				$v->cod = $cod;
				$v->empresa = trim(procurar(';">', $linha, '</font>', 2));
				$v->data = trim(procurar(';">', $linha, '</font>', 4));
				$v->cargo = trim(procurar(';">', $linha, '</a>', 6));
				$v->pag = trim(procurar('onClick="MostraDetalhes(', $linha, ')'));
				$v->pag = procurar(',',$v->pag);

				$v->save();
			}

		}
	}

	function obterDetalhes(){
		$this->where('salario =','');
		$this->or_where('desc =','');
		$this->get(2);
		foreach($this as $k => $v){
			$data[$k]['url'] = 'http://www.e-panelinha.com.br/pro/detalheOportunidade.asp?NumOportunidade='.$v->cod.'&Pag='.$v->pag.'&Chamadora=BuscaOportunidade.asp';
			$data[$k]['opcoes']['CURLOPT_REFERER'] = 'http://www.e-panelinha.com.br/pro/login.asp';
			$data[$k]['cookie'] = $this->getCookieName();
		}

		$CI =& get_instance();
		$html = $CI->curl->get($data);
		echo $html[0];
		foreach($html as $vaga){
			// Varre a pagina

			$vaga = procurar('<b>Descri', $vaga, '<input type="button"');

			$this->desc = trim(procurar(';">', $vaga, '</font>'));
			$this->salario = trim(procurar('<font style="font-family: Verdana; font-size: 12px; font-style: normal; font-variant: normal; color: black;">', $vaga, '</font>', 2));
			echo $vaga;
			//$this->update(array('desc' => $this->desc, 'salario' => $this->salario), False);
		}

	}

	function recrutarNoSync(){
		$l = new Log();
		$l->texto = '['.$this->id.'] [Inico] Recrute';
		$l->save();
		$this->CI->curl->get_async(base_url().'site/recrutar/'.$this->id);
	}
}
?>