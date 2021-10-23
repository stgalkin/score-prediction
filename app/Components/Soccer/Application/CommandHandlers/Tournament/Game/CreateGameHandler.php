<?php

declare(strict_types=1);

namespace App\Components\Soccer\Application\CommandHandlers\Tournament\Game;

use App\Components\Soccer\Domain\Tournament\Commands\Game\CreateGame;
use App\Components\Soccer\Domain\Tournament\Exceptions\Game\AllGamesAlreadyPlayedException;
use App\Components\Soccer\Domain\Tournament\Exceptions\Game\GameAlreadyPlayedException;
use App\Components\Soccer\Domain\Tournament\Exceptions\Game\TeamCantPlayThemSelfException;
use App\Components\Soccer\Domain\Tournament\Exceptions\Team\TeamNotFoundException;
use App\Components\Soccer\Domain\Tournament\Exceptions\TournamentNotFoundException;
use App\Components\Soccer\Domain\Tournament\Services\Game\CreateGameService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use Throwable;

final class CreateGameHandler
{
    /**
     * CreateGameHandler constructor.
     *
     * @param CreateGameService $service
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private CreateGameService $service,
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param CreateGame $command
     *
     * @throws Exception
     * @throws OptimisticLockException
     * @throws Throwable
     * @throws AllGamesAlreadyPlayedException
     * @throws GameAlreadyPlayedException
     * @throws TeamCantPlayThemSelfException
     * @throws TeamNotFoundException
     * @throws TournamentNotFoundException
     */
    public function __invoke(CreateGame $command): void
    {
        $this->entityManager->getConnection()->beginTransaction();

        try {
            ($this->service)($command);

            $this->entityManager->getConnection()->commit();
        } catch (Throwable $e) {
            $this->entityManager->getConnection()->rollback();
            throw $e;
        }

        $this->logger->info("Game ID={$command->getId()} successfully created", ['data' => $command]);
    }
}
