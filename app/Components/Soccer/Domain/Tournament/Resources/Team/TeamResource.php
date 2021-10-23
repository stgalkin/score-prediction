<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Resources\Team;

use App\Components\Soccer\Domain\Tournament\Entities\Team\Team;

class TeamResource implements \JsonSerializable
{
    /**
     * TeamResource constructor.
     *
     * @param string $id
     * @param string $name
     */
    public function __construct(
        private string $id,
        private string $name,
    ) {
    }

    /**
     * @param Team $entity
     *
     * @return TeamResource
     */
    public static function fromEntity(Team $entity): TeamResource
    {
        return new self(
            id:   $entity->id()->getValue(),
            name: $entity->name()->getValue(),
        );
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
