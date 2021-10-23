<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Commands\Game;

class PlayAllGames
{
    public const NAME = 'soccer.tournament.game.play.all';

    public const PROP_TOURNAMENT_ID = 'tournament_id';

    public const REQUIRED_PROPERTIES = [
        self::PROP_TOURNAMENT_ID,
    ];

    /**
     * @param string $id
     * @param string $tournamentId
     */
    public function __construct(
        private string $tournamentId,
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
     * @param array $data
     *
     * @return PlayAllGames
     */
    public static function fromArray(array $data): PlayAllGames
    {
        return new self(
            $data[self::PROP_TOURNAMENT_ID],
        );
    }
}
