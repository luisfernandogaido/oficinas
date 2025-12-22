<?php
namespace client;

use function json_encode;

class MachineLearning extends Rest
{
    const URL_BASE = 'https://machinelearning.gaido.dev';
//    const URL_BASE = 'http://localhost:8001';
    const TOKEN = 'cKnEGx8An2k2';

    public function __construct()
    {
        parent::__construct(self::URL_BASE, self::TOKEN);
    }

    public function hello(): array
    {
        return $this->json($this->get('/'));
    }
}