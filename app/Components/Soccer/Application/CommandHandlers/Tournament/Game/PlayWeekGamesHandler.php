<?php

declare(strict_types=1);

namespace App\Components\Soccer\Application\CommandHandlers\Tournament\Game;

use App\Components\Soccer\Domain\Tournament\Commands\Game\PlayWeekGames;
use App\Components\Soccer\Domain\Tournament\Exceptions\Game\GameAlreadyPlayedException;
use App\Components\Soccer\Domain\Tournament\Exceptions\Game\TeamCantPlayThemSelfException;
use App\Components\Soccer\Domain\Tournament\Exceptions\Team\TeamNotFoundException;
use App\Components\Soccer\Domain\Tournament\Exceptions\TournamentNotFoundException;
use App\Components\Soccer\Domain\Tournament\Services\Game\PlayWeekGamesService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Psr\Log\LoggerInterface;
use Throwable;

final class PlayWeekGamesHandler
{
    /**
     * PlayWeekGamesHandler constructor.
     *
     * @param PlayWeekGamesService $service
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private PlayWeekGamesService $service,
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param PlayWeekGames $command
     *
     * @throws Exception
     * @throws OptimisticLockException
     * @throws Throwable
     * @throws GameAlreadyPlayedException
     * @throws TeamCantPlayThemSelfException
     * @throws TeamNotFoundException
     * @throws TournamentNotFoundException
     */
    public function __invoke(PlayWeekGames $command): void
    {
        $this->entityManager->getConnection()->beginTransaction();

        try {
            ($this->service)($command);

            $this->entityManager->getConnection()->commit();
        } catch (Throwable $e) {
            $this->entityManager->getConnection()->rollback();
            throw $e;
        }

        $this->logger->info("Games for week={$command->getWeek()} successfully played", ['data' => $command]);
    }
}
