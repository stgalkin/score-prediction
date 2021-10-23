<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine\Repositories\Base;

use App\Shared\Domain\ValueObjects\AbstractIdentity;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;
use RuntimeException;

abstract class AbstractDoctrineRepository
{
    public const INNER_JOIN_TYPE = 'inner';
    public const LEFT_JOIN_TYPE = 'left';

    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * @var QueryBuilder
     */
    private QueryBuilder $builder;

    /**
     * @param ManagerRegistry $registry
     * @param string $className
     * @param string $alias
     */
    public function __construct(ManagerRegistry $registry, private string $className, private string $alias)
    {
        $manager = $registry->getManagerForClass($className);

        if (!$manager instanceof EntityManagerInterface) {
            throw new RuntimeException("Doctrine can't resolve manager");
        }

        $this->manager = $manager;
        $this->refreshBuilder();
    }

    /**
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    protected function countFiltered(): int
    {
        return (int)$this->getBuilder()->select("count({$this->getAlias()}.id)")->getQuery()->getSingleScalarResult();
    }

    /**
     * @return QueryBuilder
     */
    protected function refreshBuilder(): QueryBuilder
    {
        return $this->setBuilder();
    }

    /**
     * @return QueryBuilder
     */
    protected function getBuilder(): QueryBuilder
    {
        return $this->builder;
    }

    /**
     * @return QueryBuilder
     */
    private function setBuilder(): QueryBuilder
    {
        $this->builder = $this->getEntityManager()
            ->createQueryBuilder()
            ->select($this->alias)
            ->from($this->className, $this->alias);

        return $this->builder;
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    protected function getAlias(): string
    {
        if (trim($this->alias) === '') {
            throw new InvalidArgumentException('Alias cannot be empty');
        }

        return $this->alias;
    }

    /**
     * @param array $criteria
     *
     * @return int
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function count(array $criteria): int
    {
        $this->handleQueryFilters($this->handleFilters($criteria)[$this->getAlias()] ?? []);

        $result = $this->countFiltered();

        $this->refreshBuilder();

        return $result;
    }

    /**
     * @param string $column
     * @return string
     */
    protected function getColumnAlias(string $column): string
    {
        return str_replace('.', '_', strtolower($column));
    }

    /**
     * @return array
     */
    public function getColumnNames(): array
    {
        return $this->getEntityManager()->getClassMetadata($this->className)->getColumnNames();
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->manager;
    }

    /**
     * @param AbstractIdentity $identity
     * @return object|null
     */
    protected function find(AbstractIdentity $identity): ?object
    {
        return $this->getEntityManager()->find($this->className, $identity);
    }

    /**
     * @param string[] $ids
     * @return object[]
     */
    protected function findByIds(array $ids): array
    {
        return $this->getBuilder()
            ->andWhere("{$this->getAlias()}.id IN (:ids)")
            ->setParameter('ids', $ids, Connection::PARAM_STR_ARRAY)
            ->getQuery()->getResult();
    }
}
