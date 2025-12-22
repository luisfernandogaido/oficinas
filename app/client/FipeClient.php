<?php

namespace app\client;

use Generator;
use function conf;
use function curl_exec;
use function curl_getinfo;
use function curl_setopt;
use function error_get_last;
use function error_reporting;
use function fclose;
use function fgets;
use function floatval;
use function fopen;
use function http_build_query;
use function ini_get;
use function ini_set;
use function is_float;
use function is_numeric;
use function json_decode;
use function number_format;
use function preg_match;
use function print_r;
use function stream_context_create;
use function stream_get_meta_data;
use function trim;
use const CURLOPT_CUSTOMREQUEST;
use const CURLOPT_POSTFIELDS;
use const CURLOPT_URL;
use const E_ALL;
use const SERVIDOR;
use Exception;

class FipeClient
{
    const URL_BASE = 'https://fipe.profinanc.com.br';
//    const URL_BASE = 'http://localhost:4010';
    const TOKEN = '884c080c9ecb8a2f';

    /**
     * @var resource
     */
    private $ch;

    /**
     * CncClient constructor.
     */
    public function __construct()
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . self::TOKEN,
        ]);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function hello(): string
    {
        curl_setopt($this->ch, CURLOPT_URL, self::URL_BASE . '/hello');
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, false);
        $res = curl_exec($this->ch);
        if ($this->httpCode() != 200) {
            throw new \Exception($res);
        }
        return $res;
    }

    /**
     * @param string $ref
     * @param string $tipo
     * @return array
     */
    public function marcas(string $ref, string $tipo): array
    {
        $qs = http_build_query([
            'ref' => $ref,
            'tipo' => $tipo,
        ]);
        curl_setopt($this->ch, CURLOPT_URL, self::URL_BASE . '/marcas?' . $qs);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, false);
        $res = curl_exec($this->ch);
        if ($this->httpCode() != 200) {
            return [];
        }
        return json_decode($res, true);
    }

    /**
     * @param string $ref
     * @param string $tipo
     * @param string $marca
     * @return array
     */
    public function modelos(string $ref, string $tipo, string $marca): array
    {
        $qs = http_build_query([
            'ref' => $ref,
            'tipo' => $tipo,
            'marca' => $marca,
        ]);
        curl_setopt($this->ch, CURLOPT_URL, self::URL_BASE . '/modelos?' . $qs);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, false);
        $res = curl_exec($this->ch);
        if ($this->httpCode() != 200) {
            return [];
        }
        return json_decode($res, true);
    }

    public function versoesSeach(string $text): ?array
    {
        $qs = http_build_query([
            'text' => $text,
        ]);
        curl_setopt($this->ch, CURLOPT_URL, self::URL_BASE . '/versoes/search?' . $qs);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, false);
        $res = curl_exec($this->ch);
        if ($this->httpCode() != 200) {
            return [];
        }
        return json_decode($res, true);
    }

    public function versoesReferencias(string $codigoFipe, int $ano, int $limit = 0): ?array
    {
        $qs = http_build_query([
            'codigo_fipe' => $codigoFipe,
            'ano' => $ano,
            'limit' => $limit,
        ]);
        curl_setopt($this->ch, CURLOPT_URL, self::URL_BASE . '/versoes_referencias?' . $qs);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, false);
        $res = curl_exec($this->ch);
        if ($this->httpCode() != 200) {
            return [];
        }
        return json_decode($res, true);
    }

    public function consultaFipeHistorico(string $fipe, string $ref): ?array
    {
        $qs = http_build_query([
            'ref' => $ref,
            'text' => $fipe,
        ]);
        curl_setopt($this->ch, CURLOPT_URL, self::URL_BASE . '/versoes/search?' . $qs);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, false);
        $res = curl_exec($this->ch);
        if ($this->httpCode() != 200) {
            return [];
        }
        return json_decode($res, true);
    }

    /**
     * @param $ref
     * @return Generator
     * @throws Exception
     */
    public function refJsonl($ref): Generator
    {
        $token = self::TOKEN;
        $url = self::URL_BASE . '/ref/jsonl?ref=' . $ref;
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Authorization: Bearer {$token}\r\n",
                'timeout' => 30,
                'ignore_errors' => true,
            ]
        ]);
        $stream = fopen($url, 'r', false, $context);
        if (!$stream) {
            throw new Exception("Não foi possível abrir stream: " . ($error['message'] ?? 'desconhecido'));
        }
        while ($linha = fgets($stream)) {
            $linha = trim($linha);
            if ($linha !== '') {
                yield json_decode($linha, true);
            }
        }
        fclose($stream);
    }

    private function httpCode(): int
    {
        return curl_getinfo($this->ch)['http_code'];
    }

    /**
     * @param string $modelo
     * @return float|null
     */
    public static function motorizacao(string $modelo): ?float
    {
        $er1 = '/\d\.\d/';
        $er2 = '/[^-Y ]?[0-9]{3,4}/';
        preg_match($er1, $modelo, $matches);
        $ret = null;
        if ($matches) {
            $ret = floatval($matches[0]);
        }
        preg_match($er2, $modelo, $matches);
        if ($matches) {
            $ret = floatval($matches[0]);
        }
        if (!is_float($ret)) {
            return null;
        }
        if ($ret >= 1000) {
            $ret /= 1000;
        }
        //todo procurar padrão mais robusto, como por exemplo sufixo "CC" para não converter modelos que têm números
        if ($ret >= 100) {
            $ret /= 1000;
        }
        return $ret;
    }
}