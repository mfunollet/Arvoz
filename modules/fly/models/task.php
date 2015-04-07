<?php

class Task extends DataMapperExt {

    var $table = 'tasks';
    // Mantem a sessão caso usario interrompa logando
    var $lock_session = FALSE;
    // Numero de páginas que cada request vai fazer por vez
    var $saltos = 40;
    // a cada X socketadas ele vai conferir o nivel
    var $confere = 4;

    function __construct($id = NULL) {
        //register_shutdown_function(array(&$this, 'MyDestructor'));
        parent::__construct($id);
    }

    function MyDestructor() {
        echo 'Script executed with success' . PHP_EOL;
        echo $this->email;
    }

    function create_request() {
        //http://www.submarinoviagens.com.br/Passagens/selecionarvoo
        $data[0]['url'] = 'http://www.submarinoviagens.com.br/Produtos/Aereo/MasterPricer.aspx';
        $data[0]['url'] .= '?Origem=RIO';
        $data[0]['url'] .= 'Destino=MAO&';
        $data[0]['url'] .= 'Data=16/11/2012&';
        $data[0]['url'] .= 'Hora=&';
        $data[0]['url'] .= 'Origem=RIO&';
        $data[0]['url'] .= 'Destino=MAO&';
        $data[0]['url'] .= 'Data=19/11/2012&';
        $data[0]['url'] .= 'Hora=&';
        $data[0]['url'] .= 'NumADT=1&';
        $data[0]['url'] .= 'NumCHD=0&';
        $data[0]['url'] .= 'NumINF=0&';
        $data[0]['url'] .= 'SomenteDireto=0&';
        $data[0]['url'] .= 'SomenteIda=0&';
        $data[0]['url'] .= 'Cia=&';
        $data[0]['url'] .= 'Cabine=0';
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.submarinoviagens.com.br/';

        $html = $this->CI->curl->get($data);
        echo $html;
        return false;
    }

    /* 	Logout no site	 */

    function logout() {
        $this->last_login = NULL;
        $this->save();
        $this->CI->curl->delete_cookie($this->get_cookie_name());
        $this->CI->authentication->logout();
    }

    /* 	obtem informações da página de overview */

    function obterOverview() {
        $data[0]['url'] = 'http://www.darkthrone.com/overview';
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.darkthrone.com/';
        $data[0]['cookie'] = $this->get_cookie_name();
        $html = $this->CI->curl->get($data);

        // Varrer String
        $this->was_atacked = (strpos($html, 'You have been attacked')) ? TRUE : FALSE;
        $this->name = procurar('<div class="character_personality"><strong>', $html, '</strong>');
        $this->lvl = procurar('Level: ', $html, '<br />');
        $this->citizens = limparNumero(procurar('Citizens: ', $html, '<br />'));
        $this->gold = limparNumero(procurar('Gold: ', $html, '<br />'));
        $this->bankgold = limparNumero(procurar('<td>', trim(procurar('Gold in Bank</a></td>', $html, '</td>'))));
        $this->exp = limparNumero(procurar('alt="Experience" /> Experience: ', $html, '<br />'));
        $this->nextleveexp = limparNumero(procurar('(next level in ', $html, ')<br />'));
        $this->turns = limparNumero(trim(procurar('Turns Available: <br />', $html, '</div>')));
        $this->armySize = trim(procurar('Army Size</a></td>', $html, '</td>')) . ')';
        $this->armySize = limparNumero(procurar('<td>', $this->armySize, ')'));
        $this->fort = trim(procurar('Fort Health</a></td>', $html, ')</td>'));
        $this->fort = procurar('(', $this->fort, '%');
        $this->offense = trim(procurar('Offense</a></td>', $html, '</td>')) . ')';
        $this->offense = limparNumero(procurar('<td>', $this->offense, ')'));

        $this->turnTime = array();
        $this->turnTime['minutes'] = procurar(' <span class="minutes">', $html, '</span>');
        $this->turnTime['seconds'] = procurar(':<span class="seconds">', $html, '</span>');

        $this->dtTime = array();
        $this->dtTime['hours'] = procurar('<span class="hours">', $html, '</span>');
        $this->dtTime['minutes'] = procurar(':<span class="minutes">', $html, '</span>');

        $this->save();
    }

    /* 	treinar aldeao */

