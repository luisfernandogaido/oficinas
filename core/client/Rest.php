<?php
namespace client;

use CurlHandle;
use Exception;
use function curl_close;
use function curl_exec;
use function curl_getinfo;
use function curl_init;
use function curl_setopt;
use function is_array;
use function json_decode;
use function json_encode;
use function trim;
use const CURLOPT_CUSTOMREQUEST;
use const CURLOPT_ENCODING;
use const CURLOPT_HTTPHEADER;
use const CURLOPT_POSTFIELDS;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_SSL_VERIFYPEER;
use const CURLOPT_URL;

class Rest
{
    protected string $urlBase;
    private string $token;
    protected CurlHandle $ch;
    protected string $authorization;

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
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_ENCODING, 'gzip');
        $this->authorization = 'Authorization: Bearer ' . $this->token;
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
            $this->authorization,
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
     * @param string|array|null $body
     * @param bool $json
     * @return string|array
     * @throws Exception
     */
    public function post(string $endpoint, string|array|null $body = null, bool $json = true): string|array
    {
        curl_setopt($this->ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'POST');
        if (is_array($body)) {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, json_encode($body));
            curl_setopt($this->ch, CURLOPT_HTTPHEADER, [
                $this->authorization,
                'Content-Type: application/json',
            ]);
        } else {
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $body);
        }
        curl_setopt($this->ch, CURLOPT_URL, $this->urlBase . $endpoint);
        $ret = curl_exec($this->ch);
        $code = $this->httpCode();
        if ($code >= 400) {
            throw new Exception("error $code: $ret");
        }
        if ($json) {
            return json_decode($ret, true);
        }
        return $ret;
    }

    /**
     * @param string $endpoint
     * @param string|array|null $body
     * @return string
     * @throws Exception
     */
    public function put(string $endpoint, string|array|null $body = null): string
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
     * @param string|array|null $body
     * @return string
     * @throws Exception
     */
    public function delete(string $endpoint, string|array|null $body = null): string
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
    public function json(string $jsonString): mixed
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

    public function __destruct()
    {
        if (isset($this->ch)) {
            curl_close($this->ch);
        }
    }
}