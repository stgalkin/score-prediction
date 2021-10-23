<?php

declare(strict_types=1);

namespace App\Components\Soccer\Domain\Tournament\Interfaces;

use App\Components\Soccer\Domain\Tournament\Exceptions\TournamentNotFoundException;
use App\Components\Soccer\Domain\Tournament\Entities\Tournament;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId;
use Doctrine\ORM\OptimisticLockException;

interface TournamentRepositoryInterface
{
    public const ALIAS = 'tournaments';

    /**
     * @param TournamentId $identity
     *
     * @return Tournament
     * @throws TournamentNotFoundException
     */
    public function byIdentity(TournamentId $identity): Tournament;

    /**
     * @param array $criteria
     * @return int
     */
    public function count(array $criteria): int;

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array
     */
    public function findBy(array $criteria, ?array $orderBy = null, int $limit = null, int $offset = null): array;

    /**
     * @param Tournament $entity
     *
     * @throws OptimisticLockException
     */
    public function save(Tournament $entity): void;
}