    function treinarAldeao($mineiros = '', $off_sold1 = '') {
        $data[0]['url'] = 'http://www.darkthrone.com/training/train';
        $data[0]['post']['choice'] = 'TRAIN';
        $data[0]['post']['qty_c2'] = $mineiros; // Mineiros
        $data[0]['post']['qty_c3'] = $off_sold1; // Offense Soldier (Type 1)
        $data[0]['post']['qty_c4'] = '';
        $data[0]['post']['qty_c5'] = '';
        $data[0]['post']['qty_c6'] = '';
        $data[0]['post']['train'] = 'train';
        $data[0]['post']['train.x'] = rand(1, 72);
        $data[0]['post']['train.y'] = rand(1, 5);
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.darkthrone.com/';
        $data[0]['cookie'] = $this->get_cookie_name();
        $html = $this->CI->curl->get($data);

        // Varre String
        // TRUE se consegiu fazer a ação
        if (strpos($html, 'successfully trained')) {
            return TRUE;
        }
        return FALSE;
    }

    /* 	Usar upbattle */

    function usarUpbattle() {
        $data[0]['url'] = 'http://www.darkthrone.com/overview';
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.darkthrone.com/';
        $data[0]['cookie'] = $this->get_cookie_name();
        $this->CI->curl->get($data);
    }

    function usarPontosDeLevel() {
        $data[0]['url'] = 'http://www.darkthrone.com/proficiencies';
        $data[0]['post']['button_add'] = 'button_add';
        $data[0]['post']['button_add.x'] = rand(1, 90);
        $data[0]['post']['button_add.y'] = rand(1, 19);
        $data[0]['post']['prof'] = $this->prof; //'offense'
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.darkthrone.com/';
        $data[0]['cookie'] = $this->get_cookie_name();
        $html = $this->CI->curl->get($data);

        $this->strengthBonus = limparNumero(trim(procurar('Current bonus: ', $html, '%<br />')));

        // TRUE se consegiu fazer a ação
        if (strpos($html, 'A proficiency point has been added. ')) {
            return TRUE;
        }
        return FALSE;
    }

    function recrutarNoSync($quantidade_loops = 1) {
        for ($i = 1; $i <= $quantidade_loops; $i++) {
            $this->save_log('[' . $this->id . '] [Inico] RecruteNoSync ' . $i . ' / ' . $quantidade_loops);
            $this->CI->curl->get_async(site_url('site/recrutar/' . $this->id));
        }
    }

    /* 	recrutar */

    function execute_recrute($cod = '') {
        $data[0]['url'] = 'http://www.darkthrone.com/recruiter/recruit/' . $cod;
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.darkthrone.com/recruiter/recruit/';
        $data[0]['cookie'] = $this->get_cookie_name();
        return $this->CI->curl->get($data);
    }

    function save_log($texto = '') {
        $l = new Log();
        $l->texto = $texto;
        $l->save();
    }

    function save_recrut_clicks($clicks) {
        $r = new Recrut();
        $r->where('create_time > ', date('Y-m-d 00:00:00', time()));
        $r->where_related($this);
        $r->get(1);
        // podia ser > em vez de !=?
        // $re para novo registro, $r para atualizar
        if ($r->clicked == NULL || $r->clicked != $clicks) {
            $re = new Recrut();
            $re->clicked = $clicks;
            $re->save($this);
        } else {
            $this->save_log('[' . $this->id . '] [AVISO] Recrute ja executado hoje clicks !' . $r->clicked . '!=' . $clicks);
        }
    }

    function recrutar($quantidade = 30) {
        $cod = '';
        $i = 0;
        $html = '';
        $clicks = 0;
        $r = new Recrut();

        while ($i < $quantidade) {
            $html = $r->is_recrute_done($this, TRUE, $cod);
            // Check if recrute is done
            if ($html === TRUE) {
                $this->save_log('[' . $this->id . '] [Fim] Recrute');
                $this->save_recrut_clicks(375);
                return TRUE;
            } elseif (strpos($html, 'You have clicked too quickly, please wait')) {
                $this->save_log('[' . $this->id . '] [run] Recrute too quickly clicks:' . $clicks);
            } else {
                $cod = procurar('</script><a href="/recruiter/recruit/', $html, '" id="recruit_link"');
                $clicks = trim(procurar('<h4 class="strong">', $html, '/ 375 clicked today'));
                $this->save_log('[' . $this->id . '] [run] Recrute clicks:' . $clicks);
                $this->save_recrut_clicks($clicks);
                sleep(1);
            }
            $i++;
        }
        if (!$r->is_recrute_done($this)) {
            $this->recrutarNoSync(1);
        }
    }

