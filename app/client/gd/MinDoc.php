<?php

namespace app\client\gd;

use Exception;
use JsonSerializable;

class MinDoc implements JsonSerializable
{
    public string $id;
    public string $type;
    public int $order;
    public string $text;
    public ?File $file;
    public string $createdAt;
    public ?string $updatedAt;

    /**
     * @param array $arr
     * @return MinDoc
     * @throws Exception
     */
    public static function fromJson(array $arr): MinDoc
    {
        if (!isset($arr['id'])) {
            throw new Exception('arr de mindoc mal-formado');
        }
        $md = new MinDoc();
        $md->id = $arr['id'];
        $md->type = $arr['type'];
        $md->order = $arr['order'];
        $md->text = $arr['text'];
        $md->file = File::fromJson($arr['file']);
        $md->createdAt = $arr['createdAt'];
        $md->updatedAt = $arr['updatedAt'] ?? null;
        return $md;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'order' => $this->order,
            'text' => $this->text,
            'file' => $this->file,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }


}