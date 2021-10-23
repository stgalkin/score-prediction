<?php

use App\Components\Soccer\Domain\Tournament\Entities\Game\Game;
use App\Components\Soccer\Domain\Tournament\Entities\Team\Team;
use App\Shared\Infrastructure\Doctrine\DBAL\Types\Soccer\Tournament\TournamentIdType;
use App\Components\Soccer\Infrastructure\Repositories\Tournament\TournamentDoctrineRepository;
use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;

/**
 * @var ClassMetadata $metadata
 * @psalm-suppress UndefinedGlobalVariable
 */
$builder = new ClassMetadataBuilder($metadata);

$builder
    ->setCustomRepositoryClass(TournamentDoctrineRepository::class)
    ->setTable('tournaments')
    // id
    ->createField('id', TournamentIdType::NAME)
    ->makePrimaryKey()
    ->build()


    // teams
    ->createOneToMany('teams', Team::class)
    ->setIndexBy('id')
    ->cascadePersist()
    ->mappedBy('tournament')
    ->build()

    // games
    ->createOneToMany('games', Game::class)
    ->setIndexBy('id')
    ->cascadePersist()
    ->mappedBy('tournament')
    ->build()
;
