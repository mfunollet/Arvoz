<?php

include_once(APPPATH . 'modules/base/controllers/base_controller.php');

class Lol extends CI_Controller {

// Alface 3,11,19
// Violeta 4,12,20
    var $logged_dt;

    function Lol() {
        parent::__construct();
        /*if ($this->session->userdata('logged_dt')) {
            $c = new Conta();
            $c->where('id', $this->session->userdata('logged_dt'));
            $c->get();
            $this->logged_dt = $c;
        }
        $this->data['data']['logged_dt'] = $this->logged_dt;

        $this->_add_menu_item('dashboard', 'Menu', array('site', 'Início'));
        if ($this->logged_dt) {
            $this->_add_menu_item('top', '', array('site/logout', 'Logout'));

            $this->_add_menu_item('dashboard', 'Menu', array('site/auto', 'Modo Automático'));
            $this->_add_menu_item('dashboard', 'Menu', array('site/clicker', 'Auto Clicker'));
            $this->_add_menu_item('dashboard', 'Menu', array('site/rastrearMelhores/' . $this->logged_dt, 'Rastrear os melhores'));
        } else {
            $this->_add_menu_item('top', '', array('site/login/3', 'Logar Kami'));
            $this->_add_menu_item('top', '', array('site/login/1', 'Logar Alface'));
            $this->_add_menu_item('top', '', array('site/login/2', 'Logar Violeta'));
        }

        $this->_add_menu('top_right', 'top');


        $this->_add_menu('left', 'dashboard');

        $this->layout = 'powerdt_layout';
        $this->config->load('config');

        $this->data['js_files'][] = 'powerdt.js';
         */
    }

    function testelogin() {
        $c = new Conta();
        $c->where('id', 1);
        $c->get();
        $c->login();
        $c->login();
        $c->obterOverview();
        echo 'nome:' . $c->name . '<br>';

        $b = new Conta();
        $b->where('id', 2);
        $b->get();
        $b->login();
        $b->login();
        $b->obterOverview();
        echo 'rita:' . $b->name . '<br>';

        $c->obterOverview();
        echo $c->name . '<br>';
        $c->logout();
        $b->logout();
    }

    function testerectute() {
        $r = new Recrut();
        $r->where('create_time > ', date('Y-m-d 03:00:00', time()));
        $r->where_related($this->logged_dt);
        $r->get(1);

        echo $r->check_last_query(). '<br>';
        echo date('Y-m-d 03:00:00', time()) . '<br>';
        echo $r->id.' - ';
        echo $r->create_time . '<br>';
/*
        $re = new Recrut();
        $re->clicked = 999;
        $re->save($this->logged_dt);

        echo $re->check_last_query(). '<br>';

        $r = new Recrut();
        $r->where('create_time > ', date('Y-m-d 03:00:00', time()));
        $r->where_related($this->logged_dt);
        $r->get(1);
        echo date('Y-m-d 03:00:00', time()) . '<br>';
        echo $r->id.' - ';
        echo $r->create_time . '<br>';*/
    }

    function teste() {
        $r = new Recrut();
        $r->select('*, max(clicked) as max');
        $r->where('create_time > ', date('Y-m-d 03:00:00', time()));
        $r->where_related($this->logged_dt);
        $r->get();
        echo $r->max . ' contra ' . $r->clicked . '<br>';
        echo $r->check_last_query();
        echo $r->create_time . '<br />';
        echo date('Y-m-d 03:00:00', time()) . '<br>';
        echo date('Y-m-d 00:00:00', time());
        //$this->logged_dt->teste();
    }

    function show_info() {
        if (!$this->logged_dt) {
            $this->_set_json_output('FaÃ§a login');
        } else {
            $this->logged_dt->login();
            $this->logged_dt->obterOverview();
            if ($this->logged_dt->was_atacked) {
                $this->msg_error('Você foi atacado');
            }
            parent::page();
        }
    }

    function show_recrut() {
        if (!$this->logged_dt) {
            $this->_set_json_output('FaÃ§a login');
        } else {
            $r = new Recrut();
            if ($r->is_recrute_done($this->logged_dt)) {
                $clicks_percent = 100;
                $clicked = 375;
            } else {
                if (empty($r->clicked)) {
                    $clicks_percent = 0;
                    $clicked = 0;
                } else {
                    $clicked = $r->clicked;
                    $clicks_percent = floor(($clicked * 100) / 375);
                }
            }
            $this->data['data']['clicks_percent'] = $clicks_percent;
            $this->data['data']['clicked'] = $clicked;

            parent::page();
        }
    }

    function login($id = NULL) {
        $c = new Conta();
        $c->where('id', $id);
        $c->get();
        if ($c->exists()) {
            $this->session->set_userdata('logged_dt', $c->id);
            $c->login();
            redirect('site');
        } else {
            echo 'Conta nÃ£o existe';
        }
    }

    function logout() {
        $this->logged_dt->logout();
        redirect('site');
    }

    function auto($id = NULL) {
        if ($id) {
            if ($this->logged_dt->id) {
                $this->logged_dt->logout();
            }
            $c = new Conta();
            $c->where('id', $id);
            $c->get();
            $this->logged_dt = $c;
        }

        $this->logged_dt->login();
        $this->logged_dt->login();
        $this->logged_dt->obterOverview();

        if ($this->logged_dt->fort < 100) {
            $this->logged_dt->arrumarFort();
        }

        $this->logged_dt->usarPontosDeLevel();

        $this->logged_dt->getEconomy();

        $this->logged_dt->depositarDinheiro();
        if (!$this->logged_dt->depositarDinheiro()) {
            $this->logged_dt->gastarDinheiroComArmory();
        }
        $this->logged_dt->treinarAldeao($this->logged_dt->citizens); // treinar todos citizens e, mineiros

        $this->recrutarNoSync(1);
    }

    function autoAllNoSync() {
        $contas = new Conta();
        $contas->get();
        foreach ($contas as $c) {
            $this->autoNoSync($c->id);
        }
    }

    function autoNoSync($id = NULL) {
        $this->curl->get_async(site_url('site/auto/' . $id));
    }

    //localhost/arvoz/site/recrutarNoSync/2
    function recrutarNoSync($quantidade_loops = 10) {
        $r = new Recrut();
        if (!$r->is_recrute_done($this->logged_dt)) {
            $this->logged_dt->recrutarNoSync($quantidade_loops);
        }
    }

    function recrutar($id = 1, $quantidade = 10) {
        $c = new Conta();
        $c->where('id', $id);
        $c->get();
        $this->logged_dt = $c;
        $this->logged_dt->login();
        $this->logged_dt->recrutar($quantidade);
    }

    function getInimigosErroControl($id = 1) {
        $i = 4;
        while ($this->uri->segment($i)) {
            $erros[] = $this->uri->segment($i);
            $i++;
        }
        $this->logged_dt->pesquisarInimigosDaPagina(1, $erros);
    }

