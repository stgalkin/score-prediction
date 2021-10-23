<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Commands\Game;

class PlayGame
{
    public const NAME = 'soccer.tournament.game.play';

    public const PROP_ID = 'id';
    public const PROP_TOURNAMENT_ID = 'tournament_id';

    public const REQUIRED_PROPERTIES = [
        self::PROP_ID,
        self::PROP_TOURNAMENT_ID,
    ];

    /**
     * @param string $id
     * @param string $tournamentId
     */
    public function __construct(
        private string $id,
        private string $tournamentId,
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
     * @param array $data
     *
     * @return PlayGame
     */
    public static function fromArray(array $data): PlayGame
    {
        return new self(
            $data[self::PROP_ID],
            $data[self::PROP_TOURNAMENT_ID],
        );
    }
}
