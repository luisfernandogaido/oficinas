<?php
namespace app\client\gd;

use JsonSerializable;
use math\Bytes;

class File implements JsonSerializable
{
    public string $id;
    public string $name;
    public int $size;
    public string $type;
    public string $hash;
    public string $path;
    public string $url;
    public string $description;
    public string $tags;
    public string $createdAt;
    public string $modifiedAt;

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'size' => $this->size,
            'type' => $this->type,
            'hash' => $this->hash,
            'path' => $this->path,
            'url' => $this->url,
            'description' => $this->description,
            'tags' => $this->tags,
            'createdAt' => $this->createdAt,
            'modifiedAt' => $this->modifiedAt,
        ];
    }

    public function size(): string
    {
        return (new Bytes($this->size))->formata();
    }

    public static function fromJson(?array $arr): ?File
    {
        if ($arr == null) {
            return null;
        }
        $f = new File();
        $f->id = $arr['id'];
        $f->name = $arr['name'];
        $f->size = $arr['size'];
        $f->type = $arr['type'];
        $f->hash = $arr['hash'];
        $f->path = $arr['path'];
        $f->url = $arr['url'];
        $f->description = $arr['description'];
        $f->tags = $arr['tags'];
        $f->createdAt = $arr['createdAt'];
        $f->modifiedAt = $arr['modifiedAt'];
        return $f;
    }
}