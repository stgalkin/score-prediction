<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Commands\Game;

class CreateGame
{
    public const NAME = 'soccer.tournament.game.create';

    public const PROP_ID = 'id';
    public const PROP_TOURNAMENT_ID = 'tournament_id';

    public const PROP_HOME_TEAM_ID = 'home_team_id';
    public const PROP_AWAY_TEAM_ID = 'away_team_id';
    public const PROP_WEEK = 'week';

    public const REQUIRED_PROPERTIES = [
        self::PROP_ID,
        self::PROP_TOURNAMENT_ID,
        self::PROP_HOME_TEAM_ID,
        self::PROP_AWAY_TEAM_ID,
        self::PROP_WEEK,
    ];

    /**
     * @param string $id
     * @param string $tournamentId
     * @param string $homeTeamId
     * @param string $awayTeamId
     * @param int $week
     */
    public function __construct(
        private string $id,
        private string $tournamentId,
        private string $homeTeamId,
        private string $awayTeamId,
        private int $week,
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
     * @return string
     */
    public function getHomeTeamId(): string
    {
        return $this->homeTeamId;
    }

    /**
     * @return string
     */
    public function getAwayTeamId(): string
    {
        return $this->awayTeamId;
    }

    /**
     * @return int
     */
    public function getWeek(): int
    {
        return $this->week;
    }

    /**
     * @param array $data
     *
     * @return CreateGame
     */
    public static function fromArray(array $data): CreateGame
    {
        return new self(
            $data[self::PROP_ID],
            $data[self::PROP_TOURNAMENT_ID],
            $data[self::PROP_HOME_TEAM_ID],
            $data[self::PROP_AWAY_TEAM_ID],
            $data[self::PROP_WEEK],
        );
    }
}
