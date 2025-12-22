<?php
namespace client;

use Exception;
use function array_merge;
use function curl_exec;
use function curl_getinfo;
use function curl_init;
use function curl_setopt;
use function json_decode;
use function trim;
use const CURLOPT_CUSTOMREQUEST;
use const CURLOPT_ENCODING;
use const CURLOPT_HTTPHEADER;
use const CURLOPT_POSTFIELDS;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_SSL_VERIFYPEER;
use const CURLOPT_URL;

class Rest74
{
    private string $urlBase;
    private string $token;
    protected $ch;

    /**
     * @param string $urlBase
     * @param string $token
     */
    public function __construct(string $urlBase, string $token)
    {
        $this->urlBase = $urlBase;
        $this->token = $token;
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token,
        ]);
    }

    /**
     * @param string $endpoint
     * @return string
     * @throws Exception
     */
    public function get(string $endpoint): string
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, false);
        curl_setopt($this->ch, CURLOPT_URL, $this->urlBase . $endpoint);
        $ret = curl_exec($this->ch);
        if ($ret === false) {
            throw new Exception("get $endpoint: false returned");
        }
        $code = $this->httpCode();
        if ($code >= 400) {
            throw new Exception("error $code: $ret");
        }
        return $ret;
    }

    /**
     * @param string $endpoint
     * @param $body
     * @return string
     * @throws Exception
     */
    public function post(string $endpoint, $body = null): string
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($this->ch, CURLOPT_URL, $this->urlBase . $endpoint);
        $ret = curl_exec($this->ch);
        $code = $this->httpCode();
        if ($code >= 400) {
            throw new Exception("error $code: $ret");
        }
        return $ret;
    }

    /**
     * @param string $endpoint
     * @param $body
     * @return string
     * @throws Exception
     */
    public function put(string $endpoint, $body = null): string
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($this->ch, CURLOPT_URL, $this->urlBase . $endpoint);
        $ret = curl_exec($this->ch);
        $code = $this->httpCode();
        if ($code >= 400) {
            throw new Exception("error $code: $ret");
        }
        return $ret;
    }

    /**
     * @param string $endpoint
     * @param $body
     * @return string
     * @throws Exception
     */
    public function delete(string $endpoint, $body = null): string
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($this->ch, CURLOPT_URL, $this->urlBase . $endpoint);
        $ret = curl_exec($this->ch);
        $code = $this->httpCode();
        if ($code >= 400) {
            throw new Exception("error $code: $ret");
        }
        return $ret;
    }

    /**
     * @param string $jsonString
     * @return mixed
     * @throws Exception
     */
    public function json(string $jsonString)
    {
        $ret = json_decode($jsonString, true);
        if ($ret === null && trim($jsonString) !== 'null') {
            throw new Exception("json_decode != null: $jsonString");
        }
        return json_decode($jsonString, true) ?? [];
    }

    /**
     * @return int
     */
    public function httpCode(): int
    {
        return curl_getinfo($this->ch)['http_code'];
    }

    public function setHeaders(array $adicionalHeaders)
    {
        $headers = [
            'Authorization: Bearer ' . $this->token,
        ];
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, array_merge($headers, $adicionalHeaders));
    }
}