    function arrumarFort() {
        $data[0]['url'] = 'http://www.darkthrone.com/repair';
        $data[0]['post']['submit_repair_max'] = 'submit_repair_max';
        $data[0]['post']['submit_repair_max.x'] = rand(1, 82);
        $data[0]['post']['submit_repair_max.y'] = rand(1, 9);
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.darkthrone.com/';
        $data[0]['cookie'] = $this->get_cookie_name();
        $html = $this->CI->curl->get($data);

        // TRUE se consegiu fazer a ação
        if (strpos($html, '???')) {
            return TRUE;
        }
        return FALSE;
    }

    function getEconomy() {
        $data[0]['url'] = 'http://www.darkthrone.com/economy';
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.darkthrone.com/';
        $data[0]['cookie'] = $this->get_cookie_name();
        $html = $this->CI->curl->get($data);

        $this->dayGold = limparNumero(trim(procurar('<b>Daily Income:</b> ', $html, '</p>')));
        $this->workers = limparNumero(trim(procurar('<b>Total Workers:</b> ', $html, '<br />')));
    }

    /* Get Upgrades */

    function getUpgrades() {
        $data[0]['url'] = 'http://www.darkthrone.com/upgrades';
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.darkthrone.com/';
        $data[0]['cookie'] = $this->get_cookie_name();
        $html = $this->CI->curl->get($data);
        $this->OffenseBonus = limparNumero(trim(procurar('Offense Bonus: ', $html, '%<br />')));
    }

    /* 	atacar inimigo */

    function atacarInimigo($idDt, $turnos = 10) {
        $data[0]['url'] = 'http://www.darkthrone.com/attack/index/' . $idDt;
        $data[0]['post']['id'] = $idDt;
        $data[0]['post']['turns'] = $turnos;
        $data[0]['post']['x'] = rand(1, 20);
        $data[0]['post']['y'] = rand(1, 12);
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.darkthrone.com/';
        $data[0]['cookie'] = $this->get_cookie_name();
        $html = $this->CI->curl->get($data);

//		$i = new Inimigo($idDt);
        //$i->gold =
//		$i->save();
        echo $html;
        // TRUE se consegiu fazer a ação
        if (strpos($html, 'You just attacked ')) {
            return TRUE;
        }
        return FALSE;
    }

    /* 	pesquisar Todos Inimigos q tem no max 5 nível acima ou baixo */

    function pesquisarTodosInimigos() {
        // Obtem a página do meio
        $pagmeio = $this->pesquisarInimigosDaPagina(0, NULL, TRUE);

        $i = $pagmeio - 1;
        $e = $pagmeio + 1;
        $total1 = 0;
        $total2 = 0;
        $encontrei1 = FALSE;
        $encontrei2 = FALSE;

        // Começa o ciclo para pags abaixo
        /* 		while($encontrei1 == FALSE && $i > 0){
          // Para quando retornar FALSE da primeira vez
          if(($total1%$this->confere) == 0){
          $encontrei1 = $this->pesquisarInimigosDaPagina($i);
          $i--;
          //echo 'A: '.$i.' - '.$total1.'<br />';flush();
          }else{
          $this->pesquisarInimigosDaPaginaNoSync($i, 'menos');
          $i = $i-$this->saltos;
          //echo 'b: '.$i.' - '.$total1.'<br />';flush();
          }
          $total1++;
          }
         */
        // Começa o ciclo para pags acima
        while ($encontrei2 == FALSE && $e <= $this->ultimaPag) {
            // Para quando retornar FALSE da primeira vez
            if (($total2 % $this->confere) == 0) {
                $encontrei2 = $this->pesquisarInimigosDaPagina($e);
                $e++;
                //var_dump($encontrei2);
                //echo $e.' '.$total2.'<br />';
            } else {
                $this->pesquisarInimigosDaPaginaNoSync($e, 'mais');
                $e = $e + $this->saltos;
            }
            $total2++;
        }
    }

    /* 	pesquisar Inimigos da $pag, sem retornar o resultado */

    function pesquisarInimigosDaPaginaNoSync($pag = 0, $direcao = 'mais') {
        // Abre um proxy para redirecionar para http://dt/site/getInimigosControl/1/221
        // que então ira chamar pesquisarInimigosDaPagina em outra sessão
        $this->CI->curl->get_async(site_url('site/getInimigosControl/' . $this->id . '/' . $pag . '/' . $direcao));
        //echo anchor(base_url().'site/getInimigosControl/'.$this->id.'/'.$pag.'/'.$direcao,base_url().'site/getInimigosControl/'.$this->id.'/'.$pag.'/'.$direcao);
    }

