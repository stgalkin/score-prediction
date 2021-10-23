<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Resources\Game;

use App\Components\Soccer\Domain\Tournament\Entities\Game\Game;
use App\Components\Soccer\Domain\Tournament\Resources\Team\TeamResource;

class GameResource implements \JsonSerializable
{
    /**
     * GameResource constructor.
     *
     * @param string $id
     * @param array $homeTeam
     * @param array $awayTeam
     * @param int $homeGoals
     * @param int $awayGoals
     */
    public function __construct(
        private string $id,
        private array $homeTeam,
        private array $awayTeam,
        private int $homeGoals,
        private int $awayGoals,
    ) {
    }

    /**
     * @param Game $entity
     *
     * @return GameResource
     */
    public static function fromEntity(Game $entity): GameResource
    {
        return new self(
            id:        $entity->id()->getValue(),
            homeTeam:  TeamResource::fromEntity($entity->homeTeam())->toArray(),
            awayTeam:  TeamResource::fromEntity($entity->awayTeam())->toArray(),
            homeGoals: $entity->homeGoals()->getValue(),
            awayGoals: $entity->awayGoals()->getValue(),
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
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'home_team' => $this->getHomeTeam(),
            'home_goals' => $this->getHomeGoals(),
            'away_team' => $this->getAwayTeam(),
            'away_goals' => $this->getAwayGoals(),
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
     * @return array
     */
    public function getHomeTeam(): array
    {
        return $this->homeTeam;
    }

    /**
     * @return array
     */
    public function getAwayTeam(): array
    {
        return $this->awayTeam;
    }

    /**
     * @return int
     */
    public function getHomeGoals(): int
    {
        return $this->homeGoals;
    }

    /**
     * @return int
     */
    public function getAwayGoals(): int
    {
        return $this->awayGoals;
    }
}
