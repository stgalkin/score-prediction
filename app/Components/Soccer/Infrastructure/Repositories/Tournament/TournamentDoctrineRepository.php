<?php

declare(strict_types=1);

namespace App\Components\Soccer\Infrastructure\Repositories\Tournament;

use App\Components\Soccer\Domain\Tournament\Exceptions\TournamentNotFoundException;
use App\Components\Soccer\Domain\Tournament\Entities\Tournament;
use App\Shared\Domain\ValueObjects\Soccer\Tournament\TournamentId;
use App\Components\Soccer\Domain\Tournament\Interfaces\TournamentRepositoryInterface;
use App\Shared\Infrastructure\Doctrine\Repositories\Base\AbstractDoctrineRepository;
use Doctrine\Persistence\ManagerRegistry;

final class TournamentDoctrineRepository extends AbstractDoctrineRepository implements TournamentRepositoryInterface
{
    /**
     * ActivityLogDoctrineRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(
        ManagerRegistry $registry,
    ) {
        parent::__construct($registry, Tournament::class, self::ALIAS);
    }

    /**
     * @inheritDoc
     */
    public function byIdentity(TournamentId $identity): Tournament
    {
        $entity = $this->find($identity);

        return $entity instanceof Tournament
            ? $entity
            : throw new TournamentNotFoundException(sprintf('Tournament (ID=%s) not found', $identity->getValue()));
    }

    /**
     * @inheritDoc
     */
    public function save(Tournament $entity): void
    {
        $em = $this->getEntityManager();

        if (!$em->contains($entity)) {
            $this->getEntityManager()->persist($entity);
        }

        $em->flush();
    }

    public function findBy(array $criteria, ?array $orderBy = null, int $limit = null, int $offset = null): array
    {
        // TODO: Implement findBy() method.
    }

}
