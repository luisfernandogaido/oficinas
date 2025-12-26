<?php
namespace app\client;

use client\Rest;
use Exception;
use function basename;
use function count;
use function curl_exec;
use function curl_file_create;
use function curl_setopt;
use function http_build_query;
use function is_array;
use function json_encode;
use function mime_content_type;
use const CURLOPT_CUSTOMREQUEST;
use const CURLOPT_POST;
use const CURLOPT_POSTFIELDS;
use const CURLOPT_URL;

class Grana extends Rest
{
    const URL_BASE = 'https://grana.gaido.dev';
//    const URL_BASE = 'http://localhost:4024';
    const TOKEN = 'jJtd6xgzHDFJ4Zye';

    const TIPO_BOLETO = 'BOLETO';
    const TIPO_CREDIT_CARD = 'CREDIT_CARD';
    const TIPO_PIX = 'PIX';
    const TIPO_UNDEFINED = 'UNDEFINED';

    public function __construct()
    {
        parent::__construct(self::URL_BASE, self::TOKEN);
    }

    /**
     * @return string
     * @throws Exception
     */
    public function hello(): string
    {
        return $this->get('/hello');
    }

    /**
     * @param string $nome
     * @param string $cpfCnpj
     * @param string $email
     * @param string $celular
     * @param string $tipo
     * @param int $parcelas
     * @param float $valorParcela
     * @param string $primeiroVencimento
     * @param string $descricao
     * @return array
     * @throws Exception
     */
    public function asaasCriaCobranca(
        string $nome,
        string $cpfCnpj,
        string $email,
        string $celular,
        string $tipo,
        int $parcelas,
        float $valorParcela,
        string $primeiroVencimento,
        string $descricao,
    ): array {
        $body = [
            'nome' => $nome,
            'cpf_cnpj' => $cpfCnpj,
            'email' => $email,
            'celular' => $celular,
            'tipo' => $tipo,
            'parcelas' => $parcelas,
            'valor_parcela' => $valorParcela,
            'primeiro_vencimento' => $primeiroVencimento,
            'descricao' => $descricao,
        ];
        $res = $this->post('/asaas-cria-cobranca', json_encode($body));
        if ($this->httpCode() != 200) {
            throw new Exception('asaasCriaCobranca: ' . $res);
        }
        return $res;
    }

    /**
     * @param string $status
     * @param string $installment
     * @param string $ini
     * @param string $fim
     * @return array
     * @throws Exception
     */
    public function asaasCobrancas(string $status, string $installment, string $ini, string $fim): array
    {
        $qs = http_build_query([
            'status' => $status,
            'installment' => $installment,
            'ini' => $ini,
            'fim' => $fim,
        ]);
        $res = $this->get('/asaas-cobrancas?' . $qs);
        if ($this->httpCode() != 200) {
            throw new Exception('asaasCobrancas: ' . $res);
        }
        return $this->json($res);
    }

    /**
     * @param string $id
     * @return array
     * @throws Exception
     */
    public function asaasCobranca(string $id): array
    {
        $res = $this->get('/asaas-cobrancas/' . $id);
        if ($this->httpCode() != 200) {
            throw new Exception('asaasCobranca: ' . $res);
        }
        return $this->json($res);
    }

    /**
     * @param string $id
     * @return array
     * @throws Exception
     */
    public function asaasCobrancaExclui(string $id): array
    {
        $res = $this->delete('/asaas-cobrancas/exclui/' . $id);
        if ($this->httpCode() != 200) {
            throw new Exception('asaasCobrancaExclui: ' . $res);
        }
        return $this->json($res);
    }
}