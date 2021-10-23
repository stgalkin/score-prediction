<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Resources;

use App\Components\Soccer\Domain\Tournament\Entities\Game\Game;
use App\Components\Soccer\Domain\Tournament\Entities\Team\Team;
use App\Components\Soccer\Domain\Tournament\Entities\Tournament;
use App\Components\Soccer\Domain\Tournament\Resources\Game\GameResource;
use App\Components\Soccer\Domain\Tournament\Resources\Team\TeamResource;

class TournamentDTO implements \JsonSerializable
{
    /**
     * TournamentResource constructor.
     *
     * @param string $id
     * @param array $games
     * @param array $teams
     */
    public function __construct(
        private string $id,
        private array $games,
        private array $teams,
    ) {
    }

    /**
     * @param Tournament $entity
     *
     * @return TournamentResource
     */
    public static function fromEntity(Tournament $entity): TournamentResource
    {
        return new self(
            id:    $entity->id()->getValue(),
            games: $entity->games()->map(fn(Game $entity) => GameResource::fromEntity($entity)->toArray())->getValues(),
            teams: $entity->teams()->map(fn(Team $entity) => TeamResource::fromEntity($entity)->toArray())->getValues(),
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
            'games' => $this->getGames(),
            'teams' => $this->getTeams(),
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
    public function getGames(): array
    {
        return $this->games;
    }

    /**
     * @return array
     */
    public function getTeams(): array
    {
        return $this->teams;
    }
}
