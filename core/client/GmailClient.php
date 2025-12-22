<?php

namespace client;

use Exception;
use function http_build_query;
use function json_encode;

class GmailClient extends Rest74
{
    const URL_BASE = 'https://gmail.gaido.dev';
    const TOKEN = '3f3f380dc57a';

    public function __construct()
    {
        parent::__construct(self::URL_BASE, self::TOKEN);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function hello(): array
    {
        return $this->json($this->get('/hello'));
    }

    /**
     * @return array
     * @throws Exception
     */
    public function token(): array
    {
        return $this->json($this->get('/token'));
    }

    /**
     * @param string $app
     * @param string $usuario
     * @param string $para
     * @param string $assunto
     * @param string $corpo
     * @param string $de
     * @param string $senha
     * @param bool $lento
     * @return string
     * @throws Exception
     */
    public function envia(
        string $app,
        string $usuario,
        string $para,
        string $assunto,
        string $corpo,
        bool $lento,
        string $de,
        string $senha
    ): string {
        $body = [
            'app' => $app,
            'usuario' => $usuario,
            'para' => $para,
            'assunto' => $assunto,
            'corpo' => $corpo,
            'de' => $de,
            'senha' => $senha,
            'lento' => $lento,
        ];
        return $this->post('/envia', json_encode($body));
    }

    /**
     * @param string $app
     * @param string $ini
     * @param string $fim
     * @param string $search
     * @param int $limit
     * @return array
     * @throws Exception
     */
    public function envios(
        string $app,
        string $ini,
        string $fim,
        string $search,
        int $limit = 0

    ): array {
        $pars = http_build_query([
            'app' => $app,
            'ini' => $ini,
            'fim' => $fim,
            'search' => $search,
            'limit' => $limit,
        ]);
        return $this->json($this->get('/envios?' . $pars));
    }

    public function enviosShort(
        string $app,
        string $ini,
        string $fim,
        string $search,
        int $limit = 0
    ): array {
        $pars = http_build_query([
            'app' => $app,
            'ini' => $ini,
            'fim' => $fim,
            'search' => $search,
            'limit' => $limit,
        ]);
        return $this->json($this->get('/envios-short?' . $pars));
    }

    /**
     * @param string $id
     * @return array
     * @throws Exception
     */
    public function envio(
        string $id
    ): array {
        return $this->json($this->get('/envio/' . $id));
    }

    public function reenvia(string $app, string $usuario, string $id): string
    {
        $body = [
            'app' => $app,
            'usuario' => $usuario,
            'id' => $id,
        ];
        return $this->post('/reenvia', json_encode($body));
    }
}