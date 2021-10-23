<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Services\Game;

use App\Components\Soccer\Domain\Tournament\Entities\Game\Game;
use App\Components\Soccer\Domain\Tournament\Exceptions\TournamentNotFoundException;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId;
use App\Components\Soccer\Domain\Tournament\Commands\Game\PlayAllGames;
use App\Components\Soccer\Domain\Tournament\Commands\Game\PlayGame;
use App\Components\Soccer\Domain\Tournament\Interfaces\TournamentRepositoryInterface;
use Illuminate\Support\Facades\Bus;

final class PlayAllGamesService
{
    /**
     * @param TournamentRepositoryInterface $repository
     */
    public function __construct(
        private TournamentRepositoryInterface $repository,
    ) {
    }

    /**
     * @param PlayAllGames $command
     *
     * @throws TournamentNotFoundException
     */
    public function __invoke(PlayAllGames $command): void
    {
        $tournament = $this->repository->byIdentity(
            new TournamentId($command->getTournamentId()),
        );

        // create next week and play games
        foreach ($tournament->games()->filter(fn(Game $game) => !$game->isPlayed()) as $game) {
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

