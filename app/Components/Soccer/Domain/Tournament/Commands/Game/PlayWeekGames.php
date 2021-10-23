<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Commands\Game;

class PlayWeekGames
{
    public const NAME = 'soccer.tournament.game.week.play';

    public const PROP_TOURNAMENT_ID = 'tournament_id';
    public const PROP_WEEK = 'week';

    public const REQUIRED_PROPERTIES = [
        self::PROP_TOURNAMENT_ID,
        self::PROP_WEEK,
    ];

    /**
     * @param string $tournamentId
     * @param int $week
     */
    public function __construct(
        private string $tournamentId,
        private int $week,
    ) {
    }

    /**
     * @return string
     */
    public function getTournamentId(): string
    {
        return $this->tournamentId;
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
     * @return PlayWeekGames
     */
    public static function fromArray(array $data): PlayWeekGames
    {
        return new self(
            $data[self::PROP_TOURNAMENT_ID],
            $data[self::PROP_WEEK],
        );
    }
}