    function getInimigosControl($id = 1, $pag = 0, $direcao = 'mais') {
        $this->logged_dt->pesquisarInimigosDaPagina($pag, $direcao);
    }

    function get() {
        $ti = time();
        $url = base_url() . 'site/getInimigosControl/1/222';
        $i = 0;
        while ($i != 100) {
            //echo ' - '.$i.'<br />';
            $this->curl->get_async($url);
            $i++;
        }
        echo time() - $ti;
    }

    function clicker($modo = 0) {
        $this->data['data']['modo'] = $modo;
        parent::page();
    }

    function topclicker($modo = 0) {

        if ($modo == 1) {
            $data['links'][] = 'http://openspider.info';
            $data['links'][] = 'http://www.333surfer.info/';
            $data['links'][] = 'http://darkhide.com';
            $data['links'][] = 'http://www.999surfer.info/';
            $data['links'][] = 'http://personal-note.com/';
            $data['links'][] = 'http://www.anonymspace.info/';
            $data['links'][] = 'http://digitalproxy.info/';
            $data['links'][] = 'http://beautifulworld.us';
            $data['links'][] = 'http://www.unlocate.me';
            $data['links'][] = 'http://fanhide.com';
            $data['links'][] = 'http://houseroid.com/';
            $data['links'][] = 'http://hothide.com';
            $data['links'][] = 'http://clearhide.com';
            $data['links'][] = 'http://proxybb.com';
            $data['links'][] = 'http://flyout.org';
            $data['links'][] = 'http://proxweb.info';
            $data['links'][] = 'http://secretpage.org';
            $data['links'][] = 'http://d-mp3.info/';
            $data['links'][] = 'http://hidemyip.biz';
            $data['links'][] = 'http://fubrus.com';
            $data['links'][] = 'http://www.diggingtunnels.com/';
            $data['links'][] = 'http://daxo.info';
            $data['links'][] = 'http://dontbelive.org/';
            $data['links'][] = 'http://netrabbit.org';
            $data['links'][] = 'http://fbproxy.net/';
            $data['links'][] = 'http://aliveprox.info';
            $data['links'][] = 'http://fullhide.com';
            $data['links'][] = 'http://www.ccck.info';
            $data['links'][] = 'http://mightyproxy.info';
            $data['links'][] = 'http://web-proxy.us';
            $data['links'][] = 'http://www.nonpublic.us';
            $data['links'][] = 'http://adinterim.info/';
            $data['links'][] = 'http://www.redhotproxy.info';
            $data['links'][] = 'http://www.fazap.com';
            $data['links'][] = 'http://www.proxyglype.com';
            $data['links'][] = 'http://flowerhide.com';
            $data['links'][] = 'http://fwbreaker.com';
            $data['links'][] = 'http://telephonehide.com';
            $data['links'][] = 'http://dogpass.net';
            $data['links'][] = 'http://www.ceoproxy.com/';
            $data['links'][] = 'http://www.iptarget.info';
            $data['links'][] = 'http://www.anonymdude.info/';
            $data['links'][] = 'http://www.golno.info';
            $data['links'][] = 'http://www.snowyears.com';
            $data['links'][] = 'http://2010a.info';
            $data['links'][] = 'http://goodhider.com';
            $data['links'][] = 'http://hidepriv.com';
            $data['links'][] = 'http://www.anonymousagent.info/';
            $data['links'][] = 'http://www.polno.info';
            $data['links'][] = 'http://eagleproxy.info';
            $data['links'][] = 'http://12proxy.com/';
            $data['links'][] = 'http://devillovesme.com';
            $data['links'][] = 'http://fastpriv.com';
            $data['links'][] = 'http://freelypass.com';
            $data['links'][] = 'http://www.fakeus.com/';
            $data['links'][] = 'http://proxyrig.info';
            $data['links'][] = 'http://removelog.com';
            $data['links'][] = 'http://fb-proxy.info';
            $data['links'][] = 'http://2010c.info';
            $data['links'][] = 'http://masterpoxy.info';
            $data['links'][] = 'http://buddhalovesme.com';
            $data['links'][] = 'http://2010b.info';
            $data['links'][] = 'http://bmproxy.info';
            $data['links'][] = 'http://undervip.com';
            $data['links'][] = 'http://www.profake.com/';
            $data['links'][] = 'http://onlypriv.com';
            $data['links'][] = 'http://fullpriv.com';
            $data['links'][] = 'http://www.hitfunny.com/';
            $data['links'][] = 'http://buvq.com';
            $data['links'][] = 'http://fastfbproxy.info';
            $data['links'][] = 'http://safeusproxy.info';
            $data['links'][] = 'http://2010d.info';
            $data['links'][] = 'http://bosslovesme.com';
            $data['links'][] = 'http://speedusproxy.info';
            $data['links'][] = 'http://safeprxbrowser.info';
            $data['links'][] = 'http://2010e.info';
            $data['links'][] = 'http://internetproxy.eu';
            $data['links'][] = 'http://secureprxbrowsing.info';
            $data['links'][] = 'http://2010f.info';
            $data['links'][] = 'http://myhider.com';
            $data['links'][] = 'http://mspaceproxy.info';
            $data['links'][] = 'http://openfirewall.net';
            $data['links'][] = 'http://superhide.net';
            $data['links'][] = 'http://surfsafewithus.info';
            $data['links'][] = 'http://usfbproxy.info';
            $data['links'][] = 'http://ussafesurfing.info';
            $data['links'][] = 'http://naifishincltd.com/';
            $data['links'][] = 'http://fbsafesurfing.info';
            $data['links'][] = 'http://turboproxy.info';
            $data['links'][] = 'http://ip4school.info';
            $data['links'][] = 'http://ytsafesurfing.info';
            $data['links'][] = 'http://reconproxy.com';
            $data['links'][] = 'http://anonymuosbrowser.info';
            $data['links'][] = 'http://americanbypass.info';
            $data['links'][] = 'http://thisisweb.net';
            $data['links'][] = 'http://ipforfb.info';
            $data['links'][] = 'http://ip4fb.info';
            $data['links'][] = 'http://anonymuosproxy.info';
            $data['links'][] = 'http://anonymuossurfing.info';
            $data['links'][] = 'http://bypassproxy4school.info';
            $data['links'][] = 'http://www.fanproxy.com';
            $data['links'][] = 'http://americanip.info';
            $data['links'][] = 'http://zeu.in';
            $data['links'][] = 'http://usip4school.info';
            $data['links'][] = 'http://americanbrowser.info';
            $data['links'][] = 'http://www.proxyfrag.com';
            $data['links'][] = 'http://anonym-proxy.info';
            $data['links'][] = 'http://americanipproxy.info';
            $data['links'][] = 'http://americanization.info';
            $data['links'][] = 'http://swapip.com';
            $data['links'][] = 'http://viewthrough.info/';
            $data['links'][] = 'http://bypassproxy4fbook.info';
            $data['links'][] = 'http://www.blockbreak.info';
            $data['links'][] = 'http://tunnelthrough.info';
            $data['links'][] = 'http://unblockmeonline.com';
            $data['links'][] = 'http://unblock-it.info/';
            $data['links'][] = 'http://rapidprivacy.com';
            $data['links'][] = 'http://krishnalovesme.com';
            $data['links'][] = 'http://byewall.com';
            $data['links'][] = 'http://rockhopped.com';
            $data['links'][] = 'http://fbip4school.info';
            $data['links'][] = 'http://dontblockme.net';
            $data['links'][] = 'http://ip4fbook.info';
            $data['links'][] = 'http://iplama.com';
            $data['links'][] = 'http://proxyumbn.info';
            $data['links'][] = 'http://clemproxy.info';
            $data['links'][] = 'http://hidewithme.info';
            $data['links'][] = 'http://proxyss.info';
            $data['links'][] = 'http://proxytulary.info';
            $data['links'][] = 'http://www.proxifinder.com';
            $data['links'][] = 'http://proxyte.info';
            $data['links'][] = 'http://proxyulette.info';
            $data['links'][] = 'http://spacehide.com';
            $data['links'][] = 'http://losthide.com';
            $data['links'][] = 'http://surfinghide.com';
            $data['links'][] = 'http://reaproxy.info';
            $data['links'][] = 'http://mockblock.com';
            $data['links'][] = 'http://proxo.org';
            $data['links'][] = 'http://webshide.com';
            $data['links'][] = 'http://proxyli.info';
            $data['links'][] = 'http://proxysc.info';
            $data['links'][] = 'http://jobhide.com';
            $data['links'][] = 'http://www.hidelinkonline.com';
            $data['links'][] = 'http://proxychance.info';
            $data['links'][] = 'http://legalproxy.info';
            $data['links'][] = 'http://internethide.com';
            $data['links'][] = 'http://proxyicon.info';
            $data['links'][] = 'http://workinghide.com';
            $data['links'][] = 'http://evilproxy.info';
            $data['links'][] = 'http://proxycentury.info';
            $data['links'][] = 'http://proxyhub.info';
            $data['links'][] = 'http://proxyshop.info';
            $data['links'][] = 'http://proxypeople.info';
            $data['links'][] = 'http://proxysolutions.info';
            $data['links'][] = 'http://sellproxy.info';
            $data['links'][] = 'http://unprotectit.com';
            $data['links'][] = 'http://surfproxyanswers.info';
            $data['links'][] = 'http://andsurfproxy.info';
            $data['links'][] = 'http://aboutsurfproxy.info';
            $data['links'][] = 'http://buysurfproxy.info';
            $data['links'][] = 'http://maskedip.info';
            $data['links'][] = 'http://zproxys.info/';
            $data['links'][] = 'http://switchip.info';
            $data['links'][] = 'http://surfproxychance.info';
            $data['links'][] = 'http://freespeedproxy.info/';
            $data['links'][] = 'http://faan.co.uk';
            $data['links'][] = 'http://surfproxycentral.info';
            $data['links'][] = 'http://surfingip.info';
            $data['links'][] = 'http://surfproxycentury.info';
            $data['links'][] = 'http://whyblockme.info';
            $data['links'][] = 'http://surfproxyhelp.info';
            $data['links'][] = 'http://cybersurfproxy.info';
            $data['links'][] = 'http://stealthip.info';
            $data['links'][] = 'http://watchmesurf.info';
            $data['links'][] = 'http://prettysurf.info';
            $data['links'][] = 'http://tips4timemanagement.info/';
            $data['links'][] = 'http://flipip.info';
            $data['links'][] = 'http://sneakandsurf.info';
            $data['links'][] = 'http://totallymasked.info';
            $data['links'][] = 'http://mrsurf.info';
            $data['links'][] = 'http://6tunnel.com';
            $data['links'][] = 'http://peepsurf.info';
            $data['links'][] = 'http://proxski.info';
            $data['links'][] = 'http://surfproxyhub.info';
            $data['links'][] = 'http://maskandsurf.info';
            $data['links'][] = 'http://schoolproxy4free.com';
            $data['links'][] = 'http://esurfproxy.info';
            $data['links'][] = 'http://onlinebypass.info';
            $data['links'][] = 'http://surfproxyicon.info';
            $data['links'][] = 'http://onlinesurfer.info';
            $data['links'][] = 'http://ultrafastproxy.com';
            $data['links'][] = 'http://e-surfproxy.info';
            $data['links'][] = 'http://surfproxyinfo.info';
            $data['links'][] = 'http://dtunnel.info';
            $data['links'][] = 'http://surfproxyland.info';
            $data['links'][] = 'http://www.bluproxy.info';
            $data['links'][] = 'http://maxunblocker.info';
            $data['links'][] = 'http://www.nutiv.com';
            $data['links'][] = 'http://no3s.info';
            $data['links'][] = 'http://evilsurfproxy.info';
            $data['links'][] = 'http://proxymeme.info/';
            $data['links'][] = 'http://www.thermobol.info';
            $data['links'][] = 'http://go2s.info';
            $data['links'][] = 'http://stopandsurf.info';
            $data['links'][] = 'http://totalanonymous.info';
            $data['links'][] = 'http://dailyip.info';
            $data['links'][] = 'http://newipaddress.info';
            $data['links'][] = 'http://findsurfproxy.info';
            $data['links'][] = 'http://surfproxylife.info';
            $data['links'][] = 'http://goodsurfproxy.info';
            $data['links'][] = 'http://getsurfproxy.info';
            $data['links'][] = 'http://surfproxynow.info';
            $data['links'][] = 'http://surfproxyonline.info';
            $data['links'][] = 'http://brows3.com';
            $data['links'][] = 'http://proxies911.info';
            $data['links'][] = 'http://donotblock.info';
            $data['links'][] = 'http://bebo911.info';
            $data['links'][] = 'http://proxp.info';
            $data['links'][] = 'http://fetchproxy.com';
            $data['links'][] = 'http://sellsurfproxy.info';
            $data['links'][] = 'http://surfproxyshop.info';
            $data['links'][] = 'http://surfproxys.info';
            $data['links'][] = 'http://legalsurfproxy.info';
            $data['links'][] = 'http://rtunnel.info';
            $data['links'][] = 'http://janusurf.com';
            $data['links'][] = 'http://coverbrowse.com';
            $data['links'][] = 'http://i-surfproxy.info';
            $data['links'][] = 'http://roxprox.com';
            $data['links'][] = 'http://intotheweb.info';
            $data['links'][] = 'http://mastersurfproxy.info';
            $data['links'][] = 'http://thesurfproxy.info';
            $data['links'][] = 'http://surfproxypeople.info';
            $data['links'][] = 'http://justwatch.info';
            $data['links'][] = 'http://surfproxysolutions.info';
            $data['links'][] = 'http://tunnel-here.info/';
            $data['links'][] = 'http://surfproxywatch.info';
            $data['links'][] = 'http://a2010a.info';
            $data['links'][] = 'http://surfproxyworld.info';
            $data['links'][] = 'http://surfproxyster.info';
            $data['links'][] = 'http://yoursurfproxy.info';
            $data['links'][] = 'http://surfproxyzone.info';
            $data['links'][] = 'http://netunblocker.info';
            $data['links'][] = 'http://surfproxytime.info';
            $data['links'][] = 'http://a2010b.info';
            $data['links'][] = 'http://anonymousanswers.info';
            $data['links'][] = 'http://hide2010.com';
            $data['links'][] = 'http://a2010c.info';
            $data['links'][] = 'http://andanonymous.info';
            $data['links'][] = 'http://mysurfproxy.info';
            $data['links'][] = 'http://anonymouschance.info';
            $data['links'][] = 'http://a2010d.info';
            $data['links'][] = 'http://aboutanonymous.info';
            $data['links'][] = 'http://antianonymous.info';
            $data['links'][] = 'http://anonymouscentury.info';
            $data['links'][] = 'http://askanonymous.info';
            $data['links'][] = 'http://anonymouscentral.info';
            $data['links'][] = 'http://cyberanonymous.info';
            $data['links'][] = 'http://a2010e.info';
            $data['links'][] = 'http://anonymoushelp.info';
            $data['links'][] = 'http://a2010f.info';
            $data['links'][] = 'http://buyanonymous.info';
            $data['links'][] = 'http://meebohide.com';
            $data['links'][] = 'http://eanonymous.info';
            $data['links'][] = 'http://unlockip.org';
            $data['links'][] = 'http://scootby.com';
            $data['links'][] = 'http://farmproxy.com';
            $data['links'][] = 'http://liftblock.com';
            $data['links'][] = 'http://coverurl.org';
            $data['links'][] = 'http://goodanonymous.info';
            $data['links'][] = 'http://proxhide.com';
            $data['links'][] = 'http://anonymousicon.info';
            $data['links'][] = 'http://anonymouslife.info';
            $data['links'][] = 'http://urlhide.org';
            $data['links'][] = 'http://anonymouspeople.info';
            $data['links'][] = 'http://masteranonymous.info';
            $data['links'][] = 'http://proxysaint.com';
            $data['links'][] = 'http://anonymoushub.info';
            $data['links'][] = 'http://findanonymous.info';
            $data['links'][] = 'http://totallyip.info';
            $data['links'][] = 'http://anonymouss.info';
            $data['links'][] = 'http://anonymousland.info';
            $data['links'][] = 'http://e-anonymous.info';
            $data['links'][] = 'http://i-anonymous.info';
            $data['links'][] = 'http://evilanonymous.info';
            $data['links'][] = 'http://legalanonymous.info';
            $data['links'][] = 'http://anonymoussolutions.info';
            $data['links'][] = 'http://anonymoustime.info';
            $data['links'][] = 'http://lmpproxy.info/';
            $data['links'][] = 'http://sellanonymous.info';
            $data['links'][] = 'http://anonymousshop.info';
            $data['links'][] = 'http://anonymousster.info';
            $data['links'][] = 'http://theanonymous.info';
            $data['links'][] = 'http://youranonymous.info';
            $data['links'][] = 'http://km-proxy.de';
            $data['links'][] = 'http://anonymouszone.info';
            $data['links'][] = 'http://totalbypass.info';
            $data['links'][] = 'http://andanonymoussurf.info';
            $data['links'][] = 'http://anonymousworld.info';
            $data['links'][] = 'http://activeip.info';
            $data['links'][] = 'http://anonymouswatch.info';
            $data['links'][] = 'http://anonymoussurfanswers.info';
            $data['links'][] = 'http://anonymoussurfcentral.info';
            $data['links'][] = 'http://anonymoussurfchance.info';
            $data['links'][] = 'http://onlineproxi.info';
            $data['links'][] = 'http://antianonymoussurf.info';
            $data['links'][] = 'http://bypassu.info/';
            $data['links'][] = 'http://aboutanonymoussurf.info';
            $data['links'][] = 'http://cyberanonymoussurf.info';
            $data['links'][] = 'http://anonymoussurfhub.info';
            $data['links'][] = 'http://eanonymoussurf.info';
            $data['links'][] = 'http://anonymoussurfcentury.info';
            $data['links'][] = 'http://livelysurf.info';
            $data['links'][] = 'http://anonymoussurfland.info';
            $data['links'][] = 'http://anonymoussurficon.info';
            $data['links'][] = 'http://askanonymoussurf.info';
            $data['links'][] = 'http://filterfail.info';
            $data['links'][] = 'http://anonymoussurfhelp.info';
            $data['links'][] = 'http://buyanonymoussurf.info';
            $data['links'][] = 'http://evilanonymoussurf.info';
            $data['links'][] = 'http://b2010a.info';
            $data['links'][] = 'http://e-anonymoussurf.info';
            $data['links'][] = 'http://findanonymoussurf.info';
            $data['links'][] = 'http://anonymoussurfinfo.info';
            $data['links'][] = 'http://anonymoussurfshop.info';
            $data['links'][] = 'http://newanonymoussurf.info';
            $data['links'][] = 'http://anonymoussurflife.info';
            $data['links'][] = 'http://masteranonymoussurf.info';
            $data['links'][] = 'http://anonymoussurfsolutions.info';
            $data['links'][] = 'http://sellanonymoussurf.info';
            $data['links'][] = 'http://anonymoussurftime.info';
            $data['links'][] = 'http://xproxy.pl';
            $data['links'][] = 'http://anonymoussurfster.info';
            $data['links'][] = 'http://activetunnel.info';
            $data['links'][] = 'http://myanonymoussurf.info';
            $data['links'][] = 'http://anonymoussurfworld.info';
            $data['links'][] = 'http://b2010b.info';
            $data['links'][] = 'http://youranonymoussurf.info';
            $data['links'][] = 'http://anonymoussurfzone.info';
            $data['links'][] = 'http://ipcare.net';
            $data['links'][] = 'http://andanonymousproxy.info';
            $data['links'][] = 'http://firewallfail.info';
            $data['links'][] = 'http://anonymoussurfwatch.info';
            $data['links'][] = 'http://strongsurf.info';
            $data['links'][] = 'http://anonymousproxychance.info';
            $data['links'][] = 'http://antianonymousproxy.info';
            $data['links'][] = 'http://theanonymoussurf.info';
            $data['links'][] = 'http://anonymousproxyanswers.info';
            $data['links'][] = 'http://anonymousproxyhelp.info';
            $data['links'][] = 'http://crazyunblock.info';
            $data['links'][] = 'http://anonymousproxycentral.info';
            $data['links'][] = 'http://buyanonymousproxy.info';
            $data['links'][] = 'http://hideyourip.net';
            $data['links'][] = 'http://xpbypass.info';
            $data['links'][] = 'http://aboutanonymousproxy.info';
            $data['links'][] = 'http://evilanonymousproxy.info';
            $data['links'][] = 'http://findanonymousproxy.info';
            $data['links'][] = 'http://bypass4.info/';
            $data['links'][] = 'http://anonymousproxyland.info';
            $data['links'][] = 'http://anonymousproxycentury.info';
            $data['links'][] = 'http://nicesurf.info';
            $data['links'][] = 'http://b2010c.info';
            $data['links'][] = 'http://anonymousproxyinfo.info';
            $data['links'][] = 'http://anonymousproxyicon.info';
            $data['links'][] = 'http://anonymousproxynow.info';
            $data['links'][] = 'http://eanonymousproxy.info';
            $data['links'][] = 'http://webwideworld.info';
            $data['links'][] = 'http://e-anonymousproxy.info';
            $data['links'][] = 'http://clicks4cancer.org';
            $data['links'][] = 'http://askanonymousproxy.info';
            $data['links'][] = 'http://anonymousproxylife.info';
            $data['links'][] = 'http://getanonymousproxy.info';
            $data['links'][] = 'http://aztunnel.info';
            $data['links'][] = 'http://legalanonymousproxy.info';
            $data['links'][] = 'http://i-anonymousproxy.info';
            $data['links'][] = 'http://anonymousproxypeople.info';
            $data['links'][] = 'http://fragyourmom.info';
            $data['links'][] = 'http://maxihide.com';
            $data['links'][] = 'http://redportal.org/';
            $data['links'][] = 'http://b2010d.info';
            $data['links'][] = 'http://b2010e.info';
            $data['links'][] = 'http://acee.net/';
            $data['links'][] = 'http://duckquack.info';
            $data['links'][] = 'http://bettersurf.info';
            $data['links'][] = 'http://anonymousproxytime.info';
            $data['links'][] = 'http://anonymousproxyshop.info';
            $data['links'][] = 'http://anonymousproxysolutions.info';
            $data['links'][] = 'http://anonymousproxyworld.info';
            $data['links'][] = 'http://youranonymousproxy.info';
            $data['links'][] = 'http://theanonymousproxy.info';
            $data['links'][] = 'http://newhider.com';
            $data['links'][] = 'http://anonymousproxywatch.info';
            $data['links'][] = 'http://gohi5.info';
            $data['links'][] = 'http://myanonymousproxy.info';
            $data['links'][] = 'http://sellanonymousproxy.info';
            $data['links'][] = 'http://b2010f.info';
            $data['links'][] = 'http://greensurf.info';
            $data['links'][] = 'http://andproxysurf.info';
            $data['links'][] = 'http://proxysurfhelp.info';
            $data['links'][] = 'http://buyproxysurf.info';
            $data['links'][] = 'http://proxysurfcentury.info';
            $data['links'][] = 'http://aboutproxysurf.info';
            $data['links'][] = 'http://antiproxysurf.info';
            $data['links'][] = 'http://proxysurfanswers.info';
            $data['links'][] = 'http://proxysurfchance.info';
            $data['links'][] = 'http://proxysurfcentral.info';
            $data['links'][] = 'http://cyberproxysurf.info';
            $data['links'][] = 'http://surfyourschool.com/';
            $data['links'][] = 'http://proxysurficon.info';
            $data['links'][] = 'http://findproxysurf.info';
            $data['links'][] = 'http://evilproxysurf.info';
            $data['links'][] = 'http://eproxysurf.info';
            $data['links'][] = 'http://proxy3g.com';
            $data['links'][] = 'http://proxysurfland.info';
            $data['links'][] = 'http://proxysurfinfo.info';
            $data['links'][] = 'http://e-proxysurf.info';
            $data['links'][] = 'http://goodproxysurf.info';
            $data['links'][] = 'http://proxysurfs.info';
            $data['links'][] = 'http://proxysurflife.info';
            $data['links'][] = 'http://getproxysurf.info';
            $data['links'][] = 'http://anonyip.com';
            $data['links'][] = 'http://proxysurfnow.info';
            $data['links'][] = 'http://ripby.com/';
            $data['links'][] = 'http://masterproxysurf.info';
            $data['links'][] = 'http://proxysurfster.info';
            $data['links'][] = 'http://notfilter.info';
            $data['links'][] = 'http://proxysurfsolutions.info';
            $data['links'][] = 'http://corkut.info';
            $data['links'][] = 'http://legalproxysurf.info';
            $data['links'][] = 'http://jabproxy.com/';
            $data['links'][] = 'http://webfilterfail.info';
            $data['links'][] = 'http://proxysurftime.info';
            $data['links'][] = 'http://proxysurfzone.info';
            $data['links'][] = 'http://c2010a.info';
            $data['links'][] = 'http://proxysurfworld.info';
            $data['links'][] = 'http://proxysurfwatch.info';
            $data['links'][] = 'http://sellproxysurf.info';
            $data['links'][] = 'http://searchatschool.info/';
            $data['links'][] = 'http://rockbreaker.info';
            $data['links'][] = 'http://justsurf.info';
            $data['links'][] = 'http://surfonschool.com/';
            $data['links'][] = 'http://i-proxysurf.info';
            $data['links'][] = 'http://askusproxy.info';
            $data['links'][] = 'http://c2010b.info';
            $data['links'][] = 'http://antiusproxy.info';
            $data['links'][] = 'http://andusproxy.info';
            $data['links'][] = 'http://totallyliked.com';
            $data['links'][] = 'http://aboutusproxy.info';
            $data['links'][] = 'http://proxyweb.com.es';
            $data['links'][] = 'http://123school.info';
            $data['links'][] = 'http://c2010c.info';
            $data['links'][] = 'http://buyusproxy.info';
            $data['links'][] = 'http://cyberusproxy.info';
            $data['links'][] = 'http://usproxyhub.info';
            $data['links'][] = 'http://e-usproxy.info';
            $data['links'][] = 'http://whitesurf003.info/';
            $data['links'][] = 'http://whitesurf008.info/';
            $data['links'][] = 'http://c2010d.info';
            $data['links'][] = 'http://evilusproxy.info';
            $data['links'][] = 'http://surfngo.info/';
            $data['links'][] = 'http://getusproxy.info';
            $data['links'][] = 'http://goodusproxy.info';
            $data['links'][] = 'http://c2010e.info';
            $data['links'][] = 'http://opentheweb.info';
            $data['links'][] = 'http://whitesurf011.info';
            $data['links'][] = 'http://whitesurf002.info/';
            $data['links'][] = 'http://masterusproxy.info';
            $data['links'][] = 'http://legalusproxy.info';
            $data['links'][] = 'http://usproxys.info';
            $data['links'][] = 'http://usproxyonline.info';
            $data['links'][] = 'http://trueproxyanswers.info';
            $data['links'][] = 'http://newusproxy.info';
            $data['links'][] = 'http://i-usproxy.info';
            $data['links'][] = 'http://c2010f.info';
            $data['links'][] = 'http://viewpro.info';
            $data['links'][] = 'http://schoolmaths.info';
            $data['links'][] = 'http://surfchat.info/';
            $data['links'][] = 'http://revisemaths.info';
            $data['links'][] = 'http://andtrueproxy.info';
            $data['links'][] = 'http://schoolworker.info';
            $data['links'][] = 'http://usproxyzone.info';
            $data['links'][] = 'http://d2010b.info';
            $data['links'][] = 'http://d2010a.info';
            $data['links'][] = 'http://cybertrueproxy.info';
            $data['links'][] = 'http://trueproxycentury.info';
            $data['links'][] = 'http://d2010d.info';
            $data['links'][] = 'http://buytrueproxy.info';
            $data['links'][] = 'http://d2010e.info';
            $data['links'][] = 'http://d2010c.info';
            $data['links'][] = 'http://proxybrite.info';
            $data['links'][] = 'http://asktrueproxy.info';
            $data['links'][] = 'http://abouttrueproxy.info';
            $data['links'][] = 'http://etrueproxy.info';
            $data['links'][] = 'http://trueproxychance.info';
            $data['links'][] = 'http://trueproxyhelp.info';
            $data['links'][] = 'http://d2010f.info';
            $data['links'][] = 'http://hiddensurfingfromschool.info';
            $data['links'][] = 'http://hellolocks.info';
            $data['links'][] = 'http://legaltrueproxy.info';
            $data['links'][] = 'http://livesurfer.info';
            $data['links'][] = 'http://whitesurf006.info/';
            $data['links'][] = 'http://itrueproxy.info';
            $data['links'][] = 'http://hiddengate.info/';
            $data['links'][] = 'http://hidewindows.info';
            $data['links'][] = 'http://66613.info/';
            $data['links'][] = 'http://whitesurf007.info/';
            $data['links'][] = 'http://bjproxy.info/';
            $data['links'][] = 'http://whitesurf001.info/';
            $data['links'][] = 'http://whitesurf004.info';
            $data['links'][] = 'http://besthideme.info';
            $data['links'][] = 'http://trueproxysolutions.info';
            $data['links'][] = 'http://trueproxyzone.info';
            $data['links'][] = 'http://trueproxytime.info';
            $data['links'][] = 'http://proxybrowseranswers.info';
            $data['links'][] = 'http://trueproxyshop.info';
            $data['links'][] = 'http://andproxybrowser.info';
            $data['links'][] = 'http://ipkill.org';
            $data['links'][] = 'http://trueproxyster.info';
            $data['links'][] = 'http://bid0.info';
            $data['links'][] = 'http://mytunnel.info/';
            $data['links'][] = 'http://thetrueproxy.info';
            $data['links'][] = 'http://antiproxybrowser.info';
            $data['links'][] = 'http://proxybrowserchance.info';
            $data['links'][] = 'http://eproxybrowser.info';
            $data['links'][] = 'http://surfbreaker.info';
            $data['links'][] = 'http://freshday.info';
            $data['links'][] = 'http://proxybrowserhub.info';
            $data['links'][] = 'http://bonakona.info';
            $data['links'][] = 'http://proxfly.com';
            $data['links'][] = 'http://whitesurf012.info/';
        } else {
            $data['links'][] = 'http://www.darkthrone.com/recruiter/outside/D5OD8OE6OA0OA3OD8OF1'; // Alface
            $data['links'][] = 'http://www.darkthrone.com/recruiter/outside/A0OF9OF1OE4OB7OD8OF1'; // Violeta
            $data['links'][] = 'http://daxo.info/browse.php?u=Oi8vd3d3LmRhcmt0aHJvbmUuY29tL3JlY3J1aXRlci9vdXRzaWRlL0Q1T0Q4T0U2T0EwT0EzT0Q4T0Yx&b=5';
            //$data['links'][] = 'http://concealmyip.com/browse.php?u=Oi8vd3d3LmRhcmt0aHJvbmUuY29tL3JlY3J1aXRlci9vdXRzaWRlL0Q1T0Q4T0U2T0EwT0EzT0Q4T0Yx&b=7';
//fuck	$data['links'][] = 'http://concealedweb.com/browse.php?u=Oi8vd3d3LmRhcmt0aHJvbmUuY29tL3JlY3J1aXRlci9vdXRzaWRlL0Q1T0Q4T0U2T0EwT0EzT0Q4T0Yx&b=7';
            $data['links'][] = 'http://www.dynamicdesignz.net/proxy/index.php?q=aHR0cDovL3d3dy5kYXJrdGhyb25lLmNvbS9yZWNydWl0ZXIvb3V0c2lkZS9ENU9EOE9FNk9BME9BM09EOE9GMQ%3D%3D';
            $data['links'][] = 'http://www.proxyresources.com/templates/template1/index.php?q=aHR0cDovL3d3dy5kYXJrdGhyb25lLmNvbS9yZWNydWl0ZXIvb3V0c2lkZS9ENU9EOE9FNk9BME9BM09EOE9GMQ%3D%3D';
            $data['links'][] = 'http://www.thebackdoorproxy.com/index.php?q=aHR0cDovL3d3dy5kYXJrdGhyb25lLmNvbS9yZWNydWl0ZXIvb3V0c2lkZS9ENU9EOE9FNk9BME9BM09EOE9GMQ%3D%3D';
            $data['links'][] = 'http://www.proxy.mjbaboon.com/index.php?q=aHR0cDovL3d3dy5kYXJrdGhyb25lLmNvbS9yZWNydWl0ZXIvb3V0c2lkZS9ENU9EOE9FNk9BME9BM09EOE9GMQ%3D%3D';
            $data['links'][] = 'http://www.kowalczuk.info/index.php?q=aHR0cDovL3d3dy5kYXJrdGhyb25lLmNvbS9yZWNydWl0ZXIvb3V0c2lkZS9ENU9EOE9FNk9BME9BM09EOE9GMQ%3D%3D';
            $data['links'][] = 'http://dimension.sproowl.net/index.php?q=aHR0cDovL3d3dy5kYXJrdGhyb25lLmNvbS9yZWNydWl0ZXIvb3V0c2lkZS9ENU9EOE9FNk9BME9BM09EOE9GMQ%3D%3D&hl=2ed';
            $data['links'][] = 'http://proxyboost.net/index.php?q=aHR0cDovL3d3dy5kYXJrdGhyb25lLmNvbS9yZWNydWl0ZXIvb3V0c2lkZS9ENU9EOE9FNk9BME9BM09EOE9GMQ%3D%3D';
            $data['links'][] = 'http://0pp.info/index.php?q=aHR0cDovL3d3dy5kYXJrdGhyb25lLmNvbS9yZWNydWl0ZXIvb3V0c2lkZS9ENU9EOE9FNk9BME9BM09EOE9GMQ%3D%3D';

            $data['links'][] = 'http://www.proxy-service.de/web-proxy3.php/Oi8vd3d3/LmRhcmt0/aHJvbmUu/Y29tL3Jl/Y3J1aXRl/ci9vdXRz/aWRlL0Q1/T0Q4T0U2/T0EwT0Ez/T0Q4T0Yx/b1/';

            $data['links'][] = 'http://coverproxy.com/browse.php?u=Oi8vd3d3LmRhcmt0aHJvbmUuY29tL3JlY3J1aXRlci9vdXRzaWRlL0Q1T0Q4T0U2T0EwT0EzT0Q4T0Yx&b=5';
            $data['links'][] = 'http://awesomeproxy.com/browse.php?u=Oi8vd3d3LmRhcmt0aHJvbmUuY29tL3JlY3J1aXRlci9vdXRzaWRlL0Q1T0Q4T0U2T0EwT0EzT0Q4T0Yx&b=5';
            $data['links'][] = 'http://www.surfall.net/index.php?q=aHR0cDovL3d3dy5kYXJrdGhyb25lLmNvbS9yZWNydWl0ZXIvb3V0c2lkZS9ENU9EOE9FNk9BME9BM09EOE9GMQ%3D%3D';
            $data['links'][] = 'http://hide-my.com/browse.php/kL0G4D0G/mR0GjR0G/2H0G4D0G/1D0YyEJn/mEKqi9vp/yEKn1W3L/yW3Yg92L/hHzoiWUn/0gzpuEzY/3q3qi8vB/rs2s2n2p/09n341o6/b29/';

            $data['links'][] = 'http://60surfer.info/browse.php?u=Oi8vd3d3LmRhcmt0aHJvbmUuY29tL3JlY3J1aXRlci9vdXRzaWRlL0Q1T0Q4T0U2T0EwT0EzT0Q4T0Yx&b=5&f=norefer';
            $data['links'][] = 'http://mysneaky.com/browse.php?u=Oi8vd3d3LmRhcmt0aHJvbmUuY29tL3JlY3J1aXRlci9vdXRzaWRlL0Q1T0Q4T0U2T0EwT0EzT0Q4T0Yx&b=7';
            $data['links'][] = 'http://freethewww.com/browse.php?u=Oi8vd3d3LmRhcmt0aHJvbmUuY29tL3JlY3J1aXRlci9vdXRzaWRlL0Q1T0Q4T0U2T0EwT0EzT0Q4T0Yx&b=7';

            $data['links'][] = 'http://www.free4u.gr/proxy/index.php?q=aHR0cDovL3d3dy5kYXJrdGhyb25lLmNvbS9yZWNydWl0ZXIvb3V0c2lkZS9ENU9EOE9FNk9BME9BM09EOE9GMQ%3D%3D';
            $data['links'][] = 'http://warpproxy.com/browse.php?u=Oi8vd3d3LmRhcmt0aHJvbmUuY29tL3JlY3J1aXRlci9vdXRzaWRlL0Q1T0Q4T0U2T0EwT0EzT0Q4T0Yx&b=5';
            $data['links'][] = 'http://prxy.eu/browse.php?u=Oi8vd3d3LmRhcmt0aHJvbmUuY29tL3JlY3J1aXRlci9vdXRzaWRlL0Q1T0Q4T0U2T0EwT0EzT0Q4T0Yx&b=5';
            $data['links'][] = 'http://proxyforfree.info/browse.php?u=Oi8vd3d3LmRhcmt0aHJvbmUuY29tL3JlY3J1aXRlci9vdXRzaWRlL0Q1T0Q4T0U2T0EwT0EzT0Q4T0Yx&b=5';
            // FuLL bug $data['links'][] = 'http://www.iweb365.info/browse.php/d223c335/b81Oi8vd/3d3LmRhc/mt0aHJvb/mUuY29tL/3JlY3J1a/XRlci9vd/XRzaWRlL/0Q1T0Q4T/0U2T0EwT/0EzT0Q4T/0Yx/b2/';
        }
        $this->load->view('site/topclicker', $data);
    }

