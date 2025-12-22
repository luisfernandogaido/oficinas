<?php

class Curl
{
    private $ch;
    private $proxy;
    private $port;

    public function __construct($proxy = null, $port = null)
    {
        $this->proxy = $proxy;
        $this->port = $port;
        $this->ch = \curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_USERAGENT, "cURL");
    }

    public function __destruct()
    {
        curl_close($this->ch);
    }

    public function init()
    {
        $this->ch = curl_init();
    }

    public function close()
    {
        curl_close($this->ch);
    }

    public function opt($chave, $valor)
    {
        curl_setopt($this->ch, $chave, $valor);
    }

    public function exec($url, $post = [])
    {
        curl_setopt($this->ch, CURLOPT_URL, $url);
        if ($this->proxy && $this->port) {
            curl_setopt($this->ch, CURLOPT_PROXY, $this->proxy);
            curl_setopt($this->ch, CURLOPT_PROXYPORT, $this->port);
        }
        if ($post) {
            if (is_array($post)) {
                $post = http_build_query($post);
            }
            curl_setopt($this->ch, CURLOPT_POST, true);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
        } else {
            curl_setopt($this->ch, CURLOPT_POST, false);
        }
        $retorno = curl_exec($this->ch);
        return $retorno;
    }

    /**Constrói uma instância com base nas configurações de proxy.
     * @return Curl
     * @throws Exception
     */
    public static function fabrica()
    {
        $conf = conf('proxy');
        if (key_exists(SERVIDOR, $conf)) {
            $servidor = $conf[SERVIDOR]['servidor'];
            $porta = $conf[SERVIDOR]['porta'];
        } else {
            $servidor = null;
            $porta = null;
        }
        return new Curl($servidor, $porta);
    }
}
