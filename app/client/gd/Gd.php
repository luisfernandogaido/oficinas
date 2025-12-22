<?php

namespace app\client\gd;

use client\Rest;
use Exception;

use function http_build_query;
use function json_encode;

class Gd extends Rest
{
    const URL_BASE = 'https://gd.gaido.dev';
//    const URL_BASE = 'http://localhost:4028';
    const TOKEN = 'KRccEESCD7yP';

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
     * @param array $ret
     * @return Doc
     * @throws Exception
     */
    private function retDoc(array $ret): Doc
    {
        $doc = new Doc();
        $doc->id = $ret['id'] ?? '';
        if ($doc->id == '') {
            throw new Exception('retorno de documento inválido');
        }
        $doc->hash = $ret['hash'];
        $doc->name = $ret['name'];
        $doc->description = $ret['description'];
        $doc->type = $ret['type'];
        $doc->owner = $ret['owner'];
        $doc->members = $ret['members'];
        $doc->createdAt = $ret['createdAt'];
        $doc->updatedAt = $ret['updatedAt'];
        $doc->minidocs = [];
        foreach ($ret['minidocs'] ?? [] as $minidoc) {
            $doc->minidocs[] = MinDoc::fromJson($minidoc);
        }
        return $doc;
    }

    /**
     * @param string $name
     * @param string $description
     * @param string $type
     * @param string $owner
     * @param array|null $members
     * @return Doc
     * @throws Exception
     */
    public function docNew(string $name, string $description, string $type, string $owner, ?array $members): Doc
    {
        $body = json_encode([
            'name' => $name,
            'description' => $description,
            'type' => $type,
            'owner' => $owner,
            'members' => $members,
        ]);
        return $this->retDoc($this->post('/doc/new', $body));
    }


    /**
     * @param string $hash
     * @param string $owner
     * @return Doc
     * @throws Exception
     */
    public function docFind(string $hash, string $owner): Doc
    {
        $qs = http_build_query([
            'owner' => $owner,
        ]);
        return $this->retDoc($this->json($this->get("/doc/$hash?$qs")));
    }

    /**
     * @param Doc $doc
     * @return void
     * @throws Exception
     */
    public function docUpdate(Doc $doc): void
    {
        $body = json_encode($doc);
        $this->post('/doc/update', $body);
    }

    /**
     * @param string $hash
     * @param string $owner
     * @return void
     * @throws Exception
     */
    public function deleteDoc(string $hash, string $owner): void
    {
        $this->get("/doc/delete/$hash?owner=" . $owner);
    }

    /**
     * @param string $owner
     * @param string $search
     * @return Doc[]
     * @throws Exception
     */
    public function docs(string $owner, string $search): array
    {
        $qs = http_build_query([
            'owner' => $owner,
            'search' => $search,
        ]);
        $docs = [];
        $arr = $this->json($this->get('/docs/?' . $qs));
        foreach ($arr as $ret) {
            $docs[] = $this->retDoc($ret);
        }
        return $docs;
    }

    /**
     * @param string $hash
     * @param string $text
     * @param string $owner
     * @param string $idBefore
     * @return MinDoc
     * @throws Exception
     */
    public function minDocNewText(string $hash, string $owner, string $text, string $idBefore): MinDoc
    {
        $body = [
            'docHash' => $hash,
            'text' => $text,
            'owner' => $owner,
            'id_before' => $idBefore,
        ];
        $arr = $this->post('/mindoc/new-text', json_encode($body));
        return MinDoc::fromJson($arr);
    }

    /**
     * @param string $hash
     * @param string $id
     * @param string $owner
     * @param string $text
     * @return MinDoc
     * @throws Exception
     */
    public function minDocUpdateText(string $hash, string $id, string $owner, string $text): MinDoc
    {
        $body = [
            'hash' => $hash,
            'id' => $id,
            'owner' => $owner,
            'text' => $text,
        ];
        $arr = $this->post('/mindoc/update-text', json_encode($body));
        return MinDoc::fromJson($arr);
    }

    /**
     * @param string $hash
     * @param string $id
     * @param string $owner
     * @return void
     * @throws Exception
     */
    public function minDocDelete(string $hash, string $id, string $owner): void
    {
        $body = [
            'hash' => $hash,
            'id' => $id,
            'owner' => $owner,
        ];
        $this->post('/mindoc/delete', json_encode($body));
    }

    /**
     * @param string $hash
     * @param string $owner
     * @param string $fileId
     * @param string $name
     * @param string $size
     * @param string $type
     * @param string $fileHash
     * @param string $path
     * @param string $url
     * @param string $description
     * @param string $tags
     * @param string $createdAt
     * @param string $modifiedAt
     * @param string $idBefore
     * @return MinDoc
     * @throws Exception
     */
    public function minDocNewFile(
        string $hash,
        string $owner,
        string $fileId,
        string $name,
        int $size,
        string $type,
        string $fileHash,
        string $path,
        string $url,
        string $description,
        string $tags,
        string $createdAt,
        string $modifiedAt,
        string $idBefore
    ): MinDoc {
        $body = [
            'docHash' => $hash,
            'owner' => $owner,
            'id' => $fileId,
            'name' => $name,
            'size' => $size,
            'type' => $type,
            'hash' => $fileHash,
            'path' => $path,
            'url' => $url,
            'description' => $description,
            'tags' => $tags,
            'createdAt' => $createdAt,
            'modifiedAt' => $modifiedAt,
            'id_before' => $idBefore,
        ];
        $arr = $this->json($this->post('/mindoc/new-file', json_encode($body)));
        return MinDoc::fromJson($arr);
    }

    /**
     * @param string $hash
     * @param string $id
     * @param string $owner
     * @param string $name
     * @param string $description
     * @param string $tags
     * @return MinDoc
     * @throws Exception
     */
    public function minDocUpdateFile(
        string $hash,
        string $id,
        string $owner,
        string $name,
        string $description,
        string $tags,
    ): MinDoc {
        $body = [
            'hash' => $hash,
            'id' => $id,
            'owner' => $owner,
            'name' => $name,
            'description' => $description,
            'tags' => $tags,
        ];
        $arr = $this->json($this->post('/mindoc/update-file', json_encode($body)));
        return MinDoc::fromJson($arr);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function empresasCnaes(): array
    {
        return $this->json($this->get('/empresas/cnaes'));
    }

    /**
     * @return array
     * @throws Exception
     */
    public function empresasMunicipios(): array
    {
        return $this->json($this->get('/empresas/municipios'));
    }

    public function empresasFind(
        string $cnaeCodes,
        string $muniCodes,
        bool $recentes,
        bool $temEmail,
        bool $emailRelevante,
        bool $temCelular,
        int $limit,
    ): array {
        $qs = http_build_query([
            'cnae_codes' => $cnaeCodes,
            'muni_codes' => $muniCodes,
            'recentes' => $recentes ? 'true' : 'false',
            'tem_email' => $temEmail ? 'true' : 'false',
            'email_relevante' => $emailRelevante ? 'true' : 'false',
            'tem_celular' => $temCelular ? 'true' : 'false',
            'limit' => $limit,
        ]);
        return $this->json($this->get('/empresas?' . $qs));
    }

    /**
     * @param string $ip
     * @return array
     * @throws Exception
     */
    public function ip(string $ip): array
    {
        return $this->json($this->get("/ip/$ip"));
    }

    /**
     * @param string $domain
     * @return bool
     * @throws Exception
     */
    public function disposable(string $domain): bool
    {
        $domain = urldecode($domain);
        return $this->get("/check_disposable?domain=$domain") == 'true';
    }

}