    /* 	pesquisar Inimigos da $pag, se $pag nao for 0, pesquisa a pag do meio */

    function pesquisarInimigosDaPagina($pag = 0, $direcao = 'mais') {
        $i = 0;
        if (!is_array($direcao)) {
            // Aqui cuida de pedir a pagina do meio e os pacotes sem erros
            if ($pag < 0) {
                return false;
            }
            $pagina_inicial = $pag;
            for ($i = 0; $i <= $this->saltos; $i++) {
                $data[$i]['url'] = 'http://www.darkthrone.com/userlist/attack' . (($pag != 0) ? '?page=' . $pag : '');
                $data[$i]['opcoes']['CURLOPT_REFERER'] = 'http://www.darkthrone.com/';
                $data[$i]['cookie'] = $this->get_cookie_name();
                if ($pag == 0) {
                    break;
                }
                if ($direcao == 'mais') {
                    $pag++;
                } else {
                    $pag--;
                }
            }
        } else {
            // Aqui cuida dos erros
            foreach ($direcao as $pag) {
                $data[$i]['url'] = 'http://www.darkthrone.com/userlist/attack' . (($pag != 0) ? '?page=' . $pag : '');
                $data[$i]['opcoes']['CURLOPT_REFERER'] = 'http://www.darkthrone.com/';
                $data[$i]['cookie'] = $this->get_cookie_name();
                $i++;
            }
        }
        $htmls = $this->CI->curl->get($data);

        // Pesquisa de 1 unico request
        if (!is_array($htmls)) {
            $htmls = array(0 => $htmls);
        }

        foreach ($htmls as $k => $html) {
            // Verifica se deu erro
            if (strpos($html, 'An error occured on this page.')) {
                $erro[] = ($direcao == 'mais') ? $pagina_inicial + $k : $pagina_inicial - $k;
                continue;
            }

            // Varre cada inimigo
            // #pega só a repetição dos inimigos
            $lista = procurar('<table cellspacing="0" id="user_list">', $html, '</tbody>');
            // #retira o cabeçalho da tabela
            $lista = procurar('</tr>', $lista);

            // #Separa cada linnha em um inimigo e começa o loop (remove o bug do explode() tb)
            $htmlInimigos = explode('</tr>', $lista);
            unset($htmlInimigos[count($htmlInimigos) - 1]);

            foreach ($htmlInimigos as $k => $linha) {
                $lvl = procurar('-->', $linha, '</td>', 5);
                if (!empty($lvl) && $pag != 0 && ($lvl > $this->attlvlmax || $lvl < $this->attlvlmin)) {
                    //$this->save_log('lvl: '.$lvl.'<br />'.'attlvlmax: '.$this->attlvlmax.'<br />'.'attlvlmin: '.$this->attlvlmin.'<br />');
                    return true;
                }
                $rank = procurar('<!--', $linha, '-->');
                $nick = procurar('<!--', $linha, '-->', 2);
                $idDt = procurar('/viewprofile/index/', $linha, '" id=');
                // Remove bug do $idDt $idDt = (strpos($idDt, '?')) ? substr($idDt, 0, strpos($idDt, '?')-1) :$idDt;
                $gold = procurar('<!--', $linha, '-->', 3);
                $armySize = procurar('<!--', $linha, '-->', 4);
                $clan = procurar('<!--', $linha, '-->', 6);
                //echo $lvl.' - '.$rank.' - '.$nick.' - '.$lvl.' - '.$idDt.' - '.$gold.' - '.$armySize.' - '.$clan.'<br />';

                $i = new Inimigo();
                $i->where('idDt', $idDt);
                $i->get();

                $i->idDt = $idDt;
                $i->rank = $rank;
                $i->nick = $nick;
                $i->gold = $gold;
                if ($clan == 4 || $clan == 3) {
                    $i->armySize = floor($armySize * 1.05);
                } else {
                    $i->armySize = $armySize;
                }
                $i->lvl = $lvl;
                $i->race = $clan;
                $i->save();
            }
        }

        // para retornar a página do meio e o lvl de attaque max e min
        if ($pag == 0) {
            $this->attlvlmin = procurar('You may attack a user from levels ', $htmls[0], ' to');
            $this->attlvlmax = procurar('You may attack a user from levels ' . $this->attlvlmin . ' to ', $htmls[0], '.</p>');
            $this->save();
            $pagmeio = procurar('Viewing page ', $htmls[0], ' of');
            $this->ultimaPag = trim(procurar('Viewing page ' . $pagmeio . ' of ', $htmls[0], '</td>'));
            return $pagmeio;
        }

        if (isset($erro)) {
            // corrige os erros
            $str_erros = '';
            foreach ($erro as $value) {
                $str_erros .= $value . '/';
            }
            $this->CI->curl->get_async(site_url('site/getInimigosErroControl/' . $this->id . '/' . $str_erros));
            sleep(5);
        }

        return false;
    }

