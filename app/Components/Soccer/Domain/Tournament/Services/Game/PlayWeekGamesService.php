<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Services\Game;

use App\Components\Soccer\Domain\Tournament\Exceptions\TournamentNotFoundException;
use App\Components\Soccer\Domain\Tournament\Entities\Game\Game;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Week;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId;
use App\Components\Soccer\Domain\Tournament\Commands\Game\PlayGame;
use App\Components\Soccer\Domain\Tournament\Commands\Game\PlayWeekGames;
use App\Components\Soccer\Domain\Tournament\Interfaces\TournamentRepositoryInterface;
use Illuminate\Support\Facades\Bus;

final class PlayWeekGamesService
{
    /**
     * @param TournamentRepositoryInterface $repository
     */
    public function __construct(
        private TournamentRepositoryInterface $repository,
    ) {
    }

    /**
     * @param PlayWeekGames $command
     *
     * @throws TournamentNotFoundException
     */
    public function __invoke(PlayWeekGames $command): void
    {
        $tournament = $this->repository->byIdentity(
            new TournamentId($command->getTournamentId()),
        );

        $week = new Week($command->getWeek());

        foreach ($tournament->games()->filter(fn(Game $game) => $game->week()->equals($week)) as $game) {
            Bus::dispatch(
                PlayGame::fromArray(
                    [
                        PlayGame::PROP_ID => $game->id()->getValue(),
                        PlayGame::PROP_TOURNAMENT_ID => $command->getTournamentId(),
                    ]
                )
            );
        }
    }
}

