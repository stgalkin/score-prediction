<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Commands\Game;

class UpdateGame
{
    public const NAME = 'soccer.tournament.game.update';

    public const PROP_ID = 'id';
    public const PROP_TOURNAMENT_ID = 'tournament_id';

    public const PROP_HOME_GOALS = 'home_goals';
    public const PROP_AWAY_GOALS = 'away_goals';

    public const REQUIRED_PROPERTIES = [
        self::PROP_ID,
        self::PROP_TOURNAMENT_ID,
    ];

    /**
     * @param string $id
     * @param string $tournamentId
     * @param int|null $homeGoals
     * @param int|null $awayGoals
     */
    public function __construct(
        private string $id,
        private string $tournamentId,
        private ?int $homeGoals = null,
        private ?int $awayGoals = null,
    ) {
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
    public function getTournamentId(): string
    {
        return $this->tournamentId;
    }

    /**
     * @return int|null
     */
    public function getHomeGoals(): ?int
    {
        return $this->homeGoals;
    }

    /**
     * @return int|null
     */
    public function getAwayGoals(): ?int
    {
        return $this->awayGoals;
    }

    /**
     * @param array $data
     *
     * @return UpdateGame
     */
    public static function fromArray(array $data): UpdateGame
    {
        return new self(
            $data[self::PROP_ID],
            $data[self::PROP_TOURNAMENT_ID],
            $data[self::PROP_HOME_GOALS] ?? null,
            $data[self::PROP_AWAY_GOALS] ?? null,
        );
    }
}