    /* 	Depositar dinheiro */

    function depositarDinheiro() {
        $data[0]['url'] = 'http://www.darkthrone.com/bank/deposit';
        $data[0]['post']['amount'] = floor(($this->gold * 80) / 100);
        $data[0]['post']['deposit'] = 'deposit';
        $data[0]['post']['deposit.x'] = rand(1, 47);
        $data[0]['post']['deposit.y'] = rand(1, 2);
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.darkthrone.com/';
        $data[0]['cookie'] = $this->get_cookie_name();
        $html = $this->CI->curl->get($data);

        // TRUE se consegiu fazer a ação
        if (strpos($html, 'gold has been successfully deposited into your bank account.')) {
            return TRUE;
        }
        return FALSE;
    }

    function gastarDinheiroComArmory() {
        if ($this->gold > 35500) {
            $qnt = floor($this->gold / 35500);
            $this->comprarArmoryBasico($qnt, $qnt, $qnt, $qnt, $qnt, $qnt);
        }
    }

    /* 	comprar armory */

    function comprarArmoryBasico($arm1 = 0, $arm2 = 0, $arm3 = 0, $arm4 = 0, $arm5 = 0, $arm6 = 0) {
        $data[0]['url'] = 'http://www.darkthrone.com/armory/buy_items';
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.darkthrone.com/';
        $data[0]['cookie'] = $this->get_cookie_name();
        $data[0]['post']['buy'] = 'buy';
        $data[0]['post']['buy.x'] = rand(0, 36);
        $data[0]['post']['buy.y'] = rand(0, 10);
        $data[0]['post']['qty_io_s1_0'] = $arm1; //Dagger
        $data[0]['post']['qty_io_s2_0'] = $arm2; //Padded Hood
        $data[0]['post']['qty_io_s3_0'] = $arm3; //Padded Armor
        $data[0]['post']['qty_io_s4_0'] = $arm4; //Padded Boots
        $data[0]['post']['qty_io_s5_0'] = $arm5; //Padded Bracers
        $data[0]['post']['qty_io_s7_0'] = $arm6; //Small Wooden Shield
        $html = $this->CI->curl->get($data);

        // Varre String
        // TRUE se consegiu fazer a ação
        if (strpos($html, 'You have bought')) {
            return TRUE;
        }
        return FALSE;
    }

    /* 	Comprar mercenarios */

    function comprarMercenarios() {
        $data[0]['url'] = 'http://www.darkthrone.com/overview';
        $data[0]['opcoes']['CURLOPT_REFERER'] = 'http://www.darkthrone.com/';
        $data[0]['cookie'] = $this->get_cookie_name();
        $this->CI->curl->get($data);
    }

    /* Função dos bixos feios */

    function calcularOffenceMax() {
        // pegar numero de soldados
        if (empty($this->workers)) {
            $this->getEconomy();
        }
        // pegar strength Bonus
        if (empty($this->strengthBonus)) {
            $this->usarPontosDeLevel();
        }

        // pegar bonus de offence
        if (empty($this->OffenseBonus)) {
            $this->getUpgrades();
        }

        // calcula bonus totais
        $bonusTotal = 1 + (0.1 + ($this->strengthBonus * 0.01) + ($this->OffenseBonus * 0.01) );

        // Numero total de unidades
        $unidades = $this->armySize + $this->citizens + $this->workers;

        // retorna offence maximo
        return (($unidades * 3) + ($unidades * 71) ) * $bonusTotal;
    }

    /*
      You meet the upgrade requirement.
      http://www.darkthrone.com/upgrades/upgrade
      'Upgrade completed successfully'
      newvalue	1
      submitbtn	b8
      upgrade_to	upgrade_to
      upgrade_to.x	125
      upgrade_to.y	19
     */
}

?>