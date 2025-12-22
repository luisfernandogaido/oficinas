<?php
namespace app\client;

use client\Rest;
use function curl_setopt;
use const CURLOPT_HTTPHEADER;

class Es extends Rest
{
    public function __construct()
    {
        $urlBase = 'https://es.gaido.dev';
        parent::__construct($urlBase, '');
        $this->authorization = 'Authorization: Basic ZWxhc3RpYzpOWjkqdVhDTGJDUmV1MTZ0Y0VHcw==';
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
            $this->authorization,
        ]);
    }
}