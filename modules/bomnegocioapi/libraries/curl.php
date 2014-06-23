<?php

class Curl {

    var $tmpDir = '';
    var $tmpFile = "cookie";
    var $headers = FALSE;
    var $debug = FALSE;

    function __construct($params = array()) {
        $this->tmpDir = realpath('.') . '\\tmp\\';
    }

    function juntarArray($array = FALSE) {
        if (!is_array($array)) {
            return FALSE;
        }
        $arraylinha = '';
        foreach ($array as $key => $valor) {
            $arraylinha .= '&' . $key . '=' . $valor;
        }
        return '?' . substr($arraylinha, 1);
    }

    function is_cookie($cookieName) {
        if (file_exists($this->tmpDir . $cookieName)) {
            return true;
        }
        return false;
    }

    function delete_cookie($cookieName) {
        $file = $this->tmpDir . $cookieName;
        if (file_exists($file)) {
            unlink($file);
            return TRUE;
        }
        return false;
    }

    function get_async($url, $data = array()) {
        $params = (empty($data['post'])) ? NULL : $data['post'];
        $post_string = '';
        if (!empty($params)) {
            foreach ($params as $key => &$val) {
                if (is_array($val))
                    $val = implode(',', $val);
                $post_params[] = $key . '=' . urlencode($val);
            }
            $post_string = implode('&', $post_params);
        }
        $parts = parse_url($url);
        $fp = fsockopen($parts['host'], isset($parts['port']) ? $parts['port'] : 80, $errno, $errstr, 5);

        $out = "POST " . $parts['path'] . " HTTP/1.0\r\n";
        $out.= "Host: " . $parts['host'] . "\r\n";
        $out.= "Content-Type: application/x-www-form-urlencoded\r\n";
        $out.= "Content-Length: " . strlen($post_string) . "\r\n";
        $out.= "Connection: Close\r\n\r\n";
        if (isset($post_string)){
            $out.= $post_string;
        }
        fwrite($fp, $out);
        fclose($fp);
    }

    function get_cookie($cookieName) {
        $file = $this->tmpDir . $cookieName;
        if (file_exists($file)) {
            $handle = fopen($file, 'r');
            $txtcookie = fread($handle, filesize($file));
            fclose($handle);
            return $txtcookie;
        } else {
            return false;
        }
    }

    function request($data = FALSE) {
        // if (!is_dir($this->tmpDir)) {
        //     echo '"' . $this->tmpDir . '" nao e um diretorio';
        //     exit;
        // }
        $data['url'] = (!isset($data['url'])) ? FALSE : $data['url'];
        $data['get'] = (!isset($data['get'])) ? FALSE : $data['get'];
        $data['post'] = (!isset($data['post'])) ? FALSE : $data['post'];
        $data['opcoes'] = (!isset($data['opcoes'])) ? FALSE : $data['opcoes'];
        $data['cookie'] = (!isset($data['cookie'])) ? FALSE : $data['cookie'];
        $cookie_modo = (!isset($data['cookie_modo'])) ? 'usar' : 'criar';

        if (strtoupper(substr($data['url'], 0, 5)) == 'HTTPS') {
            $data['opcoes'][CURLOPT_SSL_VERIFYPEER] = false;
            $data['opcoes'][CURLOPT_SSL_VERIFYHOST] = 2;
            $data['opcoes'][CURLOPT_CAINFO] = $this->tmpDir . 'cert';
            //$data['opcoes'][CURLOPT_CAINFO] = $_SERVER['DOCUMENT_ROOT'] . $this->tmpDir . 'cert';
        }

        $curly = curl_init();

        // COOKIE
        $this->cookie_file_path = ($data['cookie']) ? $this->tmpDir . $data['cookie'] : FALSE;
        //$this->tmpFile = ($data['cookie']) ? $data['cookie'] : FALSE;
        // GET
        if ($data['get']) {
            $data['url'] = $data['url'] . $this->juntarArray($data['get']);
        }

        // informar URL e outras fun��es ao CURL
        curl_setopt($curly, CURLOPT_URL, $data['url']);
        curl_setopt($curly, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)");
        curl_setopt($curly, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curly, CURLOPT_FOLLOWLOCATION, FALSE);

        if ($this->cookie_file_path) {
            curl_setopt($curly, CURLOPT_COOKIEFILE, $this->cookie_file_path);
            curl_setopt($curly, CURLOPT_COOKIEJAR, $this->cookie_file_path);
        }
        if (is_array($data['opcoes']) && isset($data['opcoes']['CURLOPT_REFERER'])) {
            $ref = $data['opcoes']['CURLOPT_REFERER'];
            unset($data['opcoes']['CURLOPT_REFERER']);
        } else {
            $ref = 'http://www.google.com/';
        }
        curl_setopt($curly, CURLOPT_REFERER, $ref);

        // POST
        if (isset($data['post'])) {
            curl_setopt($curly, CURLOPT_POST, 1);
            curl_setopt($curly, CURLOPT_POSTFIELDS, $data['post']);
        }

        // Op��es extras
        if (is_array($data['opcoes']) && isset($data['opcoes'])) {
            curl_setopt_array($curly, $data['opcoes']);
        }
        return $curly;
    }

    function get($data, $opcoes = array()) {
        if (count($data) > 1) {
            $ch = curl_multi_init();
            foreach ($data as $id => $req) {
                $curly[$id] = $this->request($req);
                curl_multi_add_handle($ch, $curly[$id]);
            }
            // Executar os handles
            $running = null;
            do {
                curl_multi_exec($ch, $running);
            } while ($running > 0);

            // Pegar o conteudo e remover os handles
            foreach ($data as $id => $req) {
                $html[$id] = curl_multi_getcontent($curly[$id]);
                curl_multi_remove_handle($ch, $curly[$id]);
            }
            curl_multi_close($ch);
        } else {
            // Procura a chave no array
            $ch = $this->request($data[0]);
            // Executar o handle
            $html = curl_exec($ch);
            if ($this->debug) {
                $debug_array = curl_getinfo($ch);
                print_r($debug_array);
                //print_r($debug_array['certinfo']);
            }
            curl_close($ch);
        }
        return $html;
    }

}

?>
