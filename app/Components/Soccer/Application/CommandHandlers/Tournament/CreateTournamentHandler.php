<?php

declare(strict_types=1);

namespace App\Components\Soccer\Application\CommandHandlers\Tournament;

use App\Components\Soccer\Domain\Tournament\Commands\CreateTournament;
use App\Components\Soccer\Domain\Tournament\Exceptions\TournamentTeamsStaffedException;
use App\Components\Soccer\Domain\Tournament\Services\CreateTournamentService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Throwable;

final class CreateTournamentHandler
{
    /**
     * CreateTournamentHandler constructor.
     *
     * @param CreateTournamentService $service
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
        private CreateTournamentService $service,
        private LoggerInterface $logger,
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @param CreateTournament $command
     *
     * @throws Throwable
     * @throws TournamentTeamsStaffedException
     * @throws Exception
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function __invoke(CreateTournament $command): void
    {
        $this->entityManager->getConnection()->beginTransaction();

        try {
            ($this->service)($command);

            $this->entityManager->getConnection()->commit();
        } catch (Throwable $e) {
            $this->entityManager->getConnection()->rollback();
            throw $e;
        }

        $this->logger->info("Tournament with  ID={$command->getId()} successfully created", ['data' => $command]);
    }
}
