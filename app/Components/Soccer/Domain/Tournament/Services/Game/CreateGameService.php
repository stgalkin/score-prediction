<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Services\Game;

use App\Components\Soccer\Domain\Tournament\Exceptions\Game\AllGamesAlreadyPlayedException;
use App\Components\Soccer\Domain\Tournament\Exceptions\Game\GameAlreadyPlayedException;
use App\Components\Soccer\Domain\Tournament\Exceptions\Game\TeamCantPlayThemSelfException;
use App\Components\Soccer\Domain\Tournament\Exceptions\Team\TeamNotFoundException;use App\Components\Soccer\Domain\Tournament\Exceptions\TournamentNotFoundException;
use App\Components\Soccer\Domain\Tournament\Entities\Game\Game;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\Game\GameId;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\Team\TeamId;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId;
use App\Components\Soccer\Domain\Tournament\Commands\Game\CreateGame;
use App\Components\Soccer\Domain\Tournament\Interfaces\TournamentRepositoryInterface;
use Doctrine\ORM\OptimisticLockException;

final class CreateGameService
{
    /**
     * @param TournamentRepositoryInterface $repository
     */
    public function __construct(
        private TournamentRepositoryInterface $repository,
    ) {
    }

    /**
     * @param CreateGame $command
     *
     * @throws AllGamesAlreadyPlayedException
     * @throws GameAlreadyPlayedException
     * @throws OptimisticLockException
     * @throws TeamCantPlayThemSelfException
     * @throws TournamentNotFoundException
     * @throws TeamNotFoundException
     */
    public function __invoke(CreateGame $command): void
    {
        $entity = $this->repository->byIdentity(
            new TournamentId($command->getTournamentId()),
        );

        $game = new Game(
            tournament: $entity,
            id: new GameId(),
            homeTeam: $entity->teamByIdentity(new TeamId($command->getHomeTeamId())),
            awayTeam: $entity->teamByIdentity(new TeamId($command->getAwayTeamId())),
        );

        $entity->addGame($game);

        $this->repository->save($entity);
    }
}

