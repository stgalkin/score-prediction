<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Services\Game;

use App\Components\Soccer\Domain\Tournament\Commands\Game\PlayGame;
use App\Components\Soccer\Domain\Tournament\Commands\Game\UpdateGame;
use Illuminate\Support\Facades\Bus;

final class PlayGameService
{
    const GOALS_LIMIT_FROM = 0;
    const GOALS_LIMIT_TO = 5;

    /**
     * @param PlayGame $command
     *
     */
    public function __invoke(PlayGame $command): void
    {
        Bus::dispatch(
            UpdateGame::fromArray(
                [
                    UpdateGame::PROP_ID => $command->getId(),
                    UpdateGame::PROP_TOURNAMENT_ID => $command->getTournamentId(),
                    UpdateGame::PROP_HOME_GOALS => $this->getGoals(),
                    UpdateGame::PROP_AWAY_GOALS => $this->getGoals(),
                ]
            )
        );
    }

    /**
     * @return int
     */
    private function getGoals(): int
    {
        return rand(self::GOALS_LIMIT_FROM, self::GOALS_LIMIT_TO);
    }
}

