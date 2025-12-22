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

class Storage extends Rest
{
    public function __construct()
    {
        $urlBase = $_SERVER['STORAGE_URL'];
        $token = $_SERVER['STORAGE_TOKEN'];
        parent::__construct($urlBase, $token);
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
     * @param string $app
     * @param string $user
     * @param string $action
     * @param string $reference
     * @param int $ttl
     * @param int $dim
     * @param int $crf
     * @param int $quality
     * @param string $filename
     * @param string|null $type
     * @param string|null $name
     * @return array
     * @throws Exception
     */
    public function upload(
        string $app,
        string $user,
        string $action,
        string $reference,
        int $ttl,
        int $dim,
        int $crf,
        int $quality,
        string $filename,
        ?string $type = null,
        ?string $name = null
    ): array {
        curl_setopt($this->ch, CURLOPT_URL, $this->urlBase . '/files/upload');
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, null);
        if (!$type) {
            $type = mime_content_type($filename);
        }
        if (!$name) {
            $name = basename($filename);
        }
        $post = [
            'app' => $app,
            'user' => $user,
            'action' => $action,
            'reference' => $reference,
            'ttl' => $ttl,
            'dim' => $dim,
            'crf' => $crf,
            'quality' => $quality,
            'file' => curl_file_create($filename, $type, $name)
        ];
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
        $ret = curl_exec($this->ch);
        if ($ret === false) {
            throw new Exception("upload: false returned");
        }
        $code = $this->httpCode();
        if ($code >= 400) {
            throw new Exception("error $code: $ret");
        }
        return $this->json($ret);
    }

    /**
     * @param string $app
     * @param string $user
     * @param string $action
     * @param string $reference
     * @param int $ttl
     * @param int $dim
     * @param int $crf
     * @param int $quality
     * @return array
     * @throws Exception
     */
    public function uploadFromFiles(
        string $app,
        string $user,
        string $action,
        string $reference,
        int $ttl,
        int $dim,
        int $crf,
        int $quality,
    ): array {
        curl_setopt($this->ch, CURLOPT_URL, $this->urlBase . '/files/upload');
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, null);
        $post = [
            'app' => $app,
            'user' => $user,
            'action' => $action,
            'reference' => $reference,
            'ttl' => $ttl,
            'dim' => $dim,
            'crf' => $crf,
            'quality' => $quality,
        ];
        foreach ($_FILES as $file) {
            if (is_array($file['name'])) {
                $n = count($file['name']);
                for ($i = 0; $i < $n; $i++) {
                    if ($file['error'][$i]) {
                        throw new Exception('uploadFromFiles: ' . $file['error'][$i]);
                    }
                    $f = curl_file_create($file['tmp_name'][$i], $file['type'][$i], $file['name'][$i]);
                    $post['file_' . $i] = $f;
                }
            } else {
                if ($file['error']) {
                    throw new Exception('uploadFromFiles: ' . $file['error']);
                }
                $post['file'] = curl_file_create($file['tmp_name'], $file['type'], $file['name']);
            }
        }
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
        $ret = curl_exec($this->ch);
        if ($ret === false) {
            throw new Exception("uploadFromFiles false returned");
        }
        $code = $this->httpCode();
        if ($code >= 400) {
            throw new Exception("error $code: $ret");
        }
        return $this->json($ret);
    }

    /**
     * @param string|null $app
     * @param string|null $action
     * @param string|null $reference
     * @param string|null $user
     * @param string|null $hash
     * @param int|null $limit
     * @return array
     * @throws Exception
     */
    public function list(
        ?string $app = null,
        ?string $action = null,
        ?string $reference = null,
        ?string $user = null,
        ?string $hash = null,
        ?int $limit = 0
    ): array {
        $qs = http_build_query([
            'app' => $app ?: '',
            'action' => $action ?: '',
            'reference' => $reference ?: '',
            'user' => $user ?? '',
            'hash' => $hash ?? '',
            'limit' => $limit ?? '',
        ]);
        return $this->json($this->get('/files/list?' . $qs));
    }

    /**
     * @param string $search
     * @param string|null $app
     * @param int|null $limit
     * @return array
     * @throws Exception
     */
    public function search(string $search, ?string $app = '', ?int $limit = 0): array
    {
        $qs = http_build_query([
            'search' => $search ?? '',
            'app' => $app ?? '',
            'limit' => $limit ?? '',
        ]);
        return $this->json($this->get('/files/search?' . $qs));
    }

    /**
     * @param string $id
     * @param string $name
     * @param string $description
     * @param string $tags
     * @param int $ttl
     * @return void
     * @throws Exception
     */
    public function edit(string $id, string $name, string $description, string $tags, int $ttl): void
    {
        $body = json_encode([
            'name' => $name,
            'description' => $description,
            'tags' => $tags,
            'ttl' => $ttl,
        ]);
        $this->put('/files/edit/' . $id, $body);
    }

    /**
     * @param string $id
     * @param int $ttl
     * @return void
     * @throws Exception
     */
    public function renew(string $id, int $ttl): void
    {
        $qs = http_build_query([
            'id' => $id,
            'ttl' => $ttl,
        ]);
        $this->get('/renew?' . $qs);
    }

    /**
     * @param string $id
     * @return array
     * @throws Exception
     */
    public function fileId(string $id): array
    {
        return $this->json($this->get('/file/id/' . $id));
    }

    /**
     * @param string $hash
     * @return array
     * @throws Exception
     */
    public function fileHash(string $hash): array
    {
        return $this->json($this->get('/file/hash/' . $hash));
    }

    /**
     * @param string $id
     * @return void
     * @throws Exception
     */
    public function fileDelete(string $id): void
    {
        $this->delete('/file/delete/' . $id);
    }

    /**
     * @param string $hashOrUrl
     * @return void
     * @throws Exception
     */
    public function fileDeleteByHash(string $hashOrUrl): void
    {
        $hash = basename($hashOrUrl);
        $this->delete('/file/delete/hash/' . $hash);
    }

    /**
     * @param string $id
     * @return void
     * @throws Exception
     */
    public function toValt(string $id): void
    {
        $this->get('/to_vault?id=' . $id);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function resync(): void
    {
        $this->get('/resync');
    }
}