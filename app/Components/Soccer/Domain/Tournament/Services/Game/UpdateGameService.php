<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Services\Game;

use App\Components\Soccer\Domain\Tournament\Exceptions\Team\TeamNotFoundException;
use App\Components\Soccer\Domain\Tournament\Exceptions\TournamentNotFoundException;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\Game\GameId;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId;
use App\Components\Soccer\Domain\Tournament\Commands\Game\UpdateGame;
use App\Components\Soccer\Domain\Tournament\Interfaces\TournamentRepositoryInterface;
use App\Components\Soccer\Domain\Tournament\ValueObjects\Team\Game\Goals;
use Doctrine\ORM\OptimisticLockException;

final class UpdateGameService
{
    /**
     * @param TournamentRepositoryInterface $repository
     */
    public function __construct(
        private TournamentRepositoryInterface $repository,
    ) {
    }

    /**
     * @param UpdateGame $command
     *
     * @throws OptimisticLockException
     * @throws TeamNotFoundException
     * @throws TournamentNotFoundException
     */
    public function __invoke(UpdateGame $command): void
    {
        $tournament = $this->repository->byIdentity(
            new TournamentId($command->getTournamentId()),
        );

        $entity = $tournament->gameByIdentity(new GameId($command->getId()));

        if (is_int($command->getAwayGoals())) {
            $entity->changeAwayGoals(new Goals($command->getAwayGoals()));
        }

        if (is_int($command->getHomeGoals())) {
            $entity->changeHomeGoals(new Goals($command->getHomeGoals()));
        }

        $entity->played();

        $this->repository->save($tournament);
    }
}