    function rastrearMelhores($id = 1, $multi = 11) {
        // COMENTARIO INVALIDO atacar se a qntidade de dinheiro do cara for > 30*Income && Asize dele for < o meu /2

        $data['multi'] = $multi;
        $data['id'] = $id;
        // Verifica se o rastreamento foi feito a mais de 30 min
        $i = new Inimigo();
        $i->get(1);

        $c = new Conta($id);
        $this->logged_dt->login();
        $this->logged_dt->obterOverview();
        $this->logged_dt->getEconomy();

        $data['OffenceMax'] = number_format(floor($this->logged_dt->calcularOffenceMax()), 0, ',', '.');

        $time = time() - (60 * 60 * 2);
        if (!isset($i->criado) || ($i->criado + 1800) < $time) {
            $this->db->empty_table('inimigos');
            $this->logged_dt->pesquisarTodosInimigos();
        }

        $this->load->library('table');
        $tmpl = array('table_open' => '<table border="1" cellpadding="2" cellspacing="1" style="border-style:solid; border:1px; text-align:right;">');
        $this->table->set_template($tmpl);


        $unidades = ($this->logged_dt->armySize + $this->logged_dt->citizens + $this->logged_dt->workers); //floor((80*$this->logged_dt->armySize)/100);
        $goldMin = ($this->logged_dt->dayGold * $multi);

        $i = new Inimigo();
        $i->where('lvl <=', $this->logged_dt->lvl + 5);
        $i->where('lvl >=', $this->logged_dt->lvl - 5);
        $i->where('armySize <', $unidades);
        $i->where('gold >', $goldMin);
        $i->order_by('(gold/armySize)', 'DESC');
        $i->order_by('gold', 'asc');
        $i->get(20);
        $data['inimigosFiltrados'] = count($i->all);
        //$i->check_last_query();
        $qnt = floor($i->result_count() * 1.7);
        $data['totalInimigos'] = $i->count();



        $this->table->set_heading('IdDt', 'Nick', 'ArmySize', 'Gold', 'Level', 'Ataque RÃ¡pido', 'A&ccedil;&atilde;o');
        foreach ($i->all as $inimigo) {
            $top[$inimigo->idDt] = dechex(rand(1050000, 10000000));
            $this->table->add_row('<div style="background-color:#' . $top[$inimigo->idDt] . ';">' . $inimigo->idDt . '</div>', anchor('http://www.darkthrone.com/viewprofile/index/' . $inimigo->idDt, $inimigo->nick), number_format($inimigo->armySize, 0, ',', '.'), number_format($inimigo->gold, 0, ',', '.'), $inimigo->lvl, anchor('atacarInimigo/' . $id . '/' . $inimigo->idDt, 'Atacar'), anchor('apagarInimigo/' . $id . '/' . $inimigo->id, 'Apagar')
            );
            // Seta cores para cada um
        }

        $data['tabela1'] = $this->table->generate();
        $this->table->clear();


        $i = new Inimigo();
        $i->where('lvl <=', $this->logged_dt->lvl + 5);
        $i->where('lvl >=', $this->logged_dt->lvl - 5);
        $i->where('gold >', $goldMin);
        $i->order_by('(gold/armySize)', 'DESC');
        $i->order_by('gold', 'DESC');
        $i->get($qnt);
        $data['inimigosFiltrados'] = count($i->all) + $data['inimigosFiltrados'];

        $this->table->set_template($tmpl);
        $this->table->set_heading('IdDt', 'Nick', 'ArmySize', 'Gold', 'Level', 'Ataque RÃ¡pido', 'A&ccedil;&atilde;o');
        foreach ($i->all as $inimigo) {
            $div = (isset($top[$inimigo->idDt])) ? ' style="background-color:#' . $top[$inimigo->idDt] . ';"' : '';
            $this->table->add_row('<div' . $div . '>' . $inimigo->idDt . '</div>', anchor('http://www.darkthrone.com/viewprofile/index/' . $inimigo->idDt, $inimigo->nick), number_format($inimigo->armySize, 0, ',', '.'), number_format($inimigo->gold, 0, ',', '.'), $inimigo->lvl, anchor('atacarInimigo/' . $id . '/' . $inimigo->idDt, 'Atacar'), anchor('apagarInimigo/' . $id . '/' . $inimigo->id, 'Apagar')
            );
        }


        $data['tabela2'] = $this->table->generate();
        $this->table->clear();


        // direita
        unset($top);
        $i = new Inimigo();
        $i->where('lvl <=', $this->logged_dt->lvl + 5);
        $i->where('lvl >=', $this->logged_dt->lvl - 5);
        $i->where('armySize <', $unidades);
        $i->order_by('gold', 'DESC');
        $i->get($qnt);
        $data['inimigosFiltrados'] = count($i->all) + $data['inimigosFiltrados'];

        $this->table->set_template($tmpl);
        $this->table->set_heading('IdDt', 'Nick', 'ArmySize', 'Gold', 'Level', 'Ataque RÃ¡pido', 'A&ccedil;&atilde;o');
        foreach ($i->all as $inimigo) {
            $top[$inimigo->idDt] = dechex(rand(1050000, 10000000));
            $this->table->add_row('<div style="background-color:#' . $top[$inimigo->idDt] . ';">' . $inimigo->idDt . '</div>', anchor('http://www.darkthrone.com/viewprofile/index/' . $inimigo->idDt, $inimigo->nick), number_format($inimigo->armySize, 0, ',', '.'), number_format($inimigo->gold, 0, ',', '.'), $inimigo->lvl, anchor('atacarInimigo/' . $id . '/' . $inimigo->idDt, 'Atacar'), anchor('apagarInimigo/' . $id . '/' . $inimigo->id, 'Apagar')
            );
        }


        $data['tabela3'] = $this->table->generate();
        $this->table->clear();


        $i = new Inimigo();
        $i->where('lvl <=', $this->logged_dt->lvl + 5);
        $i->where('lvl >=', $this->logged_dt->lvl - 5);
        $i->order_by('gold', 'DESC');
        $i->get($qnt);
        $data['inimigosFiltrados'] = count($i->all) + $data['inimigosFiltrados'];

        $this->table->set_template($tmpl);
        $this->table->set_heading('IdDt', 'Nick', 'ArmySize', 'Gold', 'Level', 'Ataque RÃ¡pido', 'A&ccedil;&atilde;o');
        foreach ($i->all as $inimigo) {
            $div = (isset($top[$inimigo->idDt])) ? ' style="background-color:#' . $top[$inimigo->idDt] . ';"' : '';
            $this->table->add_row('<div' . $div . '>' . $inimigo->idDt . '</div>', anchor('http://www.darkthrone.com/viewprofile/index/' . $inimigo->idDt, $inimigo->nick), number_format($inimigo->armySize, 0, ',', '.'), number_format($inimigo->gold, 0, ',', '.'), $inimigo->lvl, anchor('atacarInimigo/' . $id . '/' . $inimigo->idDt, 'Atacar'), anchor('apagarInimigo/' . $id . '/' . $inimigo->id, 'Apagar')
            );
        }


        $data['tabela4'] = $this->table->generate();
        $this->table->clear();

        // Pega o melhor para atacar mesmo
        $in = new Inimigo();
        $in->select('gold');
        $in->where('lvl <=', $this->logged_dt->lvl + 5);
        $in->where('lvl >=', $this->logged_dt->lvl - 5);
        $in->where('armySize <', $unidades);
        $in->order_by('gold', 'DESC');
        $in->get(1, 10);
        //$in->check_last_query();

        $ie = new Inimigo();
        $ie->where('lvl <=', $this->logged_dt->lvl + 5);
        $ie->where('lvl >=', $this->logged_dt->lvl - 5);
        $ie->where('armySize <', $unidades);
        $ie->where('gold >', $in->gold);
        $ie->order_by('(gold/armySize)', 'DESC');
        $ie->order_by('gold', 'DESC');
        $ie->get(1);


        $this->table->set_template($tmpl);
        $this->table->set_heading('IdDt', 'Nick', 'ArmySize', 'Gold', 'Level', 'Ataque RÃ¡pido', 'A&ccedil;&atilde;o');
        foreach ($ie->all as $inimigo) {
            $div = (isset($top[$inimigo->idDt])) ? ' style="background-color:#' . $top[$inimigo->idDt] . ';"' : '';
            $this->table->add_row('<div' . $div . '>' . $inimigo->idDt . '</div>', anchor('http://www.darkthrone.com/viewprofile/index/' . $inimigo->idDt, $inimigo->nick), number_format($inimigo->armySize, 0, ',', '.'), number_format($inimigo->gold, 0, ',', '.'), $inimigo->lvl, anchor('atacarInimigo/' . $id . '/' . $inimigo->idDt, 'Atacar'), anchor('apagarInimigo/' . $id . '/' . $inimigo->id, 'Apagar')
            );
        }

        $data['tabela5'] = $this->table->generate();

        $data['unidades'] = number_format($unidades, 0, ',', '.');
        $data['goldMin'] = number_format($goldMin, 0, ',', '.');
        ;
        //$this->logged_dt->atacarInimigo(2056380, 5);
        $this->load->view('site/rastrearMelhores', $data);
    }

    function atacarInimigo($id = 1, $idDt = 1, $turnos = 10) {
        $this->logged_dt->login();
        $this->logged_dt->atacarInimigo($idDt, $turnos);
        redirect('site/rastrearMelhores/' . $id);
    }

    function apagarInimigo($id = 1, $idInimigo = NULL) {
        $i = new Inimigo($idInimigo);
        $i->delete();
        redirect('site/rastrearMelhores/' . $id);
    }

    function index() {
        //$this->_set_title('Dashboard');
        //parent::page();
        echo anchor('lol/readchampion/26630189','Alface');
    }
    
    function readChampion($code = NULL){
        $c = new Champion();
        $c->code = $code;
        $c->get_general();
    }

}

/* End of file site.php */
/* Location: ./application/controllers/site.php */
