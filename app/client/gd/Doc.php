<?php
namespace app\client\gd;

use DateTime;
use JsonSerializable;

class Doc implements JsonSerializable
{
    public string $id;
    public string $hash;
    public string $name;
    public string $description;
    public string $type;
    public string $owner;
    public ?array $members;

    /**
     * @var MinDoc[]|null
     */
    public ?array $minidocs;
    public string $createdAt;
    public string $updatedAt;
    public ?string $deletedAt;

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'hash' => $this->hash,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'owner' => $this->owner,
            'members' => $this->members,
            'minidocs' => $this->minidocs,
        ];
    }

    public function createdAt(): string
    {
        return (new DateTime($this->createdAt))->format('d/m/Y H:i');
    }
}